<?php

namespace Admin;

use Model\SectionFacade;
use Nette\Forms\Controls\SelectBox;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuFormItemFactory extends Object
{

	/** @var SectionFacade */
	protected $sectionFacade;

	/** @var MenuTreeFactory */
	protected $menuTreeFactory;

	/** @var TreeFormItemCreator */
	protected $treeFormItemCreator;

	function __construct(SectionFacade $sectionFacade, MenuTreeFactory $menuTreeFactory, TreeFormItemCreator $treeFormItemCreator)
	{
		$this->sectionFacade = $sectionFacade;
		$this->menuTreeFactory = $menuTreeFactory;
		$this->treeFormItemCreator = $treeFormItemCreator;
	}

	/**
	 * Vytvori kontejner pro vyber kategorie
	 * @param int|NULL $sectionId
	 * @return SelectBox
	 */
	public function create($sectionId = NULL)
	{
		if ($sectionId === NULL) {
			$options = $this->getSectionOptions();
		} else {
			$options = $this->getOptions($sectionId);
		}

		return new SelectBox('Kategorie', $options);
	}

	/**
	 * @param int $sectionId
	 * @return array
	 */
	protected function getOptions($sectionId)
	{
		return $this->treeFormItemCreator->createOptions($this->getTree($sectionId));
	}

	/**
	 * @return array
	 */
	protected function getSectionOptions()
	{
		$options = [];

		foreach ($this->sectionFacade->all() as $section) {
			$options[$section['name']] = $this->getOptions($section['id']);
		}

		return $options;
	}

	/**
	 * @param int $sectionId
	 * @return \Esports\Utils\Tree
	 */
	protected function getTree($sectionId)
	{
		$tree = $this->menuTreeFactory->create($sectionId);
		$tree->onNodeCreated = function ($node, $data) {
			$node->name = $data->name;
		};

		return $tree;
	}

}
