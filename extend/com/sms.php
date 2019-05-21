<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: luffy <1549626108@qq.com>
// +----------------------------------------------------------------------
namespace com;

use com\SignatureHelper;

/**
 * wangh
 * Class SmsApp
 * 短信发送类
 */
class sms {

    private  $accessKeyId;  // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
    private  $accessKeySecret;
    private  $SignName;    // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
    private  $TemplateCode;    // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template

    public function __construct() {
        $this->accessKeyId      = 'LTAIHGCVtIniuaKy';
        $this ->accessKeySecret = 'CsXS0lJKOS8LIo4UCQ2BCTS9GmHhlg';
        $this ->SignName        = '艾美睿零售';
        $this ->TemplateCode    = 'SMS_156277705';
    }

    /**
     * 发送短信(注册)
     */
    public function sendSms(){
        $code = $this -> getCode(); //验证码

        $params = array ();
        $params["PhoneNumbers"] = '17602537690';
        $params["SignName"] = $this -> SignName;
        $params["TemplateCode"] = $this ->TemplateCode;
        $params['TemplateParam'] = array(
            "code" => $code,
//            "product" => "倍速创恒"
        );
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"]);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        // @return bool|\stdClass 返回API接口调用结果，当发生错误时返回false

        $helper = new \com\SignatureHelper();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $this ->accessKeyId,
            $this ->accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId"  => "cn-hangzhou",
                "Action"    => "SendSms",
                "Version"   => "2017-05-25",
            ))
        );

        header("Content-Type: text/plain; charset=utf-8"); // 输出为utf-8的文本格式
        $content = (array)$content ;

    }

    /**
     * 生成验证码
     * @author xiayy
     * @date 2016-11-11
     */
    public function getCode($length =6 ){
        $min = pow(10 , ($length - 1));
        $max = pow(10, $length) - 1;
        return rand($min, $max);
    }

} 