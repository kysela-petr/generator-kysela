<?php

namespace Admin\Content;

use Admin\DataFormPresenter;

class PresenterPresenter extends DataFormPresenter
{

	public function __construct(IPresenterGridFactory $gridFactory)
	{
		parent::__construct();
		$this->gridFactory = $gridFactory;
	}

}
