/**
 * Funcionalita tlačítka pro reset multiselectu, kde je povolen jen jeden prvek
 * Created by mk on 13.10.15.
 */

/*jslint browser: true*/
/*global $, jQuery*/

$(function () {
	'use strict';

	$(document).on('click', '.select2-clear', function () {
		var parent = $(this).closest('.form-group');
		var select = parent.find('.select2-chosen');
		parent.find('input[type="hidden"]').val('');
		select.html('');
		select.trigger("change");
	});
});