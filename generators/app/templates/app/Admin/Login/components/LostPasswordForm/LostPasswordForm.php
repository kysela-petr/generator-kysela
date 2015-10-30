<?php
/**
 * @author Martin Kovařčík.
 */
namespace Admin\Login;

use App\Component\Component;
use App\Utils\MailFactory;
use Model\TokenProvider;
use Model\UserProvider;
use Nette\Application\UI\Form;
use Nette\Application\UI\ITemplate;

class LostPasswordForm extends Component
{

	/** @var \App\Utils\MailFactory */
	private $mailFactory;

	/** @var Callable[] zavolá se po odeslání emailu pro odeslání hesla */
	public $onEmailSend = [];

	/** @var Callable[] zavolá se jestliže nebylo nalezeno uživatelské jméno */
	public $onRecordNotFound = [];

	/** @var \Model\TokenProvider */
	private $tokenProvider;

	/** @var \Model\UserProvider */
	private $userProvider;

	/** @var string odesílatel emailu */
	private $from;


	/**
	 * @param \Model\UserProvider $userProvider
	 * @param \App\Utils\MailFactory $mailFactory
	 * @param \Model\TokenProvider $tokenProvider
	 */
	function __construct(UserProvider $userProvider, MailFactory $mailFactory, TokenProvider $tokenProvider)
	{
		$this->mailFactory = $mailFactory;
		$this->tokenProvider = $tokenProvider;
		$this->userProvider = $userProvider;
	}

	/**
	 * @param string $from
	 */
	public function setFrom($from)
	{
		$this->from = $from;
	}

	/**
	 * Vytvori prihlasovaci formular
	 * @return \Nette\Application\UI\Form
	 */
	protected function createComponentForm()
	{
		$form = new Form;
		$form->setTranslator($this->translator);
		$form->addText('username')
			->setAttribute('placeholder', 'Login')
			->setRequired('Prosím zadejte vaše přihlašovací jméno');

		$form->addSubmit('submit', 'Odeslat');
		$form->onSuccess[] = function (Form $form) {
			$this->formSucceeded($form);
		};

		return $form;
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	private function formSucceeded(Form $form)
	{
		$values = $form->getValues();

		$record = $this->userProvider->username($values->username)->fetch();

		if (!$record) {
			$this->onRecordNotFound($this->translator->translate("Uživatel '$values->username' v systému neexistuje"));
		}

		$data = new \StdClass;
		$data->from = $this->from;
		$data->to = $record->email;
		$data->subject = $this->translator->translate('Zapomenuté heslo do adminu');

		$tokenData = $this->getTokenData($record->email);
		$data->token = $tokenData->token;
		$data->valid_to = $tokenData->valid_to;

		$this->mailFactory->sendLostPasswordEmail($data);

		$this->onEmailSend($this->translator->translate("Na Váš email byl zaslán link pro obnovu hesla"));
	}

	/**
	 * @param $email
	 * @return \Nette\Database\Table\ActiveRow
	 */
	private function getTokenData($email)
	{
		return $this->tokenProvider->generateToken($email);
	}

	/**
	 * @param ITemplate $template
	 */
	protected function prepareTemplate(ITemplate $template)
	{
		parent::prepareTemplate($template);
		$this->template->setFile(__DIR__ . '/lostpassword.latte');
	}

}

interface ILostPasswordFormFactory
{
	/** @return LostPasswordForm */
	public function create();
}
