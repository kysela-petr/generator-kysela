<?php

namespace Model;

use Model\SimpleModel\IsSimpleFacade;
use Nette\Object;

/**
 * @author Generator
 */
class MenuFacade extends Object
{

	use IsSimpleFacade;

	/** @var MenuService */
	protected $menuService;

	/**
	 * @param \Model\MenuService $menuService
	 */
	function __construct(MenuService $menuService)
	{
		$this->menuService = $menuService;
		$this->simpleService = $this->menuService;
	}

	/**
	 * Nastavi polozce menu rodice
	 * @param int $id
	 * @param int|NULL $parentId
	 */
	public function setParent($id, $parentId)
	{
		$this->menuService->setParent($id, $parentId);
	}

	/**
	 * Nastavi polozce vazbu na sekci webu
	 * @param int $id
	 * @param int|NULL $sectionId
	 */
	public function setSection($id, $sectionId)
	{
		$this->menuService->setSection($id, $sectionId);
	}

	/**
	 * Nastavi polozce vazbu na submodul
	 * @param int $id
	 * @param string|NULL $submodule
	 */
	public function setSubmodule($id, $submodule)
	{
		$this->menuService->setSubmodule($id, $submodule);
	}

	/**
	 * Nastavi polozce vazbu na presenter
	 * @param int $id
	 * @param string|NULL $presenter
	 */
	public function setPresenter($id, $presenter)
	{
		$this->menuService->setPresenter($id, $presenter);
	}

	/**
	 * Nastavi polozce vazbu na statickou stranku
	 * @param int $id
	 * @param int|NULL $pageId
	 */
	public function setPage($id, $pageId)
	{
		$this->menuService->setPage($id, $pageId);
	}

	/**
	 * Nastavi polozce vazbu na subject
	 * @param int $id
	 * @param int|NULL $setId
	 */
	public function setSubject($id, $setId)
	{
		$this->menuService->setSubject($id, $setId);
	}


	/**
	 * Nastavi polozce vazbu na jinou polozku
	 * @param int $id
	 * @param int|NULL $symlinkId
	 */
	public function setSymlink($id, $symlinkId)
	{
		$this->menuService->setSymlink($id, $symlinkId);
	}
}
