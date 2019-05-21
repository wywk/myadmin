//注意：选项卡 依赖 element 模块，否则无法进行功能性操作
layui.use('element', function(){
    var element = layui.element;
});
layui.use(['layer','table','form','laydate'], function() {
    var $ = layui.jquery,
        layer = layui.layer;
    table = layui.table;
    form = layui.form;
    //监听工具条
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
                });
                break;
            case 'fileTypeDrop':
                layer.confirm('真的删除行么', function(index){
                    $.ajax({
                        url 	: "/admin/system_gallery_group/drop",
                        type 	: "post",
                        dataType: "json",
                        data	: {'id':id},
                        success : function(res) {}
                    });
                    obj.del();
                    layer.close(index);
                });
                break;
        }

    });
    //监听工具条
    table.on('tool(admin_source)', function(obj){
        var id = obj.data.id;
        if(obj.event === 'edit'){
            layer.open({
                type: 2,
                title: "编辑来源",
                closeBtn: 1,
                shadeClose: true,
                shade: [0.3],
                area: ["600px", "300px"],
                content: ["/admin/admin_source/edit/id/"+id, "yes"],
                end: function(index, layero){
                    table.reload('source-table');
                },
            });
        } else if(obj.event === 'drop'){
            layer.confirm('真的删除行么', function(index){
                $.ajax({
                    url 	: "/admin/admin_source/drop",
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
})
//添加图库分组
$(document).on('click', '#fileTypeAdd', function() {
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
//批量删除
$(document).on('click', '.fileTypeDel', function() {
    var checkData = table.checkStatus('js-table');
    if (checkData.data.length >= 1) {
        var id = [];
        $.each(checkData.data, function (index, item) {
            id.push(item.id)
        });
        var ids = id.join(',');
        layer.confirm('真的删除行么', function(index){
            $.ajax({
                url 	: "/admin/system_gallery_group/drop",
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
        layer.confirm('未选中数据', function(index){
            layer.close(index);
        });
    }
});
// $("#fileTypeAdd").click(function() {
//
// })
$("#search").click(function() {
    var name = $("#name").val();
    var phone = $("#phone").val();
    var telephone = $("#telephone").val();
    var status = $("#status").val();
    var type = $("#type").val();
    var start_time = $("#start_time").val();
    var end_time = $("#end_time").val();
    var table = layui.table;
    table.reload('js-table', {
        where: { //设定异步数据接口的额外参数，任意设
            name: name,
            phone: phone,
            telephone: telephone,
            status: status,
            type: type,
            start_time: start_time,
            end_time: end_time
        },
        page: {
            curr: 1 //重新从第 1 页开始
        }
    });
})
//添加来源
$(document).on('click', '#sourceAdd', function() {
    layer.open({
        type: 2,
        title: "添加来源",
        closeBtn: 1,
        shadeClose: true,
        shade: [0.3],
        area: ["600px", "300px"],
        content: ["/admin/admin_source/add", "yes"],
        end: function(index, layero){
            table.reload('source-table');
        },
    });

});
//批量删除来源
$(document).on('click', '.deleteSourceAll', function() {
    var checkData = table.checkStatus('source-table');
    if (checkData.data.length >= 1) {
        var id = [];
        $.each(checkData.data, function (index, item) {
            id.push(item.id)
        });
        var ids = id.join(',');
        layer.confirm('真的删除行么', function(index){
            $.ajax({
                url 	: "/admin/admin_source/drop",
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