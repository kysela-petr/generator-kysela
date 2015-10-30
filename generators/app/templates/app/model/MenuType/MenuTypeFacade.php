<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleFacade;

/**
 * @author Generator
 */
class MenuTypeFacade extends Object {

	use IsSimpleFacade;

	/** @var MenuTypeService */
	protected $menuTypeService;

	function __construct(MenuTypeService $menuTypeService) {
		$this->menuTypeService = $menuTypeService;
		$this->simpleService = $this->menuTypeService;
	}

}
