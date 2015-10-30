<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin;

use Model\RoleFacade;
use Nette\Forms\Controls\MultiSelectBox;
use Nette\Object;

class RoleFormItemFactory extends Object
{

	/** @var \Model\RoleFacade */
	private $roleFacade;

	/**
	 * @param \Model\RoleFacade $roleFacade
	 */
	function __construct(RoleFacade $roleFacade)
	{
		$this->roleFacade = $roleFacade;
	}

	/**
	 * @return mixed
	 */
	public function create()
	{
		$return = new MultiSelectBox("Role", $this->getItems());
		$return->setAttribute('class', 'select2');

		return $return->setOption('no-form-control', TRUE);
	}

	/**
	 * @return array
	 */
	private function getItems()
	{
		return $this->roleFacade->all()->fetchPairs('code', 'name');
	}
}
