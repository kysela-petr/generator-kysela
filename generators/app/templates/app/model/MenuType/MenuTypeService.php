<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleService;

/**
 * @author Generator
 */
class MenuTypeService extends Object {

	use IsSimpleService;

	/** @var MenuTypeRepository */
	protected $menuTypeRepository;

	public function __construct(MenuTypeRepository $menuTypeRepository) {
		$this->menuTypeRepository = $menuTypeRepository;
		$this->simpleRepository = $this->menuTypeRepository;
	}

}
