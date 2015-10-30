<?php

namespace App;

use Nette\Object;

/**
 * @author Petr Hlavac
 */
class UrlLinkSettings extends Object implements ILinkSettings
{

	/** @var string */
	private $url;

	function __construct($url)
	{
		$this->url = $url;
	}

	/**
	 * @return null
	 */
	function getPresenter()
	{
		return NULL;
	}

	/**
	 * @return null
	 */
	function getParams()
	{
		return NULL;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

}
