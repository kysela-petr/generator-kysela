<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin;

use Model\SectionFacade;
use Model\SectionFilter as ModelSectionFilter;
use Nette\Application\UI\ITemplate;
use Nette\Database\Table\Selection;
use Nette\Security\User;

class UserSectionView extends Component
{

	/** @var \Model\SectionFacade */
	private $sectionFacade;

	/** @var \Admin\SectionOrderer */
	private $sectionOrderer;

	/** @var \Model\SectionFilter */
	private $sectionFilter;

	/** @var \Nette\Security\User */
	private $user;

	/**
	 * @param \Model\SectionFacade $sectionFacade
	 * @param \Admin\SectionOrderer $sectionOrderer
	 * @param \Model\SectionFilter $sectionFilter
	 * @param \Nette\Security\User $user
	 */
	function __construct(SectionFacade $sectionFacade, SectionOrderer $sectionOrderer, ModelSectionFilter $sectionFilter, User $user)
	{
		$this->sectionFacade = $sectionFacade;
		$this->sectionOrderer = $sectionOrderer;
		$this->sectionFilter = $sectionFilter;
		$this->user = $user;
	}

	/**
	 * @return \Nette\Database\Table\Selection
	 */
	protected function getData()
	{
		$selection = $this->sectionFacade->all();
		if (!$this->user->getIdentity()->super) {
			$this->sectionFilter->filterId($selection, $this->user->getIdentity()->sections);
		}

		return $selection;
	}

	/**
	 * @return \Nette\Database\Table\Selection
	 */
	protected function allSections()
	{
		return $this->sectionOrderer->order($this->sectionFacade->all());
	}

	/**
	 * @param \Nette\Application\UI\ITemplate $template
	 */
	protected function prepareTemplate(ITemplate $template)
	{
		parent::prepareTemplate($template);

		$template->setFile(__DIR__ . '/userSection.latte');
		$template->data = $this->allSections();
		$template->userData = $this->getData();
	}

}

interface IUserSectionViewFactory
{
	/**
	 * @return UserSectionView
	 */
	public function create();
}
