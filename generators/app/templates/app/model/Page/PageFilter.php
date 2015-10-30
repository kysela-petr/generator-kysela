<?php

namespace Model;

use Nette\Database\Table\Selection;
use Nette\Object,
	Model\SimpleModel\IsSimpleFilter;

/**
 * @author Petr Hlavac
 */
class PageFilter extends Object {

	use IsSimpleFilter;

	/** @var Filter\Factory */
	protected $filterFactory;

	/** @var string */
	protected $sectionTableName;

	function __construct($tableName, $sectionTableName)
	{
		$this->filterFactory = new Filter\Factory($tableName);
		$this->simpleFilterFactory = $this->filterFactory;
		$this->sectionTableName = $sectionTableName;
	}

	/**
	 * @param Selection $context
	 * @param int $sectionId
	 * @param string $via [optional]
	 * @return Selection
	 */
	public function filterSection(Selection $context, $sectionId, $via = '')
	{
		$context->alias($this->sectionTableName, 'alternate_section');

		$filterFactory = new Filter\Factory($this->sectionTableName);
		$nFilter = $filterFactory->createN($context);

		$nFilter->setup('id', $sectionId)
			->via("$via:page_has_section");

		$nFilter->setup('id', $sectionId, 'alternate_section')
			->via($via);

		return $nFilter->build();
	}

	/**
	 * @param Selection $context
	 * @param boolean $show
	 * @param string $via [optional]
	 * @return Selection
	 */
	public function filterShow(Selection $context, $show, $via = '')
	{
		return $this->filterFactory->create($context, 'show', $show)
			->via($via)
			->build();
	}
}
