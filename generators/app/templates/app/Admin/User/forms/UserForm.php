<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin\User;

use Admin\BaseForm;
use Admin\RoleFormItemFactory;
use Admin\SectionFormItemFactory;
use Esports\Helper\PasswordHasher;
use Model\RecordNotFoundException;
use Model\RoleFacade;
use Model\UserFacade;
use Model\UserFilter;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;

class UserForm extends BaseForm
{

	/** @var int */
	private $id;

	/** @var \Model\UserFacade */
	private $userFacade;

	/** @var \Model\UserFilter */
	private $userFilter;

	/** @var \Esports\Helper\PasswordHasher */
	private $passwordHasher;

	/** @var \Admin\RoleFormItemFactory */
	private $roleFormItemFactory;

	/** @var \Admin\SectionFormItemFactory */
	private $sectionFormItemFactory;

	/** @var \Model\RoleFacade */
	private $roleFacade;

	/**
	 * @param \Model\UserFacade $userFacade
	 * @param \Model\UserFilter $userFilter
	 * @param \Esports\Helper\PasswordHasher $passwordHasher
	 * @param \Admin\RoleFormItemFactory $roleFormItemFactory
	 * @param \Admin\SectionFormItemFactory $sectionFormItemFactory
	 * @param \Model\RoleFacade $roleFacade
	 */
	function __construct(UserFacade $userFacade, UserFilter $userFilter, PasswordHasher $passwordHasher, RoleFormItemFactory $roleFormItemFactory, SectionFormItemFactory $sectionFormItemFactory, RoleFacade $roleFacade)
	{
		parent::__construct();

		$this->userFacade = $userFacade;
		$this->userFilter = $userFilter;
		$this->passwordHasher = $passwordHasher;
		$this->roleFormItemFactory = $roleFormItemFactory;
		$this->sectionFormItemFactory = $sectionFormItemFactory;
		$this->roleFacade = $roleFacade;
	}

	/**
	 * @return \Nette\Application\UI\Form
	 */
	protected function createForm()
	{
		$form = new Form;
		$base = $form->addContainer('base');
		$password = $form->addContainer('password');
		$elections = $form->addContainer('elections');

		$base->addText('username', 'Uživatelské jméno')
			->addRule(Form::FILLED, "Vyplňte prosím uživatelské jméno");
		$base->addTextNull('name', 'Jméno')
			->addRule(Form::FILLED, "Vyplňte prosím jméno");
		$base->addTextNull('surname', 'Příjmení')
			->addRule(Form::FILLED, "Vyplňte prosím příjmení");

		$base->addText('email', 'Email')
			->setRequired()
			->addRule(Form::EMAIL, "Zadejte prosím validní emailovou adresu.");

		$password->addTextNull('password', 'Heslo')
			->setType('password')
			->setAttribute('autocomplete', 'off')
			->addCondition(Form::FILLED)
			->addRule(Form::MIN_LENGTH, 'Zadané heslo je příliš krátké, zvolte si heslo alespoň o %d znacích', 8);

		$password->addPassword('passwordCheck', 'Heslo pro kontrolu')
			->setOmitted()
			->setAttribute('autocomplete', 'off')
			->addConditionOn($password['password'], Form::FILLED)
			->addRule(Form::EQUAL, 'Heslo pro kontrolu se neshoduje', $password['password']);

		$elections['role'] = $this->roleFormItemFactory->create();
		$elections['role']
			->addRule(Form::FILLED, "Přiřaďte prosím uživateli roli");

		$elections['section'] = $this->sectionFormItemFactory->createMultiselectBox();

		$form->addSubmit('save', 'Uložit');
		$form->onValidate[] = function (Form $form) {
			$this->checkUsername($form);
		};
		$form->onValidate[] = function (Form $form) {
			$this->checkNewUserPassword($form);
		};
		$form->onValidate[] = function (Form $form) {
			$this->checkSectionBindins($form);
		};

		return $form;
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	public function add(Form $form)
	{
		$user = $this->userFacade->add($form['base']->getValues(TRUE));
		$this->id = $user->id;

		$this->reassignData($form);
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	public function edit(Form $form)
	{
		$this->userFacade->edit($this->id, $form['base']->getValues(TRUE));

		$this->reassignData($form);
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	public function reassignData(Form $form)
	{
		$this->userFacade->setRole($this->id, $form['elections']['role']->getValue());
		$this->userFacade->setSection($this->id, $form['elections']['section']->getValue());

		$password = $form['password']['password']->getValue();
		if ($password) {
			$this->userFacade->edit($this->id, ['password' => $this->passwordHasher->hash($password)]);
		}
	}

	/**
	 * @param int $id
	 * @throws \Model\RecordNotFoundException
	 */
	public function load($id)
	{
		$user = $this->getUserData($this->getUserData($id))->fetch();

		if (!$user) {
			throw new RecordNotFoundException;
		}

		$this->id = $id;

		$this['form']['base']->setDefaults($user->toArray());
		$this['form']['elections']['role']->setDefaultValue($this->getRole($this->id)->fetchPairs('code', 'code'));
		$this['form']['elections']['section']->setDefaultValue($this->getSection($this->id)->fetchPairs('id', 'id'));
	}

	/**
	 * @param $id
	 * @return \Nette\Database\Table\Selection
	 */
	private function getUserData($id)
	{
		return $this->userFilter->filterId($this->userFacade->all(), $id);
	}

	/**
	 * @param $id
	 * @return \Nette\Database\Table\Selection
	 */
	private function getRole($id)
	{
		$selection = $this->userFacade->all();
		$selection->select(':user_has_role.role.code');
		$selection->where(':user_has_role.role.code IS NOT NULL');

		return $this->userFilter->filterId($selection, $id);
	}

	/**
	 * @param $id
	 * @return \Nette\Database\Table\Selection
	 */
	private function getSection($id)
	{
		$selection = $this->userFacade->all();
		$selection->select(':user_has_section.section.id, :user_has_section.section.name');
		$selection->where(':user_has_section.section.id IS NOT NULL');

		return $this->userFilter->filterId($selection, $id);
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	private function checkUsername(Form $form)
	{
		$username = $form['base']['username']->getValue();

		$user = $this->userFilter
			->filterUsername($this->userFacade->all(), $username)
			->fetch();

		if (!$user) {
			return;
		}

		if (!($user->id == $this->id)) {
			$form->addError('Zadaný login již existuje, zadejte prosím jiný');
		}
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	private function checkNewUserPassword(Form $form)
	{
		if ($this->id > 0) {
			return;
		}

		$password = $form['password']['password']->getValue();

		if (Strings::length($password) <= 0) {
			$form->addError('Zadejte heslo pro nového uživatele');
		}
	}

	/**
	 * Kontrola - uživatel musí mít sekci, v případě že není superadmin
	 * @param \Nette\Application\UI\Form $form
	 */
	private function checkSectionBindins(Form $form)
	{
		$sections = $form['elections']['section']->getValue();

		if (count($sections)) {
			return;
		}

		$roles = $form['elections']['role']->getValue();

		$record = $this->roleFacade->all()
			->where('super=1 AND code', $roles)
			->fetch();

		if ($record) {
			return;
		}

		$form->addError('Uživatel, který není superadmin, musí mít zvolenu sekci');
	}
}

interface IUserFormFactory
{
	/** @return \Admin\User\UserForm */
	public function create();
}
