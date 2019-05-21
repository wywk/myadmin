<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: luffy <1549626108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model;

use think\Model;
use think\Cache;

/**
 * 模型基类
 * @author	luffy
 * @date    2019-04-01
 * @version 1.0
 */
class Base extends Model {
	
	/**
	 * 是否缓存
	 */
	protected $is_cache = FALSE;
	
	/**
	 * 请求参数
	 */
	protected $param;

    /**
     * 缓存对象
     */
    protected $cache;
     
    /**
     * 自定义初始化
     */
    protected function initialize(){
    	parent::initialize();

    	//获取请求参数
    	$this->param = request()->param();
    	//初始化缓存
  //   	$this->cache = Cache::connect(config('cache.default'));
    }
    
    //************************基础操作************************//
    /**
     * 删除表
     * @author 	luffy
     * @param 	string $tablename 表名
     * @return 	bool
     */
    final public function drop_table($tablename){
    	$tablename = config('database.prefix') . $tablename;
    	return $this->query("DROP TABLE {$tablename}");
    }

    /**
     * 获取表总记录数
     * @author 	luffy
     * @param 	string $tablename 表名
     * @return 	bool
     */
    final public function getAllCount($tablename = ''){
        if (empty($tablename)) {
            // 当前模型名
            $this->getTableName();
        }
        //获取数据
        $result = $this->query("EXPLAIN SELECT count(*) FROM {$tablename}");
        return $result[0]['rows'];
    }

    /**
     * 获取实例化模型表明
     * @author 	luffy
     * @return 	bool
     */
    public function getTableName(){
        $this->class = get_called_class();
        // 当前模型名
        $tablename  = preg_replace("/([A-Z])/", ",\\1", $this->name);
        $tablename  = explode(',', strtolower($tablename));
        $tablename  = implode('_',array_filter($tablename));
        $tablename  = config('database.prefix') . $tablename;
        return $tablename;
    }

    /**
     * 读取全部表名
     * @author 	luffy
     * @param 	void
     * @return 	array
     */
    final public function list_tables(){
    	$tables = [];
    	$data = $this->query("SHOW TABLES");
    	foreach ($data as $v) {
    		$tables[] = $v['Tables_in_' . strtolower(config('database.database'))];
    	}
    	return $tables;
    }
    
    /**
     * 检查表是否存在
     * @author 	luffy
     * @param 	string $table 不带表前缀
     * @return 	bool
     */
    final public function table_exists($table){
    	$tables = $this->list_tables();
    	return in_array(config('database.prefix') . $table, $tables) ? true : false;
    }
    
    /**
     * 获取表字段
     * @author 	luffy
     * @param 	string $table 不带表前缀
     * @return 	array
     */
    final public function get_fields($table){
    	$fields = [];
    	$table = config('database.prefix') . $table;
    	$data = $this->query("SHOW COLUMNS FROM {$table}");
    	foreach ($data as $v) {
    		$fields[$v['Field']] = $v['Type'];
    	}
    	return $fields;
    }
    
    /**
     * 检查字段是否存在
     * @author 	luffy
     * @param 	string $table 不带表前缀
     * @param 	string $field
     * @return 	bool
     */
    final public function field_exists($table, $field){
    	$fields = $this->get_fields($table);
    	return array_key_exists($field, $fields);
    }
    
    protected function _before_write(&$data){
    	
    }
    //************************基础操作************************//
    
    //************************缓存处理************************//
	/**
	 * 获取函数缓存
	 * @author 	luffy
	 * @param 	string funcName 调用方法名称(带前缀'_cache')
	 * @param 	mixed ... 其他参数列表
	 * @return 	mixed
	 */
	protected function getFuncCache($funcName){
		//拼接key
		$argList = func_get_args();
		if ($this->name) array_unshift($argList, $this->name);
		$key = get_key($argList);
		$key = $this->getKey($key);
		//从缓存中获取
		$data = $this->cache->get($key);
		if (!$data) {
			array_shift($argList);
			if ($this->name) array_shift($argList);
			$act = '_cache' . ucfirst($funcName);
			$data = call_user_func_array([$this, $act], $argList);
			$this->setCache($key, $data);
		}
		return $data;
	}

