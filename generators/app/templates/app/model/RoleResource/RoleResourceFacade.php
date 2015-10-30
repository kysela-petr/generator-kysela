<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleFacade;

/**
 * @author Generator
 */
class RoleResourceFacade extends Object {

	use IsSimpleFacade;

	/** @var RoleResourceService */
	protected $roleResourceService;

	function __construct(RoleResourceService $roleResourceService) {
		$this->roleResourceService = $roleResourceService;
		$this->simpleService = $this->roleResourceService;
	}

	/**
	 * @param string $id
	 * @param array $data
	 */
	public function add($id, $data) {
		$data['code'] = $id;
		$this->roleResourceService->add($data);
	}

}
