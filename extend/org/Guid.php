<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: matengfei <matengfei2000@gmail.com>
// +----------------------------------------------------------------------

namespace org;

/**
 * GUID生成类
 *
 */
class System {    
    static function currentTimeMillis(){
        list($usec, $sec) = explode(" ", microtime());    
        return $sec.substr($usec, 2, 3);   
    }   
}

class NetAddress {    
    var $Name = 'localhost';    
    var $IP = '127.0.0.1';    
    static function getLocalHost(){    
        $address = new NetAddress();    
        $address->Name = isset($_ENV["COMPUTERNAME"]) ? $_ENV["COMPUTERNAME"] : '';    
        $address->IP = isset($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_ADDR"] : '';    
        return $address;    
    }
    
    function toString(){    
        return strtolower($this->Name . '/' . $this->IP);    
    }    
}

class Random {    
    static function nextLong(){    
        $tmp = rand(0,1) ? '-' : '';    
        return $tmp.rand(1000, 9999).rand(1000, 9999).rand(1000, 9999).rand(100, 999).rand(100, 999);    
    }
}

// 三段    
// 一段是微秒 一段是地址 一段是随机数    
class Guid {
	
    var $valueBeforeMD5;    
    var $valueAfterMD5;
       
    function __construct() {
        $this->getGuid();    
    }
        
    function getGuid(){    
        $address = NetAddress::getLocalHost();    
        $this->valueBeforeMD5 = $address->toString().':'.System::currentTimeMillis().':'.Random::nextLong();    
        $this->valueAfterMD5 = md5($this->valueBeforeMD5);
    }
    
    function newGuid(){
        $Guid = new Guid();    
        return $Guid;    
    }
      
    function toString(){    
        //$raw = strtoupper($this->valueAfterMD5);
        $raw = $this->valueAfterMD5;
        return substr($raw,0,8).'-'.substr($raw,8,4).'-'.substr($raw,12,4).'-'.substr($raw,16,4).'-'.substr($raw,20);    
    }
    
}