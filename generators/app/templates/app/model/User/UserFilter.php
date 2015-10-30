<?php

namespace Model;

use Model\SimpleModel\IsSimpleFilter;
use Nette\Database\Table\Selection;
use Nette\Object;

/**
 * @author Petr Hlavac
 */
class UserFilter extends Object
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
	 * @param string $username
	 * @param string $via [optional]
	 * @return Selection
	 */
	public function filterUsername(Selection $context, $username, $via = '')
	{
		return $this->filterFactory->create($context, 'username', $username)
			->via($via)
			->build();
	}

	/**
	 * @param Selection $context
	 * @param string $email
	 * @param string $via [optional]
	 * @return Selection
	 */
	public function filterEmail(Selection $context, $email, $via = '')
	{
		return $this->filterFactory->create($context, 'email', $email)
			->via($via)
			->build();
	}

}
