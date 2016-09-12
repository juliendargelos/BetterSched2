<?php
	session_start();
	require_once 'src/autoload.php';

	Mvc\Route::init();
	Mvc\Controller::init();
	Mvc\View::init();
?>
