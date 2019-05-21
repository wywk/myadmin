<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:94:"D:\phpstudy\PHPTutorial\WWW\bschthinkphp\public/../application/admin\view\public\calendar.html";i:1555490213;}*/ ?>
<div class="layui-form-item layui-input-inline">
    <label class="layui-form-label">操作时间：</label>
    <div class="layui-input-inline">
        <input type="text" name="start_time" id="start_time" placeholder="请选择开始日期" autocomplete="off" class="layui-input startTime" value="<?php echo (isset($start_time) && ($start_time !== '')?$start_time:''); ?>" readonly="readonly">
    </div>
</div>
<div class="layui-form-item layui-input-inline">
    <div class="layui-input-inline">
        <input type="text" name="end_time" id="end_time" placeholder="请选择结束日期" autocomplete="off" class="layui-input endTime" value="<?php echo (isset($end_time) && ($end_time !== '')?$end_time:''); ?>" readonly="readonly">
    </div>
</div>