<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleFacade;

/**
 * @author Generator
 */
class SectionFacade extends Object
{

	use IsSimpleFacade;

	/** @var SectionService */
	protected $sectionService;

	function __construct(SectionService $sectionService)
	{
		$this->sectionService = $sectionService;
		$this->simpleService = $this->sectionService;
	}

	/**
	 * @param int $id
	 * @param []$types
	 */
	public function setMenuType($id, $types)
	{
		$this->sectionService->setMenuType($id, $types);
	}

	/**
	 * @param int $id
	 * @return \Nette\Database\Table\Selection
	 */
	public function getMenuType($id)
	{
		return $this->sectionService->getMenuType($id);
	}
}
