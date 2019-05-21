// <!--搜索-->
$("#search").click(function() {
    var name = $("#name").val();
    var link_man = $("#link_man").val();
    var link_tel = $("#link_tel").val();
    var table = layui.table;
    table.reload('js-table', {
        where: { //设定异步数据接口的额外参数，任意设
            name: name,
            link_man: link_man,
            link_tel: link_tel,
        },
        page: {
            curr: 1 //重新从第 1 页开始
        }
    });
})
//添加

layui.use(['layer','table','form'], function() {
    var $ = layui.jquery,
        layer = layui.layer;
    table = layui.table;
    form = layui.form;


    //监听工具条
    table.on('tool(demo)', function(obj){
        var id = obj.data.id;
        switch (obj.event){
            case 'edit':
                editUrl('j-edit',id);
                break;
            case 'info':
                window.location.href = "/admin/store/info/id/"+id;
                break;
            case 'del':
                layer.confirm('真的删除行么', function(index){
                    $.ajax({
                        url 	: "/admin/store/drop",
                        type 	: "post",
                        dataType: "json",
                        data	: {'id':id},
                        success : function(res) {}
                    });
                    obj.del();
                    layer.close(index);
                });
                break;
            case 'paySet':
                editUrl('j-paySet',id);
        }
    });
});



layui.use('laydate', function() {
    var laydate = layui.laydate;
    laydate.render({
        elem: '.startTime', //指定元素
        type: 'datetime' //去掉type就不显示时分秒
    });
});
layui.use('laydate', function() {
    var laydate = layui.laydate;
    laydate.render({
        elem: '.endTime', //指定元素
        type: 'datetime' //去掉type就不显示时分秒
    });
});
layui.use('layer', function() {
    var $ = layui.jquery,
        layer = layui.layer;
    //触发事件
    var active = {
        setTop: function() {
            var that = this;
            //多窗口模式，层叠置顶
            layer.open({
                type: 2 //此处以iframe举例
                ,
                title: '弹框事例',
                area: ['560px', '360px'],
                shade: 0,
                maxmin: true,
                content: '{:url(\'user/add\')}',
                btn: ['确定', '关闭'] //只是为了演示
                ,
                yes: function() {
                    layer.closeAll();
                },
                btn2: function() {
                    layer.closeAll();
                },
                zIndex: layer.zIndex //重点1
                ,
                success: function(layero) {
                    layer.setTop(layero); //重点2
                }
            });
        }
    };
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
                    url 	: "/admin/store/drop",
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
    $(document).on('click', '.j-add', function() {
        editUrl('j-add',0);
    });

});