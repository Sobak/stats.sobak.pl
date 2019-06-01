<?php

class ServiceSobak implements ServiceInterface {
	public $title = 'Sobakowy Blog';
	public $url = 'http://sobak.pl/';

	public function grab($config) {
		$response = json_decode(file_get_contents("http://sobak.pl/api/stats"), true);

		// Get number of posts per day
		$start_date = new DateTime($config['start_date']);
		$today = new DateTime();

		$posts = $response['total_posts'];
		$blog_age = $today->diff($start_date)->days;

		$posts_per_day = $posts / $blog_age;

		return [
			'posts' => (int)$posts,
			'posts_per_day' => (float)$posts_per_day,
			'words' => (int)$response['total_words'],
		];
	}

	public function template($data) {
		return [
			'Wpisów' => $data['posts'],
			'Wpisów dziennie' => round($data['posts_per_day'], 2),
			'Łącznie słów' => number_format($data['words'], 0, '.', ' ')
		];
	}
}
