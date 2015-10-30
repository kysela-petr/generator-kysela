<?php

namespace App;

use Esports\Utils\Tree;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class CurrentMenuItemFinder extends Object
{

	/** @var Tree */
	protected $tree;

	/** @var string */
	protected $location;

	/** @var array */
	protected $params;

	/** @var MenuLinkComparator */
	protected $menuLinkComparator;

	function __construct(Tree $tree, $location, array $params, MenuLinkComparator $menuLinkComparator)
	{
		$this->tree = $tree;
		$this->location = $location;
		$this->params = $params;
		$this->menuLinkComparator = $menuLinkComparator;
	}

	/**
	 * @return array
	 */
	public function buildCurrentPositionMap()
	{
		$currentId = $this->findCurrentItemId();

		return $this->buildMap($currentId);
	}

	/**
	 * @return int|NULL
	 */
	private function findCurrentItemId()
	{
		$map = $this->tree->getMap();

		foreach ($map as $id => $item) {
			if (!isset($item['link'])) {
				continue;
			}

			if ($item['link'] && $this->isSameUrl($item['link']) && $this->isSameParams($item['link'])) {
				return $item['id'];
			}
		}

		return NULL;
	}

	/**
	 * @param ILinkSettings $linkSettings
	 * @return boolean
	 */
	protected function isSameUrl(ILinkSettings $linkSettings)
	{
		return $this->menuLinkComparator->has($this->location, $linkSettings->getPresenter());
	}

	/**
	 * @param \App\ILinkSettings $linkSettings
	 * @return boolean
	 */
	protected function isSameParams(ILinkSettings $linkSettings)
	{
		$params = $linkSettings->getParams();

		if (!$params) {
			return TRUE;
		}

		foreach ($params as $k => $v) {
			if (!(isset($this->params[$k]) && $this->params[$k] == $v)) {
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * @param int $currentId
	 * @return array
	 */
	private function buildMap($currentId)
	{
		$map = $this->tree->getMap();
		$currentMap = [];
		while ($currentId) {
			array_unshift($currentMap, $currentId);
			$currentId = $map[$currentId]['parentId'];
		}

		return $currentMap;
	}

}
