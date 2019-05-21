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
 * 伪浏览器类
 *
 */
class Browser {
	
	private $curlHandle, $curHeader;
	private $cookie;
	private $referer, $ip;
	
	/**
	 * 构造函数
	 */
	public function __construct($url=NULL){
		$this->initCurl();
		$this->curHeader = NULL;
		$this->cookie = NULL;
		$this->referer = $url;
		$this->ip = NULL;
	}
	
	/**
	 * 析构函数
	 */
	function __destruct(){
		curl_close($this->curlHandle);
		@unlink($this->cookie);
	}
	
	/**
	 * GET方式访问
	 * 
	 * @author 	matengfei
	 * @param 	string $url	 访问地址
	 * @param 	bool $isAjax 是否为ajax请求
	 * @return 	string
	 */
	public function visitByGet($url, $isAjax = false){
		$cookiefile = $this->getCookieFile($url);
		$this->setHeader($isAjax);
		curl_setopt($this->curlHandle, CURLOPT_URL, $url);
		curl_setopt($this->curlHandle, CURLOPT_POST, false);
		curl_setopt($this->curlHandle, CURLOPT_COOKIEJAR, $cookiefile);
		curl_setopt($this->curlHandle, CURLOPT_COOKIEFILE, $cookiefile);
		curl_setopt($this->curlHandle, CURLOPT_ENCODING, 'gzip');
		curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($this->curlHandle);
		$separatePos = strpos($result , "\r\n\r\n");
		$header = substr($result, 0, $separatePos);
		$content = substr($result, $separatePos+4);
		$this->curHeader = $header;
		$this->referer = $url;
		return $content;
	}
	
	/**
	 * POST方式访问
	 * 
	 * @author 	matengfei
	 * @param 	string $url	 访问地址
	 * @param 	array $data
	 * @param 	bool $isAjax 是否为ajax请求
	 * @return 	string
	 */
	public function visitByPost($url, $data, $isAjax = false){
		$cookiefile = $this->getCookieFile($url);
		$this->setHeader($isAjax);
		curl_setopt($this->curlHandle, CURLOPT_URL, $url);
		curl_setopt($this->curlHandle, CURLOPT_POST, true);
		curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($this->curlHandle, CURLOPT_COOKIEJAR, $cookiefile);
		curl_setopt($this->curlHandle, CURLOPT_COOKIEFILE, $cookiefile);
		curl_setopt($this->curlHandle, CURLOPT_ENCODING, 'gzip');
		curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($this->curlHandle);
		$separatePos = strpos($result, "\r\n\r\n");
		$header = substr($result, 0, $separatePos);
		$content = substr($result, $separatePos+4);
		$this->curHeader = $header;
		return $content;
	}
	
	/**
	 * 伪造IP
	 * 
	 * @author 	matengfei
	 * @param 	string  $ip  IP地址
	 * @return 	string
	 */
	public function useIp($ip = NULL){
		if ($ip == NULL) {
			$ip1 = rand(10, 255);
			$ip2 = rand(10, 255);
			$ip3 = rand(10, 255);
			$ip4 = rand(10, 255);
			$ip = "{$ip1}.{$ip2}.{$ip3}.{$ip4}";
		}
		$this->ip = $ip;
	}
	
	/**
	 * 获取当前的Header信息
	 * 
	 * @author 	matengfei
	 * @param 	void
	 * @return 	array	
	 */
	public function getLastHeaderInfo() {
		$header = $this->curHeader;
		$headerLines = explode("\r\n", $header);
		$headerList = array();
		foreach ($headerLines as $line) {
			$pos = strpos($line, ":");
			if ($pos) {
				$key = substr($line, 0, $pos);
				$headerList[$key] = trim(substr($line, $pos+1));
			}else{
				$headerList[] = $line;
			}
		}
		return $headerList;
	}
	
	/**
	 * 获取当前HTTP状态
	 * 
	 * @author  matengfei
	 * @param   void
	 * @return 	int	
	 */
	public function getLastHttpStatus(){
		$header = $this->curHeader;
		$headerLines = explode("\r\n", $header);
		$status = 500;
		foreach ($headerLines as $line) {
			$pos = strpos($line, 'HTTP/');
			if ($pos !== false) {
				list($pro, $status) = explode(' ', $line);
				break;
			}
		}
		return (int)$status;
	}
	
	/**
	 * 设置头信息
	 * 
	 * @author 	matengfei
	 * @param 	bool  $isAjax
	 * @return 	void
	 */
	private function setHeader($isAjax){
		$header = array();
		$header[] = "User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154";
		if ($this->ip) {
			$header[] = "CLIENT-IP:{$this->ip}";
			$header[] = "X-FORWARDED-FOR:{$this->ip}";
		}
		if ($this->referer) {
			$header[] = "Referer:{$this->referer}";
		}
		if ($isAjax) {
			$header[] = "X-Requested-With:XMLHttpRequest";
		}
		curl_setopt($this->curlHandle, CURLOPT_HTTPHEADER, $header);
	}
	
	/**
	 * 设置上一个访问页面地址
	 * 
	 * @author 	matengfei
	 * @param 	string $url
	 * @return 	void
	 */
	private function setReferer($url){
		$this->referer = $url;
	}
	
	/**
	 * 初始化CURL
	 * 
	 * @author 	matengfei
	 * @param 	void
	 * @return 	void
	 */
	private function initCurl(){
		$this->curlHandle = curl_init();
		curl_setopt($this->curlHandle, CURLOPT_HEADER, 1);
		curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curlHandle, CURLOPT_FOLLOWLOCATION, true);
	}
	
	/**
	 * 获取COOKIE文件
	 * 
	 * @author 	matengfei
	 * @param 	string $url	访问地址
	 * @return 	string
	 */
	private function getCookieFile($url){
		if ($this->cookie == NULL) {
			$filename = parse_url($url, PHP_URL_HOST);
			$filename = trim($filename, '/') . '.tmp'. rand(1000,9999);
			$filename = ROOT_PATH . 'data/temp/'. $filename;
			$this->cookie = $filename;
		}
		return $this->cookie;
	}
	
}