	/**
	 * 获取函数缓存扩展
	 * @author 	luffy
	 * @param 	string $key  set
	 * @param 	string $funcName 调用方法名称(不带前缀'_cache')
	 * @param 	int $time 缓存时长
	 * @param 	mixed ... 其他参数列表
	 * @return 	mixed
	 */
	protected function getFuncCacheEx($funcName, $time=0){
		$argList = func_get_args();
		unset($argList[1]);
		$argList = array_values($argList);
		if ($this->name) array_unshift($argList, $this->name);
		$key = get_key($argList);
		$key = $this->getKey($key);
		$data = $this->cache->get($key);
		if (!$data) {
			array_shift($argList);
			if ($this->name) array_shift($argList);
			$data = call_user_func_array([$this, $funcName], $argList);
			$this->setCache($key, $data, $time);
		}
		return $data;
	}

	/**
	 * 重置函数缓存
	 * @author 	luffy
	 * @param 	string $funcName 调用方法名称(带前缀'_cache')
	 * @param 	mixed ... 其他参数列表
	 * @return 	boolean
	 */
	protected function resetFuncCache($funcName){
		//拼接key
		$argList = func_get_args();
		if ($this->name) array_unshift($argList, $this->name);
		$key = get_key($argList);
		//删除缓存
		$this->deleteCache($key);
		//设置缓存
		array_shift($argList);
		if ($this->name) array_shift($argList);
		$act = "_cache".ucfirst($funcName);
		$data = call_user_func_array([$this, $act], $argList);
		return $this->setCache($key, $data);
	}

	/**
	 * 重置函数缓存扩展
	 * @author 	luffy
	 * @param 	string $funcName 调用方法名称(不带前缀'_cache')
	 * @param 	int $time 缓存时长
	 * @param 	mixed ... 其他参数列表
	 * @return 	boolean
	 */
	protected function resetFuncCacheEx($funcName, $time=0){
		//拼接key
		$argList = func_get_args();
		if ($this->name) array_unshift($argList, $this->name);
		$key = get_key($argList);
		//删除缓存
		$this->deleteCache($key);
		//设置缓存
		array_shift($argList);
		if ($this->name) array_shift($argList);
		$data = call_user_func_array([$this, $funcName], $argList);
		return $this->setCache($key, $data, $time);
	}

	/**
	 * 删除函数缓存
	 * @author 	luffy
	 * @param 	string $funcName 方法名称
	 * @return 	boolean
	 */
	protected function deleteFuncCache($funcName){
		$argList = func_get_args();
		if ($this->name) array_unshift($argList, $this->name);
		$key = get_key($argList);
		return $this->deleteCache($key);
	}

    /**
     * 获取缓存
     * @author 	luffy
     * @param 	string $key
     * @return 	mixed
     */
	protected function getCache($key){
    	$key = $this->getKey($key);
    	$data = $this->cache->get($key);
    	return $data;
    }
    
    /**
     * 设置缓存
     * @author 	luffy
     * @param 	string $key
     * @param 	mixed $value
     * @param 	int $ttl
     * @return 	boolean
     */
 	protected function setCache($key, $value, $ttl=0){
    	$key = $this->getKey($key);
    	if (!$value) return false;
    	return $this->cache->set($key, $value, $ttl);
    }
    
    /**
     * 删除缓存
     * @author luffy
     * @param string $key
     * @return boolean
     */
    protected function deleteCache($key){
    	$key = $this->getKey($key);
    	return $this->cache->rm($key);
    }

    /**
     * 获取缓存key
     * @author 	luffy
     * @param 	string $key
     * @return 	string
     */
    protected function getKey($key){
		return $key;
    }
    
    /**
     * 重置缓存
     * @author 	luffy
     * @param 	int $id
     * @return 	boolean
     */
    public function _cacheReset($id){
    	return $this->resetFuncCache('info', $id);
    }
    
    /**
     * 删除缓存
     * @author 	luffy
     * @param 	int $id
     * @return 	boolean
     */
    protected function _cacheDelete($id){
    	return $this->deleteFuncCache('info', $id);
    }
    
    /**
     * 获取全表缓存
     * @author 	luffy
     * @param 	int $pri 0自然数组 1主键数组
     * @param 	array $cond
     * @return 	array
     */
    public function getAll($pri=0, $cond=['mark'=>1]){
    	if (config('app_debug')) {
    		//$this->_cacheResetAll($pri, $cond);
    	}
    	return $this->getFuncCache('all', $pri, $cond);
    }
    
