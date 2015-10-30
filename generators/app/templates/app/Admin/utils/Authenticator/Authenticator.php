<?php

namespace Admin;

use Model;
use Nette\Object;
use Nette\Security;

/**
 * Autentifikator uzivatelu administrace
 * @author Petr Hlavac
 */
class Authenticator extends Object implements Security\IAuthenticator
{

	/** @var Model\UserFacade */
	protected $userFacade;

	/** @var Model\UserFilter */
	protected $userFilter;

	/** @var \Esports\Helper\PasswordVerifier */
	protected $passwordVerifier;

	/** @var \Model\RoleFilter */
	private $roleFilter;

	/**
	 * @param \Model\UserFacade $userFacade
	 * @param \Model\UserFilter $userFilter
	 * @param \Model\RoleFilter $roleFilter
	 * @param \Esports\Helper\PasswordVerifier $passwordVerifier
	 */
	function __construct(Model\UserFacade $userFacade, Model\UserFilter $userFilter, Model\RoleFilter $roleFilter, \Esports\Helper\PasswordVerifier $passwordVerifier)
	{
		$this->userFacade = $userFacade;
		$this->userFilter = $userFilter;
		$this->passwordVerifier = $passwordVerifier;
		$this->roleFilter = $roleFilter;
	}

	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;

		$context = $this->userFacade->all();
		$this->userFilter->filterUsername($context, $username);

		$user = $context->fetch();

		if (!$user) {
			throw new Security\AuthenticationException("User $username not found");
		}

		if (!$this->passwordVerifier->verify($password, $user['password'])) {
			throw new Security\AuthenticationException("Wrong password for user $username");
		}

		$data = [
			'username' => $user['username'],
			'name' => $user['name'],
			'surname' => $user['surname'],
			'super' => $this->isSuperRole($user['id']),
			'sections' => $this->getSections($user['id'])
		];

		return new Security\Identity($user['id'], $this->getRoles($user['id']), $data);
	}

	/**
	 * @param int $id
	 * @return array
	 */
	protected function getRoles($id)
	{
		$selection = $this->userFilter->filterId($this->userFacade->all(), $id);
		$selection->select(':user_has_role.role.code');

		return array_keys($selection->fetchPairs('code'));
	}

	/**
	 * @param int $id
	 * @return array
	 */
	protected function isSuperRole($id)
	{
		$selection = $this->userFilter->filterId($this->userFacade->all(), $id);
		$this->roleFilter->filterSuper($selection, TRUE, ':user_has_role');

		$record = $selection->fetch();

		if (!$record) {
			return FALSE;
		}

		return TRUE;

	}

	/**
	 * @param $id
	 * @return array
	 */
	protected function getSections($id)
	{
		$selection = $this->userFilter->filterId($this->userFacade->all(), $id);
		$selection->select(':user_has_section.section.id');

		return $selection->fetchPairs('id', 'id');
	}

}
