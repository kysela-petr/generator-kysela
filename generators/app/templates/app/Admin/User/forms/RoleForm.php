<?php
/**
 * @author Martin Kovařčík.
 */
namespace Admin\User;

use Admin\BaseForm;
use Model\RecordNotFoundException;
use Model\RoleFacade;
use Model\RoleFilter;
use Nette\Application\UI\Form;

class RoleForm extends BaseForm
{

	/** @var string */
	protected $primary;

	/** @var \Model\RoleFacade */
	private $roleFacade;

	/** @var \Model\RoleFilter */
	private $roleFilter;

	/**
	 * @param \Model\RoleFacade $roleFacade
	 * @param \Model\RoleFilter $roleFilter
	 */
	function __construct(RoleFacade $roleFacade, RoleFilter $roleFilter)
	{
		parent::__construct();

		$this->roleFacade = $roleFacade;
		$this->roleFilter = $roleFilter;
	}

	/**
	 * @return \Nette\Application\UI\Form
	 */
	protected function createForm()
	{
		$form = new Form;
		$form->addText('code', 'Kód')
			->addRule(Form::FILLED, "Vyplňte prosím kód");
		$form->addText('name', 'Název')
			->addRule(Form::FILLED, "Vyplňte prosím název");
		$form->addSubmit('save', 'Uložit');

		return $form;
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	public function edit(Form $form)
	{
		$this->roleFacade->edit($this->primary, $form->getValues(TRUE));
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	public function add(Form $form)
	{
		$id = $form['code']->getValue();
		$data = ['name' => $form['name']->getValue()];
		$role = $this->roleFacade->add($id, $data);
		$this->primary = $role->code;
	}

	/**
	 * @param $primary
	 * @throws \Model\RecordNotFoundException
	 */
	public function load($primary)
	{
		$record = $this->getRoleData($primary)->fetch();

		if (!$record) {
			throw new RecordNotFoundException;
		}

		$this->primary = $primary;
		$this['form']->setDefaults($record->toArray());
	}

	/**
	 * @param code $primary
	 * @return \Nette\Database\Table\Selection
	 */
	private function getRoleData($primary)
	{
		return $this->roleFilter->filterId($this->roleFacade->all(), $primary);
	}

}

interface IRoleFormFactory
{
	/** @return \Admin\User\RoleForm */
	public function create();
}
