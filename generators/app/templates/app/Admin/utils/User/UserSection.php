<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin;

use Model\SectionFacade;
use Model\SectionFilter as ModelSectionFilter;
use Nette\Object;
use Nette\Security\User;

class UserSection extends Object
{
	/** @var \Model\SectionFacade */
	private $sectionFacade;

	/** @var \Model\SectionFilter */
	private $sectionFilter;

	/** @var \Nette\Security\User */
	private $user;

	/**
	 * @param \Model\SectionFacade $sectionFacade
	 * @param \Model\SectionFilter $sectionFilter
	 * @param \Nette\Security\User $user
	 */
	public function __construct(SectionFacade $sectionFacade, ModelSectionFilter $sectionFilter, User $user)
	{
		$this->sectionFacade = $sectionFacade;
		$this->sectionFilter = $sectionFilter;
		$this->user = $user;
	}

	/**
	 * @return \Nette\Database\Table\Selection
	 */
	public function getUserSection()
	{
		$selection = $this->sectionFacade->all();
		if (!$this->user->getIdentity()->super) {
			$this->sectionFilter->filterId($selection, $this->user->getIdentity()->sections);
		}

		return $selection;
	}

}

interface IUserSectionFactory
{
	/** @return \Admin\UserSection */
	public function create();
}
