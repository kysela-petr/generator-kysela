<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleService;

/**
 * @author Generator
 */
class PresenterService extends Object {

	use IsSimpleService;

	/** @var PresenterRepository */
	protected $presenterRepository;

	public function __construct(PresenterRepository $presenterRepository) {
		$this->presenterRepository = $presenterRepository;
		$this->simpleRepository = $this->presenterRepository;
	}

}
