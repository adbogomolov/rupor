<?php
/**
 * Отправка почты
 *
 * @author irina
 */
class BRMail {
	
	private $to;
	private $subject;
	private $message;
	private $headers;
	
	/**
	 * Конструктор 
	 * @param string $to
	 * @param string $subject
	 * @param string $message
	 */
	public function __construct($to, $subject, $message) {
		
		$this->to = $to;
        $this->headers  = "MIME-Version: 1.0\r\n";
        $this->headers .= "Content-type: text/plain; charset=utf-8\r\n";
		$this->subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
		$this->message = $message;		
	}
	
	/**
	 * Отправка почты
	 * @return boolean
	 */
	public function send () {
		
		if (mail($this->to, $this->subject, $this->message, $this->headers)) {
			return true;
		} 
		return false;
	}
}

?>
