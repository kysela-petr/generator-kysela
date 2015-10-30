<?php

namespace Admin;

use Grido\Components\Filters\Filter;
use Nette\Application\UI\ITemplate;
use Nette\ComponentModel\IComponent;
use Nette\Utils\Html;

abstract class BaseGrid extends Component
{

	/** @var boolean Pamatovat si stav gridu */
	protected $rememberState = TRUE;

	/** @var callable[] */
	public $onDelete = [];

	/** @var callable[] */
	public $onDataChange = [];

	/** @var GridHelpers */
	protected $helpers;

	/** @var string */
	protected $gridName = 'grid';

	/** @var \Model\SectionFilter */
	protected $sectionFilter;

	/**
	 * Vytvori instanci gridu
	 * @return \Admin\Grid
	 */
	abstract protected function createGrid();

	/**
	 * @return \Admin\Grid
	 */
	protected function createPreparedGrid()
	{
		$grid = new Grid;
		$grid->translator->setLang('cs');
		$grid->setFilterRenderType(Filter::RENDER_INNER);

		return $grid;
	}

	/**
	 * @return \Admin\Grid
	 */
	public function createComponentGrid()
	{
		$grid = $this->createGrid();
		$grid->setFilterRenderType(Filter::RENDER_INNER);

		return $grid;
	}

	/**
	 * Nastavi helpery a translator pro grid i vlastni sablonu
	 * @param \Nette\Application\UI\ITemplate $template
	 */
	protected function prepareTemplate(ITemplate $template)
	{
		parent::prepareTemplate($template);
		$template->gridName = $this->gridName;
		$template->setFile(__DIR__ . '/BaseGrid.latte');
	}

	/**
	 * @see parent
	 * @param IComponent $presenter
	 */
	protected function attached($presenter)
	{
		parent::attached($presenter);

		$this[$this->gridName]->setRememberState($this->rememberState);
	}

	/**
	 * Smaze aktualni radek
	 * @param int $primaryKeyValue
	 */
	public function deleteRow($primaryKeyValue)
	{
		$this->onDelete($primaryKeyValue);
	}

	/**
	 * Custom renderer pro odkaz.
	 * @param string $destination
	 * @param string| [] $args
	 * @param string $text
	 * @return Html
	 */
	public function customRenderLink($destination, $args, $text)
	{
		return Html::el('a')->href($this->presenter->link($destination, $args))->setText($text);
	}

	// ---------------------- SETS & GETS -----------------------------

	/**
	 * Vrati cestu k sablonam pro vlastni vykresleni
	 * @return string
	 */
	protected function getRendererDir()
	{
		return __DIR__;
	}

	/**
	 * Vraci cestu k vykreslovaci sablone
	 * @param string $name
	 * @return string
	 */
	protected function getTemplateRenderer($name)
	{
		return $this->getRendererDir() . "/$name.latte";
	}

	/**
	 * Pamatovat si stav gridu?
	 * @param bool $rememberState
	 */
	protected function setRememberState($rememberState)
	{
		$this->rememberState = $rememberState;
	}

	/**
	 * @param \Admin\GridHelpers $helpers
	 */
	function setHelpers(GridHelpers $helpers)
	{
		$this->helpers = $helpers;
	}

	/**
	 * @param \Model\SectionFilter $sectionFilter
	 */
	public function setSectionFilter(\Model\SectionFilter $sectionFilter)
	{
		$this->sectionFilter = $sectionFilter;
	}
}
