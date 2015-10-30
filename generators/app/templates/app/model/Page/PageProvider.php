<?php
/**
 * @author Martin Kovařčík.
 */

namespace Model;

use Nette\Object;

class PageProvider extends Object
{

	/** @var \Model\PageFacade */
	private $pageFacade;

	/** @var \Model\PageFilter */
	private $pageFilter;

	/** @var \Model\SectionFilter */
	private $sectionFilter;

	/**
	 * @param \Model\PageFacade $pageFacade
	 * @param \Model\PageFilter $pageFilter
	 * @param \Model\SectionFilter $sectionFilter
	 */
	function __construct(PageFacade $pageFacade, PageFilter $pageFilter, SectionFilter $sectionFilter)
	{
		$this->pageFacade = $pageFacade;
		$this->pageFilter = $pageFilter;
		$this->sectionFilter = $sectionFilter;
	}

	/**
	 * @return \Nette\Database\Table\Selection
	 */
	public function all()
	{
		return $this->pageFilter->filterShow($this->pageFacade->all(), TRUE);
	}

	/**
	 * @param $sectionId
	 * @return \Nette\Database\Table\Selection
	 */
	public function section($sectionId)
	{
		return $this->pageFilter->filterSection($this->all(), $sectionId);
	}

	/**
	 * @param $id
	 * @return \Nette\Database\Table\Selection
	 */
	public function page($id)
	{
		return $this->pageFilter->filterId($this->all(), $id);
	}
}
