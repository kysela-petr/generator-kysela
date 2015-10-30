<?php
/**
 * @author Martin Kovařčík.
 */

namespace App\Utils;

use Esports\Utils\PresenterBridge;
use Nette\Application\UI\ITemplateFactory;
use Nette\Mail\Message;
use Nette\Object;

class MailFactory extends Object
{

	/** @var \Esports\Utils\PresenterBridge */
	public $presenterBridge;

	/** @var \Nette\Application\UI\ITemplateFactory */
	private $templateFactory;

	/** @var \App\Utils\AppMailer */
	private $appMailer;

	/**
	 * @param \Esports\Utils\PresenterBridge $presenterBridge
	 * @param \Nette\Application\UI\ITemplateFactory $templateFactory
	 * @param \App\Utils\AppMailer $appMailer
	 */
	public function __construct(PresenterBridge $presenterBridge, ITemplateFactory $templateFactory, AppMailer $appMailer)
	{
		$this->presenterBridge = $presenterBridge;
		$this->templateFactory = $templateFactory;
		$this->appMailer = $appMailer;
	}

	/**
	 * Vraci presenter pro moznost generovani linku v templatach emailu
	 * @return \Nette\Application\UI\Presenter
	 */
	private function getPresenter()
	{
		return $this->presenterBridge->getPresenter();
	}

	/**
	 * Nastavi templatu emailu
	 * @param string $templateFile
	 * @return \Nette\Application\UI\ITemplate
	 */
	private function setTemplate($templateFile)
	{
		$template = $this->templateFactory->createTemplate();
		$template->setFile(__DIR__ . '/templates/' . $templateFile);
		$template->_presenter = $this->getPresenter();

		return $template;
	}

	/**
	 * Odesila email pres PHP nastaveni
	 * @param string $from
	 * @param string | array $to
	 * @param string $body
	 * @param string $subject
	 */
	private function sendMail($from, $to, $body, $subject)
	{
		$mail = new Message;
		$mail->setFrom($from)
			->setSubject($subject)
			->setHtmlBody($body);

		if (is_array($to)) {
			foreach ($to as $toItem) {
				$mail->addTo($toItem);
			}
		} else {
			$mail->addTo($to);
		}

		$this->appMailer->send($mail);
	}

	/**
	 * @param $data
	 */
	public function sendContactEmail($data)
	{
		$template = $this->setTemplate('contact.latte');
		$template->data = $data;
		$this->sendMail($data->email, explode(';', $data->emailTo), $template, $data->subject);
	}

	/**
	 * @param $data
	 */
	public function sendLostPasswordEmail($data)
	{
		$template = $this->setTemplate('lostpassword.latte');
		$template->data = $data;
		$this->sendMail($data->from, $data->to, $template, $data->subject);
	}

}
