<?php

namespace Admin;

use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuMoverTreeFactory extends Object implements ITreeFactory
{

	/** @var int */
	protected $sectionId;

	/** @var MenuTreeFactory */
	protected $menuTreeFactory;

	function __construct($sectionId, MenuTreeFactory $menuTreeFactory)
	{
		$this->sectionId = $sectionId;
		$this->menuTreeFactory = $menuTreeFactory;
	}

	/**
	 * @return Tree
	 */
	public function create()
	{
		$tree = $this->menuTreeFactory->create($this->sectionId);
		$tree->onNodeCreated[] = function ($item, $data) {
			$item->priority = $data['priority'];
		};

		return $tree;
	}

}
