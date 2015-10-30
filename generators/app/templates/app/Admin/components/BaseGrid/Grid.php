<?php

namespace Admin;


use Nette\InvalidArgumentException;

class Grid extends \Grido\Grid
{

	protected function applyFiltering()
	{
		try {
			parent::applyFiltering();
		} catch (InvalidArgumentException $e) {
			$this->filter = [];
			$this->setDefaultFilter([]);

			if ($session = $this->getRememberSession())
				$session->remove();
		}
	}

	/**
	 * PROVIZORNE dokud nebude opraveno primo v grido
	 * @see parent
	 */
	public function getRememberSession($forceStart = FALSE)
	{
		$presenter = $this->getPresenter();
		$session = $presenter->getSession();
		$parent = $this->getParent();

		if (!$session->isStarted() && $forceStart) {
			$session->start();
		}

		return $session->isStarted() ?
			$session->getSection($presenter->getName() . ($parent != $presenter ? ('\\' . ucfirst($parent->name)) : '') . '\\' . ucfirst($this->getName())) : NULL;
	}

}