    /**
     * 缓存全表信息
     * @author 	luffy
     * @param 	int $pri 0自然数组 1主键数组
     * @param 	array $cond
     * @return 	array
     */
    protected function _cacheAll($pri=0, $cond=['mark'=>1]){
    	$list = $this::all($cond);
    	if ($list) {
    		$list = collection($list)->toArray();
    	}
    	$arr = [];
    	if ($pri) {
    		foreach ($list as $val) {
    			$arr[$val['id']] = $val;
    		}
    		$list = $arr;
    	}
    	return $list;
    }
    
    /**
     * 重置全表缓存
     * @author	 luffy
     * @param 	int $pri 0自然数组 1主键数组
     * @param 	array $cond
     * @return 	void
     */
    protected function _cacheResetAll($pri=0, $cond=['mark'=>1]){
    	$data = $this->resetFuncCache('all', $pri, $cond);
    }
    //************************缓存处理************************//
    
    //************************底层操作************************//
    /**
     * 获取记录总数
     * @author 	luffy
     * @param 	mixed $where 条件表达式
     * @return 	int
     */
    function getCount($where){
    	return $this->where($where)->count();
    }
    
    /**
     * 获取字段值
     * @author 	luffy
     * @param 	mixed $where 条件表达式
     * @param 	string $name 字段名
     * @return 	string
     */
    function getValue($where, $name){
    	return $this->where($where)->value($name);
    }
    
    /**
     * 获取某个列的数组
     * @author 	luffy
     * @param 	mixed $where 条件表达式
     * @param 	string $name 字段名
     * @return 	array
     */
    function getColumn($where, $name){
    	return $this->where($where)->column($name);
    }
    
    /**
     * 判断是否存在
     * @author 	luffy
     * @param 	mixed $where 条件表达式
     * @return 	bool
     */
    function isExists($where){
    	$total = $this->where($where)->count();
    	return $total > 0 ? true : false;
    }
    
    /**
     * 根据条件物理删除记录
     * @author 	luffy
     * @param 	mixed $where 条件表达式
     * @return 	mixed
     */
    function doDelete($where){
    	if (empty($where)) return false;
    	return $this->where($where)->delete();
    }
    //************************底层操作************************//
       
    //************************基类操作************************//
    /**
     * 获取基本信息
     * @author 	luffy
     * @param 	int $id
     * @param 	string $fields
     * @return 	array
     */
    public function getInfo($id, $fields='*'){
    	if (!$this->is_cache) {
    		$info = $this->field($fields)->where(['id'=>$id])->find()->toArray();
    		if ($info) {
    			$info = $this->formatInfo($info);
    		}
 			return $info;
    	}
    	if (config('app_debug')) {
    		$this->_cacheDelete($id);
    	}
    	$info = $this->getFuncCache('info', $id);
    	if ($fields && '*' != $fields) {
    		if (!is_array($fields)) {
    			$fields_arr = explode(',', $fields);
    		} else {
    			$fields_arr = $fields;
    		}
    		$arr = [];
    		foreach ($fields_arr as $val) {
    			if (array_key_exists($val, $info)) {
    				$arr[$val] = $info[$val];
    			}
    		}
    		$info = $arr;
    	}
    	return $info;
    }
    
    /**
     * 缓存基本信息
     * @author 	luffy
     * @param 	int $id
     * @return 	array
     */
    protected function _cacheInfo($id){
    	$info = $this::get($id) ? $this::get($id)->toArray() : [];
    	if ($info) {
    		$info = $this->formatInfo($info);
    	}
    	return $info;
    }
    
    /**
     * 格式化信息
     * @author 	luffy
     * @param 	array $info
     * @return 	array
     */
    protected function formatInfo($info){
    	if (isset($info['add_user'])) {
    		if ($info['add_user']) {
    			$user_info = db('admin_user')->where('id', $info['add_user'])->field(['name'])->find();
    			$info['format_add_user'] = $user_info['name'];
    		}
    	}
    	if (isset($info['upd_user'])) {
    		if ($info['upd_user']) {
    			$user_info = db('admin_user')->where('id', $info['upd_user'])->field(['upd_user'])->find();
    			$info['format_upd_user'] = $user_info['upd_user'];
    		}
    	}
    	if (isset($info['add_time'])) {
    		if ($info['add_time']) {
    			$info['format_add_time'] = date('Y-m-d H:i', $info['add_time']);
    		}
    	}
    	if (isset($info['upd_time'])) {
    		if ($info['upd_time']) {
    			$info['format_upd_time'] = date('Y-m-d H:i', $info['upd_time']);
    		}
    	}
    	if (isset($info['status']) && isset($this->status)) {
    		$info['format_status'] = $info['status'] ? $this->status[$info['status']] : '';
    	}
    	if (isset($info['image'])) {
    		$info['image_url'] = $info['image'] ?  UPLOAD_URL . $info['image'] : '';
    	}
    	return $info;
    }
    
