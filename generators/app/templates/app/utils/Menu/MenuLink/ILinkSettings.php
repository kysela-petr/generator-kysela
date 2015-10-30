<?php

namespace App;

interface ILinkSettings
{

	/**
	 * @return string|NULL
	 */
	public function getPresenter();

	/**
	 * @return array|NULL
	 */
	public function getParams();

	/**
	 * @return string
	 */
	public function getUrl();
}
