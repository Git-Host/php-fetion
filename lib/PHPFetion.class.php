<?php
/**
 * PHP飞信发送类
 * @author quanhengzhuang <phpqhz@gmail.com>
 * @version 1.1.0
 */
class PHPFetion {
	
	/**
	 * 发送者手机号
	 * @var string
	 */
	protected $_mobile;
	
	/**
	 * 飞信密码
	 * @param string
	 */
	protected $_password;
	
	/**
	 * Cookie路径
	 * @param string
	 */
	protected $_cookie;
	
	/**
	 * 构造函数
	 * @param string $mobile 手机号(登录者)
	 * @param string $password 飞信密码
	 */
	public function __construct($mobile, $password) {
		if($mobile === '' || $password === '') {
			return false;
		}
		
		$this->_mobile = $mobile;
		$this->_password = $password;
		$this->_cookie = dirname(__FILE__).'/'.$mobile.'_cookie.txt';
		
		$this->_login();
	}
	
	/**
	 * 向指定的手机号发送飞信
	 * @param string $mobile 手机号(接收者)
	 * @param string $message 短信内容
	 * @return string
	 */
	public function send($mobile, $message) {
		if($mobile == $this->_mobile) {
			// 给自己发短信
			return $this->_toMyself($message);
		} else {
			// 给好友发短信
			$uid = $this->_getUid($mobile);
			return $this->_toUid($uid, $message);
		}
	}
	
	/**
	 * 登录
	 * @return string
	 */
	protected function _login() {
		$post = array(
			'm' => $this->_mobile,
			'pass' => $this->_password,
			'loginstatus' => 1,
		);
		
		$curl = curl_init('http://f.10086.cn/im/login/inputpasssubmit1.action');
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_COOKIEJAR, $this->_cookie);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		$result = curl_exec($curl);
		curl_close($curl);
		
		return $result;
	}
	
	/**
	 * 获取飞信ID
	 * @param string $mobile 手机号
	 * @return string
	 */
	protected function _getUid($mobile) {
		$post = array(
			'searchText' => $mobile,
		);
		
		$curl = curl_init('http://f.10086.cn/im/index/searchOtherInfoList.action');
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_COOKIEFILE, $this->_cookie);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		$result = curl_exec($curl);
		curl_close($curl);
		
		// 匹配
		preg_match('/toinputMsg\.action\?touserid=(\d+)/si', $result, $matches);
		
		return isset($matches[1]) ? $matches[1] : '';
	}
	
	/**
	 * 向好友发送飞信
	 * @param string $uid 飞信ID
	 * @param string $message 短信内容
	 * @return string
	 */
	protected function _toUid($uid, $message) {
		$post = array(
			'msg' => $message,
		);
		
		$curl = curl_init('http://f.10086.cn/im/chat/sendMsg.action?touserid='.$uid);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_COOKIEFILE, $this->_cookie);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		$result = curl_exec($curl);
		curl_close($curl);
		
		return $result;
	}
	
	/**
	 * 给自己发飞信
	 * @param string $message
	 * @return string
	 */
	protected function _toMyself($message) {
		$post = array(
			'msg' => $message,
		);
		
		$curl = curl_init('http://f.10086.cn/im/user/sendMsgToMyselfs.action');
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_COOKIEFILE, $this->_cookie);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		$result = curl_exec($curl);
		curl_close($curl);
		
		return $result;
	}
	
	/**
	 * 退出飞信
	 * @return string
	 */
	protected function _logout() {
		$curl = curl_init('http://f.10086.cn/im/index/logoutsubmit.action?t=30310469084920296');
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_COOKIEFILE, $this->_cookie);
		$result = curl_exec($curl);
		curl_close($curl);
		
		return $result;
	}

	/**
	 * 析构函数
	 */
	public function __destruct() {
		// 退出飞信
		$this->_logout();
		
		// 删除Cookie文件
		unlink($this->_cookie);
	}
	
}
