<?php

namespace Model;

use Nette\Object;

/**
 * Konstanty pro urceni typu polozky hlavniho menu
 * Na zaklade techto konstant probiha generovani linku v menu.
 * @author Petr Hlavac
 */
class MenuTypeConst extends Object
{

	const
		SECTION = 'section',                    // Sekce webu
		PAGE = 'page',                            // Staticka stranka
		URL = 'url',                            // URL odkaz
		SUBMODULE = 'submodule',                // Submodul
		HTML = 'html',                            // HTML kod
		SUBMENUHOLDER = 'submenuholder',        // Holder submenu
		SYMLINK = 'symlink',                    // Link na dalsi polozku
		HEADING = 'heading',                    // Nadpis
		PRESENTER = 'presenter',                // Presenter
		SUBJECT = 'subject';                    // Subject
}
