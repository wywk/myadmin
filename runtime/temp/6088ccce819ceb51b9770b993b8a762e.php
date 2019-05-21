<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:93:"D:\phpstudy\PHPTutorial\WWW\bschthinkphp\public/../application/admin\view\admin_menu\add.html";i:1557905303;s:75:"D:\phpstudy\PHPTutorial\WWW\bschthinkphp\application\admin\view\layout.html";i:1555490212;s:82:"D:\phpstudy\PHPTutorial\WWW\bschthinkphp\application\admin\view\public\header.html";i:1555490213;s:82:"D:\phpstudy\PHPTutorial\WWW\bschthinkphp\application\admin\view\public\footer.html";i:1555924200;}*/ ?>
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

    <fieldset class="layui-elem-field layui-field-title">
    <legend>菜单添加</legend>
</fieldset>
<form class="layui-form css-eidtform" id="editForm">
    <div class="layui-form-item">
        <label class="layui-form-label">菜单名称</label>
        <div class="layui-input-block">
            <input type="input" name="name" lay-verify="title" autocomplete="off" placeholder="请输入名称" class="layui-input css-width">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">父级</label>
        <div class="layui-input-block" style="width: 46.5%">
            <select name="pid">
                <option value="0">根菜单</option>
                <?php echo $parentTree; ?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">控制器</label>
        <div class="layui-input-block">
            <input type="input" name="controller" placeholder="请输入控制器" autocomplete="off" class="layui-input css-width">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">方法</label>
        <div class="layui-input-block">
            <input type="input" name="action" placeholder="请输入方法" autocomplete="off" class="layui-input css-width">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">参数</label>
        <div class="layui-input-block">
            <input type="input" name="param" placeholder="请输入参数" autocomplete="off" class="layui-input css-width">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">排序</label>
        <div class="layui-input-block">
            <input type="input" name="sort" placeholder="请输入排序" autocomplete="off" class="layui-input css-width">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">备注</label>
        <div class="layui-input-block">
            <input type="input" name="remark" placeholder="请输入备注" autocomplete="off" class="layui-input css-width">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="submit" class="layui-btn" lay-filter="submit" value="提交" lay-submit/>
            <button type="reset" class="layui-btn layui-btn-primary" onclick="editBankUrl('/admin/admin_menu/index')">返回</button>
        </div>
    </div>
</form>



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