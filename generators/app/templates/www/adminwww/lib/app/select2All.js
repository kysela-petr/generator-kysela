/**
 * Funcionalita tlačítka pro výběr všech prvků v select2
 * Created by mk on 1.7.15.
 */

/*jslint browser: true*/
/*global $, jQuery*/

$(function () {
	'use strict';
	$(document).on('click', '.select2-all', function () {
		var select = $(this).closest('.form-group').find('select');
		select.find('option').prop("selected", "selected");
		select.trigger("change");
		$(this).addClass('active');
	});
});