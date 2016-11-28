<?php

session_start();

if(!isset($_SESSION['valid']))
{
	header('Location: index.php');
}
else
{
	require_once 'system/controller/controller.php';

	$controller = new cController($_GET, $_POST);

	$controller->redirectSession();

	echo $controller->display();
}
