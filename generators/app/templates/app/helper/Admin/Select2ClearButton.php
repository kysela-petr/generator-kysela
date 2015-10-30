<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin\Helpers;

use Nette\Utils\Html;

class Select2ClearButton
{
	/**
	 * @return \Nette\Utils\Html
	 */
	public static function createButton()
	{
		$el = Html::el('button', [
			'type' => 'button',
			'class' => 'btn btn-default btn-xs pull-right select2-clear'
		]);
		$el->setText('Smazat');

		return $el;
	}
}
