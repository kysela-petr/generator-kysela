<?php

namespace Admin\Content;

use Admin\BaseGrid;
use Model\MenuFacade;
use Admin\MenuTreeFactory;
use Nette\Utils\Strings;
use Admin\TreeToArray;

/**
 * Datagrid pro zobrazeni kategorii
 * @author Petr Hlavac
 */
class MenuGrid extends BaseGrid
{

	/** @var int */
	protected $sectionId;

	/** @var MenuFacade */
	protected $menuFacade;

	/** @var MenuTreeFactory */
	protected $menuTreeFactory;

	/** @var array */
	public $onClickLeft = [];

	/** @var array */
	public $onClickRight = [];

	/** @var array */
	public $onClickUp = [];

	/** @var array */
	public $onClickDown = [];

	/** @var array */
	protected $items;

	function __construct($sectionId, MenuFacade $menuFacade, MenuTreeFactory $menuTreeFactory)
	{
		parent::__construct();
		$this->sectionId = $sectionId;
		$this->menuFacade = $menuFacade;
		$this->menuTreeFactory = $menuTreeFactory;
	}

	protected function createGrid()
	{
		$context = $this->getItems();

		$grid = $this->createPreparedGrid();
		$grid->setModel($context);

		$grid->addColumnNumber('id', 'ID')
			->setSortable()
			->setFilterNumber();

		$grid->addColumnText('name', 'Název')
			->setCustomRender(function ($row) {
				return Strings::indent($row['name'], $row['level'] * 5, '&nbsp;');
			})
			->setSortable()
			->setFilterText();

		$grid->addColumnText('type_name', 'Typ');

		$activeCol = $grid->addColumnText('show', 'Zobrazit');
		$this->helpers->setupAsBool($activeCol);

		$grid->addActionEvent('left', '<', $this->clickLeft);
		$grid->addActionEvent('right', '>', $this->clickRight);
		$grid->addActionEvent('up', '/\\', $this->clickUp);
		$grid->addActionEvent('down', '\\/', $this->clickDown);

		$grid->addActionHref('add', 'Add');
		$this->helpers->addEditAction($grid);
		$this->helpers->addDeleteEvent($grid, $this->deleteRow);

		return $grid;
	}

	public function delete($id)
	{
		$this->menuFacade->delete($id);
	}

	public function clickLeft($id)
	{
		$this->onClickLeft($id, $this->sectionId);
	}

	public function clickRight($id)
	{
		$this->onClickRight($id, $this->sectionId);
	}

	public function clickUp($id)
	{
		$this->onClickUp($id, $this->sectionId);
	}

	public function clickDown($id)
	{
		$this->onClickDown($id, $this->sectionId);
	}

	/**
	 * @return array
	 */
	protected function getItems()
	{
		if (!$this->items) {
			$this->items = $this->createItems();
		}

		return $this->items;
	}

	/**
	 * @return array
	 */
	protected function createItems()
	{
		$tree = $this->menuTreeFactory->create($this->sectionId);
		$tree->onNodeCreated[] = function ($item, $data) {
			$item->name = $data['name'];
			$item->show = $data['show'];
			$item->type_name = $data['type_name'];
		};

		$convertor = new TreeToArray;
		$convertor->onItemAdd[] = function ($item, $level) {
			$item['level'] = $level;
		};

		return $convertor->convert($tree);
	}

}

interface IMenuGridFactory
{

	/**
	 * @param int $sectionId
	 * @return MenuGrid
	 */
	public function create($sectionId);
}
