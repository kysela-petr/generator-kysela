<?php

namespace App;

use Esports\Utils\PresenterBridge;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class PresenterLinkSettings extends Object implements ILinkSettings
{

	/** @var string */
	private $destination;

	/** @var array */
	private $params;

	/** @var PresenterBridge */
	private $presenterBridge;

	/**
	 * @param $destination
	 * @param $params
	 * @param \Esports\Utils\PresenterBridge $presenterBridge
	 */
	function __construct($destination, $params, PresenterBridge $presenterBridge)
	{
		$this->destination = $destination;
		$this->params = $params;
		$this->presenterBridge = $presenterBridge;
	}

	/**
	 * @return string
	 */
	function getPresenter()
	{
		return $this->destination;
	}

	/**
	 * @return array
	 */
	function getParams()
	{
		return $this->params;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->presenterBridge->getPresenter()->link($this->getPresenter(), $this->getParams());
	}

}

interface IPresenterLinkSettingsFactory
{

	/**
	 * @param string $destination
	 * @param array $params
	 * @return PresenterLinkSettings
	 */
	public function create($destination, $params);
}
