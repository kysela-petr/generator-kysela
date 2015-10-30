<?php

namespace Admin;

use Nette\Localization\ITranslator;

class Translator implements ITranslator
{

	public function translate($message, $count = 0)
	{
		return $message;
	}

}
