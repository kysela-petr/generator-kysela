<?php

namespace App;

use Model\MenuFilter;
use Model\MenuProvider;
use Nette\Database\Table\Selection;
use Nette\InvalidArgumentException;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuItemDataSource extends Object
{

	/** @var MenuProvider */
	protected $menuProvider;

	/** @var MenuFilter */
	protected $menuFilter;

	/**
	 * @param \Model\MenuProvider $menuProvider
	 * @param \Model\MenuFilter $menuFilter
	 */
	function __construct(MenuProvider $menuProvider, MenuFilter $menuFilter)
	{
		$this->menuProvider = $menuProvider;
		$this->menuFilter = $menuFilter;
	}

	/**
	 * @param string $id
	 * @param string|callable $callback [optional]
	 * @return \Nette\Database\Table\ActiveRow
	 * @throws InvalidArgumentException
	 */
	public function get($id, $callback = NULL)
	{
		$selection = $this->menuFilter->filterId($this->menuProvider->all(), $id);
		$this->apply($selection, $callback);
		$item = $selection->fetch();

		if (!$item) {
			throw new InvalidArgumentException;
		}

		return $item;
	}

	/**
	 * @param \Nette\Database\Table\Selection $selection
	 * @param string|Callable $callback
	 * @return \Nette\Database\Table\Selection
	 */
	private function apply(Selection $selection, $callback)
	{
		if (!$callback) {
			return;
		}
		if (is_string($callback)) {
			$selection->select($callback);
		} else {
			$callback($selection);
		}
	}

}
