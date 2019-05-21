<?php
namespace app\common\model;

use app\common\model\Base;

/**
 * 用户模型
 * Class User
 * @package app\common\model
 * @author zhangkx
 * @date 2019/4/25
 */
class User extends Base
{

    /**
     * 自定义初始化
     */
    protected function initialize(){
        parent::initialize();
    }

    /**
     * 状态
     * @var array
     */
    public $status = [
        1 => '启用',
        2 => '停用'
    ];

    /**
     * 来源
     * @var array
     */
    public $source = [
        1 => '超管后台',
        2 => '店铺后台',
        3 => 'PC前台',
        4 => '公众号',
        5 => '小程序',
        6 => 'app端'
    ];

    /**
     * 性别
     * @var array
     */
    public $sex = [
        1 => '男',
        2 => '女',
        3 => '保密'
    ];


    /**
     * 根据用户ID获取角色,返回值为数组
     * 
     * @author    matengfei
     * @param     int $uid 用户ID
     * @return     array 用户所属的角色[
     *     ['user_id'=>'用户id','role_id'=>'角色id','name'=>'角色名称','rules'=>'角色拥有的规则id,多个,号隔开']
     * ]
     */
    public function getRoles($uid) {
        static $roles = [];
        if (isset($roles[$uid])) {
            return $roles[$uid];
        }
        $user_roles = db('role_user')->alias('a')
            ->join('__ROLE__ b', 'b.id=a.role_id')
            ->where("a.user_id='{$uid}' and b.status='1'")
            ->field('user_id,role_id,name,rules')->select();
        $roles[$uid] = $user_roles ? $user_roles : [];
        return $roles[$uid];
    }
    
    /*
     * 获取用户及其角色的所有权限
     */
    public function getRules($user_id) {
    	$rule_id_list = [];
    	// 角色
    	$roles = $this->getRoles($user_id);
    	foreach ($roles as $val) {
    		$rules = explode(',', $val['rules']);
    		$rule_id_list = array_merge($rule_id_list, $rules);
    	}
    	// 自身
    	$user_info = $this->getInfo($user_id);
    	$rules = explode(',', $user_info['rules']);
    	$rule_id_list = array_merge($rule_id_list, $rules);
    	// 去重
    	$rule_id_list = array_unique($rule_id_list);
    	return $rule_id_list;
    }

}