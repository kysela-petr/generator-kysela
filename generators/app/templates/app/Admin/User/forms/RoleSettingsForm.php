<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin\User;

use Admin\BaseForm;
use Admin\RoleActionFormItemFactory;
use Admin\RoleResourceFormItemFactory;
use Model\RoleFacade;
use Nette\Application\UI\Form;

class RoleSettingsForm extends BaseForm
{

	/** @var string */
	protected $id;

	/** @var \Admin\User\RoleFacade */
	private $roleFacade;

	/** @var \Admin\RoleActionFormItemFactory */
	private $roleActionFormItemFactory;

	/** @var \Admin\RoleResourceFormItemFactory */
	private $roleResourceFormItemFactory;


	/**
	 * @param int $id
	 * @param \Model\RoleFacade $roleFacade
	 * @param \Admin\RoleActionFormItemFactory $roleActionFormItemFactory
	 * @param \Admin\RoleResourceFormItemFactory $roleResourceFormItemFactory
	 */
	function __construct($id, RoleFacade $roleFacade, RoleActionFormItemFactory $roleActionFormItemFactory, RoleResourceFormItemFactory $roleResourceFormItemFactory)
	{
		parent::__construct();
		$this->id = $id;
		$this->roleFacade = $roleFacade;
		$this->roleActionFormItemFactory = $roleActionFormItemFactory;
		$this->roleResourceFormItemFactory = $roleResourceFormItemFactory;
	}

	/**
	 * @return \Nette\Application\UI\Form
	 */
	protected function createForm()
	{
		$form = new Form;
		$form['resource'] = $this->roleResourceFormItemFactory->create();
		$form['action'] = $this->roleActionFormItemFactory->create();
		$form->addSubmit('save', 'Přidat');

		return $form;
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	public function add(Form $form)
	{
		$resourceId = $form['resource']->getValue();
		$actionId = $form['action']->getValue();
		$this->roleFacade->addResource($this->id, $resourceId, $actionId);
	}

}

interface IRoleSettingsFormFactory
{

	/**
	 * @param string $id
	 * @return \Admin\User\RoleSettingsForm
	 */
	public function create($id);
}
