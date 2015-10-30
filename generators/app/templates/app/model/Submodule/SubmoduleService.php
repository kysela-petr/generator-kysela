<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleService;

/**
 * @author Generator
 */
class SubmoduleService extends Object {

	use IsSimpleService;

	/** @var SubmoduleRepository */
	protected $submoduleRepository;

	public function __construct(SubmoduleRepository $submoduleRepository) {
		$this->submoduleRepository = $submoduleRepository;
		$this->simpleRepository = $this->submoduleRepository;
	}

}
