<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:90:"D:\phpstudy\PHPTutorial\WWW\bschthinkphp\public/../application/admin\view\index\index.html";i:1557386956;s:75:"D:\phpstudy\PHPTutorial\WWW\bschthinkphp\application\admin\view\layout.html";i:1555490212;s:82:"D:\phpstudy\PHPTutorial\WWW\bschthinkphp\application\admin\view\public\header.html";i:1555490213;s:82:"D:\phpstudy\PHPTutorial\WWW\bschthinkphp\application\admin\view\public\footer.html";i:1555924200;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn" >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="Keywords" content="" />
    <meta name="Description" content="" />
    <meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>EduaskCMS - 后台管理系统</title>
    <?php echo $ViewFileSet->css($view_css, true); ?>
    <?php echo $ViewFileSet->script($view_js, true); ?>
</head>
<script>
    var layer;
    $(function(){
        layui.use(['layer'], function(){
            layer = layui.layer;
            layer.config({
                zIndex:10000
            });
        })
    })
    HKUC.ajax_request.defaultSuccessHandlers= {
        'success':function(rslt_msg,rslt_data){
            layer.alert(rslt_msg,{
                icon:1
            });
        },
        'error':function(rslt_msg,rslt_data){
            layer.alert(rslt_msg,{
                icon:2
            });
        },
        'nopower':function(msg,data){
            layer.alert(msg,{
                icon:2
            });
        }
    };
    HKUC.ajax_request.defaultErrorHandlers={
        403:function(text,rerun){
            layer.alert('登录超时，请刷新重新登录',{
                icon:2
            });
        },
        404:function(text,rerun){
            layer.alert('页面不存在',{
                icon:2
            });
        }
    };
    var winWidth = $(window).width();
    var heiHeght = $(window).height();
