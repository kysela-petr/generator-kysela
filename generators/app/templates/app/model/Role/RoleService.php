<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleService;

/**
 * @author Generator
 */
class RoleService extends Object {

	use IsSimpleService;

	/** @var RoleRepository */
	protected $roleRepository;

	public function __construct(RoleRepository $roleRepository) {
		$this->roleRepository = $roleRepository;
		$this->simpleRepository = $this->roleRepository;
	}

}
