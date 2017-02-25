<?php
class cErrorHandle
{

  private $err_msg = null;
  private $err_page = null;
  private $err_subpage = null;
  private $err_page_id = null;

  private $store_name = null;
  private $store_email = null;
  private $store_matr = null;
  private $store_title = null;
  private $store_keywords = null;
  private $store_abstract = null;
  private $store_description = null;
  private $store_field = null;

  function __construct()
  {
    $this->err_msg = false;
    $this->err_page = false;
    $this->err_subpage = false;
    $this->err_page_id = false;

    $this->store_name = false;
    $this->store_email = false;
    $this->store_title = false;
    $this->store_matr = false;
    $this->store_title = false;
    $this->store_keywords = false;
    $this->store_abstract = false;
    $this->store_description = false;
    $this->store_field = false;
  }

  // error page
  public function getPage()
  {
    return $this->err_page;
  }

  public function resetPage($page, $subpage, $page_id)
  {
    $this->err_page = $page;
    $this->err_subpage = $subpage;
    $this->err_page_id = $page_id;
  }

  public function getSubpage()
  {
    return $this->err_subpage;
  }

  public function getPageId()
  {
    return $this->err_page_id;
  }

// stored content
  public function getErrMsg()
  {
    return $this->err_msg;
  }

  public function getStoredName()
  {
    if(!$this->store_name) return false;
    return "value='".$this->store_name."'";
  }

  public function getStoredEmail()
  {
    if(!$this->store_email) return false;
    return "value='".$this->store_email."'";
  }

  public function getStoredMatr()
  {
    if(!$this->store_email) return false;
    return "value='".$this->store_matr."'";
  }

  public function getStoredTitle()
  {
    if(!$this->store_title) return false;
    return "value='".$this->store_title."'";
  }

  public function getStoredKeywords()
  {
    if(!$this->store_keywords) return false;
    return "value='".$this->store_keywords."'";
  }

  public function getStoredField()
  {
    if(!$this->store_field) return false;
    return "value='".$this->store_field."'";
  }

  public function getStoredAbstract()
  {
    if(!$this->store_abstract) return false;
    return $this->store_abstract;
  }

  public function getStoredDescription()
  {
    if(!$this->store_abstract) return false;
    return $this->store_description;
  }

  public function errLoginFail()
  {
    $this->err_msg = "Error: Die E-Mail Adresse oder das Passwort sind falsch <br>";
    return true;
  }

  public function errBadId($form)
  {
    $this->err_msg = "Error: Die ID dieser Transaktion entspricht nicht der Erwartung <br>";
    $this->err_page = $form;
    $this->err_subpage = "none";
    $this->err_page_id = 0;
  }

  public function errFormNewTeacher($name,$email,$pw,$email_exists)
  {
    $this->err_page = "teacher";
    $this->err_subpage = "new";
    $msg = "Error: ";

    if($name !== false)
      $this->store_name = $name;
    else
      $msg = "".$msg."Der eingegebene Name entspricht nicht dem Erwarteten Format <br>";

    if($email !== false)
      $this->store_email = $email;
    else
      $msg = "".$msg."Die eingegebene E-Mail Adresse entspricht nicht dem erwarteten Format <br>";

    if($pw === false)
      $msg = "".$msg."Das eingegebene Passwort entspricht nicht dem erwarteten Format <br>";

    if($email_exists)
        $msg = "".$msg."Die eingebene Email existiert bereits <br>";

    $this->err_msg = $msg;
  }

  public function errFormEditTeacher($id,$name,$email,$email_exists)
  {

    if ($id === false)
    {
      $this->errBadId("teacher");
      return;
    }
    else
    {
      $this->err_page_id = $id;
    }

    $this->err_page = "teacher";
    $this->err_subpage = "edit";
    $msg = "Error: ";

    // INFO no storing here. Always use the current data!
    if($name === false)
      $msg = "".$msg."Der eingegebene Name entspricht nicht dem Erwarteten Format <br>";

    if($email === false)
      $msg = "".$msg."Die eingegebene E-Mail Adresse entspricht nicht dem erwarteten Format <br>";

    if($email_exists)
      $msg = "".$msg."Die eingebene Email existiert bereits <br>";

    $this->err_msg = $msg;
  }

  public function errFormEditTeacherPw($id,$not_equal)
  {
    if ($id === false)
    {
      $this->errBadId("teacher");
      return;
    }
    else
    {
      $this->err_page_id = $id;
    }

    $this->err_page = "teacher";
    $msg = "Error: ";
    $this->err_subpage = "edit";


    if($not_equal)
      $msg = "".$msg."Die Passwörter stimmen nich überein <br>";
    else
      $msg = "".$msg."Das Passwort entspricht nicht dem erwarteten Format <br>";

    $this->err_msg = $msg;
  }

  public function errFormNewProject($teacher_id,$title,$keywords,$abstract,$description,$degree,$skills)
  {
    // TODO emergency logout
    if ($teacher_id === false)
    {
      $this->errBadId("project");
      return;
    }

    $this->err_page = "project";
    $msg = "Error: ";
    $this->err_subpage = "new";

    if($title !== false)
      $this->store_title = $title;
    else
      $msg = "".$msg."Der Titel entspricht nicht dem erwarteten Format <br>";

    if($keywords !== false)
      $this->store_keywords = $keywords;
    else
      $msg = "".$msg."Der Schlagwörter entsprichen nicht dem erwarteten Format <br>";

    if($abstract !== false)
      $this->store_abstract = $abstract;
    else
      $msg = "".$msg."Das Abstrakt entspricht nicht dem erwarteten Format <br>";

    if($description !== false)
      $this->store_description = $description;
    else
      $msg = "".$msg."Die Beschreibung entspricht nicht dem erwarteten Format <br>";

    // INFO do not store content of checkboxes!
    if($degree === false)
      $msg = "".$msg."Der angestrebte Abschluss entspricht nicht dem erwarteten Format <br>";

    if($skills === false)
      $msg = "".$msg."Die Fähigkeiten entsprechen nicht dem erwarteten Format <br>";

      $this->err_msg = $msg;
  }