</script>
<body>

    <style type="text/css">
   .rightmenu{font-size:12px; padding:5px 10px; border-radius: 2px;}
   .rightmenu li{line-height:20px; cursor: pointer;}
   ul.layui-tab-title li:first-child i{display:none;}
   .closeMenu{display: none;position: absolute;clear: both;z-index: 999;width: 110px;border:1px solid #e2e2e2;top: 29px !important;padding: 5px 0;background-color: #fff;}
   .closeMenu li{display: block;padding:5px 0 5px 10px;background-color: #fff;font-size: 14px;cursor: pointer;text-align:left;color:#666}
   .closeMenu li:hover{background-color: #eee;}
   .closeMenu li i{padding-right: 2px;}
</style>
<div id="gloBox" class="default">
    <div id="gloTop" class="clearfix">
        <div class="gtLeft clearfix">
            <div class="logo "><img src="http://www.luffy.com/static/public/images/logo.png" alt="EduaskCMS"/></div>
            <div class="menuBar" >
                <ul class="list">
                    <li class="bar_line bar_top"></li>
                    <li class="bar_line bar_mid"></li>
                    <li class="bar_line bar_foot"></li>
                </ul>
            </div>
        </div>
        <div class="gtRght">
            <ul class="layui-nav list" lay-filter="">
                <li class="layui-nav-item circle gohome first"><a class="tooltip" data-tip-text="访问前台" data-tip-type="3" href="/Home/index/index.html" target="_blank"><i class="fa fa-home"></i></a></li>
                <li class="layui-nav-item circle"><a class="tooltip javascript" rel="full_screen" data-tip-text="F11全屏" data-tip-type="3" href="javascript:void(0);"><i class="fa fa-arrows "></i></a></li>
                <li class="layui-nav-item circle"><a class="tooltip javascript" rel="simple_clear" data-tip-text="清除缓存" data-tip-type="3" href="/run/tool/clearcache.html"><i class="fa fa-remove" style="margin: -7px 0 0 -5px;"></i></a></li>
                <li class="layui-nav-item circle"><a target="_blank" class="tooltip" data-tip-text="培训手册" data-tip-type="3" href="https://www.kancloud.cn/laowu199/e_manual"><i class="fa fa-book" style="margin: -7px 0 0 -7px;"></i></a></li>
                <li class="layui-nav-item circle skin-down"><a  href="javascript:void(0);"><i class="fa fa-yelp" style="margin: -7px 0 0 -6px;"></i></a>
                    <div class="skin-show clearfix">
                        <a data-skin="default" rel="change_skin"  style="background: #009688;" class="javascript"></a>
                        <a data-skin="green" rel="change_skin"  style="background: rgba(0,166,90,1);" class="javascript"></a>
                        <a data-skin="pink"  rel="change_skin" style="background: rgba(250,96,134,1);" class="javascript"></a>
                        <a data-skin="blue"  rel="change_skin" style="background: rgba(0,192,239,1);" class="javascript"></a>
                        <a data-skin="red"   rel="change_skin" style="background: rgba(250,42,0,1);" class="javascript"></a>
                    </div>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;" class="admin-user" >
                        <span class="admin-user-headpic"><img  src="/images/admin/default_headimg.png" alt=""/></span><span class="admin-user-name en-font"><?php echo $userName; ?></span>
                    </a>
                    <dl class="layui-nav-child">
                        <i class="i"></i>
                        <dd><a class="new_tab edit-pass" data-icon="fa-user" style="cursor: pointer" data-id="<?php echo $userId; ?>"><i class="fa fa-pencil" aria-hidden="true"></i>修改密码</a></dd>
                        <dd><a class="javascript" rel="lockScreen" style="cursor: pointer" data-id="<?php echo $userId; ?>"><i class="fa fa-lock" aria-hidden="true" style="padding-right: 2px;padding-left: 2px;"></i>锁屏(Alt+L)</a></dd>
                        <dd class="bt"><a href="<?php echo url('login/logout'); ?>"><i class="fa fa-sign-out" aria-hidden="true"></i>注销登录</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>
    <div id="gloLeft" class="sizing">
        <div class="leftbg"></div>
        <div id="leftBar">
            <ul class="list layui-nav-tree layui-nav-side" id="gloMenu" lay-filter="treenav">
                <?php if($navs): foreach($navs as $key => $val): ?>
                        <li class="current">
                            <div class="navT">
                                <a href="javascript:;"><i data-icon="fa-qrcode" class="fa fa-qrcode animated" ></i><cite><?php echo $val['name']; ?></cite></a>
                            </div>
                            <div class="navC">
                                <ul class="list">
                                    <?php if($val['child']): foreach($val['child'] as $k => $v): ?>
                                            <li class="b"><a href="javascript:;" class="site-url" data-title="<?php echo $v['name']; ?>" data-id="<?php echo $v['id']; ?>" data-url="/admin/<?php echo $v['controller']; ?>/<?php echo $v['action']; ?>.html"><i data-icon="fa-qrcode" class="fa fa-angle-right fa-lg animated"></i><cite><?php echo $v['name']; ?></cite></a></li>
                                            <li class="s"><a href="javascript:;" class="site-url tooltip" data-tip-text="<?php echo $v['name']; ?>" data-tip-bg="#009688" data-title="<?php echo $v['name']; ?>" data-id="<?php echo $v['id']; ?>" data-url="/admin/<?php echo $v['controller']; ?>/<?php echo $v['action']; ?>.html"><i class="fa fa-reorder"></i></a></li>
                                        <?php endforeach; endif; ?>
                                </ul>
                            </div>
                        </li>
                    <?php endforeach; endif; ?>
            </ul>
        </div>
    </div>
    <div id="gloRght">
        <div class="layui-tab admin-nav-card layui-tab-brief" lay-filter="contentnav" lay-allowClose="true">
            <div class="topBg coverBg"></div>
            <div class="tab-bg"></div>
            <a href="javascript:void(0);" class="tab-prev"><i class="fa fa-angle-double-left fa-2x"></i></a>
            <a href="javascript:void(0);" class="tab-next"><i class="fa fa-angle-double-right fa-2x"></i></a>
            <ul class="layui-tab-title topTitle">
                <li class="layui-this">
                    <i class="fa fa-desktop" aria-hidden="true"></i><cite>后台首页</cite>
                </li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show admin_home">
                    <div class="admin_content">
                        <div class="admin_content_box">
                            <div id="admHome">
                                <div class="homeRght">
                                    <div class="headimg">
                                        <a href="/run/member/modify/parent_id/1.html" data-icon="fa-user" data-title="修改信息" class="new_tab"><img  src="/images/admin/default_headimg.png" alt=""/></a>
                                    </div>
                                    <div class="welcome en-font">
                                        您好！<span><?php echo $userName; ?></span>
                                        <a href="/run/user/logout.html" ><i style="color: red;" class="fa fa-sign-out" aria-hidden="true"></i></a>
                                    </div>
                                    <div class="time bline en-font">
                                        <b></b>
                                        <i class="fa fa-clock-o" aria-hidden="true"></i><span class="showtime">2018-09-19 09:22:49</span>
                                    </div>
                                    <div class="info bline en-font">
                                        真实姓名：<span class="c">未设置</span>
                                    </div>
                                    <div class="info bline en-font">
                                        登录时间：<span class="c">2018-09-18 17:17:27</span>
                                    </div>
                                    <div class="info bline en-font">
                                        登录地址：<span class="c">127.0.0.1</span>
                                    </div>

                                    <div class="notice">
                                        <div class="con">
                                            <div class="layui-btn-group skin">
                                                <a href="javascript:;" class="site-url layui-btn new_tab" data-id="28" data-url="/run/menu/content.html" data-title="留言列表" data-icon="fa-pencil-square-o">留言<span class="layui-badge layui-bg-gray">0</span></a>
                                                <a class="layui-btn" onclick="layer.msg('敬请期待')">订单<span class="layui-badge layui-bg-gray">0</span></a>
                                                <a class="layui-btn" onclick="layer.msg('敬请期待')">消息<span class="layui-badge layui-bg-gray">0</span></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="quick">
                                        <div class="con">
                                            <div class="layui-btn-group">
                                                <a href="/run/tool/getlog.html" class="layui-btn layui-btn-primary">下载日志</a>
                                                <a href="/run/tool/removelog.html" class="layui-btn layui-btn-primary javascript" rel="simple_clear">清除日志</a>
                                                <a href="/run/tool/switchtrace.html" class="layui-btn layui-btn-primary javascript" rel="switch_trace">关闭Trace</a>
                                            </div>
                                            <div class="layui-btn-group">
                                                <a href="/run/tool/clearcache.html" class="layui-btn layui-btn-primary javascript" rel="simple_clear">清除缓存</a>
                                                <a href="/run/tool/removetemp.html" class="layui-btn layui-btn-primary javascript" rel="simple_clear" >清临时文件</a>
                                                <a href="https://www.kancloud.cn/laowu199/e_manual" target="_blank" class="layui-btn layui-btn-primary">培训手册</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <div class="homeLeft">
                                    <div class="cms_count">
                                        <ul class="grid">
                                            <li class="li-1">
                                                <div class="info">
                                                    <a href="javascript:;" class="site-url new_tab" data-id="29" data-url="/run/menu/content.html" data-title="文章列表" data-icon="fa-file-text">
                                                        <i class="icon"><img  src="/images/admin/article.png" alt=""/></i>
                                                        <span class="number en-font">0</span>
                                                        <span class="name">文章</span>
                                                    </a>
                                                </div>

                                            </li>
                                            <li class="li-2">
                                                <div class="info">
                                                    <a href="javascript:;" class="site-url new_tab" data-id="30" data-url="/run/menu/content.html" data-title="产品列表" data-icon="fa-camera">
                                                        <i class="icon"><img  src="/images/admin/product.png" alt=""/></i>
                                                        <span class="number en-font">0</span>
                                                        <span class="name">产品</span>
                                                    </a>
                                                </div>
                                            </li>
                                            <li class="li-3">
                                                <div class="info">
                                                    <a href="javascript:;" class="site-url new_tab" data-id="31" data-url="/run/menu/content.html" data-title="图集" data-icon="fa-image">
                                                        <i class="icon"><img  src="/images/admin/album.png" alt=""/></i>
                                                        <span class="number en-font">0</span>
                                                        <span class="name">图集</span>
                                                    </a>
                                                </div>
                                            </li>
                                            <li class="li-4">
                                                <div class="info">
                                                    <a href="javascript:;" class="site-url new_tab" data-id="32" data-url="/run/menu/content.html" data-title="会员" data-icon="fa-users">
                                                        <i class="icon"><img  src="/images/admin/user.png" alt=""/></i>
                                                        <span class="number en-font">1</span>
                                                        <span class="name">会员</span>
                                                    </a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="cms_info clearfix">
                                        <div class="you">
                                            <div class="info">
                                                <div class="title en-font">使用者：<a href="/run/setting/lists.html"  class="new_tab" data-title="设置列表" data-icon=" fa-gears"><i class="fa fa-pencil-square-o"></i></a></div>
                                                <div class="con">
                                                    <ul class="list">
                                                        <li><i class="fa fa-thumbs-up"></i>名称：<span>EduaskCMS</span></li>
                                                        <li><i class="fa fa-wifi"></i>网址：<span class="en-font"><a href="http://www.zkkm.com/" target="_blank">http://www.zkkm.com/</a></span></li>
                                                        <li><i class="fa fa-phone"></i>电话：<span class="en-font">公司电话</span></li>
                                                        <li><i class="fa fa-envelope"></i>邮箱：<span class="en-font">公司邮箱</span></li>
                                                        <li><i class="fa fa-map-marker"></i> 地址：<span>公司地址</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dev">
                                            <div class="info">
                                                <div class="title en-font">开发者：</div>
                                                <div class="con">
                                                    <ul class="list">
                                                        <li><i class="fa fa-thumbs-up"></i>名称：<span>开发企业名称</span></li>
                                                        <li><i class="fa fa-wifi"></i>网址：<span class="en-font"><a href="开发企业网址" target="_blank">开发企业网址</a></span></li>
                                                        <li><i class="fa fa-phone"></i>电话：<span class="en-font">开发企业电话</span></li>
                                                        <li><i class="fa fa-envelope"></i>邮箱：<span class="en-font">开发企业邮箱</span></li>
                                                        <li><i class="fa fa-map-marker"></i> 地址：<span>开发企业地址</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="cms_about clearfix">
                                        <div class="server">
                                            <div class="info">
                                                <div class="con">
                                                    <table class="layui-table">
                                                        <tr>
                                                            <th width="25%">存储配额限制</th>
                                                            <th width="25%">配额已使用</th>
                                                            <th width="25%">配额续费日期</th>
                                                            <th>域名到期日期</th>
                                                        </tr>
                                                        <tr>
                                                            <td>1GB</td>
                                                            <td  class="skin">
                                                                <a href="/run/tool/getsitesize.html" class="javascript link" rel="get_site_size">『计算』 <span id="showSiteSize" class="layui-badge layui-bg-orange">0KB</span></a>
                                                            </td>
                                                            <td>不关心</td>
                                                            <td>不关心</td>
                                                        </tr>
                                                        <tr>
                                                            <th>操作系统</th>
                                                            <th>服务器环境</th>
                                                            <th>服务器IP</th>
                                                            <th>上传最大限制</th>
                                                        </tr>
                                                        <tr>
                                                            <td>WINNT</td>
                                                            <td>Apache</td>
                                                            <td>127.0.0.1</td>
                                                            <td>64M</td>
                                                        </tr>
                                                        <tr>
                                                            <th>PHP版本</th>
                                                            <th>MYSQL版本</th>
                                                            <th>脚本超时时间</th>
                                                            <th>CURL支持</th>
                                                        </tr>
                                                        <tr>
                                                            <td>5.5.12</td>
                                                            <td>5.6.17</td>
                                                            <td>120S</td>
                                                            <td>YES</td>
                                                        </tr>
                                                        <tr>
                                                            <th>系统官网</th>
                                                            <th>建站手册</th>
                                                            <th>检查更新</th>
                                                            <th>系统版本</th>
                                                        </tr>
                                                        <tr>
                                                            <td class="skin"><a href="http://www.eduaskcms.xin" class="link" target="_blank">『访问』</a></td>
                                                            <td class="skin"><a href="https://www.kancloud.cn/laowu199/e_use"  class="link" target="_blank">『查阅』</a></td>
                                                            <td class="skin"><a href="https://gitee.com/eduaskcms/eduaskcms" class="link" target="_blank">『求点赞』</a></td>
                                                            <td>EduaskCMS V1.0.4</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
             <!--右击弹出关闭选项框-->
			<div class="closeMenu">
				<ul>
					<li data-type='refresh'>刷新</li>
					<li data-type='closethis'>关闭</li>
					<li data-type='closeall'>关闭全部</li>
					<li data-type='closenotthis'>关闭其他</li>
				</ul>
			</div>       
    </div>
</div>
<div id="lockScreen" style="display: <?php echo $lockStatus==1?'block' : 'none'; ?>;">
    <div class="init">
        <div class="relative">
            <div class="lockTime en-font"></div>
            <div class="pic"><img  src="/images/admin/default_headimg.png" alt=""/><p class="en-font"><?php echo $userName; ?></p></div>
        </div>
        <div class="wrbox">
            <input type="password" id="screenPwd" class="wrin" value="" autocomplete="off" placeholder="请输入密码解锁"/><br /><button id="closeLock" class="layui-btn" data-id="<?php echo $userId; ?>">立即解锁</button>
        </div>
    </div>
</div>
        <script type="text/javascript">
            var layer,Tab;
		    layui.use('element', function(){
		        var element = layui.element;
		        var active={
		            tabAdd:function(url,id,name){
		                element.tabAdd('contentnav',{
		                    title:name,
		                    content:'<iframe class="iframes" data-frameid="'+id+'" scrolling="auto" frameborder="0" src="'+url+'" style="width:100%"></iframe>',
		                    id:id
		                });
		                rightMenu();
		                iframeWH();
		            },
		            tabChange:function(id){
		                element.tabChange('contentnav',id);
		            },
		            tabDelete:function(id){
		                element.tabDelete('contentnav',id);
		            },
		            tabDeleteAll:function(ids){
		                $.each(ids,function(index,item){
		                    element.tabDelete('contentnav',item);
		                });
		            }
		        };
		        $(".site-url").on('click',function(){
		            var nav=$(this);
		            var length = $("ul.layui-tab-title li").length;
		            if(length<=0){
		                active.tabAdd(nav.attr("data-url"),nav.attr("data-id"),nav.attr("data-title"));
		            }else{
		                var isData=false;
		                $.each($("ul.layui-tab-title li"),function(){
		                    if($(this).attr("lay-id")==nav.attr("data-id")){
		                        isData=true;
		                    }
		                });
		                if(isData==false){
		                    active.tabAdd(nav.attr("data-url"),nav.attr("data-id"),nav.attr("data-title"));
		                }
		                active.tabChange(nav.attr("data-id"));
		            }
		        });
		        function rightMenu(){
		            //右击弹出
		            $(".layui-tab-title li").eq(0).nextAll().on('contextmenu',function(e){
		                var menu=$(".closeMenu");
		                menu.find("li").attr('data-id',$(this).attr("lay-id"));
		                l = e.clientX-210;
		                t = e.clientY-50;
		                menu.css({ left:l, top:t}).show();
		                return false;
		            });
		        }
		        //关闭按钮
        		$(document).click(function(){
					$(window.parent.document).find(".closeMenu").hide();
				})

				$(".closeMenu li").click(function(){
					if($(this).attr("data-type")=="refresh"){
						$('.layui-show .iframes').attr('src', $('.layui-show .iframes').attr('src'));
					}else if($(this).attr("data-type")=="closethis"){
		                var tabtitle = $(".layui-tab-title li");
		                var ids = new Array();
		                tabtitle.each(function(i,v){
		                	if($(this).hasClass('layui-this')){
		                		ids.push($(this).attr("lay-id"));
		                	}else{

		                	}
		                });
		                //如果关闭所有 ，即将所有的lay-id放进数组，执行tabDeleteAll
		                active.tabDeleteAll(ids);
		            }else if($(this).attr("data-type")=="closeall"){
		                var tabtitle = $(".layui-tab-title li");
		                var ids = new Array();
		                tabtitle.each(function(i){
		                    ids.push($(this).attr("lay-id"));
		                });
		                //如果关闭所有 ，即将所有的lay-id放进数组，执行tabDeleteAll
		                console.log(ids)
		                active.tabDeleteAll(ids);
		            }else if($(this).attr("data-type")=="closenotthis"){
		                var tabtitle = $(".layui-tab-title li");
		                var ids = new Array();
		                tabtitle.each(function(i,v){
		                	if($(this).hasClass('layui-this')){
		                	}else{
		                		ids.push($(this).attr("lay-id"));
		                	}
		                });
		                //如果关闭所有 ，即将所有的lay-id放进数组，执行tabDeleteAll
		                active.tabDeleteAll(ids);
		            }
		            $('.closeMenu').hide(); //最后再隐藏右键菜单
		        });

		    });
	    	function iframeWH(){
	            var H = $(window).height()-80;
	            $("iframe").css("height",H+"px");
                var rWidth = $(window).width() - $("#gloLeft").width();
            	$('.iframes').css('width',rWidth);
	        }
	        $(window).resize(function(){
	            iframeWH();
	        });
            $('#gloMenu>li').each(function(){
                var childLen  = $(this).find('.navC').find('li').length;
                if(childLen) {
                    var html = $(this).find('.navT').find('a').html();
                    $(this).find('.navT').html('<span>'+html+'</span>') ;
                }
            })
            $('#gloMenu').on('click','.navT',function(){
                var parent  = $(this).closest('li');
                var index   = parent.index();
                if(parent.find('.navC').find('li').length){
                    if(parent.hasClass('open')){
                        parent.find('.navC').stop(true).slideUp(300,function(){ parent.removeClass('open')}) ;
                    }else{
                        var openLi  = $('#gloMenu').find('li.open') ;
                        openLi.removeClass('open').find('.navC').stop(true).slideUp(300) ;
                        parent.addClass('open').find('.navC').stop(true).slideDown(300) ;
                    }

                }
            })
            $('#gloMenu').on('click','a',function(){
                //if(!$(this).hasClass('isNav')) return false ;
                var href  = $(this).attr('href');
                var title = $(this).attr('data-title') || $(this).attr('title');
                if(!title)  title=$(this).text();
                var icon  = $(this).attr('data-icon') || $(this).find('i.fa').attr('data-icon');
                $('#gloMenu').find('a.current').removeClass('current');
                $(this).addClass('current') ;
//              Tab.tabAdd({
//                  title: title,
//                  href : href,
//                  icon : icon
//              })
                return false ;
            })

            $('#gloTop').find('.menuBar').click(function(){
                if($('#gloBox').hasClass('menu_close')){
                    $('#gloBox').removeClass('menu_close');
                }else{
                    $('#gloBox').addClass('menu_close');
                    $('.layui-nav-tree').css('width','unset');
                    $('.iframes').css('width','100%');
                    $('.admin_content').css('width','100%');
                }
            })
            $('.skin-down').hover(function(){
                $(this).find('.skin-show').stop(true,true).slideDown(300);
            },function(){
                $(this).find('.skin-show').stop(true,true).slideUp(300);
            })
            function change_skin(){
                var skin  = $(this).attr('data-skin');
                var url = "/run/tool/set_skin.html";
                HKUC.ajax_request.call(this,url,{
                            skin : skin
                        },
                        {
                            'success':function(msg,data){
                                $('#gloBox').removeClass().addClass(skin);
                            },
                            'error':function(msg,data){
                                layer.closeAll();
                                layer.msg(msg)
                            }
                        }
                );
            }
            function simple_clear(){
                var url = $(this).attr('href');
                HKUC.ajax_request.call(this,url,null,
                        {
                            'success':function(msg,data){
                                layer.closeAll();
                                layer.msg(msg)
                            },
                            'error':function(msg,data){
                                layer.closeAll();
                                layer.msg(msg)
                            }
                        }
                );
            }
            function switch_trace(){
                var url = $(this).attr('href');
                HKUC.ajax_request.call(this,url,null,
                        {
                            'success':function(msg,data){
                                layer.closeAll();
                                layer.msg(msg,{
                                    time:1000,
                                    end:function(){
                                        window.location.reload();
                                    }
                                });
                            },
                            'error':function(msg,data){
                                layer.closeAll();
                                layer.msg(msg)
                            }
                        }
                );
            }
            function get_site_size(){
                var url = $(this).attr('href');
                layer.msg('查询中请稍后...',{ time:30*60*1000, shade :[0.01, '#393D49']});
                HKUC.ajax_request.call(this,url,null,
                        {
                            'success':function(msg,data){
                                layer.closeAll();
                                $('#showSiteSize').html(msg)
                            },
                            'error':function(msg,data){
                                layer.closeAll();
                                layer.msg(msg)
                            }
                        }
                );
            }
            function newTime(){
                var now  = new Date();
                var year = now.getFullYear() ;
                var month = (now.getMonth()+1) >=10 ? (now.getMonth()+1): '0' + (now.getMonth()+1);
                var date  = now.getDate() >=10 ? now.getDate(): '0' + now.getDate();
                var hour = now.getHours() >=10 ? now.getHours(): '0' + now.getHours();
                var minute = now.getMinutes() >=10 ? now.getMinutes(): '0' + now.getMinutes();
                var second = now.getSeconds() >=10 ? now.getSeconds(): '0' + now.getSeconds();
                var datetime = year + '-' + month + '-' + date + ' ' + hour + ':' + minute + ':' + second;
                $('.showtime').html(datetime);
                $('.lockTime').html(hour + ':' + minute + ':' + second)
            }
            newTime()
            setInterval(newTime,1000)
            //同时按下alt+L锁屏
            document.onkeydown = function(event){
                if (event.keyCode == 76 && event.altKey){
                    lockScreen()
                }
            }
            //锁屏
            function lockScreen(){
                if($('#lockScreen').is(':visible')) return false ;
                $('#screenPwd').val('');
                $('#lockScreen').fadeIn(300, function(){
                    $('#closeLock').addClass('shake');
                })
                var url = "/admin/index/screenLock";
                $.ajax({
                    url 	: url,
                    type 	: "post",
                    dataType: "json",
                    data	: {'id':1,type:1},
                    success : function(res) {
                    }
                });
            }
            $('#screenPwd').keyup(function(event){
                if (event.keyCode == 13) {
                    $('#closeLock').trigger('click');
                }
            })
            //解锁
            $('#closeLock').click(function(){
                var url = "/admin/index/screenLock";
                var pwd = $.trim($('#screenPwd').val());
                var id = $(this).attr('data-id');
                if (!pwd) {
                    layer.msg('请输入密码');
                    return false;
                }
                $.ajax({
                    url 	: url,
                    type 	: "post",
                    dataType: "json",
                    data	: {'id':id,password:pwd},
                    success : function(res) {
                        if (res.code == 1) {
                            layer.closeAll();
                            $('#lockScreen').fadeOut(300);
                        } else {
                            layer.closeAll();
                            layer.msg(res.msg, {icon: 5,time: 1000});
                        }
                    }
                });
            })

            //修改密码
            $('.edit-pass').click(function () {
                var id = $(this).attr('data-id');
                layer.open({
                    type: 2,
                    title: "修改密码",
                    closeBtn: 1,
                    shadeClose: true,
                    shade: [0.3],
                    area: ["600px", "300px"],
                    content: ["/admin/admin_user/editPassWord/id/"+id, "yes"]
                });
            })

            //resize
            $(window).resize(function(){
                winWidth = $(window).width();
                heiHeght = $(window).height();
                $('#gloRght').height(heiHeght - 51);
                $(".admin_content").width(winWidth-$("#gloLeft").width()-20);
                $('#gloLeft,#gloSLeft').css('height',(heiHeght - 51) + 'px')
                $('.layui-tab-content').height(heiHeght - 51 - 40);

            }).trigger('resize')

            //Tab
            $(window).resize(function(){
                if(typeof(Tab) != 'undefined') Tab.resize();
            })

            $('.tab-prev').unbind('click').bind('click',function(){
                var left    = $('.layui-tab-title').position().left ;
                left  = left+117 <0 ? left+117 :0 ;
                $('.layui-tab-title').stop(true).animate({ left : left },500);
            })

            $('.tab-next').unbind('click').bind('click',function(){
                var left    = $('.layui-tab-title').position().left ;
                var boxWid  = $('.layui-tab-title').width() ;
                var liWid   = 0;
                $('.layui-tab-title').children('span').remove().end().find('li').each(function(){
                    liWid += $(this).outerWidth() ;
                })
                left  = left-117 > -(liWid-boxWid) ? left-117 :-(liWid-boxWid);
                if(left>0)left =  0;
                $('.layui-tab-title').stop(true).animate({ left : left },500);
            })
            function full_screen(){
                var docElm = document.documentElement;
                //W3C
                if (docElm.requestFullscreen) {
                    docElm.requestFullscreen();
                }
                //FireFox
                else if (docElm.mozRequestFullScreen) {
                    docElm.mozRequestFullScreen();
                }
                //Chrome等
                else if (docElm.webkitRequestFullScreen) {
                    docElm.webkitRequestFullScreen();
                }
                //IE11
                else if (docElm.msRequestFullscreen) {
                    docElm.msRequestFullscreen();
                }
            }
        </script>
    </body>
</html>