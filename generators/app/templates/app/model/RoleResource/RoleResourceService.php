<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleService;

/**
 * @author Generator
 */
class RoleResourceService extends Object {

	use IsSimpleService;

	/** @var RoleResourceRepository */
	protected $roleResourceRepository;

	public function __construct(RoleResourceRepository $roleResourceRepository) {
		$this->roleResourceRepository = $roleResourceRepository;
		$this->simpleRepository = $this->roleResourceRepository;
	}

}
