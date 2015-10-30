<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleService;

/**
 * @author Generator
 */
class ModuleService extends Object {

	use IsSimpleService;

	/** @var ModuleRepository */
	protected $moduleRepository;

	public function __construct(ModuleRepository $moduleRepository) {
		$this->moduleRepository = $moduleRepository;
		$this->simpleRepository = $this->moduleRepository;
	}

}
