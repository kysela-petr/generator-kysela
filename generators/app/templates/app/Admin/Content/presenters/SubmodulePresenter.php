<?php

namespace Admin\Content;

use Admin\DataFormPresenter;

class SubmodulePresenter extends DataFormPresenter
{

	public function __construct(ISubmoduleGridFactory $gridFactory)
	{
		parent::__construct();
		$this->gridFactory = $gridFactory;
	}

}
