<?php

namespace Admin;

use Model\MenuFacade;

/**
 * @author Petr Hlavac
 */
class MenuTreeMoverFactory extends \Nette\Object
{

	/** @var MenuTreeFactory */
	protected $menuTreeFactory;

	/** @var MenuFacade */
	protected $menuFacade;

	function __construct(MenuTreeFactory $menuTreeFactory, MenuFacade $menuFacade)
	{
		$this->menuTreeFactory = $menuTreeFactory;
		$this->menuFacade = $menuFacade;
	}

	/**
	 * @param int $sectionId
	 * @return \Admin\VerticalTreeMover
	 */
	public function createVertical($sectionId)
	{
		return $this->createMover($sectionId, '\Admin\VerticalTreeMover');
	}

	/**
	 * @param int $sectionId
	 * @param string $class
	 * @return TreeMoverBase
	 */
	private function createMover($sectionId, $class)
	{
		$treeFactory = $this->createTreeFactory($sectionId);
		$modelAdapter = $this->createModelAdapter();

		return new $class($treeFactory, $modelAdapter);
	}

	/**
	 * @param int $sectionId
	 * @return \Admin\MenuMoverTreeFactory
	 */
	protected function createTreeFactory($sectionId)
	{
		return new MenuMoverTreeFactory($sectionId, $this->menuTreeFactory);
	}

	/**
	 * @return \Admin\MenuTreeModelAdapter
	 */
	protected function createModelAdapter()
	{
		return new MenuTreeModelAdapter($this->menuFacade);
	}

	/**
	 * @param int $sectionId
	 * @return \Admin\PriorityHorizontalTreeMover
	 */
	public function createHorizontal($sectionId)
	{
		return $this->createMover($sectionId, '\Admin\PriorityHorizontalTreeMover');
	}

}
