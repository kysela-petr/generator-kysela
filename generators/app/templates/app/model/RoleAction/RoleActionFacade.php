<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleFacade;

/**
 * @author Generator
 */
class RoleActionFacade extends Object {

	use IsSimpleFacade;

	/** @var RoleActionService */
	protected $roleActionService;

	function __construct(RoleActionService $roleActionService) {
		$this->roleActionService = $roleActionService;
		$this->simpleService = $this->roleActionService;
	}

	/**
	 * @param string $id
	 * @param array $data
	 */
	public function add($id, $data) {
		$data['code'] = $id;
		$this->roleActionService->add($data);
	}

}
