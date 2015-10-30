<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin;


class UserSectionChecker
{
	/** @var [] */
	private $sectionIds = [];

	/** @var \Admin\IUserSectionFactory */
	private $userSectionFactory;

	/**
	 * @param \Admin\IUserSectionFactory $userSectionFactory
	 */
	public function __construct(IUserSectionFactory $userSectionFactory)
	{
		$this->userSectionFactory = $userSectionFactory;
	}

	/**
	 * @param int $sectionId
	 * @return bool
	 */
	public function hasSection($sectionId)
	{
		$sections = $this->getSectionIds();

		return isset($sections[$sectionId]) ? TRUE : FALSE;
	}

	/**
	 * @return array
	 */
	private function getSectionIds()
	{
		if (!$this->sectionIds) {
			$this->sectionIds = $this->userSectionFactory->create()->getUserSection()->fetchPairs('id', 'id');
		}

		return $this->sectionIds;
	}

}
