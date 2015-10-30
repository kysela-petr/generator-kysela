<?php

namespace Model;

use Model\SimpleModel\IsSimpleFilter;
use Nette\Database\Table\Selection;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class ModuleFilter extends Object
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
	 * @param string $module
	 * @param string $via [optional]
	 * @return Selection
	 */
	public function filterNotModule(Selection $context, $module, $via = '')
	{
		return $this->filterFactory->create($context, 'code', $module)
			->operator('!=')
			->via($via)
			->build();
	}
	/**
	 * @param Selection $context
	 * @param string $module
	 * @param string $via [optional]
	 * @return Selection
	 */
	public function filterCode(Selection $context, $module, $via = '')
	{
		return $this->filterFactory->create($context, 'code', $module)
			->via($via)
			->build();
	}

}
