<?php
/**
 * 通用的树型类，可以生成任何树型结构
 */
class Tree {

    /**
     * 生成树型结构所需要的2维数组
     * @var array
     */
    public $arr = array();

    /**
     * 生成树型结构所需修饰符号，可以换成图片
     * @var array
     */
    public $icon = array('│', '├', '└');

    /**
     * @var string
     */
    public $nbsp = "&nbsp;";

    /**
     * @access private
     */
    private $str = '';

    /**
     * @var string
     */
    public $ret = '';

    /**
     * @var string
     */
    public $parent_field = 'pid';

    /**
     * 构造函数，初始化类
     * @param array 2维数组，例如：
     * [
     *      1 => ['id'=>'1',$this->parent_field=>0,'name'=>'一级栏目一'],
     *      2 => ['id'=>'2',$this->parent_field=>0,'name'=>'一级栏目二'],
     *      3 => ['id'=>'3',$this->parent_field=>1,'name'=>'二级栏目一'],
     *      4 => ['id'=>'4',$this->parent_field=>1,'name'=>'二级栏目二'],
     *      5 => ['id'=>'5',$this->parent_field=>2,'name'=>'二级栏目三'],
     *      6 => ['id'=>'6',$this->parent_field=>3,'name'=>'三级栏目一'],
     *      7 => ['id'=>'7',$this->parent_field=>3,'name'=>'三级栏目二']
     *  ]
     *  @return	bool
     */
    public function init($arr=array()) {
        $this->arr = $arr;
        $this->ret = '';
        return is_array($arr);
    }

    /**
     * 得到父级数组
     * @param int $myid
     * @return array
     */
    public function get_parent($myid) {
        $newarr = array();
        if (!isset($this->arr[$myid])) {
            return false;
        }
        $pid = $this->arr[$myid][$this->parent_field];
        $pid = $this->arr[$pid][$this->parent_field];
        if (is_array($this->arr)) {
            foreach ($this->arr as $id => $a) {
                if (isset($a[$this->parent_field]) && $a[$this->parent_field] == $pid) {
                    $newarr[$id] = $a;
                }
            }
        }
        return $newarr;
    }

    /**
     * 得到子级数组
     * @param int $myid
     * @return array
     */
    public function get_child($myid) {
        $a = $newarr = array();
        if (is_array($this->arr)) {
            foreach ($this->arr as $id => $a) {
                if (isset($a[$this->parent_field])) {
                    if ($a[$this->parent_field] == $myid) {
                        $newarr[$id] = $a;
                    }
                } else {
                    if ($myid == 0) {
                        $newarr[$id] = $a;
                    }
                }
            }
        }
        return $newarr ? $newarr : false;
    }

    /**
     * 得到当前位置数组
     * @param int $myid
     * @param array $newarr
     * @return array
     */
    public function get_pos($myid, &$newarr) {
        $a = array();
        if (!isset($this->arr[$myid])) {
            return false;
        }
        $newarr[] = $this->arr[$myid];
        $pid = $this->arr[$myid][$this->parent_field];
        if (isset($this->arr[$pid])) {
            $this->get_pos($pid, $newarr);
        }
        if (is_array($newarr)) {
            krsort($newarr);
            foreach ($newarr as $v) {
                $a[$v['id']] = $v;
            }
        }
        return $a;
    }

