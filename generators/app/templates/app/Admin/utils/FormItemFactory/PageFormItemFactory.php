<?php
/**
 * @author Martin Kovařčík.
 */
namespace Admin;

use Model\PageFacade;
use Model\PageFilter;
use Nette\Forms\Controls\SelectBox;
use Nette\Object;

class PageFormItemFactory extends Object
{

	/** @var PageFacade */
	protected $pageFacade;

	/** @var \Model\PageFilter */
	private $pageFilter;

	/**
	 * @param \Model\PageFacade $pageFacade
	 * @param \Model\PageFilter $pageFilter
	 */
	function __construct(PageFacade $pageFacade, PageFilter $pageFilter)
	{
		$this->pageFacade = $pageFacade;
		$this->pageFilter = $pageFilter;
	}

	/**
	 * @param int $sectionId
	 * @return SelectBox
	 */
	public function create($sectionId)
	{
		$selection = $this->pageFacade->all();
		$this->pageFilter->filterSection($selection, $sectionId);
		$selection->select('page.id, page.name');
		$selection->order('name');

		return new SelectBox('Stránka', $selection->fetchPairs('id', 'name'));
	}

}
