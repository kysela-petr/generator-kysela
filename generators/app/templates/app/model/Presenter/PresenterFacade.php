<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleFacade;

/**
 * @author Generator
 */
class PresenterFacade extends Object {

	use IsSimpleFacade;

	/** @var PresenterService */
	protected $presenterService;

	function __construct(PresenterService $presenterService) {
		$this->presenterService = $presenterService;
		$this->simpleService = $this->presenterService;
	}

}
