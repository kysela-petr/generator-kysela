<?php

namespace Front;

use App\Presenters\BasePresenter;

class HomepagePresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}

}
