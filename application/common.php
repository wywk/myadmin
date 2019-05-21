<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 不区分大小写的in_array实现
 * @author     luffy
 * @param      string $value
 * @param      array  $array
 * @return     bool
 */
function in_array_case($value, $array){
    return in_array(strtolower($value), array_map('strtolower', $array));
}

/**
 * 数据签名认证
 * @author     luffy
 * @param      array $data 被认证的数据
 * @return     string 签名
 */
function data_auth_sign($data){
    //数据类型检测
    if (!is_array($data)) {
        $data = (array)$data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}

/**
 * 检测用户是否登录
 * @author     luffy
 * @param      bool  $module       登录数据模块
 * @return     int 0-未登录，大于0-当前登录用户ID
 */
function is_login($module){
    $user = session('user_auth','', $module);
    if (empty($user)) {
        return 0;
    } else {
        return session('user_auth_sign','', $module) == data_auth_sign($user) ? $user['uid'] : 0;
    }
}

/**
 * 检测当前用户是否为管理员
 * @author     luffy
 * @param      bool  $module       登录数据模块
 * @return     bool true-管理员，false-非管理员
 */
function is_administrator($module, $user_id = null){
    $user_id = is_null($user_id) ? is_login($module) : $user_id;
    return $user_id && (intval($user_id) === config('user_administrator'));
}

/**
 * 设置登录状态
 * @author     luffy
 * @param      array $user         登录数据
 * @param      bool  $module       登录数据模块
 * @param      bool  $remember     是否设置cookie
 * @return     int
 */
function auto_login($user, $module, $remember=false){
    //记录登录SESSION和COOKIES
    $auth = [
        'uid'   => $user['id'],
        'name'  => $user['name'],
    ];
    session('user_auth', $auth, $module);
    session('user_auth_sign', data_auth_sign($auth), $module);
    if ($remember) {
        cookie('admin_name', $user['name'], 3600*24*30);
    } else {
        cookie('admin_name', null);
    }
    return is_login($module);
}

/**
 * 获取客户端IP地址
 * @author     luffy
 * @param     int $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param     bool $adv 是否进行高级模式获取（有可能被伪装）
 * @return     mixed
 */
function get_client_ip($type=0, $adv=false){
    $type      = $type ? 1 : 0;
    static $ip = null;
    if ($ip !== null) {
        return $ip[$type];
    }
    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) {
                unset($arr[$pos]);
            }

            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 密码加密方法
 * @author    luffy
 * @param     string $pw 要加密的字符串
 * @param     string $authcode 密钥
 * @return     string
 */
function password($pw, $authcode=''){
    if (empty($authcode)) {
        $authcode = config('authcode');
    }
//     $result = md5(md5($authcode.$pw));
    $result = md5($pw);
    return $result;
}

/**
 * 密码比较方法,所有涉及密码比较的地方都用这个方法
 * @author     luffy
 * @param     string $password 要比较的密码
 * @param     string $password_in_db 数据库保存的已经加密过的密码
 * @return     bool 密码相同，返回true
 */
function compare_password($password, $password_in_db){
    return password($password) == $password_in_db;
}

/**
 * 图片名称加密方法
 * @author    luffy
 * @param     string $imagename 要加密的图片名称
 * @param     string $authcode 密钥
 * @param     int $width 图片宽度
 * @param     int $height 图片高度
 * @return    string
 */
function imagename($imagename, $authcode='', $width='', $height=''){
    if (empty($authcode)) {
        $authcode = config('authcode');
    }
    $result = md5(md5($authcode.$imagename.$width.$height));
    return $result;
}

/**
 * 选择下拉框组件
 * @author     luffy
 * @param      array $arr
 * @param      int or string $selected
 * @param      string $show_field 支持多个字段显示 格式field_a,field_b
 * @param      string $val_field
 * @return     string
 */
function make_option($arr, $selected='', $show_field='', $val_field='') {
    $ret = '';
    $show_field_arr = explode(',', $show_field);
    if (is_array($arr)) {
        foreach ($arr as $k => $v) {
            $show_text = '';
            if (is_array($v)) {
                foreach ($show_field_arr as $s) {
                    $show_text .= $v[$s].' ';
                }
                $show_text = substr($show_text, 0, -1);
                $val_field && $k = $v[$val_field];
            } else {
                $show_text = $v;
            }
            $sel = '';
            if ($selected && $k == $selected) {
                $sel = 'selected="selected"';
            }
            $ret .= '<option value="' . $k . '" ' . $sel . '>' . $show_text . '</option>';
        }
    }
    return $ret;
}

/**
 * 生成复选框checkbox
 *
 * @author    matengfei
 * @param    array $arr
 * @param    string $name
 * @param     string or $checked_array
 * @param     int $per_line
 * @param     string $value_field
 * @param     string $text_field
 * @param     string $class
 * @param     string $label
 * @return     string
 */
function make_checkbox($arr, $name, $checked_array=[], $per_line=1, $value_field='', $text_field='', $class='lay-skin="primary"', $label='') {
    $ret = '';
    $i = 0;
    foreach ($arr as $k => $v) {
        $show_name  = $name . '[]';
        $show_id    = $name . '_' . $k;
        if ($i%$per_line == 0 && $label == '') {
            $ret .= '<div>';
        }
        if (is_array($v)) {
            $s_v = $v[$value_field];
            $s_t = $v[$text_field];
        } else {
            $s_v = $k;
            $s_t = $v;
        }
        $checked = '';
        if ($checked_array && in_array($s_v, $checked_array)) {
            $checked = 'checked="checked"';
        }
        $cls = '';
        if ($class) {
            $cls = $class;
        }
        if ($label) {
            $ret .= "<{$label}{$cls}>" . '<input type="checkbox" name="' . $show_name . '" id="' . $show_id . '" value="' . $s_v . '" ' . $checked . '><label for="' . $show_id . '">' . $s_t . '</label>' . "</{$label}>";
        } else {
            $ret .= '<input type="checkbox" name="' . $show_name . '" id="' . $show_id . '" value="' . $s_v . '" title="' . $s_t . '"' . $checked . $cls . '>';
        }
        $i++;
        if ($i%$per_line == 0 && $label == '') {
            $ret .= '</div>';
        }
    }
    //最后一个DIV 补全
    if ($i%$per_line != 0 && $label == '') {
        $ret .= '</div>';
    }
    return $ret;
}

/**
 * 生成单选按钮radio
 *
 * @author    matengfei
 * @param     array $arr
 * @param     int or string $check
 * @param     string $name
 * @param     string $id
 * @param     string $val
 * @param     string $field
 * @param     int $show_num
 * @return     string
 */
function make_radio($arr, $check='', $name='', $id='', $val='', $field='', $show_num=10) {
    $ret = '';
    $m = 1;
    if (is_array($arr)) {
        foreach ($arr as $k => $v) {
            $show_name  = $name;
            $show_id    = $name . '_' . $k;
            if (is_array($v)) {
                $show_val   = $v[$val];
                $show_field = $v[$field];
            } else {
                $show_val = $k;
                $show_field = $v;
            }
            $sel = '';
            if ($k == $check) {
                $sel = 'checked="checked"';
            }
            $ret .= '<input type="radio" name="' . $show_name . '" id="' . $show_id . '" value="' . $show_val . '" ' . $sel . '" title="' . $show_field . '">';
            if ($m % $show_num == 0) {
                $ret .= '<br>';
            }

            $m ++;
        }
        $ret = rtrim($ret);
    }
    return $ret;
}

/**
 * 将2维数组的索引变为第2维某索引
 *
 * @author    matengfei
 * @param     array $arr
 * @param     string $index
 * @param   array
 */
function change_array_key($arr, $index){
    $arr1 = [];
    foreach ($arr as $key => $val){
        $arr1[$val[$index]] = $val;
    }
    return $arr1;
}

/**
 * 移动文件（如果当前环境是开发环境,进行拷贝,否则进行移动）
 *
 * @author    matengfei
 * @param     string $input
 * @param     string $newFile
 */
function ecm_rename($input, $path='', $root_path=UPLOAD_PATH){
    if (strpos($input, UPLOAD_URL) !== false) {
        $input = str_replace(UPLOAD_URL, '', $input);
    }
    $value = $input;
    if (!$value) {
        return '';
    }
    $array = explode('/', $value);
    if (in_array('temp', $array)) {
        $old_file = $root_path . $value;
        $tmp_file = str_replace('temp', $path, $value);
        $new_file = $root_path . $tmp_file;
        $new_path = dirname($new_file);
        if (!file_exists($new_path)) {
            //mkdir($new_path, 0755, true);
            ecm_mkdir($new_path, $root_path);
        }
        if (config('app_debug' == true)) {
            @copy($old_file, $new_file);
        } else {
            @rename($old_file, $new_file);
        }
        if (file_exists($old_file)) {
            unlink($old_file);
        }
        return $tmp_file;
    } else {
        return $value;
    }
}

/**
 * 创建目录（如果该目录的上级目录不存在，会先创建上级目录）
 * 依赖于 $root_path 常量，且只能创建 $root_path 目录下的目录
 * 目录分隔符必须是 / 不能是 \
 *
 * @author    matengfei
 * @param   string $absolute_path 绝对路径
 * @param     string $root_path
 * @param   int $mode 目录权限
 * @return  bool
 */
function ecm_mkdir($absolute_path, $root_path=ROOT_PATH, $mode=0777){
    if (is_dir($absolute_path)) {
        return true;
    }
    $root_path     = $root_path;
    $relative_path = str_replace($root_path, '', $absolute_path);
    $each_path     = explode('/', $relative_path);
    $cur_path      = $root_path; //当前循环处理的路径
    foreach ($each_path as $path) {
        if ($path) {
            $cur_path = $cur_path . $path . '/';
            if (!is_dir($cur_path)) {
                if (@mkdir($cur_path, $mode)) {
                    fclose(fopen($cur_path . 'index.htm', 'w'));
                } else {
                    return false;
                }
            }
        }
    }
    return true;
}

/**
 * 删除目录,不支持目录中带 ..
 *
 * @author    matengfei
 * @param    string $dir
 * @return    bool
 */
function ecm_rmdir($dir){
    $dir = str_replace(array('..', "\n", "\r"), array('', '', ''), $dir);
    $ret_val = false;
    if (is_dir($dir)) {
        $d = @dir($dir);
        if ($d) {
            while(false !==($entry = $d->read())) {
                if ($entry != '.' && $entry != '..') {
                    $entry = $dir . '/' . $entry;
                    if (is_dir($entry)) {
                        ecm_rmdir($entry);
                    } else {
                        @unlink($entry);
                    }
                }
            }
            $d->close();
            $ret_val = rmdir($dir);
        }
    } else {
        $ret_val = unlink($dir);
    }
    return $ret_val;
}

/**
 * 从文件或数组中定义常量
 *
 * @author    matengfei
 * @param    mixed $source
 * @return     void
 */
function ecm_define($source){
    if (is_string($source)) {
        /* 导入数组 */
        $source = include($source);
    }
    if (!is_array($source)) {
        /* 不是数组，无法定义 */
        return false;
    }
    foreach ($source as $key => $value) {
        if (is_string($value) || is_numeric($value) || is_bool($value) || is_null($value)) {
            /* 如果是可被定义的，则定义 */
            define(strtoupper($key), $value);
        }
    }
}

/**
 * 获取当前时间的微秒数
 *
 * @author    matengfei
 * @param     void
 * @return    float
 */
function ecm_microtime(){
    if (PHP_VERSION >= 5.0) {
        return microtime(true);
    } else {
        list($usec, $sec) = explode(' ', microtime());
        return((float)$usec +(float)$sec);
    }
}

/**
 * 去除链接的方法
 *
 * @author    matengfei
 * @param     array $tagsArr
 * @param     string $str
 * @return     mixed
 */
function ecm_stripTags($tagsArr, $str) {
    foreach ($tagsArr as $tag) {
        $p[] = "/(<(?:\/".$tag."|".$tag.")[^>]*>)/i";
    }
    $return_str = preg_replace($p, '', $str);
    return $return_str;
}

/**
 * 获取拼音
 *
 * @author    matengfei
 * @param     string $str
 * @param    int $ishead
 * @param    int $isclose
 * @param    int $islower
 * return     string
 */
function pinyin($str, $ishead=0, $isclose=1, $islower=1) {
    global $pinyins;
    $restr = '';
    $str = iconv('utf-8', 'gbk', trim($str));
    $slen = strlen($str);
    if ($slen < 2) {
        return $str;
    }
    if (count($pinyins) == 0) {
        $fp = fopen(ROOT_PATH . 'data/dat/pinyin.dat', 'r');
        while (!feof($fp)) {
            $line = trim(fgets($fp));
            $pinyins[$line[0].$line[1]] = substr($line, 3, strlen($line)-3);
        }
        fclose($fp);
    }

    for ($i=0; $i<$slen; $i++) {
        if (ord($str[$i]) > 0x80) {
            $c = $str[$i] . $str[$i+1];
            $i++;
            if (isset($pinyins[$c])) {
                if ($ishead == 0) {
                    $restr .= $pinyins[$c];
                } else {
                    $restr .= $pinyins[$c][0];
                }
            } else {
                $restr .= '_';
            }
        } elseif (preg_match("/[a-z0-9]/i", $str[$i])) {
            $restr .= $str[$i];
        } else {
            $restr .= '_';
        }
    }
    if ($isclose == 0) {
        unset($pinyins);
    }
    if ($islower) {
        $restr = strtolower($restr);
    }
    return $restr;
}

/**
 * 获取系统菜单列表
 *
 * @param array $list
 * @param int $pid
 * @return string
 */
function get_tree($list, $pid){
    $tree = new \org\Tree();
    $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
    $tree->init($list);
    $select_categorys = $tree->get_tree(0, $str, $pid);
    return $select_categorys;
}

/**
 * 将分类按照层级处理
 *
 * @author    matengfei
 * @param    array $categories
 * @param     int $parent_id
 * @param     int $level
 * @param     int $inchildren
 * @param     string $id_field
 * @param     string $parent_field
 * @return     array
 */
function tree_categories($categories, $parent_id=0, $level=1, $inchildren=1, $id_field='id', $parent_field='pid'){
    if (empty($categories)) {
        return array();
    }
    $return_data = array();
    foreach ($categories as $key => $category) {
        if ($category[$parent_field] == $parent_id) {
            $category['level_depth'] = $level;
            unset($categories[$key]);
            $children = tree_categories($categories, $category[$id_field], $level+1, $inchildren, $id_field, $parent_field);
            if (!empty($children)) {
                if ($inchildren == 0) {
                    if ($id_field) {
                        $return_data[$category[$id_field]] = $category;
                    } else {
                        $return_data[] = $category;
                    }
                    $return_data += $children;
                } else {
                    $category['children'] = $children;
                    if ($id_field) {
                        $return_data[$category[$id_field]] = $category;
                    } else {
                        $return_data[] = $category;
                    }
                }
            } else {
                if ($id_field) {
                    $return_data[$category[$id_field]] = $category;
                } else {
                    $return_data[] = $category;
                }
            }
        }
    }
    return $return_data;
}

/**
 * GUID生成函数
 *
 * @author    matengfei
 * @param    int $length
 * @return     string
 */
function guid($length=20) {
    $Guid = new \org\Guid();
    $guid = $Guid->toString();
    $guid = substr(str_replace('-', '', $guid), 0, $length);
    return $guid;
}

/**
 * 获取的URL地址的内容
 *
 * @author    matengfei
 * @param     string $url
 * @return    string
 */
function get_content($url, $show_error=true) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 50);
    $output = curl_exec($ch);
    if (curl_errno($ch) && $show_error) {
        echo 'Curl error: ' . curl_error($ch);
    }
    curl_close($ch);
    return $output;
}

