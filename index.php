<!doctype html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>Sobak - kilka nudnych statystyk</title>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato&amp;subset=latin-ext">
</head>
<body>

<h1>Sobak <span>- kilka nudnych statystyk</span></h1>

<?php
require 'services/ServiceInterface.php';

if (!file_exists('database.json') || filesize('database.json') < 10) {
	echo '<h2>Ups, baza sobie poszła...</h2>';
	die;
}

$database = json_decode(file_get_contents('database.json'), true);

foreach ($database as $service => $data) {
	require 'services/Service'.ucfirst($service).'.php';

	$class = 'Service'.ucfirst($service);
	$object = new $class;
	$template = $object->template($data);

	echo '<section id="'.$service.'">';
	echo '<h2>'.$object->title.'</h2>';
	echo '<table>';

	foreach ($template as $name => $value) {
		echo '<tr>';
		echo '<th scope="row">'.$name.'</th>';
		echo '<td>'.$value.'</td>';
		echo '</tr>';
	}

	echo '</table>';
	echo '</section>';
}
?>

<footer>
	<p>Ostatnia aktualizacja: <?=date('d.m.Y H:i', filemtime('database.json'))?> || Copyright by <a href="http://sobak.pl">Sobak</a> || Kod dostępny na <a href="https://github.com/Sobak/stats.sobak.pl">GitHub</a></p>
</footer>

</body>
</html>