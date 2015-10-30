/*
 * jQuery File Upload Plugin JS Example 8.9.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */

$(function() {
	'use strict';

	//reset tlacitko - disable/enable
	$(document).on('click','#uploadReset', function(){
		$(document).trigger('uploadResetDisableHandler');
		$(document).trigger('uploadSubmitDisableHandler');
	});

	$(document).on('uploadResetDisableHandler', function(event){
		var button = $('#uploadReset');

		button.prop('disabled', true).addClass('disabled');
	});

	$(document).on('uploadResetEnableHandler', function(event){
		var button = $('#uploadReset');

		button.prop('disabled', false).removeClass('disabled');
	});

	$(document).on('uploadSubmitDisableHandler', function(event){
		var button = $('#uploadSubmit');

		button.prop('disabled', true).addClass('disabled');
	});

	$(document).on('uploadSubmitEnableHandler', function(event){
		var button = $('#uploadSubmit');

		button.prop('disabled', false).removeClass('disabled');
	});

	// Initialize the jQuery File Upload widget:
	$('#fileupload').fileupload({
		// Uncomment the following to send cross-domain cookies:
		//xhrFields: {withCredentials: true},
		url: uploadDest,
		previewMaxWidth: 120,
		previewMaxHeight: 120
	});

	// Enable iframe cross-domain access via redirect option:
	$('#fileupload').fileupload(
			'option',
			'redirect',
			window.location.href.replace(
					/\/[^\/]*$/,
					'/cors/result.html?%s'
					)
			);

	$('#fileupload').bind('fileuploadstop', function(e, data) {
		$('#uploadredirect').show();
		$(document).trigger('uploadResetDisableHandler');
		$(document).trigger('uploadSubmitDisableHandler');
	});

	$('#fileupload').bind('fileuploadadd', function(e, data) {
		$('#uploadredirect').hide();
		$(document).trigger('uploadResetEnableHandler');
		$(document).trigger('uploadSubmitEnableHandler');
	});

	$('#fileupload').bind('fileuploadfinished', function (e, data) {
		var size = $('#fileupload tbody.files tr.template-upload').size();

		if(size<=0){
			$(document).trigger('uploadResetDisableHandler');
			$(document).trigger('uploadSubmitDisableHandler');
		}
	});


	// Load existing files:
	$('#fileupload').addClass('fileupload-processing');
	$.ajax({
		// Uncomment the following to send cross-domain cookies:
		//xhrFields: {withCredentials: true},
		url: $('#fileupload').fileupload('option', 'url'),
		dataType: 'json',
		context: $('#fileupload')[0]
	}).always(function() {
		$(this).removeClass('fileupload-processing');
	}).done(function(result) {
		$(this).fileupload('option', 'done')
				.call(this, $.Event('done'), {result: result});
	});

	$(document).trigger('uploadResetDisableHandler');
	$(document).trigger('uploadSubmitDisableHandler');

});
