$(function () {
    layui.use(['layer','table','form','laydate'], function() {
        var $ = layui.jquery,
            layer = layui.layer;
        table = layui.table;
        form = layui.form;
        laydate = layui.laydate;

        //监听状态开关操作
        form.on('switch(statusDemo)', function(obj){
            var id = $(this).attr('data-id');
            var status = 0;
            if(obj.elem.checked === true){
                status = 1;
            } else {
                status = 2;
            }
            $.ajax({
                url 	: "/admin/admin_role/setStatus",
                type 	: "post",
                dataType: "json",
                data	: {'id':id, 'status':status},
                success : function(res) {
                    if (res.code == 1) {
                        layer.msg(res.msg, {icon: 1,time: 1000});
                    } else {
                        layer.msg(res.msg, {icon: 5,time: 1000});
                    }
                }
            });
        });
        //监听工具条
        table.on('tool(demo)', function(obj){
            var id = obj.data.id;
            if(obj.event === 'edit'){
                var url = $('.j-edit').attr('data-href') + '/id/'+id;
                $.get(url, {}, function(str){
                    layer.open({
                        type: 1,
                        content: str, //注意，如果str是object，那么需要字符拼接。
                        area: ['500px', '380px'],
                        title: '编辑角色',
                        btn: ['提交', '关闭'],
                        yes: function(index, layero){
                            var data = $('#editForm').serialize();
                            $.ajax({
                                url 	: "/admin/admin_role/add",
                                type 	: "post",
                                dataType: "json",
                                data	: data,
                                success : function(res) {
                                    if (res.code == 1) {
                                        layer.msg(res.msg, {icon: 1,time: 1000});
                                        layer.close(index);
                                        table.reload('js-table')
                                    } else {
                                        layer.msg(res.msg, {icon: 5,time: 1000});
                                    }
                                }
                            });
                        },
                        cancel: function(index){
                            layer.close(index)
                        }
                    });
                });
            } else if(obj.event === 'drop'){
                layer.confirm('真的删除行么', function(index){
                    $.ajax({
                        url 	: "/admin/admin_role/drop",
                        type 	: "post",
                        dataType: "json",
                        data	: {'id':id},
                        success : function(res) {
                            if (res.code == 1) {
                                obj.del();
                                layer.close(index);
                                layer.msg(res.msg, {icon: 1,time: 1000});
                            } else {
                                layer.msg(res.msg, {icon: 5,time: 1000});
                            }
                        }
                    });
                });
            }
        });
    });
    //添加
    $(document).on('click', '.j-add', function() {
        var url = "/admin/auth_group/add";
        $.get(url, {}, function(str){
            layer.open({
                type: 1,
                content: str, //注意，如果str是object，那么需要字符拼接。
                area: ['500px', '380px'],
                title: '添加角色',
                btn: ['提交', '关闭'],
                yes: function(index, layero){
                    var data = $('#addForm').serialize();
                    $.ajax({
                        url 	: "/admin/admin_role/add",
                        type 	: "post",
                        dataType: "json",
                        data	: data,
                        success : function(res) {
                            if (res.code == 1) {
                                layer.msg(res.msg, {icon: 1,time: 1000});
                                layer.close(index);
                                table.reload('js-table')
                            } else {
                                layer.msg(res.msg, {icon: 5,time: 1000});
                            }
                        }
                    });
                },
                cancel: function(index){
                    layer.close(index)
                }
            });
        });
    });
    //批量删除
    $(document).on('click', '.deleteAll', function() {
        var checkData = table.checkStatus('js-table');
        if (checkData.data.length >= 1) {
            var id = [];
            $.each(checkData.data, function (index, item) {
                id.push(item.id)
            });
            var ids = id.join(',');
            layer.confirm('真的删除行么', function(index){
                $.ajax({
                    url 	: "/admin/admin_role/drop",
                    type 	: "post",
                    dataType: "json",
                    data	: {'id':ids},
                    success : function(res) {
                        if (res.code == 1) {
                            layer.msg(res.msg, {icon: 1,time: 1000});
                            table.reload('js-table')
                        } else {
                            layer.msg(res.msg, {icon: 5,time: 1000});
                        }
                    }
                });
            });
        } else {
            layer.msg('未选中数据', {icon: 5,time: 1000});
        }
    });
})