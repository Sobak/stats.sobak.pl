<?php

/**
 * stats.sobak.pl utilizes simplified version of TwitterOAuth
 * class created by Ricardo Pereira <github@ricardopereira.es>
 *
 * TwitterOAuth - https://github.com/ricardoper/TwitterOAuth
 * PHP library to communicate with Twitter OAuth API version 1.1
 *
 * @author Ricardo Pereira <github@ricardopereira.es>
 * @copyright 2013
 */

class TwitterOAuth
{
	protected $url = 'https://api.twitter.com/1.1/';
	protected $config = array();
	protected $call = '';
	protected $getParams = array();

	public function __construct(array $config) {
		$this->config = $config;
	}

	public function get($call, array $getParams = null) {
		$this->call = $call;

		if ($getParams !== null && is_array($getParams)) {
			$this->getParams = $getParams;
		}

		return $this->sendRequest();
	}

	protected function getParams(array $params) {
		$r = '';

		ksort($params);

		foreach ($params as $key => $value) {
			$r .= '&' . $key . '=' . rawurlencode($value);
		}

		return trim($r, '&');
	}

	protected function getUrl($withParams = false) {
		$getParams = '';

		if ($withParams === true) {
			$getParams = $this->getParams($this->getParams);

			if (!empty($getParams)) {
				$getParams = '?' . $getParams;
			}
		}

		return $this->url . $this->call . '.json' . $getParams;
	}

	protected function getOauthString() {
		// Array of oauth parameters
		$time = time();

		$oauthParams = array(
			'oauth_consumer_key' => $this->config['consumer_key'],
			'oauth_nonce' => trim(base64_encode($time), '='),
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_timestamp' => $time,
			'oauth_token' => $this->config['oauth_token'],
			'oauth_version' => '1.0'
		);

		// Create signature base string
		$url = rawurlencode($this->getUrl());
		$requestString = rawurlencode($this->getParams(array_merge($this->getParams, $oauthParams)));
		$signatureBaseString = 'GET&' . $url . '&' . $requestString;

		$signingKey = $this->config['consumer_secret'] . '&' . $this->config['oauth_token_secret'];
		$signature = base64_encode(hash_hmac('sha1', $signatureBaseString, $signingKey, true));
		$oauth = array_merge($oauthParams, array('oauth_signature' => $signature));

		ksort($oauth);

		$values = array();

		foreach ($oauth as $key => $value) {
			$values[] = $key . '="' . rawurlencode($value) . '"';
		}

		return implode(', ', $values);
	}

	protected function sendRequest() {
		$url = $this->getUrl(true);

		$header = array(
			'Authorization: OAuth ' . $this->getOauthString(),
			'Expect:'
		);

		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_HEADER => false,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
		);

		$c = curl_init();
		curl_setopt_array($c, $options);
		$response = curl_exec($c);
		curl_close($c);

		return $response;
	}
}