    /**
     * 得到树型结构
     * @param int $myid 表示获得这个ID下的所有子级
     * @param string $str 生成树型结构的基本代码，例如："<option value=\$id \$selected>\$spacer\$name</option>"
     * @param int $sid 被选中的ID，比如在做树型下拉框的时候需要用到
     * @param string $adds 前缀
     * @param string $str_group
     * @return string
     */
    public function get_tree($myid, $str, $sid = 0, $adds = '', $str_group = '') {
        $number = 1;
        //一级栏目
        $child = $this->get_child($myid);
        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $id => $value) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds . $j : '';
                @extract($value);
                $selected = $id == $sid ? 'selected' : '';
                ${$this->parent_field} == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
                $this->ret .= $nstr;
                $nbsp = $this->nbsp;
                $this->get_tree($id, $str, $sid, $adds . $k . $nbsp, $str_group);
                $number++;
            }
        }
        return $this->ret;
    }

    /**
     * 得到树型结构数组
     * @param int ID，表示获得这个ID下的所有子级
     * @param int 被选中的ID，比如在做树型下拉框的时候需要用到
     * @return string
     */
    public function get_tree_array($myid, $level = 1) {
        $retarray = array();
        //一级栏目数组
        $child = $this->get_child($myid);
        if (is_array($child)) {
            //数组长度
            foreach ($child as $id => $value) {
                @extract($value);
                $value['level']         = $level;
                $retarray[$value['id']] = $value;
                $getTreeArray   = $this->get_tree_array($id, ($level + 1));
                $getTreeArray   && $retarray[$value['id']]['child'] = $getTreeArray;
            }
        }
        return $retarray;
    }

    /**
     * 同上一方法类似,但允许多选
     */
    public function get_tree_multi($myid, $str, $sid = 0, $adds = '') {
        $number = 1;
        $child = $this->get_child($myid);
        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $id => $a) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds . $j : '';

                $selected = $this->have($sid, $id) ? 'selected' : '';
                @extract($a);
                eval("\$nstr = \"$str\";");
                $this->ret .= $nstr;
                $this->get_tree_multi($id, $str, $sid, $adds . $k . '&nbsp;');
                $number++;
            }
        }
        return $this->ret;
    }

    /**
     * @param integer $myid 要查询的ID
     * @param string $str   第一种HTML代码方式
     * @param string $str2  第二种HTML代码方式
     * @param integer $sid  默认选中
     * @param string $adds 前缀
     * @return string
     */
    public function get_tree_category($myid, $str, $str2, $sid = 0, $adds = '') {
        $number = 1;
        $child = $this->get_child($myid);
        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $id => $a) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds . $j : '';

                $selected = $this->have($sid, $id) ? 'selected' : '';
                @extract($a);
                if (empty($html_disabled)) {
                    eval("\$nstr = \"$str\";");
                } else {
                    eval("\$nstr = \"$str2\";");
                }
                $this->ret .= $nstr;
                $this->get_tree_category($id, $str, $str2, $sid, $adds . $k . '&nbsp;');
                $number++;
            }
        }
        return $this->ret;
    }

    /**
     * 同上一类方法，jquery treeview 风格，可伸缩样式（需要treeview插件支持）
     * @param int $myid 表示获得这个ID下的所有子级
     * @param string $effected_id 需要生成treeview目录数的id
     * @param string $str 末级样式
     * @param string $str2 目录级别样式
     * @param int $showlevel 直接显示层级数，其余为异步显示，0为全部显示
     * @param string $style 目录样式 默认 filetree 可增加其他样式如'filetree treeview-famfamfam'
     * @param int $currentlevel 计算当前层级，递归使用 适用改函数时不需要用该参数
     * @param bool $recursion 递归使用 外部调用时为false
     * @return 	string
     */
    function get_treeview($myid, $effected_id='example', $str="<span class='file'>\$name</span>", $str2="<span class='folder'>\$name</span>", $showlevel = 0, $style='filetree ', $currentlevel = 1, $recursion=FALSE) {
        $child = $this->get_child($myid);
        if (!defined('EFFECTED_INIT')) {
            $effected = ' id="' . $effected_id . '"';
            define('EFFECTED_INIT', 1);
        } else {
            $effected = '';
        }
        $placeholder = '<ul><li><span class="placeholder"></span></li></ul>';
        if (!$recursion) {
            $this->str .='<ul' . $effected . '  class="' . $style . '">';
        }
        if ($child) {
            foreach ($child as $id => $a) {

                @extract($a);
                if ($showlevel > 0 && $showlevel == $currentlevel && $this->get_child($id))
                    $folder = 'hasChildren'; //如设置显示层级模式@2011.07.01
                $floder_status = isset($folder) ? ' class="' . $folder . '"' : '';
                $this->str .= $recursion ? '<ul><li' . $floder_status . ' id=\'' . $id . '\'>' : '<li' . $floder_status . ' id=\'' . $id . '\'>';
                $recursion = false;
                //判断是否为终极栏目
                if ($this->get_child($id)) {
                    eval("\$nstr = \"$str2\";");
                    $this->str .= $nstr;
                    if ($showlevel == 0 || ($showlevel > 0 && $showlevel > $currentlevel)) {
                        $this->get_treeview($id, $effected_id, $str, $str2, $showlevel, $style, $currentlevel + 1, true);
                    } elseif ($showlevel > 0 && $showlevel == $currentlevel) {
                        $this->str .= $placeholder;
                    }
                } else {
                    eval("\$nstr = \"$str\";");
                    $this->str .= $nstr;
                }
                $this->str .= $recursion ? '</li></ul>' : '</li>';
            }
        }
        if (!$recursion) {
            $this->str .= '</ul>';
        }
        return $this->str;
    }

    /**
     * 同上一类方法，jquery treeview 风格，可伸缩样式（需要treeview插件支持）
     *
     * @author	matengfei
     * @param	int $myid 表示获得这个ID下的所有子级
     * @param 	string $effected_id 需要生成treeview目录数的id
     * @param 	string $str 末级样式
     * @param 	string $str2 目录级别样式
     * @param 	int $showlevel 直接显示层级数，其余为异步显示，0为全部显示
     * @param 	string $style 目录样式 默认 filetree 可增加其他样式如'filetree treeview-famfamfam'
     * @param 	int $currentlevel 计算当前层级，递归使用 适用改函数时不需要用该参数
     * @param 	bool $recursion 递归使用 外部调用时为false
     * @return 	string
     */
    function get_treeview_multi($arr, $effected_id = 'example', $str = '<span class="file">\$name</span>', $str2 = '<span class="folder">\$name</span>', $showlevel = 0 ,$style = 'filetree ', $currentlevel = 1, $recursion = false){
        if (!defined('EFFECTED_INIT')) {
            $effected = ' id="'.$effected_id.'"';
            define('EFFECTED_INIT', 1);
        } else {
            $effected = '';
        }
        $placeholder = 	'<ul><li><span class="placeholder"></span></li></ul>';
        if (!$recursion) {
            $this->str .='<ul'.$effected.'  class="'.$style.'">';
        }
        foreach ($arr as $id => $val) {
            @extract($val);
            if ($showlevel > 0 && $showlevel == $currentlevel && (isset($val['children']) && $val['children'])) {
                $folder = 'hasChildren'; //如设置显示层级模式
            }
            $floder_status = isset($folder) ? ' class="'.$folder.'"' : '';
            $this->str .= $recursion ? '<ul><li'.$floder_status.' id=\''.$id.'\'>' : '<li'.$floder_status.' id=\''.$id.'\'>';
            $recursion = false;
            if (isset($val['children']) && $val['children']) {
                if (isset($val['add_icon']) && $val['add_icon']) {
                    eval("\$nstr = \"$str\";");
                } else {
                    eval("\$nstr = \"$str2\";");
                }
                $this->str .= $nstr;
                if ($showlevel == 0 || ($showlevel > 0 && $showlevel > $currentlevel)) {
                    $this->get_treeview_multi($children, $effected_id, $str, $str2, $showlevel, $style, $currentlevel+1, true);
                } elseif ($showlevel > 0 && $showlevel == $currentlevel) {
                    $this->str .= $placeholder;
                }
            } else {
                if (isset($val['add_icon']) && $val['add_icon']) {
                    eval("\$nstr = \"$str\";");
                } else {
                    eval("\$nstr = \"$str2\";");
                }
                $this->str .= $nstr;
            }
            $this->str .= $recursion ? '</li></ul>' : '</li>';
        }
        if (!$recursion) {
            $this->str .='</ul>';
        }
        return $this->str;
    }

    /**
     * 同上一类方法，jquery treeview 风格，可伸缩样式（需要treeview插件支持）
     * @param $myid 表示获得这个ID下的所有子级
     * @param $effected_id 需要生成treeview目录数的id
     * @param $str 末级样式
     * @param $str2 目录级别样式
     * @param $showlevel 直接显示层级数，其余为异步显示，0为全部限制
     * @param $style 目录样式 默认 filetree 可增加其他样式如'filetree treeview-famfamfam'
     * @param $currentlevel 计算当前层级，递归使用 适用改函数时不需要用该参数
     * @param $recursion 递归使用 外部调用时为FALSE
     * @param $dropdown 有子元素时li的class
     */

    function get_treeview_menu($myid,$effected_id='example', $str="<span class='file'>\$name</span>", $str2="<span class='folder'>\$name</span>", $showlevel = 0,  $ul_class="" ,$li_class="" , $style='filetree ', $currentlevel = 1, $recursion=FALSE, $dropdown='hasChild') {
        $child = $this->get_child($myid);
        if (!defined('EFFECTED_INIT')) {
            $effected = ' id="' . $effected_id . '"';
            define('EFFECTED_INIT', 1);
        } else {
            $effected = '';
        }
        $placeholder = '<ul><li><span class="placeholder"></span></li></ul>';
        if (!$recursion){
            $this->str .='<ul' . $effected . '  class="' . $style . '">';
        }

        foreach ($child as $id => $a) {

            @extract($a);
            if ($showlevel > 0 && is_array($this->get_child($a['id']))){
                $floder_status = " class='$dropdown $li_class'";
            }else{
                $floder_status = " class='$li_class'";;
            }
            $this->str .= $recursion ? "<ul class='$ul_class'><li  $floder_status id= 'menu-item-$id'>" : "<li  $floder_status   id= 'menu-item-$id'>";
            $recursion = FALSE;
            //判断是否为终极栏目
            if ($this->get_child($a['id'])) {
                eval("\$nstr = \"$str2\";");
                $this->str .= $nstr;
                if ($showlevel == 0 || ($showlevel > 0 && $showlevel > $currentlevel)) {
                    $this->get_treeview_menu($a['id'], $effected_id, $str, $str2, $showlevel,   $ul_class ,$li_class ,$style, $currentlevel + 1, TRUE);
                } elseif ($showlevel > 0 && $showlevel == $currentlevel) {
                    //$this->str .= $placeholder;
                }
            } else {
                eval("\$nstr = \"$str\";");
                $this->str .= $nstr;
            }
            $this->str .=$recursion ? '</li></ul>' : '</li>';
        }
        if (!$recursion)
            $this->str .='</ul>';
        return $this->str;
    }

    /**
     * 获取子栏目json
     *
     * @author 	matengfei
     * @param	int $myid 表示获得这个ID下的所有子级
     * @param 	string $str
     * @return 	string
     */
    public function creat_sub_json($myid, $str='') {
        $sub_cats = $this->get_child($myid);
        $n = 0;
        if (is_array($sub_cats)) {
            foreach ($sub_cats as $c) {
                $data[$n]['id'] = iconv(CHARSET, 'utf-8', $c['catid']);
                if ($this->get_child($c['catid'])) {
                    $data[$n]['liclass'] = 'hasChildren';
                    $data[$n]['children'] = array(array('text' => '&nbsp;', 'classes' => 'placeholder'));
                    $data[$n]['classes'] = 'folder';
                    $data[$n]['text'] = iconv(CHARSET, 'utf-8', $c['catname']);
                } else {
                    if ($str) {
                        @extract(array_iconv($c, CHARSET, 'utf-8'));
                        eval("\$data[$n]['text'] = \"$str\";");
                    } else {
                        $data[$n]['text'] = iconv(CHARSET, 'utf-8', $c['catname']);
                    }
                }
                $n++;
            }
        }
        return json_encode($data);
    }

    /**
     * 获取位置
     *
     * @param	string $list
     * @param 	string $item
     * @return 	int
     */
    private function have($list, $item) {
        return(strpos(',,' . $list . ',', ',' . $item . ','));
    }




    
    /**
     * 把返回的数据集转换成Tree
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     * @return array
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    protected function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent['childs'][] = $data['id'];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    /**
     * 将树子节点加层级成列表
     */
    protected function _toFormatTree($tree, $level = 1) {
        foreach ($tree as $key => $value) {
            $temp = $value;
            if (isset($temp['_child'])) {
                $temp['_child'] = true;
                $temp['level'] = $level;
            } else {
                $temp['_child'] = false;
                $temp['level'] = $level;
            }
            array_push($this->formatTree, $temp);
            if (isset($value['_child'])) {
                $this->_toFormatTree($value['_child'], ($level + 1));
            }
        }
    }

    protected function cat_empty_deal($cat, $next_parentid, $pid='pid', $empty = "&nbsp;&nbsp;&nbsp;&nbsp;") {
        $str = "";
        if ($cat[$pid]) {
            for ($i=2; $i < $cat['level']; $i++) {
                $str .= $empty."│";
            }
            if ($cat[$pid] != $next_parentid && !$cat['_child']) {
                $str .= $empty."└─&nbsp;";
            } else {
                $str .= $empty."├─&nbsp;";
            }
        }
        return $str;
    }

    public function toFormatTree($list,$title = 'title',$pk='id',$pid = 'pid', $root = 0,  $empty = "&nbsp;&nbsp;&nbsp;&nbsp;"){
        if (empty($list)) {
            return false;
        }
        $list = $this->list_to_tree($list,$pk,$pid,'_child',$root);
        $this->formatTree = array();
        $this->_toFormatTree($list);
        $data = [];
        foreach ($this->formatTree as $key => $value) {
            $index = ($key+1);
            $next_parentid = isset($this->formatTree[$index][$pid]) ? $this->formatTree[$index][$pid] : '';
            $value['level_show'] = $this->cat_empty_deal($value, $next_parentid, $pid, $empty);
            $value['title_show'] = $value['level_show'].$value[$title];
            $data[] = $value;
        }
        return $data;
    }
}