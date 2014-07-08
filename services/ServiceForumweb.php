<?php

class ServiceForumweb implements ServiceInterface {
	public $title = 'ForumWeb';
	public $url = 'http://forumweb.pl/';

	public function grab($config) {
		$response = json_decode(file_get_contents('http://forumweb.pl/api/_services/get.json?_[user][_class]=users&_[user][_method]=get&_[user][user_id]='.$config['user_id'].'&_[top_10][_class]=users&_[top_10][_method]=get_top_10'), true);
		$user_response = $response['_services']['user'];
		$top_response = $response['_services']['top_10'];

		// Get position in top 10
		$top_position = false;
		$i = 0;
		foreach ($top_response['users'] as $top_user) {
			++$i;
			if ($top_user['user_id'] == $config['user_id']) {
				$top_position = $i;
				break;
			}
		}

		return [
			'help_points' => (int)$user_response['users'][0]['user_helps'],
			'posts' => (int)$user_response['users'][0]['user_posts'],
			'top_position' => $top_position
		];
	}

	public function template($data) {
		return [
			'Postów' => number_format($data['posts'], 0, '.', ' '),
			'Pozycja w rankingu użytkowników' => $data['top_position'] ? $data['top_position'] : 'Poza top10 :(',
			'Punktów pomocy' => number_format($data['help_points'], 0, '.', ' ')
		];
	}
}
