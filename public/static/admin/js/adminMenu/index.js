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
                url 	: "/admin/admin_menu/setStatus",
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
                editUrl('j-edit',id);
            } else if(obj.event === 'drop'){
                layer.confirm('真的删除行么', function(index){
                    $.ajax({
                        url 	: "/admin/admin_menu/drop",
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
        editUrl('j-add',0);
    });
})