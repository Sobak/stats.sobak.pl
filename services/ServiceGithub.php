<?php

class ServiceGithub implements ServiceInterface {
	public $title = 'GitHub';
	public $url = 'https://github.com/';

	public function grab($config) {
		$auth = base64_encode($config['username'] . ":" . $config['password']);

		$options = [
			'http' => [
				'method' => 'GET',
				'header' => "Authorization: Basic $auth\r\n" .
				            "User-Agent: stats.sobak.pl\r\n"
			],
		];

		$context = stream_context_create($options);

		$user_response = json_decode(file_get_contents('https://api.github.com/user', false, $context), true);

		return [
			'repos' => $user_response['public_repos'] + $user_response['owned_private_repos'],
			'followers' => $user_response['followers'],
			'following' => $user_response['following'],
		];
	}

	public function template($data) {
		return [
			'Repozytoriów' => $data['repos'],
			'Obserwujących' => $data['followers'],
			'Obserwowanych' => $data['following'],
		];
	}
}
