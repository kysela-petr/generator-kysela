<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleService;

/**
 * @author Generator
 */
class TokenService extends Object {

	use IsSimpleService;

	/** @var TokenRepository */
	protected $tokenRepository;

	public function __construct(TokenRepository $tokenRepository) {
		$this->tokenRepository = $tokenRepository;
		$this->simpleRepository = $this->tokenRepository;
	}

}