/**
 * 下载远程图片保存在本地 - 自动识别图片格式
 *
 * @author    matengfei
 * @param    string $url 图片地址
 * @param     string $root_path 图片保存路径
 * @param    string $dst_path 目标路径
 * @param    bool $relative 是否相对路径
 * @param    bool $rand 是否随机
 * @param     int $rand_begin 随机开始时间
 * @param   int $rand_end 随机结束时间
 * @return    false or string
 */
function getImgByUrl($url, $root_path=UPLOAD_TEMP_PATH, $dst_path='', $relative=false, $rand=false, $rand_begin=0, $rand_end=0){
    $content = get_content($url, false);
    if (!$content) { //图片资源不存在
        echo '图片为空';
        return false;
    }
    if ($content{0} . $content{1} == "\xff\xd8") {
        $ext = 'jpg';
    } elseif ($content{0} . $content{1} . $content{2} == "\x47\x49\x46") {
        $ext = 'gif';
    } elseif ($content{0} . $content{1} . $content{2} == "\x89\x50\x4e") {
        $ext = 'png';
    } else {
        echo "图片无效";
        return false;
    }
    if (!file_exists($root_path)) {
        ecm_mkdir($root_path, UPLOAD_PATH);
    }
    if ($rand) {
        if ($rand_begin && $rand_end) {
            $date = date('Ymd', mt_rand($rand_begin, $rand_end));
        } else {
            $date = date('Ymd', mt_rand(strtotime("-1 year"), time()));
        }
    } else {
        $date = date('Ymd');
    }
    if ($dst_path) {
        $dst_path = $root_path . $dst_path . '/';
    } else {
        $dst_path = $root_path . $date . '/';
    }
    if (!file_exists($dst_path)) {
        ecm_mkdir($dst_path, $root_path);
    }
    @chmod($dst_path, 0777);
    //$savename = substr(md5(date('HisYmd') . rand (0, 100)), -10);
    $savename = md5(microtime(true));
    $filename = $dst_path . $savename . ".{$ext}";
    file_put_contents($filename, $content);
    @chmod($filename, 0777);
    if ($relative) {
        $filename = str_replace(UPLOAD_PATH, '', $filename);
    }
    return $filename;
}

