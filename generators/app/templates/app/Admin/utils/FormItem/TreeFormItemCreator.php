<?php

namespace Admin;

use Esports\Utils\Tree;
use Nette\Object;
use Nette\Utils\Html;

/**
 * Vytvari formularove prvky
 * @author Petr Hlavac
 */
class TreeFormItemCreator extends Object
{

	/**
	 * Vytvori pole prvku option ze stromu
	 * @param Tree $tree
	 * @return array
	 */
	public function createOptions(Tree $tree)
	{
		$items = [];

		$arrayTree = $tree->getTree();
		$this->fill($items, $arrayTree);

		return $items;
	}

	/**
	 * @param array $items
	 * @param array $tree
	 * @param int $level
	 */
	protected function fill(&$items, &$tree, $level = 0)
	{
		foreach ($tree as $item) {
			$el = Html::el('option');

			if ($level) {
				$prefix = '|';

				if ($level > 1) {
					$prefix .= ' -';
				}

				$prefix .= str_repeat('-', $level * 2 - 1);

				$el->add(Html::el('')->setHtml($prefix . ' '));
			}

			$el->add(Html::el('')->setText($item->name))
				->value($item->id);

			$items[$item->id] = $el;

			$this->fill($items, $item->children, $level + 1);
		}
	}

}
