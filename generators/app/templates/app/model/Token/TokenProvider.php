<?php
/**
 * @author Martin Kovařčík.
 */

namespace Model;

use Nette\Object;
use Nette\Utils\Random;

class TokenProvider extends Object
{

	/** @var \Model\TokenFacade */
	private $tokenFacade;

	/** @var \Model\TokenFilter */
	private $tokenFilter;

	/**
	 * @param \Model\TokenFacade $tokenFacade
	 * @param \Model\TokenFilter $tokenFilter
	 */
	function __construct(TokenFacade $tokenFacade, TokenFilter $tokenFilter)
	{
		$this->tokenFacade = $tokenFacade;
		$this->tokenFilter = $tokenFilter;
	}

	/**
	 * @return \Nette\Database\Table\Selection
	 */
	public function all()
	{
		return $this->tokenFacade->all();
	}

	/**
	 * Vytvori novy token pro urcity email s platnosti 1 den
	 * @param string $email
	 * @return \Nette\Database\Table\ActiveRow
	 */
	public function generateToken($email)
	{
		$data = [
			'token' => Random::generate(20),
			'valid_to' => new \DateTime("now +1 day"),
			'email' => $email
		];

		return $this->tokenFacade->add($data);
	}

	/**
	 * Jestliže token a valid_to>=now
	 * @param $token
	 * @return \Nette\Database\Table\Selection
	 */
	public function valid($token)
	{
		return $this->tokenFilter->filterValid($this->all(), $token);
	}

	/**
	 * @param string $token
	 * @return bool
	 */
	public function deleteToken($token)
	{
		$record = $this->tokenFilter->filterToken($this->tokenFacade->all(), $token)->fetch();

		if (!$record) {
			return FALSE;
		}

		$this->tokenFacade->delete($record->id);

		return TRUE;
	}


}
