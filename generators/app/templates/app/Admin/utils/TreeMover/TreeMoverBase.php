<?php

namespace Admin;

use Nette\Object;

/**
 * @author Petr Hlavac
 */
class TreeMoverBase extends Object {

	/** @var Esports\Utils\Tree */
	private $tree;

	/** @var ITreeFactory */
	private $treeFactory;

	function __construct(ITreeFactory $treeFactory) {
		$this->treeFactory = $treeFactory;
	}

	/**
	 * @return Esports\Utils\Tree
	 */
	protected function getTree() {
		if (!$this->tree) {
			$this->tree = $this->treeFactory->create();
		}

		return $this->tree;
	}

	/**
	 * @return void
	 */
	protected function removeTree() {
		$this->tree = NULL;
	}

}
