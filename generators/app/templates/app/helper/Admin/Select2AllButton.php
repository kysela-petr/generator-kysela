<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin\Helpers;

use Nette\Utils\Html;

class Select2AllButton
{
	/**
	 * @return \Nette\Utils\Html
	 */
	public static function createButton()
	{
		$el = Html::el('button', [
			'type' => 'button',
			'class' => 'btn btn-default btn-xs pull-right select2-all'
		]);
		$el->setText('Všechny sekce');

		return $el;
	}
}
