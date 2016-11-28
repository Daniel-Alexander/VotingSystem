<?php

/**
* Test Admin: admin, pw: 1234
* Test Teacher: mail, pw: SicheresPw
*/

require_once 'system/controller/controller.php';

$controller = new cController($_GET, $_POST);

$controller->redirectOutside();

echo $controller->display();