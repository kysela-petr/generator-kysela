<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleService;

/**
 * @author Generator
 */
class RoleActionService extends Object {

	use IsSimpleService;

	/** @var RoleActionRepository */
	protected $roleActionRepository;

	public function __construct(RoleActionRepository $roleActionRepository) {
		$this->roleActionRepository = $roleActionRepository;
		$this->simpleRepository = $this->roleActionRepository;
	}

}
