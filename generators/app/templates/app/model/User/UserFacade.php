<?php

namespace Model;

use Model\SimpleModel\IsSimpleFacade;
use Nette\Object;

/**
 * @author Generator
 */
class UserFacade extends Object
{

	use IsSimpleFacade;

	/** @var UserService */
	protected $userService;

	function __construct(UserService $userService)
	{
		$this->userService = $userService;
		$this->simpleService = $this->userService;
	}

	/**
	 * @param int $id
	 * @param string[] $setCodes
	 */
	public function setRole($id, $setCodes)
	{
		$this->userService->setRole($id, $setCodes);
	}

	/**
	 * @param int $id
	 * @param int[] $setId
	 */
	public function setSection($id, $setId)
	{
		$this->userService->setSection($id, $setId);
	}
}
