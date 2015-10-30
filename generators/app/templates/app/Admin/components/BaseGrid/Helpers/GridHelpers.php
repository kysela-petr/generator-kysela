<?php

namespace Admin;

use Nette\Utils\Arrays;
use Nette\Utils\Html;

/**
 * Pomocnici pri tvoreni gridu
 *
 * @author ondra
 */
class GridHelpers extends \Nette\Object
{

	/**
	 *
	 * @var \Esports\Helper\Loader
	 */
	private $helperLoader;

	/** @var array */
	protected $yesNoArray = [
		'' => '',
		'1' => 'ANO',
		'0' => 'ne'
	];

	function __construct(\Esports\Helper\Loader $helperLoader)
	{
		$this->helperLoader = $helperLoader;
	}

	/**
	 * Prida filtr na datum
	 * @param \Grido\Components\Columns\Column $column
	 * @param string $dbColumnName
	 * @return \Grido\Components\Filters\Text
	 */
	public function addDateFilter(\Grido\Components\Columns\Column $column, $dbColumnName)
	{
		$filter = $column->setFilterDate();
		$filter->setWhere(function ($date, $selection) use ($dbColumnName) {
			if ($date) {
				$selection->where("DATE($dbColumnName)", $date);
			}
		})
			->getControl()
			->setAttribute('data-dateinput-type', 'date')
			->getControlPrototype()
			->class[] = 'dateinput-buttons';

		return $filter;
	}

	/**
	 * @param \Grido\Components\Columns\Column $column
	 * @return \Grido\Components\Columns\Column
	 */
	public function setupAsBool(\Grido\Components\Columns\Column $column)
	{
		$col = $column->getColumn();

		$column->setSortable();
		$column->setFilterSelect($this->yesNoArray);
		$column->setCustomRender(function ($row) use ($col) {
			if (!isset($this->yesNoArray[$row[$col]])) {
				return NULL;
			}

			return $this->yesNoArray[$row[$col]];
		});

		return $column;
	}

	/**
	 * @param \Grido\Components\Columns\Column $column
	 * @param Callable $recordCallback
	 * @return \Grido\Components\Columns\Column
	 */
	public function setupAsMultirecord(\Grido\Components\Columns\Column $column, $recordCallback)
	{
		$column->getCellPrototype()->class[] = 'multirecord';

		$column->setCustomRender(function ($row) use ($recordCallback) {
			$return = Html::el('ul', ['class' => 'select2-choices']);
			foreach ($recordCallback($row) as $item) {
				$li = Html::el('li', ['class' => 'select2-search-choice']);
				$li->create('div', $item);

				$return->add($li);
			}

			return $return;
		});

		return $column;
	}

	/**
	 * Nastavi prvni prazdnou hodnotu do pole
	 * @param array $array
	 * @return array
	 */
	public function addPrompt($array)
	{
		return Arrays::mergeTree(['' => ''], $array);
	}

	/**
	 * @param \Admin\Grid $grid
	 * @param $onClick
	 * @param string $confirmName
	 * @return \Grido\Components\Actions\Event
	 * @throws \Exception
	 */
	public function addDeleteEvent(Grid $grid, $onClick, $confirmName = 'name')
	{
		$return = $grid->addActionEvent('delete', 'Smazat', $onClick);
		$return->setIcon('trash-o')
			->getElementPrototype()->class = 'btn-danger';

		$return->setConfirm(function ($row) use ($confirmName) {
			if (is_callable($confirmName)) {
				$replaceString = $confirmName($row);
			} else {
				$replaceString = $row->$confirmName;
			}

			return ["Opravdu chcete smazat '%s'?", $replaceString];
		});

		return $return;
	}

	/**
	 * @param \Grido\Grid $grid
	 * @return \Grido\Components\Actions\Href
	 * @throws \Exception
	 */
	public function addEditAction(Grid $grid)
	{
		$return = $grid->addActionHref('edit', 'Editovat');
		$return->setIcon('pencil')
			->getElementPrototype()->class = 'btn-dark-blue';

		return $return;
	}

	/**
	 * @return array
	 */
	public function getYesNoArray()
	{
		return $this->yesNoArray;
	}

}
