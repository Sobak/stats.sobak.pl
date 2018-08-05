<?php

class ServicePhp implements ServiceInterface {
	public $title = 'PHP.net';

	public function __construct($config) {
		$this->url = 'http://people.php.net/' . $config['username'];
	}

	public function grab($config) {
		$assigned_response = simplexml_load_file('https://bugs.php.net/rss/search.php?cmd=display&assign='.$config['username']);
		$closed_response = simplexml_load_file('https://bugs.php.net/rss/search.php?cmd=display&status=Closed&assign='.$config['username']);

		return [
			'assigned' => count($assigned_response->item),
			'closed' => count($closed_response->item)
		];
	}

	public function template($data) {
		return [
			'Przypisanych błędów' => $data['assigned'],
			'Zamkniętych błędów' => $data['closed']
		];
	}
}
