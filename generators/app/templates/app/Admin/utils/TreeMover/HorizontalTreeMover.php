<?php

namespace Admin;

/**
 * @author Petr Hlavac
 */
class HorizontalTreeMover extends TreeMoverBase {

	/** @var IHorizontalTreeModelAdapter */
	protected $treeModelAdapter;

	public function __construct(ITreeFactory $treeFactory, IHorizontalTreeModelAdapter $horizontalTreeModelAdapter) {
		parent::__construct($treeFactory);
		$this->treeModelAdapter = $horizontalTreeModelAdapter;
		$this->setShouldMoveToCallback(function($itemParentId, $parentId){
			return $itemParentId != $parentId;
		});
	}

	/**
	 * Presun na nizsi uroven zanoreni
	 * @param int $id
	 */
	public function left($id) {
		$finder = new NodeFinder($this->getTree());
		$parent = $finder->findParent($id);

		if ($parent) {
			$this->setParent($id, $parent['parentId']);
		}
	}

	/**
	 * Presun na vyssi uroven zanoreni (hloubeji)
	 * @param int $id
	 */
	public function right($id) {
		$finder = new NodeFinder($this->getTree());
		$previous = $finder->findPrevious($id);

		if ($previous) {
			$this->setParent($id, $previous['id']);
		}
	}

	/**
	 * Presun na konkretni pozici
	 * @param int $id
	 * @param int $parentId
	 */
	public function moveTo($id, $parentId) {
		$finder = new NodeFinder($this->getTree());
		$item = $finder->findCurrent($id);

		if (!$this->getTree()->isParent($id, $parentId) && $this->shouldMoveTo($item['parentId'], $parentId)) {
			$this->setParent($id, $parentId);
		}
	}

	/**
	 * @param int $itemParentId
	 * @param int $parentId
	 * @return boolean
	 */
	private function shouldMoveTo($itemParentId, $parentId){
		$callback = $this->shouldMoveToCallback;
		return $callback($itemParentId, $parentId);
	}

	/**
	 * @param callable $shouldMoveToCallback
	 * @prototype function($itemParentId, $parentId)
	 */
	function setShouldMoveToCallback($shouldMoveToCallback) {
		$this->shouldMoveToCallback = $shouldMoveToCallback;
	}

	/**
	 * @param int $id
	 * @param int $parentId
	 */
	protected function setParent($id, $parentId) {
		$this->treeModelAdapter->setParent($id, $parentId);
		$this->removeTree();
	}

}
