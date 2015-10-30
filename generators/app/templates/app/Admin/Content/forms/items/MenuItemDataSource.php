<?php

namespace Admin\Content;

use Model\MenuFacade;
use Model\MenuFilter;
use Nette\Database\Table\Selection;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuItemDataSource extends Object
{

	/** @var MenuFacade */
	protected $menuFacade;

	/** @var MenuFilter */
	protected $menuFilter;

	function __construct(MenuFacade $menuFacade, MenuFilter $menuFilter)
	{
		$this->menuFacade = $menuFacade;
		$this->menuFilter = $menuFilter;
	}

	/**
	 * @param string $id
	 * @param string|callable $callback [optional]
	 * @return \Nette\Database\Table\ActiveRow|false
	 * @throws \Nette\InvalidArgumentException
	 */
	public function get($id, $callback = NULL)
	{
		$selection = $this->menuFilter->filterId($this->menuFacade->all(), $id);
		$this->apply($selection, $callback);

		return $selection->fetch();
	}

	/**
	 * @param \Nette\Database\Table\Selection $selection
	 * @param string|Callable $callback
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
