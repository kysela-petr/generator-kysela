<?php

namespace App;

use Nette\InvalidArgumentException;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuLinkMap extends Object
{

	/** @var IMenuLink[] */
	private $items;

	/**
	 * @param string $type
	 * @param IMenuLink $item
	 */
	public function add($type, IMenuLink $item)
	{
		$this->items[$type] = $item;
	}

	/**
	 * @param string $key
	 * @return IMenuLink
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
