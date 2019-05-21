

layui.use(['form', 'layedit', 'laydate'],
    function() {
        var $ = layui.jquery,
            form = layui.form,
            layer = layui.layer,
            layedit = layui.layedit,
            laydate = layui.laydate;

        //监听省份选择
        form.on('select(province)',
            function (data) {
                $('#city').html('<option value="">请选择市</option>');
                $('#area').html('<option value="">请选择区/县</option>');
                $.ajax({
                    url: "/admin/store/getCity",
                    data: {
                        parent_id: data.value
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function (data1) {
                        if (data1.code == 1) {
                            $("#city").append(data1.option);
                            form.render('select'); //刷新select选择框渲染
                        }
                    }
                });
            });
        form.on('select(city)',
            function (data) {
                $('#area').html('<option value="">请选择区/县</option>');
                $.ajax({
                    url: "/admin/store/getCity",
                    data: {
                        parent_id: data.value
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function (data1) {
                        if (data1.code == 1) {
                            $("#area").append(data1.option);
                            form.render('select'); //刷新select选择框渲染
                        }
                    }
                });
            });

    });
//时间控件
layui.use('laydate', function() {
    var laydate = layui.laydate;
    //时间范围
    laydate.render({
        elem: '#inputTime'
        , type: 'time'
        , range: true
    });
})
//图片上传
layui.use('upload', function(){
    var $ = layui.jquery
        ,upload = layui.upload;
    //普通图片上传
    var uploadInst = upload.render({
        elem: '#LAY_avatarUpload'
        ,url: '/admin/Upload/uploadImg'
        ,before: function(obj){
            //预读本地文件示例，不支持ie8
            obj.preview(function(index, file, result){
                $('#demo1').attr('src', result); //图片链接（base64）
            });
        }
        ,done: function(res){
            //如果上传失败
            if(res.code ==1){
                $('#LAY_avatarSrc').val(res.url);
            }
            if(res.code !=1){
                return layer.msg('上传失败');
            }
            //上传成功
        }
        ,error: function(){
            //演示失败状态，并实现重传
            var demoText = $('#demoText');
            demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
            demoText.find('.demo-reload').on('click', function(){
                uploadInst.upload();
            });
        }
    });

});

layui.use([ 'form' ], function() {
    var form = layui.form,
        layer = layui.layer;
    //监听提交
    form.on('submit(setmyinfo)', function(data){
        var info = data.field;
        // console.log(has);
        layui.use('jquery',function(){
            // return false;
            $.ajax({
                type: 'post',
                url: 'edit', // ajax请求路径
                data: info,
                success: function(res){
                    // console.log(res);
                    var msg = res.msg;
                    if(res.code==1 ){
                        layer.msg(msg);
                    }else{
                        layer.msg(msg);
                    }
                }
            });
        });
        return false;//禁止跳转，否则会提交两次，且页面会刷新
    });
    form.render();
});

