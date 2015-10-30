<?php

namespace Admin;

use Model\MenuFacade;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuTreeModelAdapter extends Object implements IPriorityHorizontalModelAdapter
{

	/**
	 * @var MenuFacade
	 */
	protected $menuFacade;

	function __construct(MenuFacade $menuFacade)
	{
		$this->menuFacade = $menuFacade;
	}

	public function setParent($id, $parentId)
	{
		$this->menuFacade->setParent($id, $parentId);
	}

	public function setPriority($id, $priority)
	{
		$this->menuFacade->edit($id, ['priority' => $priority]);
	}

}
