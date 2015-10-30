<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin;

use Model\RoleResourceFacade;
use Nette\Forms\Controls\SelectBox;
use Nette\Object;

class RoleResourceFormItemFactory extends Object
{

	/** @var \Model\RoleResourceFacade */
	private $roleResourceFacade;

	/**
	 * @param \Model\RoleResourceFacade $roleResourceFacade
	 */
	function __construct(RoleResourceFacade $roleResourceFacade)
	{
		$this->roleResourceFacade = $roleResourceFacade;
	}

	/**
	 * @return \Nette\Forms\Controls\SelectBox
	 */
	public function create()
	{
		return new SelectBox('Zdroje', $this->getItems());
	}

	/**
	 * @return array
	 */
	private function getItems()
	{
		return $this->roleResourceFacade->all()->order('name ASC')->fetchPairs('code', 'name');
	}
}
