<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin\User;

use Admin\DataFormPresenter;
use Model\RoleFacade;
use Model\RoleFilter;
use Nette\Utils\Strings;

class RolePresenter extends DataFormPresenter
{

	/** @var \Admin\User\IRoleSettingsGridFactory */
	private $roleSettingsGridFactory;

	/** @var \Admin\User\IRoleSettingsFormFactory */
	private $roleSettingsFormFactory;

	/** @var \Model\RoleFacade */
	private $roleFacade;

	/** @var \Model\RoleFilter */
	private $roleFilter;

	/**
	 * @param \Admin\User\IRoleFormFactory $formFactory
	 * @param \Admin\User\IRoleGridFactory $gridFactory
	 * @param \Admin\User\IRoleSettingsGridFactory $roleSettingsGridFactory
	 * @param \Admin\User\IRoleSettingsFormFactory $roleSettingsFormFactory
	 * @param \Model\RoleFacade $roleFacade
	 * @param \Model\RoleFilter $roleFilter
	 */
	public function __construct(IRoleFormFactory $formFactory, IRoleGridFactory $gridFactory, IRoleSettingsGridFactory $roleSettingsGridFactory, IRoleSettingsFormFactory $roleSettingsFormFactory, RoleFacade $roleFacade, RoleFilter $roleFilter)
	{
		parent::__construct();
		$this->formFactory = $formFactory;
		$this->gridFactory = $gridFactory;
		$this->roleSettingsGridFactory = $roleSettingsGridFactory;
		$this->roleSettingsFormFactory = $roleSettingsFormFactory;
		$this->roleFacade = $roleFacade;
		$this->roleFilter = $roleFilter;
	}

	public function renderDefault()
	{
		$this->simpleList('Přidat roli');
	}

	public function renderAdd()
	{
		$this->prepareTemplate(NULL);
	}

	public function renderEdit($id)
	{
		$this->prepareTemplate($id);
	}

	public function renderSettings($id)
	{
		$this->prepareTemplate($id);
	}

	protected function prepareTemplate($id)
	{
		$this->template->id = $id;
		$this->template->current = $this->action;
		if(Strings::length($id)){
			$this->template->roleName = $this->getRoleName($id);
		}

	}

	/**
	 * @return \Admin\User\RoleSettingsGrid
	 */
	protected function createComponentSettingsGrid()
	{
		$grid = $this->roleSettingsGridFactory->create($this->getParameter('id'));
		$grid->onDelete[] = $grid->deleteRole;

		return $grid;
	}

	/**
	 * @return \Admin\User\RoleSettingsForm
	 */
	protected function createComponentSettingsForm()
	{
		$form = $this->roleSettingsFormFactory->create($this->getParameter('id'));
		$form['form']->onSubmit[] = $form->add;

		return $form;
	}

	private function getRoleName($id)
	{
		return $this->roleFilter->filterId($this->roleFacade->all(),$id)->fetch()->name;
	}

}
