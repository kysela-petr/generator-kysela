<?php

namespace Model;

use Model\SimpleModel\IsSimpleFacade;
use Nette\Object;

/**
 * @author Generator
 */
class PageFacade extends Object
{

	use IsSimpleFacade;

	/** @var PageService */
	protected $pageService;

	function __construct(PageService $pageService)
	{
		$this->pageService = $pageService;
		$this->simpleService = $this->pageService;
	}

	/**
	 * @param int $id
	 * @param int[] $ids
	 */
	public function setSection($id, $ids)
	{
		$this->pageService->setSection($id, $ids);
	}
}
