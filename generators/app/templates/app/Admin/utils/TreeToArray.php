<?php

namespace Admin;

use Nette\Object,
	Esports\Utils\Tree;

/**
 * Konvertor prevadejici obsah Tree do vektoru
 * @author Petr Hlavac
 */
class TreeToArray extends Object
{

	/**
	 * @var callable[]
	 * @prototype function($item, $level)
	 */
	public $onItemAdd = [];

	/**
	 * @param Tree $tree
	 * @return array
	 */
	public function convert(Tree $tree)
	{
		$items = [];
		$this->fillItems($items, $tree->getTree(), 0);

		return $items;
	}

	/**
	 * @param array $items
	 * @param array $data
	 * @param int $level
	 */
	protected function fillItems(&$items, $data, $level)
	{
		foreach ($data as $item) {
			$this->onItemAdd($item, $level);
			$items[] = $item;
			$this->fillItems($items, $item['children'], $level + 1);
		}
	}

}
