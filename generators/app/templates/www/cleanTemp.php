<?php

function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir . "/" . $object) == "dir") {
					rrmdir($dir . "/" . $object);
				} else {
					unlink($dir . "/" . $object);
				}
			}
		}
		reset($objects);
		rmdir($dir);
	}
}

$folders = [];
if (isset($_GET['latte'])) {
	$folders = [
		'_Nette.Templating.Cache'
	];
}

$baseDir = __DIR__ . '/../temp/cache';
if (!$folders) {
	rrmdir($baseDir);
} else {
	foreach ($folders as $folder) {
		rrmdir($baseDir . '/' . $folder);
	}
}

die('What is cleaned, is cleaned');
