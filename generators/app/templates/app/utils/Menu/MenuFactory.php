<?php

namespace App;

use App\Helper\MenuItem;
use App\Widget\IMenuViewFactory;
use Nette\InvalidArgumentException;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuFactory extends Object
{

	/** @var MenuTreeFactory */
	protected $menuTreeFactory;

	/** @var IMenuViewFactory */
	protected $menuViewFactory;

	/** @var MenuItem */
	protected $menuItemHelper;

	/**
	 * @param \App\MenuTreeFactory $menuTreeFactory
	 * @param \App\Widget\IMenuViewFactory $menuViewFactory
	 * @param \App\Helper\MenuItem $menuItemHelper
	 */
	function __construct(MenuTreeFactory $menuTreeFactory, IMenuViewFactory $menuViewFactory, MenuItem $menuItemHelper)
	{
		$this->menuTreeFactory = $menuTreeFactory;
		$this->menuViewFactory = $menuViewFactory;
		$this->menuItemHelper = $menuItemHelper;
	}

	/**
	 * @param int $sectionId
	 * @param $module
	 * @param MenuLinkMap $menuLinkMap
	 * @return \App\Widget\MenuView
	 */
	public function create($sectionId, $module, MenuLinkMap $menuLinkMap)
	{
		$tree = $this->menuTreeFactory->create($sectionId);

		$tree->onNodeCreated[] = function ($node, $data) use ($menuLinkMap) {
			$node->name = $data->name;
			$node->type = $data->type;
			$node->html = NULL;

			if ($this->menuItemHelper->isMenuItemLink($data->type)) {
				try {
					$node->link = $menuLinkMap
						->get($data->type)
						->create($data->id);
				} catch (InvalidArgumentException $e) {
					$node->link = NULL;
				}
			} else {
				$node->html = $data->html;
			}
		};

		return $this->menuViewFactory->create($sectionId, $module, $tree);
	}
}
