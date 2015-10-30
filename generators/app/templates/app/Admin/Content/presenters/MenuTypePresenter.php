<?php

namespace Admin\Content;

use Admin\DataFormPresenter;

class MenuTypePresenter extends DataFormPresenter
{

	/**
	 * @param \Admin\Content\IMenuTypeGridFactory $gridFactory
	 */
	public function __construct(IMenuTypeGridFactory $gridFactory)
	{
		parent::__construct();
		$this->gridFactory = $gridFactory;
	}

}
