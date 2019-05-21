$(function () {
    layui.use(['layer','table','form','laydate'], function() {
        var $ = layui.jquery,
            layer = layui.layer;
        table = layui.table;
        form = layui.form;
        laydate = layui.laydate;
        //监听工具条
        table.on('tool(demo)', function(obj){
            var id = obj.data.id;
            if(obj.event === 'edit'){
                layer.open({
                    type: 2,
                    title: "编辑角色",
                    closeBtn: 1,
                    shadeClose: true,
                    shade: [0.3],
                    area: ["600px", "350px"],
                    content: ["/admin/store_role/edit/id/"+id, "yes"],
                    end: function(index, layero){
                        table.reload('source-table');
                    },
                });
            } else if(obj.event === 'drop'){
                layer.confirm('真的删除行么', function(index){
                    $.ajax({
                        url 	: "/admin/store_role/drop",
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
            } else if (obj.event === 'menu') {
                editUrl('j-menu', id);
            }
        });
    });
    //添加
    $(document).on('click', '.j-add', function() {
        layer.open({
            type: 2,
            title: "添加角色",
            closeBtn: 1,
            shadeClose: true,
            shade: [0.3],
            area: ["600px", "350px"],
            content: ["/admin/store_role/add", "yes"],
            end: function(index, layero){
                table.reload('source-table');
            },
        });
    });
})