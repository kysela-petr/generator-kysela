<?php
/**
 * @author Martin Kovařčík.
 */
namespace Admin\Login;

use App\Component\Component;
use Model\TokenProvider;
use Model\UserProvider;
use Nette\Application\UI\Form;
use Nette\Application\UI\ITemplate;

class RenewPasswordForm extends Component
{

	/** @var Callable[] zavolá se jestliže nebyl nalezen uživatel s emailem */
	public $onRecordNotFound = [];

	/** @var Callable[] zavolá se po změně hesla */
	public $onPasswordChanged = [];

	/** @var \Model\TokenProvider */
	private $tokenProvider;

	/** @var \Model\UserProvider */
	private $userProvider;

	/** @var string */
	private $email;

	/** @var null */
	private $token;

	/**
	 * @param string $email
	 * @param string $token
	 * @param \Model\UserProvider $userProvider
	 * @param \Model\TokenProvider $tokenProvider
	 */
	function __construct($email, $token, UserProvider $userProvider, TokenProvider $tokenProvider)
	{
		$this->tokenProvider = $tokenProvider;
		$this->userProvider = $userProvider;
		$this->email = $email;
		$this->token = $token;
	}

	/**
	 * Vytvori prihlasovaci formular
	 * @return \Nette\Application\UI\Form
	 */
	protected function createComponentForm()
	{
		$form = new Form;
		$form->addPassword('password', 'Heslo')
			->addRule(Form::MIN_LENGTH, 'Zadané heslo je příliš krátké zadejte heslo alespoň o %d znacích.', 8)
			->addRule(Form::FILLED, 'Zadejte prosím heslo.');
		$form->addPassword('passwordVerify', 'Heslo pro kontrolu')
			->addConditionOn($form['password'], Form::FILLED)
			->addRule(Form::FILLED, 'Zadejte prosím heslo ještě jednou pro kontrolu.')
			->addRule(Form::EQUAL, 'Hesla sa neshodují.', $form['password']);

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

		$row = $this->userProvider->email($this->email)->limit(1)->fetch();

		if ($row === FALSE) {
			$this->onRecordNotFound($this->translator->translate('Email uživatele není platný.'));
		}

		$this->userProvider->changePassword($row->id, $values->password);
		$this->tokenProvider->deleteToken($this->token);
		$this->onPasswordChanged($this->translator->translate('Heslo bylo změněno'));
	}

	/**
	 * @param ITemplate $template
	 */
	protected function prepareTemplate(ITemplate $template)
	{
		parent::prepareTemplate($template);
		$this->template->setFile(__DIR__ . '/renewpassword.latte');
	}

}

interface IRenewPasswordFormFactory
{
	/**
	 * @param $email
	 * @param $token
	 * @return \Admin\Login\RenewPasswordForm
	 */
	public function create($email,$token);
}