    /**
     * 格式化更多信息（不缓存）
     * @param 	array $info
     * @return 	array
     */
    protected function relationInfo($info){
    	return $info;
    }
    
    /**
     * 根据条件获取基本信息
     * @author 	luffy
     * @param 	array $cond
     * @param 	string $fields
     * @return 	array
     */
    public function getInfoByCond($cond, $fields='*'){
    	$id = $this->where($cond)->value('id');
    	if (!$id) return [];
    	$info = $this->getInfo($id, $fields);
    	return $info;
    }
    
    /**
     * 检测是否存在
     * @author	luffy
     * @param 	array $data
     * @param 	int $id
     * @return	int
     */
    function isExist($data, $id=0){
    	$cond['mark'] = 1;
    	foreach ($data as $key => $val) {
    		$cond[$key] = $val;
    	}
    	if ($id) {
    		$cond['id'] = ['<>', $id];
    	}
    	$info = $this->getInfoByCond($cond, 'id');
    	$id = $info['id'] ? (int)$info['id'] : 0;
    	return $id;
    }
    
    /**
     * 根据条件查询返回结果集
     * @author 	luffy
     * @param 	$where
     * @param 	$order
     * @param 	string $fields
     * @param 	int $offset
     * @param 	int $limit
     * @param 	string $pkField
     * @param 	bool $pk
     * @return 	array
     */
    function getList($where=['mark'=>1], $fields='*', $order='id DESC', $page = 0, $limit = 99999, $pk=false, $pkField='id'){
        $offset = $page ? ($page-1) * $limit : 0;
        $list = $this->where($where)->order($order)->field($fields)->limit($offset, $limit)->column('id');
        $result = [];
    	if (!empty($list)) {
    		foreach ($list as $key => $id) {
    			$info = $this->getInfo($id, $fields);
    			$info = $this->relationInfo($info);
    			$info['key'] = $key + 1;
    			if ($pk) {
    				if (array_key_exists($pkField, $info)) {
    					$result[$info[$pkField]] = $info;
    				}
    			} else {
					$result[] = $info;
    			}
    		}
    	}
    	return $result;
    }
    
    /**
     * 获取所有子级数据
     * @author 	luffy
     * @param 	int $pid
     * @param 	array $where
     * @param 	string $fields
     * @param 	string $order
     * @param 	bool $loop
     * @return 	array
     */
    function getChilds($pid=0 , $where=['mark'=>1], $fields='*', $order='id ASC', $loop=false){
    	$where['pid'] = $pid;
    	if (!$this->is_cache) {
    		$list = $this->where($where)->order($order)->field($fields)->select();
    		$result = collection($list)->toArray();
    		if ($loop) {
    			foreach ($result as $key => $val) {
    				$result[$key]['children'] = $this->getChilds($val['id'], $where, $fields, $order, $loop);
    			}
    		}
    		return $result;
    	}
    	$result = [];
    	$list = $this->where($where)->order($order)->column('id');
    	if (!empty($list)) {
    		foreach ($list as $id) {
    			$info = $this->getInfo($id);
    			if ($fields && $fields != '*') {
    				if (!is_array($fields)){
    					$fields_arr = explode(',', $fields);
    				} else {
    					$fields_arr = $fields;
    				}
    				$arr = [];
    				foreach ($fields_arr as $val) {
    					if (array_key_exists($val, $info)) {
    						$arr[$val] = $info[$val];
    					}
    				}
    				$info = $arr;
    				if ($loop) {
    					$info['children'] = $this->getChilds($id, $where, $fields, $order, $loop);
    				}
    			}
    			$result[$id] = $info;
    		}
    	}
    	return $result;
    }
    
