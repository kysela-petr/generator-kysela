<?php

namespace Admin\Utils;

use Nette;
use Nette\Object;
use Nette\Security\IAuthenticator;
use Nette\Security\IAuthorizator;
use Nette\Security\User;

/**
 * Utilita pro nastaveni uzivatele
 * @author Petr Hlavac
 */
class UserSetuper extends Object
{

	/** @var string */
	private $namespace;

	/** @var \Nette\Security\IAuthorizator */
	private $authorizator;

	/** @var \Nette\Security\IAuthenticator */
	private $authenticator;

	/**
	 * @param string $namespace
	 * @param \Nette\Security\IAuthorizator $authorizator
	 * @param \Nette\Security\IAuthenticator $authenticator
	 */
	function __construct($namespace, IAuthorizator $authorizator, IAuthenticator $authenticator)
	{
		$this->namespace = $namespace;
		$this->authorizator = $authorizator;
		$this->authenticator = $authenticator;
	}

	/**
	 * @param \Nette\Security\User $user
	 */
	public function setup(User $user)
	{
		$user->getStorage()->setNamespace($this->namespace);
		$user->setAuthenticator($this->authenticator);
		$user->setAuthorizator($this->authorizator);
	}
}
