<?php
class cSendMail
{
	private $url = null;

	function __construct()
	{
		$this->url = "http://alpha-voting.bplaced.net/";
	}

	public function sendToAssistent($assistent)
	{
		$subject = "Assignment im Projektpraktikum Automatisierung";
		$message = "Dies ist eine Automatische E-Mail des Votingsystems im Projektpraktikum Automatisierungstechnik.\nIm Anhang finden Sie die aktuellen zuordnungen.\nBitte antworten Sie nicht auf diese E-Mail.";
		$sender = "Projektpraktikum Automatisierung - Voting";
		$sender_email = "noreply@vs.de";
		$dateien = "tmp/Assignment.xls";

		return $this->mail_att($assistent, $subject, $message, $sender, $sender_email, $sender_email, $dateien);
		
		// the message
		//$msg = "First line of text\nSecond line of text";

		// use wordwrap() if lines are longer than 70 characters
		//$msg = wordwrap($msg,70);

		// send email
		//mail("daniel.thomanek@campus.tu-berlin.de","My subject",$msg);

		//$to = "somebody@example.com";
		//$subject = "My subject";
		//$txt = "Hello world!";
		//$headers = "From: webmaster@example.com";

		//mail($assistent,$subject,$txt,$headers);

	}

	public function sendToStudent($student,$crypt)
	{
		$subject = "Anmeldung im Projektpraktikum Automatisierung";
		$message = "Dies ist eine Automatische E-Mail des Votingsystems im Projektpraktikum Automatisierungstechnik\nBitte melden sie sich mit folgendem Link an:\n\n".$this->url."index.php?token=".$crypt."\n\nDer Link ist mehrfach nutzbar.\nAm Einführungstag gelangen Sie mit diesem Link zu der Abstimmseite.\n\nBitte antworten Sie nicht auf diese E-Mail.";
		$sender = "Projektpraktikum Automatisierung - Voting";
		$headers = "From: noreply@vs.de";
		
		return mail($student,$subject,$message,$headers);
		
		//return $this->mail_att($assistent, $subject, $message, $sender, $sender_email, $sender_email, null);
	}

	private function mail_att($to, $subject, $message, $sender, $sender_email, $reply_email, $dateien)
	{
	   if(!is_array($dateien)) {
	      $dateien = array($dateien);
	   }

	   $attachments = array();
	   foreach($dateien AS $key => $val) {
	      if(is_int($key)) {
	        $datei = $val;
	        $name = basename($datei);
	     } else {
	        $datei = $key;
	        $name = basename($val);
	     }

	      $size = filesize($datei);
	      $data = file_get_contents($datei);
	      $type = mime_content_type($datei);

	      $attachments[] = array("name"=>$name, "size"=>$size, "type"=>$type, "data"=>$data);
	   }

	   $mime_boundary = "-----=" . md5(uniqid(microtime(), true));

	   $header  ="From:".$sender."<".$sender_email.">\n";
	   $header .= "Reply-To: ".$reply_email."\n";

	   $header.= "MIME-Version: 1.0\r\n";
	   $header.= "Content-Type: multipart/mixed;\r\n";
	   $header.= " boundary=\"".$mime_boundary."\"\r\n";

	   $encoding = mb_detect_encoding($message, "utf-8, iso-8859-1, cp-1252");
	   $content = "This is a multi-part message in MIME format.\r\n\r\n";
	   $content.= "--".$mime_boundary."\r\n";
	   $content.= "Content-Type: text/html charset=\"$encoding\"\r\n";
	   $content.= "Content-Transfer-Encoding: 8bit\r\n\r\n";
	   $content.= $message."\r\n";

	   //$anhang ist ein Mehrdimensionals Array
	   //$anhang enthält mehrere Dateien
	   foreach($attachments AS $dat) {
	         $data = chunk_split(base64_encode($dat['data']));
	         $content.= "--".$mime_boundary."\r\n";
	         $content.= "Content-Disposition: attachment;\r\n";
	         $content.= "\tfilename=\"".$dat['name']."\";\r\n";
	         $content.= "Content-Length: .".$dat['size'].";\r\n";
	         $content.= "Content-Type: ".$dat['type']."; name=\"".$dat['name']."\"\r\n";
	         $content.= "Content-Transfer-Encoding: base64\r\n\r\n";
	         $content.= $data."\r\n";
	   }
	   $content .= "--".$mime_boundary."--";

	   return mail($to, $subject, $content, $header);
	}

}