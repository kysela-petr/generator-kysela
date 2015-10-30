<?php

namespace Model;

use Nette\Object,
	Model\SimpleModel\IsSimpleService;

/**
 * @author Generator
 */
class SectionService extends Object
{

	use IsSimpleService;

	/** @var SectionRepository */
	protected $sectionRepository;

	/** @var \Model\MenuTypeRepository */
	private $menuTypeRepository;

	/** @var \Model\SectionFilter */
	private $sectionFilter;

	public function __construct(SectionRepository $sectionRepository, MenuTypeRepository $menuTypeRepository, SectionFilter $sectionFilter)
	{
		$this->sectionRepository = $sectionRepository;
		$this->simpleRepository = $this->sectionRepository;
		$this->menuTypeRepository = $menuTypeRepository;
		$this->sectionFilter = $sectionFilter;
	}

	/**
	 * @param int $id
	 * @param array $types
	 */
	public function setMenuType($id, $types)
	{
		$currentTypes = $this->getMenuType($id)
			->fetchPairs('code', 'code');
		$this->sectionRepository->reassignMenuType($id, $types, $currentTypes);
	}

	/**
	 * @param int $id
	 * @return \Nette\Database\Table\Selection
	 */
	public function getMenuType($id)
	{
		$context = $this->menuTypeRepository->all();
		return $this->sectionFilter->filterId($context, $id, ':section_has_menu_type');
	}
}
