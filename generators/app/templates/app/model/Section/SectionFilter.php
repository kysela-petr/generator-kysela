<?php

namespace Model;

use Model\SimpleModel\IsSimpleFilter;
use Nette\Database\SqlLiteral;
use Nette\Database\Table\Selection;
use Nette\Object;


class SectionFilter extends Object
{

	use IsSimpleFilter;

	/** @var Filter\Factory */
	protected $filterFactory;

	function __construct($tableName)
	{
		$this->filterFactory = new Filter\Factory($tableName);
		$this->simpleFilterFactory = $this->filterFactory;
	}

	/**
	 * @param Selection $context
	 * @param int|int[] $id
	 * @param string $via [optional]
	 * @return Selection
	 */
	public function filterIdIsNot(Selection $context, $id, $via = '')
	{
		$filter = $this->filterFactory->create($context, 'id', $id)
			->via($via);

		if (is_array($id)) {
			$filter->operator('NOT IN');
		} else {
			$filter->operator('!=');
		}

		return $filter->build();
	}

	/**
	 * @param \Nette\Database\Table\Selection $context
	 * @param string $module
	 * @param string $via
	 * @return \Nette\Database\Table\Selection
	 */
	public function filterModule(Selection $context, $module, $via = '')
	{
		return $this->filterFactory->create($context, 'module', $module)
			->via($via)
			->build();
	}

	/**
	 * @param \Nette\Database\Table\Selection $context
	 * @param string $module
	 * @param string $via
	 * @return \Nette\Database\Table\Selection
	 */
	public function filterNotHomeSection(Selection $context, $module, $via = '')
	{
		return $this->filterFactory->create($context, 'module', $module)
			->operator('!=')
			->via($via)
			->build();
	}

	/**
	 * @param \Nette\Database\Table\Selection $selection
	 * @param $column
	 * @param $word
	 * @param string $via
	 * @return \Nette\Database\Table\Selection
	 */
	public function filterDomainSearch(Selection $selection, $column, $word, $via = '')
	{
		return $selection->where("? LIKE ?", new SqlLiteral($column), "$word%");
	}
}
