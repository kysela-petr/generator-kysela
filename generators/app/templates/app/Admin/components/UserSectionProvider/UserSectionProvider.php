<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin;

use App\Provider\IArrayProvider;
use Nette\Security\User;
use Model\ModuleFilter;
use Model\SectionFacade;
use Nette\Object;
use Model\SectionFilter as ModelSectionFilter;

class UserSectionProvider extends Object implements IArrayProvider
{

	/** @var \Model\SectionFacade */
	private $sectionFacade;

	/** @var \Model\ModuleFilter */
	private $moduleFilter;

	/** @var \Model\SectionFilter */
	private $sectionFilter;

	/** @var \Nette\Security\User */
	private $user;

	/**
	 * @param \Model\SectionFacade $sectionFacade
	 * @param \Model\ModuleFilter $moduleFilter
	 * @param \Model\SectionFilter $sectionFilter
	 * @param \Nette\Security\User $user
	 */
	function __construct(SectionFacade $sectionFacade, ModuleFilter $moduleFilter, ModelSectionFilter $sectionFilter, User $user)
	{

		$this->sectionFacade = $sectionFacade;
		$this->moduleFilter = $moduleFilter;
		$this->sectionFilter = $sectionFilter;
		$this->user = $user;
	}

	public function getData()
	{
		$selection = $this->sectionFacade->all();
		if (!$this->user->getIdentity()->super) {
			$this->sectionFilter->filterId($selection, $this->user->getIdentity()->sections);
		}

		return $selection->fetchPairs('id','id');
	}
}

interface IUserSectionProviderFactory
{
	/** @return \Admin\UserSectionProvider */
	public function create();
}
