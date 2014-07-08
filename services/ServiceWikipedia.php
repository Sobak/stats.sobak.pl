<?php

class ServiceWikipedia implements ServiceInterface {
	public $title = 'Wikipedia';
	public $url = 'http://pl.wikipedia.org/';

	public function grab($config) {
		$response = file_get_contents('https://pl.wikipedia.org/w/index.php?title=Specjalna%3AZarz%C4%85dzanie+kontem+uniwersalnym&target='.$config['username']);
		preg_match('#<li><strong>Wszystkich edycji</strong> (.\d)</li>#', $response, $matches);

		return [
			'editions' => (int)$matches[1],
		];
	}

	public function template($data) {
		return [
			'Edycji' => $data['editions']
		];
	}
}
