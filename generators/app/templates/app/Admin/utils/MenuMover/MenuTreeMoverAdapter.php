<?php

namespace Admin;

use Nette\Object;

class MenuTreeMoverAdapter extends Object
{


	/** @var VerticalTreeMover */
	protected $verticalTreeMover;

	/** @var PriorityHorizontalTreeMover */
	protected $priorityHorizontalTreeMover;

	function __construct(VerticalTreeMover $verticalTreeMover, PriorityHorizontalTreeMover $priorityHorizontalTreeMover)
	{
		$this->verticalTreeMover = $verticalTreeMover;
		$this->priorityHorizontalTreeMover = $priorityHorizontalTreeMover;
	}

	public function up($id)
	{
		$this->verticalTreeMover->up($id);
	}

	public function down($id)
	{
		$this->verticalTreeMover->down($id);
	}

	public function left($id)
	{
		$this->priorityHorizontalTreeMover->left($id);
	}

	public function right($id)
	{
		$this->priorityHorizontalTreeMover->right($id);
	}

	public function moveTo($id, $parentId)
	{
		$this->priorityHorizontalTreeMover->moveTo($id, $parentId);
	}

}
