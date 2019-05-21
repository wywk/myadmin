layui.use('layer', function(){
	var layer = layui.layer;
	layer.config({
		extend: 'espresso/style.css',
		skin: 'layer-ext-espresso'
	});
});

layui.use(['form', 'layedit', 'laydate', 'element'], function(){
	var $ = layui.jquery,
		form = layui.form,
		layer = layui.layer,
		layedit = layui.layedit,
		element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块

	//监听提交
	form.on('submit(submit)', function(data){
		var form = null;
		var form_id = $(this).data("form-id"); // 添加对 多表单 支持
		if ( form_id!=undefined && form_id!=null && $("#"+form_id).length>0 ) {
			form = $("#"+form_id);
		} else {
			form = $("#editForm");
		}

		var url = form.attr('action');
		var form_data = form.serialize();
		var id = data.field.id;
		var dialog = form.attr('dialog');
		$.ajax({
			url : url,
			type : "post",
			dataType : "json",
			//data: data.field, //数组提交会有问题
			data: form_data,
			success : function(res) {
				if (res.code) {
					if (dialog == 1) {// 删除操作，弹窗直接关闭
						setTimeout(function(){
							layer.alert(res.msg,{icon:1,time:2000},function(){
								var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
								parent.layer.close(index);
							});
						}, res.wait*1000*0);
						parent.location.reload();
					} else {// 打开窗口操作
						if (res.url) {
							//返回带跳转地址
							setTimeout(function(){
								if (typeof id != 'undefined') { //编辑
									if (id > 0) {
										layer.alert(res.msg,{icon:1,time:2000,yes:function(){// 点击
											var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
											parent.layer.close(index);
											location.href = res.url;
											parent.layui.table.reload('js-table');
										},end:function(){
											var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
											parent.layer.close(index);
											location.href = res.url;
											parent.layui.table.reload('js-table');
										}});
									} else { //添加
										console.log(2)
										layer.confirm(""+res.msg+"....",{
											btn:['继续添加','关闭页面'],
											yes:function(){
												reloadPage(window);
											},
											btn2:function(){
												location.href = res.url;
											}
										});
									}
								} else {
									console.log(3);
                                    layer.alert(res.msg,{icon:1,time:2000,yes:function(){// 点击
										var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
										parent.layer.close(index);
										location.href = res.url;
										parent.layui.table.reload('js-table');
									},end:function(){
										var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
										parent.layer.close(index);
										location.href = res.url;
										parent.layui.table.reload('js-table');
									}});
								}
							}, res.wait*1000*0);
						} else {
							//刷新当前页
							reloadPage(window);
						}
					}
				} else if (res.code === 0) {
					layer.alert(res.msg,{title:'错误提示',icon:0});
				}
			}
		});
		return false;
	});
});

function editUrl(class_name,id){
	var dataSrc = $('.'+class_name).attr('data-href') + '/id/'+id;
	window.location.href = dataSrc;
	$('.layui-show .iframes',window.parent.document).attr('src',dataSrc);
};

function editBankUrl(url){
	window.location.href = url;
	$('.layui-show .iframes',window.parent.document).attr('src', url);
};


