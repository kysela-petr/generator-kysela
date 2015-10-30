<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleFacade;

/**
 * @author Generator
 */
class ModuleFacade extends Object {

	use IsSimpleFacade;

	/** @var ModuleService */
	protected $moduleService;

	function __construct(ModuleService $moduleService) {
		$this->moduleService = $moduleService;
		$this->simpleService = $this->moduleService;
	}

}
