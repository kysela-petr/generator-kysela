<?php

namespace Admin;

class Component extends \App\Component\Component
{
	protected function redirectThis()
	{
		$this->redirect('this');
	}
}
