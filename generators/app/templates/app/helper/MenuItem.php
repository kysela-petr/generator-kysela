<?php

namespace App\Helper;

use Model\MenuTypeConst;
use Nette\Object;

/**
 * Helper pro praci s polozkami menu
 * @author Petr Hlavac
 */
class MenuItem extends Object
{

	/** @var array */
	protected $nonLinkItems = [
		MenuTypeConst::HTML,
		MenuTypeConst::SUBMENUHOLDER,
		MenuTypeConst::HEADING
	];

	/**
	 * Je tento typ odkaz?
	 * @param string $type
	 * @return bool
	 */
	public function isMenuItemLink($type)
	{
		return !in_array($type, $this->nonLinkItems, TRUE);
	}

	/**
	 * Je tento typ kontejner pro submenu?
	 * @param string $type
	 * @return bool
	 */
	public function isMenuItemSubmenuHolder($type)
	{
		return MenuTypeConst::SUBMENUHOLDER === $type;
	}

	/**
	 * Je tento typ nadpis?
	 * @param string $type
	 * @return bool
	 */
	public function isHeading($type)
	{
		return MenuTypeConst::HEADING === $type;
	}

}
