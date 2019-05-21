//添加来源
$(document).on('click', '.j-add', function() {
    layer.open({
        type: 2,
        title: "添加图库分组",
        closeBtn: 1,
        shadeClose: true,
        shade: [0.3],
        area: ["600px", "300px"],
        content: ["/admin/system_gallery_group/add", "yes"],
    });
});
//监听工具条
layui.use(['layer','table','form','laydate'], function() {
    var $ = layui.jquery,
        layer = layui.layer;
    table = layui.table;
    form = layui.form;
    table.on('tool(demo_zw)', function(obj){
        var id = obj.data.id;
        switch (obj.event){
            case 'fileTypeEdit':
                layer.open({
                    type: 2,
                    title: "编辑图库分组",
                    closeBtn: 1,
                    shadeClose: true,
                    shade: [0.3],
                    area: ["600px", "300px"],
                    content: ["/admin/system_gallery_group/edit/id/"+id, "yes"],
                    // btn: ['提交', '取消'],
                });
                break;
            case 'fileTypeDrop':
                layer.confirm('真的删除行么', function(index){
                    $.ajax({
                        url 	: "/admin/system_gallery_group/drop",
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
                    obj.del();
                    layer.close(index);
                });
                break;
        }

    });