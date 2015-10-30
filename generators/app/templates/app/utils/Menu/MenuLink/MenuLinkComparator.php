<?php

namespace App;

use Nette\Object;

/**
 * @author Petr Hlavac
 */
class MenuLinkComparator extends Object
{

	/** @var array */
	protected $aliases;

	/**
	 * @param string $currentLocation
	 * @param string $linkLocation
	 * @return boolean
	 */
	public function has($currentLocation, $linkLocation)
	{
		if ($this->isIn($currentLocation, $linkLocation)) {
			return TRUE;
		}

		return $this->isAlias($currentLocation, $linkLocation);
	}

	/**
	 * @param string $currentLocation
	 * @param string $linkLocation
	 * @return boolean
	 */
	protected function isAlias($currentLocation, $linkLocation)
	{
		if (!isset($this->aliases[$linkLocation])) {
			return FALSE;
		}

		foreach ($this->aliases[$linkLocation] as $alias) {
			if ($this->isIn($currentLocation, $alias)) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * @param string $currentLocation
	 * @param string $linkLocation
	 * @return boolean
	 */
	protected function isIn($currentLocation, $linkLocation)
	{
		return stripos($currentLocation, $linkLocation) === 0;
	}

	/**
	 * @param array $aliases
	 */
	function setAliases(array $aliases)
	{
		$this->aliases = $aliases;
	}

}
