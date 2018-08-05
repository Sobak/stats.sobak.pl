<?php

class ServiceWikipedia implements ServiceInterface {
	public $title = 'Wikipedia';

	public function __construct($config) {
		$this->url = 'https://pl.wikipedia.org/wiki/Wikipedysta:' . $config['username'];
	}

	public function grab($config) {
		$user_response = json_decode(file_get_contents('https://pl.wikipedia.org/w/api.php?action=query&list=users&usprop=editcount&format=json&ususers='.$config['username']), true);

		return [
			'editions' => $user_response['query']['users'][0]['editcount'],
		];
	}

	public function template($data) {
		return [
			'Edycji' => $data['editions']
		];
	}
}
