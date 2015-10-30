<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleFacade;

/**
 * @author Generator
 */
class TokenFacade extends Object {

	use IsSimpleFacade;

	/** @var TokenService */
	protected $tokenService;

	function __construct(TokenService $tokenService) {
		$this->tokenService = $tokenService;
		$this->simpleService = $this->tokenService;
	}

}
