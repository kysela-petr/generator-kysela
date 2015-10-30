<?php

namespace App;

use Admin\MenuOrderer;
use Esports\Utils\Tree;
use Model\MenuProvider;
use Model\SectionFilter;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuTreeFactory extends Object
{

	/** @var MenuProvider */
	protected $menuProvider;

	/** @var SectionFilter */
	protected $sectionFilter;

	/** @var MenuOrderer */
	protected $menuOrderer;


	/**
	 * @param \Model\MenuProvider $menuProvider
	 * @param \Model\SectionFilter $sectionFilter
	 * @param \Admin\MenuOrderer $menuOrderer
	 */
	function __construct(MenuProvider $menuProvider, SectionFilter $sectionFilter, MenuOrderer $menuOrderer)
	{
		$this->menuProvider = $menuProvider;
		$this->sectionFilter = $sectionFilter;
		$this->menuOrderer = $menuOrderer;
	}

	/**
	 * @param int $sectionId
	 * @return Tree
	 */
	public function create($sectionId)
	{
		$context = $this->menuProvider->all()
			->select('menu.*, :menu_has_menu.parent.id AS parentId');

		$this->menuOrderer->order($context, 'menu');
		$this->sectionFilter->filterId($context, $sectionId);

		return new Tree($context);
	}

}
