<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleService;

/**
 * @author Generator
 */
class MenuService extends Object
{

	use IsSimpleService;

	/** @var MenuRepository */
	protected $menuRepository;

	/**
	 * @param \Model\MenuRepository $menuRepository
	 */
	public function __construct(MenuRepository $menuRepository)
	{
		$this->menuRepository = $menuRepository;
		$this->simpleRepository = $this->menuRepository;
	}

	/**
	 * Nastavi polozce menu rodice
	 * @param int $id
	 * @param int|NULL $parentId
	 */
	public function setParent($id, $parentId)
	{
		if ($id == $parentId) {
			return;
		}

		$this->setItemValue($id, $parentId, 'menu');
	}

	/**
	 * Nastavi polozce vazbu na sekci webu
	 * @param int $id
	 * @param int|NULL $sectionId
	 */
	public function setSection($id, $sectionId)
	{
		$this->setItemValue($id, $sectionId, 'section');
	}

	/**
	 * Nastavi polozce vazbu na submodul
	 * @param int $id
	 * @param string|NULL $submodule
	 */
	public function setSubmodule($id, $submodule)
	{
		$this->setItemValue($id, $submodule, 'submodule');
	}

	/**
	 * Nastavi polozce vazbu na presenter
	 * @param int $id
	 * @param string|NULL $presenter
	 */
	public function setPresenter($id, $presenter)
	{
		$this->setItemValue($id, $presenter, 'presenter');
	}

	/**
	 * Nastavi polozce vazbu na statickou stranku
	 * @param int $id
	 * @param int|NULL $pageId
	 */
	public function setPage($id, $pageId)
	{
		$this->setItemValue($id, $pageId, 'page');
	}

	/**
	 * Nastavi polozce vazbu na subject
	 * @param int $id
	 * @param int|NULL $setId
	 */
	public function setSubject($id, $setId)
	{
		$this->setItemValue($id, $setId, 'subject');
	}

	/**
	 * Nastavi polozce vazbu na jinou polozku
	 * @param int $id
	 * @param int|NULL $symlinkId
	 */
	public function setSymlink($id, $symlinkId)
	{
		$this->setItemValue($id, $symlinkId, 'symlink');
	}

	/**
	 * @param int $id
	 * @param mixed $itemId
	 * @param string $itemName
	 */
	protected function setItemValue($id, $itemId, $itemName)
	{
		$actionName = ucfirst($itemName);

		if ($itemId !== NULL) {
			$this->menuRepository->{"assign$actionName"}($id, $itemId);
		} else {
			$this->menuRepository->{"remove$actionName"}($id);
		}
	}

}
