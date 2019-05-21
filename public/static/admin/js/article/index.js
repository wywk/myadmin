$(function () {
    $("#search").click(function() {
        var name = $("#name").val();
        var cate_id = $("#cate_id").val();
        var table = layui.table;
        table.reload('js-table', {
            where: { //设定异步数据接口的额外参数，任意设
                name:name,
                cate_id:cate_id,
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
        //监听性别操作
        form.on('switch(sexDemo)', function(obj){
            var status = 0;
            if(obj.elem.checked === true){
                status = 1;
            } else {
                status = 2;
            }
            $.ajax({
                url 	: "/admin/article/setStatus",
                type 	: "post",
                dataType: "json",
                data	: {'id':obj.value, 'status':status},
                success : function(res) {}
            });
        });
        //监听工具条
        table.on('tool(demo)', function(obj){
            console.log(obj.event)
            var id = obj.data.id;
            if(obj.event === 'edit'){
                editUrl('j-edit',id);
            } else if(obj.event === 'drop'){
                layer.confirm('真的删除行么', function(index){
                    $.ajax({
                        url 	: "/admin/article/drop",
                        type 	: "post",
                        dataType: "json",
                        data	: {'id':id},
                        success : function(res) {}
                    });
                    obj.del();
                    layer.close(index);
                });
            }
        });
    });
//添加
    $(document).on('click', '.j-add', function() {
        window.location.href = "/admin/article/add";
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
                    url 	: "/admin/article/drop",
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