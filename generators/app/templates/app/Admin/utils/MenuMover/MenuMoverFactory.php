<?php

namespace Admin;

use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuMoverFactory extends Object implements IMenuMoverFactory
{

	/** @var MenuTreeMoverFactory */
	protected $menuTreeMoverFactory;

	function __construct(MenuTreeMoverFactory $menuTreeMoverFactory)
	{
		$this->menuTreeMoverFactory = $menuTreeMoverFactory;
	}

	function create($sectionId)
	{
		$verticalTreeMover = $this->menuTreeMoverFactory->createVertical($sectionId);
		$horizontalTreeMover = $this->menuTreeMoverFactory->createHorizontal($sectionId);

		return new MenuTreeMoverAdapter($verticalTreeMover, $horizontalTreeMover);
	}

	public function createForcer($sectionId)
	{
		$verticalTreeMover = $this->menuTreeMoverFactory->createVertical($sectionId);
		$horizontalTreeMover = $this->menuTreeMoverFactory->createHorizontal($sectionId);
		$horizontalTreeMover->setShouldMoveToCallback(function () {
			return TRUE;
		});

		return new MenuTreeMoverAdapter($verticalTreeMover, $horizontalTreeMover);
	}

}
