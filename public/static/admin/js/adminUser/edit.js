layui.use(['layer','table','form','laydate'], function() {
    var $ = layui.jquery,
        layer = layui.layer;
    table = layui.table;
    form = layui.form;
    laydate = layui.laydate;

    var roleIdList = $('#role_ids').val().split(',');
    var roleIds = $('#role_ids').val();
    //监听复选框操作
    form.on('checkbox(role)', function(data){
        if (data.elem.checked == true) {
            roleIdList.push(data.value);
        } else {
            roleIdList = $.grep(roleIdList, function(value) {
                return value != data.value;
            });
        }
        roleIds = roleIdList.join(',');
        $('#role_ids').val(roleIds);
    });
});