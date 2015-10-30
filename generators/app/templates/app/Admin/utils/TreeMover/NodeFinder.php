<?php

namespace Admin;

use Nette\Object,
		Esports\Utils\Tree;

/**
 * @author Petr Hlavac
 */
class NodeFinder extends Object {

	/** @var Tree */
	protected $tree;

	function __construct(Tree $tree) {
		$this->tree = $tree;
	}

	/**
	 * @param int $id
	 * @return \Nette\Utils\ArrayHash
	 * @throws \Nette\InvalidStateException
	 */
	public function findCurrent($id) {
		$map = $this->tree->getMap();

		if (!isset($map[$id])) {
			throw new \Nette\InvalidStateException;
		}

		return $map[$id];
	}

	/**
	 * @param int $id
	 * @return \Nette\Utils\ArrayHash|NULL
	 * @throws \Nette\InvalidStateException
	 */
	public function findPrevious($id) {
		$branch = $this->findBranch($id);
		$previous = NULL;

		foreach ($branch as $item) {
			if ($item['id'] == $id) {
				break;
			}

			$previous = $item;
		}

		return $previous;
	}

	/**
	 * @param int $id
	 * @return \Nette\Utils\ArrayHash|NULL
	 * @throws \Nette\InvalidStateException
	 */
	public function findNext($id) {
		$branch = $this->findBranch($id);
		$next = NULL;

		$max = count($branch);

		for ($i = $max - 1; $i > 0; $i--) {
			if ($branch[$i]['id'] == $id) {
				break;
			}

			$next = $branch[$i];
		}

		return $next;
	}

	/**
	 * @param int $id
	 * @return \Nette\Utils\ArrayHash|NULL
	 * @throws \Nette\InvalidStateException
	 */
	public function findParent($id) {
		$item = $this->findCurrent($id);

		if (!$item['parentId']) {
			return NULL;
		}

		return $this->findCurrent($item['parentId']);
	}

	/**
	 * @param int $id
	 * @return array
	 * @throws \Nette\InvalidStateException
	 */
	public function findBranch($id) {
		$item = $this->findCurrent($id);

		if ($item['parentId']) {
			return $this->findCurrent($item['parentId'])['children'];
		} else {
			return $this->tree->getTree();
		}
	}

	/**
	 * @param int $id
	 * @return \Nette\Utils\ArrayHash
	 * @throws \Nette\InvalidStateException
	 */
	public function findFirst($id) {
		$branch = $this->findBranch($id);
		return $branch[0];
	}

	/**
	 * @param int $id
	 * @return \Nette\Utils\ArrayHash
	 * @throws \Nette\InvalidStateException
	 */
	public function findLast($id) {
		$branch = $this->findBranch($id);
		return $branch[count($branch) - 1];
	}

}
