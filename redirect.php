<?php

require_once 'system/controller/session.php';

$session = new cSession;

$session->start();

if(!$session->isValid())
{
	header('Location: index.php');
}
else
{
	require_once 'system/controller/controller.php';

	$controller = new cController($_GET, $_POST);

	$controller->joinSession($session);

	$controller->redirectSession();

	echo $controller->display();
}
