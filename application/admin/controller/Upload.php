<?php
namespace app\admin\controller;

use app\common\controller\Backend;
use app\admin\model\AdminLog;
use think\Db;

class Upload extends Backend
{

    public function index()
    {
        return $this->fetch();
    }
    //图片上传
    public function uploadImg(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                $path =  $info->getSaveName();

                $imgPath ='/uploads/' . $path;//原图路径
                return json(['code' => 1, 'msg' => '上传成功!', 'url' => $imgPath]);
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }
    //微信证书上传
    public function uploadWxCert(){
        // 获取表单上传文件
        $file = request()->file('file');
        // 移动到框架应用根目录/public/cert/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'cert');
            if($info){
                // 成功上传后 获取上传信息
                $path =  $info->getSaveName();
                $imgPath ='/cert/' . $path;//原图路径
                return json(['code' => 1, 'msg' => '上传成功!', 'url' => $imgPath]);
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }

//文件上传代码--带缩率图
    public function uploads()
    {
        //文件接收
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $file_path = ROOT_PATH . 'public/uploads/';
        $info = $file->validate(['size' => 4145728, 'ext' => 'jpg,png,gif,jpeg'])->move($file_path);
        //验证文件后缀后大小
        $error = $file->getError();
        if ($error) {
            return ['code' => 2, 'error' => $error];
        }
        if ($info) {
            // 成功上传后 获取上传信息
            $y_path = $info->getExtension();//文件路径
            $y_name = $info->getSaveName();//文件名
            $photo = $info->getFilename();
            $sm_imgurl =ROOT_PATH. '/public/uploads/' . $y_name;//原大图路径,当时我获取的时候费了不少时间
            $image = \think\Image::open($sm_imgurl);
            $image->thumb(50, 50, 1)->save($sm_imgurl);//生成缩略图、删除原图
            $info = $file->move($file_path);
            $reubfo = array(); //定义一个返回的数组
            if ($info) {
                // 成功上传后 获取上传信息
                return json(['code' => 0, 'msg' => '上传成功!', 'url' => '/layer_uploads/' . $info->getSaveName()]);
            } else {
                // 上传失败获取错误信息
            return json(['code' => 1, 'msg' =>$error, 'url' => '']);
            }
        }
    }
}
