<?php

namespace Model;

use Model\SimpleModel\IsSimpleService;
use Nette\Object;

/**
 * @author Generator
 */
class UserService extends Object
{

	use IsSimpleService;

	/** @var UserRepository */
	protected $userRepository;

	/** @var \Model\UserFilter */
	private $userFilter;

	/**
	 * @param \Model\UserRepository $userRepository
	 * @param \Model\UserFilter $userFilter
	 */
	public function __construct(UserRepository $userRepository, UserFilter $userFilter)
	{
		$this->userRepository = $userRepository;
		$this->simpleRepository = $this->userRepository;
		$this->userFilter = $userFilter;
	}

	/**
	 * @param $id
	 * @param string[] $setCodes
	 */
	public function setRole($id, $setCodes)
	{
		$current = $this->userFilter->filterId($this->all(), $id)
			->select(':user_has_role.role.code')
			->fetchPairs('code', 'code');

		$this->userRepository->reassignRole($id, $setCodes, $current);
	}

	/**
	 * @param $id
	 * @param int[] $setIds
	 */
	public function setSection($id, $setIds)
	{
		$current = $this->userFilter->filterId($this->all(), $id)
			->select(':user_has_section.section.id')
			->fetchPairs('id', 'id');

		$this->userRepository->reassignSection($id, $setIds, $current);
	}


}
