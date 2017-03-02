<?php
class cSession
{

  private $valid = null;

	function __construct()
	{
    $this->valid = false;
  }

  private function secure_session_start()
  {
    $session_name = 'sec_session_id';   // vergib einen Sessionnamen
    $secure = false;
    $httponly = true;
    // Zwingt die Sessions nur Cookies zu benutzen.
    if (ini_set('session.use_only_cookies', '1') === FALSE)
    {
        header("Location: /index.php");
        exit();
    }
    // Holt Cookie-Parameter.
    $cookieParams = session_get_cookie_params();
	$cookieParams["lifetime"] = 1200;
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly);
    // Setzt den Session-Name zu oben angegebenem.
    session_name($session_name);
    session_start();            // Startet die PHP-Sitzung
    session_regenerate_id();    // Erneuert die Session, löscht die alte.
  }

  public function start()
  {
    $this->secure_session_start();

    $this->valid = isset($_SESSION['valid']) ? true : false;
  }

  public function destroy()
  {

    $_SESSION = array();

    // hole Session-Parameter
    $params = session_get_cookie_params();

    // Lösche das aktuelle Cookie.
    setcookie(session_name(),
            '', time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]);

    session_destroy();
    $this->valid = false;
  }

  public function setValid($role, $id)
  {
    $_SESSION['role'] = $role;
    if($role == 0)
    {
      $_SESSION['student_id'] = $id;
    }
    else
    {
      $_SESSION['current_id'] = $id;
    }
    $_SESSION['valid'] = 1;
    $this->valid = true;
  }

  public function isValid()
  {
    return $this->valid;
  }

  public function getCurrentId()
  {
    if ($_SESSION['role'] == 0)
      return $_SESSION['student_id'];
    else
      return $_SESSION['current_id'];
  }

  public function getRole()
  {
    return isset($_SESSION['role']) ? $_SESSION['role'] : false;
  }

}