    /**
     * 添加编辑,含缓存处理
     * @author 	luffy
     * @param 	array $data 更新数据
     * @param 	int $id 主键ID
     * @param 	bool $all
     * @return 	int
     */
    function doEdit($data, $id=0, $all = false){
    	if (empty($data)) return false; //如果更新data为空，直接返回
    	$isExists = $id ? $this->isExists(['id'=>$id]) : false;
    	if ($isExists) { //修改
    		$result = $this->where(['id'=>$id])->update($data);
    		if ($this->is_cache && $result > 0) { //$result返回影响数据的条数，没修改任何数据返回 0
    			$this->_cacheReset($id);
    			$all && $this->_cacheResetAll();
    		}
    	} else { //新增
    		$result = $this::create($data);
    		if ($result) {
    			$id = $result->id;
    			if ($this->is_cache) {
    				$this->_cacheReset($id);
    				$all && $this->_cacheResetAll();
    			}
    		}
    		$result = $id;
    	}
    	return $result;
    }
    
    /**
     * 根据条件更新 一条记录
     * @author 	luffy
     * @param 	array $data
     * @param 	mixed $where
     * @return 	bool
     */
    function doUpdate($data, $where){
    	if (empty($data)) return false; //如果更新data为空，直接返回
    	if (empty($where)) return false; //如果where条件为空，直接返回
    	$id = $this->where($where)->value('id');;
    	$result = $this->doEdit($data, $id);
    	return $result;
    }
    
    /**
     * 逻辑删除记录
     * @author 	luffy
     * @param 	mixed $ids 表主键 且必须是表主键的形式才可以执行该操作
     * @param 	mixed $where
     * @param 	bool $all
     * @return 	bool
     */
    function doDrop($ids, $where=[], $all=false){
    	if (empty($ids)) return false; //如果ids为空，则直接返回
    	$where['id'] = is_array($ids) ? (['IN', $ids]) : (strpos($ids, ',') !== false ? ['IN', explode(',', $ids)] : $ids);
    	$data['mark'] = 0;
    	$result = $this->where($where)->update($data);
    	if ($this->is_cache && $result) {
    		$list = $this->where($where)->column('id');
    		if (!empty($list)) {
    			foreach ($list as $id) {
    				$this->_cacheDelete($id);
    			}
    		}
    		if ($all) {
    			$this->_cacheResetAll();
    		}
    	}
    	return $result;
    }

	/**
	 * 数据添加或修改，含缓存处理
	 * @author 	luffy
	 * @param 	array $data
	 * @param 	bool $all 重置模型的所有缓存
	 * @param 	mixed $allowField
	 * @return 	bool
	 */
	public function edit($data=[], $all=false, $allowField=false){
		if (empty($data)) {
			$data = request()->post();
		}
		if (!isset($this->pk)) {
			$this->pk = 'id';
		}
		if (isset($data[$this->pk]) && $data[$this->pk]) {
			$isExists = $this->isExists([$this->pk=>$data[$this->pk]], $data[$this->pk]);
		} else {
			$isExists = false;
		}
		if ($isExists) { //修改
			$result = $this->allowField($allowField)->isUpdate(true)->save($data, [$this->pk=>$data[$this->pk]]);
		} else { //新增
			$result = $this->allowField($allowField)->isUpdate(false)->save($data);
		}
		if ($result) {
			$id = $this->data[$this->pk];
			if ($this->is_cache) {
				$this->_cacheReset($id);
				$all && $this->_cacheResetAll();
			}
			$result = $id;
		}
		return $result;
	}
	
	/**
	 * 数据添加或修改，含缓存处理
	 * @author 	luffy
	 * @param 	array $data
	 * @param 	array $info
	 * @param 	bool $all
	 * @param 	mixed $allowField
	 * @return 	bool
	 */
	public function editInfo($data=[], $info=[], $all=false, $allowField=false){
		if (empty($data)) {
			$data = request()->post();
		}
		if (!isset($this->pk)) {
			$this->pk = 'id';
		}
		if (isset($data[$this->pk]) && $data[$this->pk]) {
			$isExists = $this->isExists([$this->pk=>$data[$this->pk]], $data[$this->pk]);
		} else {
			$isExists = false;
		}
		if ($isExists) { //修改
			$result = $this->allowField($allowField)->isUpdate(true)->save($data, [$this->pk=>$data[$this->pk]]);
		} else { //新增
			$result = $this->allowField($allowField)->isUpdate(false)->save($data);
		}
		if ($result) {
			$id = $this->data[$this->pk];
			$obj = $this::get($id);
			if (isset($data[$this->pk]) && $data[$this->pk]) { //编辑关联表
				$ret = $obj->relate->save($info);
			} else { //添加关联表
				$ret = $obj->relate()->save($info);
			}
			if ($ret != 1) {
				return false;
			}
			$this->_cacheReset($id);
			$all && $this->_cacheResetAll();
			$result = $id;
		}
		return $result;
	}
    
