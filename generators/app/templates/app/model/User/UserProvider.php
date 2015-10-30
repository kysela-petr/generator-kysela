<?php
/**
 * @author Martin Kovařčík.
 */

namespace Model;

use Esports\Helper\PasswordHasher;
use Nette\Object;

class UserProvider extends Object
{

	/** @var \Model\UserFacade */
	private $userFacade;

	/** @var \Model\UserFilter */
	private $userFilter;

	/** @var \Esports\Helper\PasswordHasher */
	private $passwordHasher;

	/**
	 * @param \Model\UserFacade $userFacade
	 * @param \Model\UserFilter $userFilter
	 * @param \Esports\Helper\PasswordHasher $passwordHasher
	 */
	function __construct(UserFacade $userFacade, UserFilter $userFilter, PasswordHasher $passwordHasher)
	{
		$this->userFacade = $userFacade;
		$this->userFilter = $userFilter;
		$this->passwordHasher = $passwordHasher;
	}

	/**
	 * @return \Nette\Database\Table\Selection
	 */
	public function all()
	{
		return $this->userFacade->all();
	}

	/**
	 * @param $username
	 * @return \Nette\Database\Table\Selection
	 */
	public function username($username)
	{
		return $this->userFilter->filterUsername($this->all(), $username);
	}

	/**
	 * @param $email
	 * @return \Nette\Database\Table\Selection
	 */
	public function email($email)
	{
		return $this->userFilter->filterEmail($this->all(), $email);
	}

	public function changePassword($id,$password)
	{
		$data = [
			'password'=>$this->passwordHasher->hash($password)
		];

		$this->userFacade->edit($id,$data);
	}
}
