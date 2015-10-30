<?php
/**
 * @author Martin Kovařčík.
 */

namespace App\Utils;

use Nette\Mail\IMailer;
use \Nette\Mail\Message;
use Nette\Object;

class AppMailer extends Object
{
	/** @var  IMailer */
	private $mailer;

	/**
	 * @param IMailer $mailer
	 */
	function __construct(IMailer $mailer)
	{
		$this->mailer = $mailer;
	}

	/**
	 * @return IMailer
	 */
	private function getMailer()
	{
		return $this->mailer;
	}

	/**
	 * @param \Nette\Mail\Message $message
	 */
	public function send(Message $message)
	{
		$this->getMailer()->send($message);
	}
}
