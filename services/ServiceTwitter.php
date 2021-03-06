<?php

class ServiceTwitter implements ServiceInterface {
	public $title = 'Twitter';

	public function __construct($config) {
		$this->url = 'https://twitter.com/' . $config['username'];
	}

	public function grab($config) {
		require 'includes/TwitterOAuth.php';

		$oauthConfig = array(
			'consumer_key' => $config['oauth']['customer_key'],
			'consumer_secret' => $config['oauth']['customer_secret'],
			'oauth_token' => $config['oauth']['oauth_access_token'],
			'oauth_token_secret' => $config['oauth']['oauth_access_token_secret']
		);

		$twitter = new TwitterOAuth($oauthConfig);
		$response = json_decode($twitter->get('users/show', ['screen_name' => $config['username']]), true);

		return [
			'tweets' => (int)$response['statuses_count'],
			'followers' => (int)$response['followers_count'],
			'following' => (int)$response['friends_count'],
		];
	}

	public function template($data) {
		return [
			'Tweetów' => $data['tweets'],
			'Obserwujących' => $data['followers'],
			'Obserwowanych' => $data['following']
		];
	}
}
