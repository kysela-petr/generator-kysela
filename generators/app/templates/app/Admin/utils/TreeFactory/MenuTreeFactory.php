<?php

namespace Admin;

use Esports\Utils\Tree;
use Model\MenuFacade;
use Model\SectionFilter as ModelSectionFilter;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuTreeFactory extends Object
{

	/** @var MenuFacade */
	protected $menuFacade;

	/** @var MenuOrderer */
	protected $menuOrderer;

	/** @var SectionFilter */
	protected $sectionFilter;

	/**
	 * @param \Model\MenuFacade $menuFacade
	 * @param \Admin\MenuOrderer $menuOrderer
	 * @param \Model\SectionFilter $sectionFilter
	 */
	function __construct(MenuFacade $menuFacade, MenuOrderer $menuOrderer, ModelSectionFilter $sectionFilter)
	{
		$this->menuFacade = $menuFacade;
		$this->menuOrderer = $menuOrderer;
		$this->sectionFilter = $sectionFilter;
	}

	/**
	 * @param int $sectionId
	 * @return Tree
	 */
	public function create($sectionId)
	{
		$context = $this->menuFacade->all()
			->select('menu.*, typ.name AS type_name, :menu_has_menu.parent.id AS parentId');

		$this->menuOrderer->order($context, 'menu');
		$this->sectionFilter->filterId($context, $sectionId);

		return new Tree($context);
	}

}
