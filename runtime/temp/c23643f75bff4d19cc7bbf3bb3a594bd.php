<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"D:\phpstudy\PHPTutorial\WWW\bschthinkphp\public/../application/admin\view\login\login.html";i:1555490213;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>后台登录</title>
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
	<!-- load css -->
    <link href="/static/public/layui/css/layui.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="/static/admin/css/login/login.css" media="all">
</head>
<body>
<div class="layui-canvs"></div>
<form id="doLogin" name="doLogin" method="post" action="<?php echo url('login'); ?>">
    <div class="layui-layout layui-layout-login">
        <h1>
            <strong>后台管理系统</strong>
            <em></em>
        </h1>
        <div class="layui-user-icon larry-login">
            <input type="text" placeholder="账号" class="login_txtbx" name="username" id="username" value="<?php echo $username; ?>"/>
        </div>
        <div class="layui-pwd-icon larry-login">
            <input type="password" placeholder="密码" class="login_txtbx" name="password" id="password" value="<?php echo $password; ?>"/>
        </div>
        <?php if(config('verify_type') == 1): ?>
            <div class="layui-val-icon larry-login" style="overflow:visible;">
                <input type="text" placeholder="验证码" class="login_txtbx" style="color:black;width:185px;float:left;margin:0px 10px 0px 0px;" name="verify" id="verify"/>
                <?php echo captcha_img(); ?>
            </div>
        <?php else: ?>
            <div id="embed-captcha"></div>
            <p id="wait" style="display:none;">正在加载验证码......</p>
        <?php endif; ?>
        <div class="layui-submit larry-login">
            <input type="checkbox" name="remember" <?php if($remember): ?> checked <?php endif; ?>/>
            记住密码
        </div>
        <div class="layui-submit larry-login">
            <input type="submit" class="submit_btn" value="立即登录"/>
        </div>
        <div class="layui-login-text">
            <p>版权所有 &copy; <a href="http://<?php echo \think\Request::instance()->server('http_host'); ?>" target="_blank">倍速创恒</a></p>
        </div>
    </div>
</form>
<script type="text/javascript" src="/static/public/js/jquery.min.js?v=2.1.4"></script>
<script type="text/javascript" src="/static/public/layui/layui.all.js"></script>
<script type="text/javascript" src="/static/public/js/jquery.form.js"></script>
<script type="text/javascript" src="/static/public/js/jparticle.jquery.js"></script>
<script type="text/javascript">
    layui.use(['jquery'],function(){
        window.jQuery = window.$ = layui.jquery;
		$(".layui-canvs").width($(window).width());
	    $(".layui-canvs").height($(window).height());
	});
	$(function(){
        $(".layui-canvs").jParticle({
            background: "#141414",
            color: "#E6E6E6"
        });
    });
	
	//初始化选中用户名输入框
    $('.layui-layout').find("input[name=username]").focus();
	
	//刷新验证码
    (function(){
        $('.layui-val-icon img').attr('title', '点击获取').addClass('verifyimg');
        $('.layui-val-icon img').on('click', function(){
            this.src='<?php echo captcha_src(); ?>?refresh='+Math.random();
        });
    })();
	
	$(function(){
		$('#doLogin').ajaxForm({
            beforeSubmit: checkForm, // 此方法主要是提交前执行的方法，根据需要设置
            success: complete, // 这是提交后的方法
            dataType: 'json'
        });
	});
	
	function checkForm(){
		var username = $.trim($('#username').val());
        var password = $.trim($('#password').val());
        var verify = $.trim($('#verify').val());
		
		if ('' == username) {
            layer.msg('请输入登录用户名', {icon:5,time:2000}, function(index){
                layer.close(index);
            });
            return false;
        }
        if ('' == password) {
            layer.msg('请输入登录密码', {icon:5,time:2000}, function(index){
                layer.close(index);
            });
            return false;
        }
		// if ('' == verify) {
        //     layer.msg('请输入验证码', {icon:5,time:2000}, function(index){
        //         layer.close(index);
        //     });
        //     return false;
        // }
		$('.submit_btn').val('登录中...').attr('disabled', true);
		return true;
    }
	
	function complete(data){
        if (data.code == 1) {
            layer.msg(data.msg, {icon:6,time:2000}, function(index){
                layer.close(index);
                window.location.href = data.data;
            });
        } else {
            layer.msg(data.msg, {icon:5,time:2000});
			$('.submit_btn').val('立即登录').attr('disabled', false);
            return false;
        }
    }

    if (window != top) {
        top.location.href = location.href;
	}
</script>
</body>
</html>