  // TODO merge this with function before
  public function errFormEditProject($project_id,$title,$keywords,$abstract,$description,$degree,$skills)
  {
    // TODO storing not necessary here!
    // TODO emergency logout
    if ($project_id === false)
    {
      $this->errBadId("project");
      return;
    }

    $this->err_page = "project";
    $msg = "Error: ";
    $this->err_subpage = "edit";
    $this->err_page_id = $project_id;

    if($title !== false)
      $this->store_title = $title;
    else
      $msg = "".$msg."Der Titel entspricht nicht dem erwarteten Format <br>";

    if($keywords !== false)
      $this->store_keywords = $keywords;
    else
      $msg = "".$msg."Der Schlagwörter entsprichen nicht dem erwarteten Format <br>";

    if($abstract !== false)
      $this->store_abstract = $abstract;
    else
      $msg = "".$msg."Das Abstrakt entspricht nicht dem erwarteten Format <br>";

    if($description !== false)
      $this->store_description = $description;
    else
      $msg = "".$msg."Die Beschreibung entspricht nicht dem erwarteten Format <br>";

    // INFO do not store content of checkboxes!
    if($degree === false)
      $msg = "".$msg."Der angestrebte Abschluss entspricht nicht dem erwarteten Format <br>";

    if($skills === false)
      $msg = "".$msg."Die Fähigkeiten entsprechen nicht dem erwarteten Format <br>";

      $this->err_msg = $msg;
  }

  public function errFormNewStudent($name,$matr,$email,$field,$grade,$skills,$email_exists)
  {

    $this->err_page = "student";
    $this->err_subpage = "new";
    $msg = "Error: ";

    if($name !== false)
      $this->store_name = $name;
    else
      $msg = "".$msg."Der Name entspricht nicht dem erwarteten Format <br>";

    if($matr !== false)
      $this->store_matr = $matr;
    else
      $msg = "".$msg."Die Matrikelnummer entspricht nicht dem erwarteten Format <br>";

    if($email !== false)
      $this->store_email = $email;
    else
      $msg = "".$msg."Die E-mail Adresse entspricht nicht dem erwarteten Format <br>";

    if($field !== false)
      $this->store_field = $field;
    else
      $msg = "".$msg."Das Studiengebiet entspricht nicht dem erwarteten Format <br>";

    // TODO find a way to store this
    if($grade === false)
      $msg = "".$msg."Der angestrebte Abschluss entspricht nicht dem erwarteten Format <br>";

    if($skills === false)
      $msg = "".$msg."Die Fähigkeiten entsprechen nicht dem erwarteten Format <br>";

    if($email_exists)
      $msg = "".$msg."Die E-Mail Adresse existiert bereits <br>";

    $this->err_msg = $msg;
  }

  // TODO merge this with function before
  public function errFormEditStudent($student_id,$name,$matr,$email,$field,$grade,$skills,$email_exists)
  {
    if ($student_id === false)
    {
      $this->errBadId("project");
      return;
    }

    $this->err_page = "student";
    $this->err_subpage = "edit";
    $this->err_page_id = $student_id;
    $msg = "Error: ";

    if($name !== false)
      $this->store_name = $name;
    else
      $msg = "".$msg."Der Name entspricht nicht dem erwarteten Format <br>";

    if($matr !== false)
      $this->store_matr = $matr;
    else
      $msg = "".$msg."Die Matrikelnummer entspricht nicht dem erwarteten Format <br>";

    if($email !== false)
      $this->store_email = $email;
    else
      $msg = "".$msg."Die E-mail Adresse entspricht nicht dem erwarteten Format <br>";

    if($field !== false)
      $this->store_field = $field;
    else
      $msg = "".$msg."Das Studiengebiet entspricht nicht dem erwarteten Format <br>";

    // TODO find a way to store this
    if($grade === false)
      $msg = "".$msg."Der angestrebte Abschluss entspricht nicht dem erwarteten Format <br>";

    if($skills === false)
      $msg = "".$msg."Die Fähigkeiten entsprechen nicht dem erwarteten Format <br>";

    if($email_exists)
      $msg = "".$msg."Die E-Mail Adresse existiert bereits <br>";

    $this->err_msg = $msg;
  }

  public function errBadSetting($setting)
  {
    $this->err_page = "settings";
    $msg = "Error: ";

    switch($setting)
    {
      case "email":
        $msg = "".$msg."Die E-Mail Adresse entspricht nicht dem erwarteten Format <br>";
        break;

      case "deadline":
        $msg = "".$msg."Die Deadline entspricht nicht dem erwarteten Format: dd.mm.yyyy <br>";
        break;

      case "skills":
        $msg = "".$msg."Die Fähigkeit entspricht nicht dem erwarteten Format<br>";
        break;

      case "nwishes":
        $msg = "".$msg."Die Anzahl der Wünsche entspricht nicht dem erwarteten Format<br>";
        break;
    }


    $this->err_msg = $msg;
  }


}