    /**
     * 数据软删除，含缓存处理
     * @author 	luffy
     * @param 	int $id
	 * @param 	bool $all
     * @return 	bool
     */
    public function drop($id, $all=false){
		$data['mark'] = 0;
		$result = $this->where(['id'=>$id])->update($data);
		if ($this->is_cache && $result) {
			$this->_cacheDelete($id);
			if ($all) {
				$this->_cacheResetAll();
			}
		}
		return $result;
    }
    
    /**
     * 关联模型
     * @author 	luffy
     * @param 	string $table
     * @return 	\think\model\relation\HasOne
     */
    public function relate($table=''){
    	if (!$table) $table = strtolower($this->name) . '_info';
    	return $this->hasOne($table, 'id', 'id', [], 'LEFT');
    }
    
    /**
     * 数据列表
     * @author 	luffy
     * @param 	array $where
     * @return 	bool
     */
    public function lists($where = ['mark'=>1], $order = 'id DESC'){
    	$list = $this->where($where)->order($order)->paginate(config('paginate.list_rows'), false, [
    		'query' => $this->param,
    	]);
    	return $list;
    }

	/**
	 * 数据分页列表,根据主键循环调取缓存
	 * @author 	luffy
	 * @param 	array $where
	 * @param 	string $order
	 * @param	string $fields
	 * @param   string $alias
	 * @param   array $join
	 * @param   array $group
	 * @return 	mixed
	 */
	public function pageList($where = ['mark'=>1], $order = 'id DESC', $fields = '*', $alias = '', $join = [], $group = '', $pageNum = ''){
		$field = $this->is_cache && empty($join) ? 'id' : $fields;
		$where_array = [];
		foreach ($where as $key => $val) {
		    if (is_numeric($key)) {
		        $where_array[] = $val;
                unset($where[$key]);
            }
        }
		$query = $alias != '' ? $this->alias($alias)->where($where)->field($field) : $this->where($where)->field($field);
		if (!empty($where_array)) {
		    foreach ($where_array as $v) {
		        $query = $query->where($v);
            }
        }
		!empty($join) && $query = $query->join($join);
		$group != '' && $query = $query->group($group);
		if (!$pageNum){
			$pageNum = config('paginate.list_rows');
		}
		$list = $query->order($order)->paginate($pageNum, false, [
			'query' => $this->param,
		]);
			// echo $this->getLastSql();
		if ($this->is_cache && empty($join)) {//开启缓存且不连表
			$data = [];
			foreach ($list as $val) {
				$info = $this->getInfo($val->id, $fields);
				$info = $this->relationInfo($info);
				$data[$val->id] = $info;
			}
		} else {//不开缓存或连表
			$data = $list;
		}
		$result = [
			'list' => $data,
			'page' => $list->render()
		];
		return $result;
	}
	
	/**
	 * 根据条件获取数据（支持连表）
	 * @author: luffy
	 */
	public function getListByCond($where = ['mark'=>1], $order = 'id DESC', $fields = '*', $alias = '', $join = [], $group = '', $limit = '') {
	    $query = $alias != '' ? $this->alias($alias)->where($where)->field($fields) : $this->where($where)->field($fields);
	    !empty($join) && $query = $query->join($join);
	    $group != '' && $query = $query->group($group);
	    $limit != '' && $query = $query->limit($limit);
	    $list = $query->order($order)->select();
	    $list = collection($list);
	    if (!$list->isEmpty()) {
	        $list = $list->toArray();
	    } else {
	        $list = [];
	    }
	    
	    return $list;
	}

    /**
     * 添加/编辑方法
     * @author zhangkx
     * @date 2019/4/24
     * @param $table
     * @param $data
     * @param $accountId
     * @return mixed
     */
    public function editData($table, $data, $accountId = 0)
    {
        $model = model($table);
        $id = $data['id'] ? : 0;
        if (!$id) {
            $data['add_user'] = $accountId;
            $data['add_time'] = time();
        }
        $result = $model->doEdit($data, $id);
        return $result;
	}
	//************************基类操作************************//

}