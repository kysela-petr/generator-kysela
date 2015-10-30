<?php

namespace Admin\Content;

use Admin\DataFormPresenter;

class ModulePresenter extends DataFormPresenter
{
	/**
	 * @param \Admin\Content\IModuleGridFactory $gridFactory
	 */
	public function __construct(IModuleGridFactory $gridFactory)
	{
		parent::__construct();
		$this->gridFactory = $gridFactory;
	}

}
