<?php

namespace Emil\Restful;

use \Emil\Restful\Support\Curl;

class Request {

	private $client;
	public $url;
	public $params;
	public $method;

	public function __construct($method, $url, $params = array()) {
		$this->client = new Curl();
		$this->url = $url;
		$this->params = $params;
		$this->method = $method;
	}

	static function get($url, $params = array()){
		return new self('GET', $url, $params);
	}
	static function post($url, $params = array()){
		return new self('POST', $url, $params);
	}
	static function head($url, $params = array()) {
		return new self('HEAD', $params);
	}
	static function put($url, $params = array()){
		$req = new self('POST', $url, $params);
		$req->param('_method', 'PUT');
		return $req;
	}
	static function delete($url, $params = array()) {
		$req = new self('POST', $url, $params);
		$req->param('_method', 'DELETE');
		return $req;
	}

	public function params($params = null) {
		if(!$params)
			return $this->params;

		$this->params = $params;
		return $this;
	}

	public function param($key, $value = null) {
		if($value == null)
			return $this->params[$key];

		$this->params[$key] = $value;
		return $this;
	}

	public function header($key, $value = null) {
		if($value == null)
			return $this->client->headers[$key];

		$this->client->headers[$key] = $value;
		return $this;
	}

	public function referer($referer = null) {
		if($referer == null)
			return $this->client->referer;

		$this->client->referer = $referer;
		return $this;
	}

	public function agent($user_agent = null) {
		if($user_agent == null)
			return $this->client->user_agent;

		$this->client->user_agent = $user_agent;
		return $this;
	}

	public function cookiejar($file = null) {
		if($file == null)
			return $this->client->cookie_file;

		$this->client->cookie_file = $file;
		return $this;
	}

	public function nofollow() {
		$this->client->follow_redirects = false;
		return $this;
	}

	public function option($key, $value = null) {
		if($value == null)
			return $this->client->options[$key];

		$this->client->options[$key] = $value;
		return $this;
	}

	public function options($options = array()) {
		if(!is_array($options) || empty($options))
			return $this->client->options;

		return $this->client->options = $options;
	}

	public function send(){
		
		//Get params can have query string and separate params
		//Therefore we call the dedicated method.
		if($this->mthod == 'GET')
			return $this->client->get($this->url, $this->params);

		return $this->client->request($this->method, $this->url, $this->params);
	}
}