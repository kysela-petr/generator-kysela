<?php

namespace Admin;

/**
 * @author Petr Hlavac
 */
class PriorityHorizontalTreeMover extends HorizontalTreeMover {

	public function __construct(ITreeFactory $treeFactory, IPriorityHorizontalModelAdapter $horizontalTreeModelAdapter) {
		parent::__construct($treeFactory, $horizontalTreeModelAdapter);
	}

	protected function setParent($id, $parentId) {
		$priority = $this->getMaxPriority($parentId);
		$this->treeModelAdapter->setPriority($id, $priority + 1);

		parent::setParent($id, $parentId);
	}

	/**
	 * @param int $id
	 * @return int
	 */
	protected function getMaxPriority($id) {
		if ($id) {
			$finder = new NodeFinder($this->getTree());
			$item = $finder->findCurrent($id);
			$array = $item['children'];
		} else {
			$array = $this->getTree()->getTree();
		}

		$count = count($array);

		if (!$count) {
			return 0;
		}

		return $array[$count - 1]['priority'];
	}

}
