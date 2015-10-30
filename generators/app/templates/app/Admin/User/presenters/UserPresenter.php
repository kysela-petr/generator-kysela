<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin\User;

use Admin\DataFormPresenter;

class UserPresenter extends DataFormPresenter
{
	/**
	 * @param \Admin\User\IUserGridFactory $gridFactory
	 * @param \Admin\User\IUserFormFactory $formFactory
	 */
	public function __construct(IUserGridFactory $gridFactory, IUserFormFactory $formFactory)
	{
		parent::__construct();
		$this->gridFactory = $gridFactory;
		$this->formFactory = $formFactory;
	}

	public function renderDefault()
	{
		$this->simpleList('Vytvořit nového uživatele');
	}

}
