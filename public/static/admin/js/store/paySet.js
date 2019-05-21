//文件上传
layui.use('upload', function(){
    var $ = layui.jquery
        ,upload = layui.upload;
    //安全证书上传
    var uploadInst = upload.render({
        elem: '#aq_cert'
        ,url: '/admin/Upload/uploadWxCert'
        ,accept:'file'
        ,exts:'pem'
        ,before: function(obj){
            //预读本地文件示例，不支持ie8
            obj.preview(function(index, file, result){

            });
        }
        ,done: function(res){
            //如果上传失败
            if(res.code ==1){
                $('#wx_cert_path').val(res.url);
            }
            if(res.code !=1){
                return layer.msg('上传失败');
            }
            //上传成功
        }
        ,error: function(){
            return false;
        }
    });
    //支付证书上传
    var uploadInst = upload.render({
        elem: '#pay_cert'
        ,url: '/admin/Upload/uploadWxCert'
        ,accept:'file'
        ,exts:'pem'
        ,before: function(obj){
            //预读本地文件示例，不支持ie8
            obj.preview(function(index, file, result){

            });
        }
        ,done: function(res){
            //如果上传失败
            if(res.code ==1){
                $('#wx_key_path').val(res.url);
            }
            if(res.code !=1){
                return layer.msg('上传失败');
            }
            //上传成功
        }
        ,error: function(){
            return false;
        }
    });

});
layui.use('element', function() {
    var $ = layui.jquery,
        element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块
});
//Demo
layui.use('form', function() {
    var form = layui.form;
    //监听提交
    form.on('submit(formDemo)', function(data) {
        var info =data.field;
        layui.use('jquery',function(){
            // return false;
            $.ajax({
                type: 'post',
                url: '/admin/store/paySet', // ajax请求路径
                data: info,
                success: function(res){
                    // console.log(res);
                    var msg = res.msg;
                    // var url = res.
                    if(res.code==1 ){
                        layer.msg(msg);
                        window.location.href = "/admin/store/paySet/id/"+id;
                    }else{
                        layer.msg(msg);
                    }
                }
            });
        });
        return false;
    });
});