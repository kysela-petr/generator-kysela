<?php

namespace Model;

use Nette\Database\SqlLiteral;
use Nette\Database\Table\Selection;
use Nette\Object,
	Model\SimpleModel\IsSimpleFilter;

/**
 * @author Petr Hlavac
 */
class TokenFilter extends Object {

	use IsSimpleFilter;

	/** @var Filter\Factory */
	protected $filterFactory;

	function __construct($tableName) {
		$this->filterFactory = new Filter\Factory($tableName);
		$this->simpleFilterFactory = $this->filterFactory;
	}

	/**
	 * @param Selection $context
	 * @param $token
	 * @param string $via [optional]
	 * @return \Nette\Database\Table\Selection
	 */
	public function filterValid(Selection $context, $token, $via = '')
	{
		$nFilter = $this->filterFactory->createN($context);

		$nFilter->setup('token', $token)
			->via($via);

		$nFilter->setup('valid_to',  new SqlLiteral('DATE(NOW())'))
			->via($via)
			->operator('>=');

		return $nFilter->build();
	}

	/**
	 * @param Selection $context
	 * @param $token
	 * @param string $via [optional]
	 * @return \Nette\Database\Table\Selection
	 */
	public function filterToken(Selection $context, $token, $via = '')
	{
		return $this->filterFactory->create($context, 'token', $token)
			->via($via)
			->build();
	}

}
