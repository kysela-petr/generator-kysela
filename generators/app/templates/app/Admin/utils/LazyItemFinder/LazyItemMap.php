<?php

namespace Admin;

use Nette\InvalidArgumentException;
use Nette\Object;

/**
 * @author Petr Hlavac
 * Na zaklade typu hleda LazyItem
 */
class LazyItemMap extends Object
{

	/** @var ILazyItem[] */
	private $items;

	/**
	 * @param string $type
	 * @param \Admin\ILazyItem $item
	 */
	public function add($type, ILazyItem $item)
	{
		$this->items[$type] = $item;
	}

	/**
	 * @param string $key
	 * @return ILazyItem
	 * @throws InvalidArgumentException
	 */
	public function get($key)
	{
		if (!isset($this->items[$key])) {
			throw new InvalidArgumentException("Cannot find item for key $key!");
		}

		return $this->items[$key];
	}

}
