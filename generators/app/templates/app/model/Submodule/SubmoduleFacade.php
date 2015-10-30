<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleFacade;

/**
 * @author Generator
 */
class SubmoduleFacade extends Object {

	use IsSimpleFacade;

	/** @var SubmoduleService */
	protected $submoduleService;

	function __construct(SubmoduleService $submoduleService) {
		$this->submoduleService = $submoduleService;
		$this->simpleService = $this->submoduleService;
	}

}
