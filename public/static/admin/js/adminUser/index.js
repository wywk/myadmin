$(function () {
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

    layui.use(['layer','table','form','laydate'], function() {
        var $ = layui.jquery,
            layer = layui.layer;
        table = layui.table;
        form = layui.form;
        laydate = layui.laydate;

        laydate.render({
            elem: '.startTime', //指定元素
            type: 'datetime' //去掉type就不显示时分秒
        });
        laydate.render({
            elem: '.endTime', //指定元素
            type: 'datetime' //去掉type就不显示时分秒
        });
        //监听开关操作
        form.on('switch(sexDemo)', function(obj){
            var id = $(this).attr('data-id');
            var status = 0;
            if(obj.elem.checked === true){
                status = 1;
            } else {
                status = 2;
            }
            $.ajax({
                url 	: "/admin/admin_user/setStatus",
                type 	: "post",
                dataType: "json",
                data	: {'id':id, 'status':status},
                success : function(res) {}
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
                        url 	: "/admin/admin_user/drop",
                        type 	: "post",
                        dataType: "json",
                        data	: {'id':id},
                        success : function(res) {}
                    });
                    obj.del();
                    layer.close(index);
                });
            } else if(obj.event === 'editPassWord'){
                layer.open({
                    type: 2,
                    title: "修改密码",
                    closeBtn: 1,
                    shadeClose: true,
                    shade: [0.3],
                    area: ["600px", "300px"],
                    content: ["/admin/admin_user/editPassWord/id/"+id, "yes"]

                });
            }
        });
    });

    //添加
    $(document).on('click', '.j-add', function() {
        editUrl('j-add');
    });

    //批量删除
    $(document).on('click', '.deleteAll', function() {
        var checkData = table.checkStatus('js-table');
        if (checkData.data.length >= 1) {
            var id = [];
            $.each(checkData.data, function (index, item) {
                id.push(item.id);
            });
            var ids = id.join(',');
            layer.confirm('真的删除行么', function(index){
                $.ajax({
                    url 	: "/admin/admin_user/drop",
                    type 	: "post",
                    dataType: "json",
                    data	: {'id':ids},
                    success : function(res) {
                        table.reload('js-table')
                        layer.close(index);
                    }
                });
            });
        } else {
            layer.confirm('未选中数据', function(index){
                layer.close(index);
            });
        }
    });


})