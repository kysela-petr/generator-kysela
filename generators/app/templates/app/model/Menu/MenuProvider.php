<?php

namespace Model;

use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuProvider extends Object {

	/** @var MenuFacade */
	protected $menuFacade;

	/** @var MenuFilter */
	protected $menuFilter;

	function __construct(MenuFacade $menuFacade, MenuFilter $menuFilter) {
		$this->menuFacade = $menuFacade;
		$this->menuFilter = $menuFilter;
	}

	/**
	 * @return \Nette\Database\Table\Selection
	 */
	public function all() {
		return $this->menuFilter->filterShow($this->menuFacade->all(), true);
	}

}