/**
 * 根据数组生成key
 *
 * @author    matengfei
 * @param    array $arr
 * @return    string
 */
function get_key($arr){
    $temp = [];
    foreach ($arr as $v) {
        if (is_array($v)) {
            $temp[] = implode('_', $v);
        } else {
            $temp[] = $v;
        }
    }
    $key = implode('_', $temp);
    return $key;
}

/**
 * 检测内容是否包含相应的关键字(格式为key1|key2|key3|....)
 *
 * @author    matengfei
 * @param    string $string - 要检测的字符串
 * @param    string $fileName - 关键字文件名
 * @return    bool - 含有关键词时返回true,否则返回false
 */
function badwordCheck($string, $fileName = '') {
    if (!$fileName) {
        $file = ROOT_PATH . 'data/dat/blacklist.dat';
    } else {
        $file = ROOT_PATH . "data/dat/{$fileName}.dat";
    }
    $words = file_get_contents($file);
    $string = strtolower($string);
    $matched = preg_match('/' . $words . '/i', $string, $result);
    if ($matched && isset($result[0]) && strlen($result[0]) > 0) {
        if (strlen($result[0]) == 2) {
            $matched = preg_match('/' . $words . '/iu', $string, $result);
        }
        if ($matched && isset($result[0]) && strlen($result[0]) > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * 获取字符串绝对长度
 *
 * @author    matengfei
 * @param     string $str 字符串
 * @return    int
 */
function absLength($str) {
    if (empty($str)) {
        return 0;
    }
    if (function_exists('mb_strlen')) {
        return mb_strlen($str, 'utf-8');
    } else {
        preg_match_all('/./u', $str, $match);
        return count($match[0]);
    }
}

/**
 * 获取中文字符串长度
 *
 * @author    matengfei
 * @param     string $str 字符串
 * @return    int
 */
function getChLen($str) {
    /* $reg = '/[\x{4e00}-\x{9fa5}]/siu';
     preg_match_all($reg, $str, $matches);
    $length = count($matches[0]); */

    /* $length = strlen(preg_replace('/[\x00-\x7F]/', '', $str));
     $length = (int)$length/3; */

    $encode = 'UTF-8';
    $str_num = mb_strlen($str, $encode);
    $length = 0;
    for ($i=0; $i < $str_num; $i++) {
        if (ord(mb_substr($str, $i, 1, $encode))> 0xa0) {
            $length++;
        }
    }
    return $length;
}

/**
 * 获取中文字母数字混合字符串长度
 *
 * @author    matengfei
 * @param     string $str 字符串
 * @return    int
 */
function getMixLen($str) {
    preg_match_all("/[0-9]{1}/", $str, $arrNum);
    preg_match_all("/[a-zA-Z]{1}/", $str, $arrAl);
    preg_match_all("/([\x{4e00}-\x{9fa5}]) {1}/u", $str, $arrCh);
    $length = (int)count($arrNum[0])+(int)count($arrAl[0])+(int)count($arrCh[0]);
    return $length;
}

/**
 * 取得数组中某一字段的集合
 *
 * @author     matengfei
 * @param     array $data_lists
 * @param     string $col_name
 * @param     string $ret a: 返回array, s: 返回隔开的字符串
 * @param    int $dimension 维度
 * @return     array or string
 */
function get_col(&$data_lists, $col_name, $ret_type='a', $dimension=2) {
    $ret = array();

    if (is_array($data_lists)) {
        switch ($dimension) {
            case 2:
                foreach ($data_lists as $r) {
                    if ($r[$col_name] && !in_array($r[$col_name], $ret)) {
                        $ret[] = $r[$col_name];
                    }
                }
                break;
            case 3:
                foreach ($data_lists as $tmp_r) {
                    foreach ($tmp_r as $r) {
                        if ($r[$col_name] && !in_array($r[$col_name], $ret)) {
                            $ret[] = $r[$col_name];
                        }
                    }
                }
                break;
        }
    }
    if ($ret_type != 'a') {
        return  implode(',', $ret);
    }
    return $ret;
}

/**
 * 下划线转驼峰
 * @param $uc true:首字母大写 false:首字母小写
 */
function camelize($uncamelized_words,$uc=true,$separator='_')
{
    if (strtolower($uncamelized_words)!=$uncamelized_words) { // 输入含大写字母
        return $uncamelized_words;
    }
    if ($uc) {
        $uncamelized_words = str_replace($separator, " ", strtolower($uncamelized_words));
    } else {
        $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
    }
    return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
}

/**
 * 驼峰命名转下划线命名
 * 思路:
 * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
 */
function uncamelize($camelCaps,$separator='_')
{
    return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}

/**
 * 获取一个service
 *
 * @author    matengfei
 * @param     string $model_name 模型名称
 * @return     object
 */
function service($name='', $appendSuffix=false){
    return model($name, 'service', $appendSuffix);

//     static $models = array();
//     $name = trim($name);
//     $name || $name='base';
//     $name = camelize($name); // 支持传入下划线分隔的命名
//     $namespace = '\\app\\admin\\service\\';
//     $class_name = $namespace.$name;
//     if (!isset($models[$class_name])) {
//         if (!class_exists($class_name)) {
//             return false;
//         }
//         $models[$class_name] = new $class_name();
//     }
//     return $models[$class_name];
}


/**
 * 获取数组某个下标的值
 * @date: 2017-8-10
 * @author: cc
 */
function getter($arr, $key, $default=null, $filter=null, $params=[]) {
    if (!is_array($arr)) {
        return $default;
    } else {
        if ( isset($arr[$key]) ) {
            if ( $filter ) {
                if (is_callable($filter)) {
                    $replace_key = array_search('{REPLACE}', $params);
                    if ($replace_key==false) {
                        array_unshift($params,$arr[$key]);
                    } else {
                        $params[$replace_key] = $arr[$key];
                    }
                    return call_user_func_array($filter, $params);
                } else {
                    return $default;
                }
            } else {
                return $arr[$key];
            }
        } else {
            return $default;
        }
    }
}

// bootstrap-suggest控件所需的数据格式
function boot_suggest_message($value, $message='', $code=200, $redirect='') {
    $data = [
        'message' => $message,
        'value' => $value,
        "code" => $code,
        "redirect" => $redirect,
    ];
    return json_encode($data);
}

/**
 * 获取格式化后的一维数组
 */
function format_array($data, $key_name, $value_name) {
    if (!is_array($data)) return [];
    $ret = [];
    if (!empty($data)) {
        foreach ($data as $val) {
            $key_name ? $ret[$val[$key_name]] = $val[$value_name] : $ret[] = $val[$value_name];
        }
    }
    return $ret;
}

/**
 * 导出数据到Excel
 * @date: 2017-8-18
 * @author: cc
 */
function exportDataToExcelTemplate($exportData, $fill_template, $file_name, $save_local = false, $local_path = ''){
    require(EXTEND_PATH  . 'org/phpexcel/IOFactory.php');
    //加载模板
    $objPHPExcel = new \PHPExcel();
    $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
    $objPHPExcel = $objReader->load($fill_template);
    $target = $objPHPExcel;

    $exportData = array_values($exportData);
    foreach ( $exportData as $key => $val ) {
        $val = array_values($val);
        $target->setActiveSheetIndex($key);
        $sheet = $target->getActiveSheet();

        $highestRow = $sheet->getHighestRow();//获取行数
        $highestColumn = $sheet->getHighestDataColumn();
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
        // echo $highestRow . '<br>' . $highestColumn . '<br>' . $highestColumnIndex;

        //excel表头的标记
        $excle_no = [
            'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
            'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
            'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ',
            'CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ',
            'DA','DB','DC','DD','DE','DF','DG','DH','DI','DJ','DK','DL','DM','DN','DO','DP','DQ','DR','DS','DT','DU','DV','DW','DX','DY','DZ',
            'EA','EB','EC','Ed','EE','EF','EG','EH','EI','EJ','EK','EL','EM','EN','EO','EP','EQ','ER','ES','ET','EU','EV','EW','EX','EY','EZ',
            'FA','FB','FC','Fd','EF','FF','FG','FH','FI','FJ','FK','FL','FM','FN','FO','FP','FQ','FR','FS','FT','FU','FV','FW','FX','FY','FZ'
        ];
        //要插入数据的行数
        $insertLines = count($val);
        for ($i=0; $i < $insertLines; $i++) {
            $line = $i + $highestRow + 1;
            for ($j=0 ; $j < count($val[$i]); $j++ ) {
                if ( is_array($val[$i][$j]) ){
                    if ( !empty($val[$i][$j]['style']) ){
                        if ( is_numeric($val[$i][$j]['value']) ) $val[$i][$j]['value'] = $val[$i][$j]['value'] . ' ';
                        $target->getActiveSheet()->getStyle("{$excle_no[$j]}{$line}")->applyFromArray($val[$i][$j]['style']);
                    }
                    $target->getActiveSheet()->getCell("{$excle_no[$j]}{$line}")->setValue($val[$i][$j]['value']);
                } else {
                    if ( is_numeric($val[$i][$j]) ) $val[$i][$j] = $val[$i][$j] . ' ';
                    $target->getActiveSheet()->getCell("{$excle_no[$j]}{$line}")->setValue($val[$i][$j]);
                }

            }
        }
    }

    if ( $save_local ){
        $filename = $local_path . $file_name . '.xlsx';
        $objWriter = \PHPExcel_IOFactory::createWriter($target, 'Excel2007');
        $objWriter->save($filename);
        return $filename;
    } else {
        //写入文件
        header("Content-type: text/csv");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8');
        $filename = $file_name . '.xlsx';
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        //header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($target, 'Excel2007');
        $objWriter->save('php://output');

        return true;
    }
}

/**
 * 文件下载
 *
 * @author  luffy
 * @param   string $path    文件路径
 * @param   string $fileName 文件名
 * @param   string $reFileName 另存为文件名
 * @return  void
 */
function getDownLoadFile($path, $fileName, $reFileName = ''){
    header("Content-type:text/html;charset=utf-8");
    if ($reFileName == '') {
        $reFileName = $fileName;
    }
    //用以解决中文不能显示出来的问题
    $file_name = iconv('utf-8', 'gb2312', $reFileName);
    $file_path = $path . $fileName;
    //首先要判断给定的文件存在与否
    if (!file_exists($file_path)) {
        return '没有该文件';
        exit;
    }
    $fp = fopen($file_path, "r");
    $file_size = filesize($file_path);
    //下载文件需要用到的头
    Header("Content-type: application/octet-stream");
    Header("Accept-Ranges: bytes");
    Header("Accept-Length:".$file_size);
    Header("Content-Disposition: attachment; filename=".$file_name);
    $buffer = 1024;
    $file_count = 0;
    //向浏览器返回数据
    while (!feof($fp) && $file_count<$file_size) {
        $file_con = fread($fp,$buffer);
        $file_count += $buffer;
        echo $file_con;
    }
    fclose($fp);
}

/**
 * 从Excel里获取数组
 * @date: 2017-8-19
 * @author: luffy
 * @param string $filePath
 * @param int $sheet
 * @return array|void
 * @throws PHPExcel_Exception
 * @throws PHPExcel_Reader_Exception
 */
function format_excel2array($filePath='',$sheet=0){
    require(EXTEND_PATH  . 'org/phpexcel/IOFactory.php');
    $PHPReader = new PHPExcel_Reader_Excel2007();        //建立reader对象
    if(!$PHPReader->canRead($filePath)){
        $PHPReader = new PHPExcel_Reader_Excel5();
        if(!$PHPReader->canRead($filePath)){
            echo 'no Excel';
            return ;
        }
    }
    $PHPExcel = $PHPReader->load($filePath);        //建立excel对象
    $currentSheet = $PHPExcel->getSheet($sheet);        //**读取excel文件中的指定工作表*/
    $allColumn = $currentSheet->getHighestColumn();        //**取得最大的列号*/
    $allRow = $currentSheet->getHighestRow();        //**取得一共有多少行*/
    $data = array();
    for($rowIndex=1;$rowIndex<=$allRow;$rowIndex++){        //循环读取每个单元格的内容。注意行从1开始，列从A开始
        $row = array();
        for($colIndex='A';$colIndex<=$allColumn;$colIndex++){
            $addr = $colIndex.$rowIndex;
            $cell = $currentSheet->getCell($addr)->getValue();
            if($cell instanceof PHPExcel_RichText){ //富文本转换字符串
                $cell = $cell->__toString();
            }
            $row[$colIndex] = $cell;
        }
        //过滤为空的数据
        $row = array_filter($row,function($item){
            if($item){
                return true ;
            }else{
                return false;
            }
        });
        if(!empty($row)){ //如果全部为空，则不取数据
            $data[$rowIndex] = $row;
        }
    }
    return $data;
}

/**
 *
 * 发送短信
 *
 */
function send_sms( $send_id, $to_id , $smsmobile, $smscontent = '',$sendtime = '', $extno = ''){

    $msg = array("status"=>false,'send_id'=> '','msg'=> '发送失败') ;
    try{
        if(!$send_id || $send_id == ''){
            throw new Exception('发送人不能为空！');
        }

        if(!$to_id || $to_id == ''){
            throw new Exception('收信人不能为空！');
        }

        if(!$smsmobile || $smsmobile == '' ){
            throw new Exception('手机号不能为空！');
        }

        if(!$smscontent || $smscontent == ''){
            throw new Exception('发送短信内容不能为空！');
        }

        $flag = 0;
        $params='';//要post的数据

        $smscontent = $smscontent != ''  ? $smscontent : '' ;
        $sendtime = $sendtime != '' ?  $sendtime : '' ;
        $extno = $extno != '' ?  $extno : '' ;

        //以下信息自己填以下
        $argv = array(
            'name'      =>  'swOA',     //必填参数。用户账号
            'pwd'       =>  'B4E3057C3FD414BC41BAEDB471ED', //必填参数。（web平台：基本资料中的接口密码）
            'content'   =>  $smscontent,        //必填参数。发送内容（1-500 个汉字）UTF-8编码
            'mobile'    =>  $smsmobile,         //必填参数。手机号码。多个以英文逗号隔开
            'stime'     =>  $sendtime,          //可选参数。发送时间，填写时已填写的时间发送，不填时为当前时间发送
            'sign'      =>  '沃邦贷',   //必填参数。用户签名。
            'type'      =>  'pt',               //必填参数。固定值 pt
            'extno'     =>  $extno              //可选参数，扩展码，用户定义扩展码，只能为数字
        );

        //构造要post的字符串
        foreach ($argv as $key=>$value) {
            if ($flag!=0) {
                $params .= "&";
                $flag = 1;
            }
            $params.= $key."="; $params.= urlencode($value);// urlencode($value);
            $flag = 1;
        }
        $url = "http://web.cr6868.com/asmx/smsservice.aspx?".$params; //提交的url地址
        $reponse = file_get_contents($url);  //获取信息发送后的状态

        //code,sendid,invalidcount,successcount,blackcount,msg
        $result = explode(',',$reponse) ;

        //发送短信
        if($result['0'] == '0'){
            $msg['status'] = true ;
            $msg['send_id'] = $result[1] ;
            $msg['msg'] = '发送成功';
        }

        $db = db() ;
        $sql = "Insert into oa_sms (`send_id`,`to_id`,`sms_type`,`content`,`send_time`,`add_user`,`add_time`,`send_mobile`,`send_code`,`send_status`) VALUE ('".$send_id."','".$to_id."',4,'".$smscontent."','".time()."','".$send_id."','".time()."','".$smsmobile."','".$result[1]."','".$result[0]."')";
        $db->Execute($sql);

    }catch(Exception $e){
        $msg['msg'] = $e->getMessage();
    }

    return $msg ;
}