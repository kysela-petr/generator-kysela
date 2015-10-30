<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleService;

/**
 * @author Generator
 */
class PageService extends Object {

	use IsSimpleService;

	/** @var PageRepository */
	protected $pageRepository;

	/** @var \Model\PageFilter */
	private $pageFilter;

	/**
	 * @param \Model\PageRepository $pageRepository
	 * @param \Model\PageFilter $pageFilter
	 */
	public function __construct(PageRepository $pageRepository, PageFilter $pageFilter) {
		$this->pageRepository = $pageRepository;
		$this->simpleRepository = $this->pageRepository;
		$this->pageFilter = $pageFilter;
	}

	/**
	 * @param int $id
	 * @param int[] $ids
	 */
	public function setSection($id,$ids)
	{
		$currentIds = $this->pageFilter->filterId($this->all(), $id)
			->select(':page_has_section.section.id')
			->fetchPairs('id', 'id');

		$this->pageRepository->reassignSection($id, $ids, $currentIds);
	}

}
