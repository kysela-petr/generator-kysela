<?php

namespace Admin;

/**
 * @author Petr Hlavac
 */
class VerticalTreeMover extends TreeMoverBase {

	/**
	 * @var IVerticalTreeModelAdapter
	 */
	protected $verticalTreeModelAdapter;

	public function __construct(ITreeFactory $treeFactory, IVerticalTreeModelAdapter $verticalTreeModelAdapter) {
		parent::__construct($treeFactory);
		$this->verticalTreeModelAdapter = $verticalTreeModelAdapter;
	}

	/**
	 * Presun na vyssi pozici
	 * @param int $id
	 */
	public function up($id) {
		$finder = new NodeFinder($this->getTree());

		$current = $finder->findCurrent($id);
		$previous = $finder->findPrevious($id);

		if ($previous) {
			$this->swapDirection($previous, $current);
		} else {
			$last = $finder->findLast($id);
			$this->verticalTreeModelAdapter->setPriority($current['id'], $last['priority'] + 1);
		}
	}

	/**
	 * Presun na nizsi pozici
	 * @param int $id
	 */
	public function down($id) {
		$finder = new NodeFinder($this->getTree());

		$current = $finder->findCurrent($id);
		$next = $finder->findNext($id);

		if ($next) {
			$this->swapDirection($next, $current);
		} else {
			$first = $finder->findFirst($id);
			$this->verticalTreeModelAdapter->setPriority($current['id'], $first['priority'] - 1);
		}
	}

	/**
	 * @param \Nette\Utils\ArrayHash $item1
	 * @param \Nette\Utils\ArrayHash $item2
	 */
	protected function swapDirection($item1, $item2) {
		$this->verticalTreeModelAdapter->setPriority($item1['id'], $item2['priority']);
		$this->verticalTreeModelAdapter->setPriority($item2['id'], $item1['priority']);
	}

}
