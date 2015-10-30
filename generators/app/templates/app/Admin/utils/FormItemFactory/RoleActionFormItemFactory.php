<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin;

use Model\RoleActionFacade;
use Nette\Forms\Controls\SelectBox;
use Nette\Object;

class RoleActionFormItemFactory extends Object
{

	/** @var \Model\RoleActionFacade */
	private $roleActionFacade;

	/**
	 * @param \Model\RoleActionFacade $roleActionFacade
	 */
	function __construct(RoleActionFacade $roleActionFacade)
	{

		$this->roleActionFacade = $roleActionFacade;
	}

	/**
	 * @return \Nette\Forms\Controls\SelectBox
	 */
	public function create()
	{
		return new SelectBox('Akce', $this->getItems());
	}

	/**
	 * @return array
	 */
	private function getItems()
	{
		return $this->roleActionFacade->all()->order('name ASC')->fetchPairs('code', 'name');
	}
}
