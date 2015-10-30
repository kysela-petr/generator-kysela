$(function () {
	$.datepicker.regional['cs'] = {
		closeText: 'Hotovo',
		prevText: 'Předchozí',
		nextText: 'Další',
		currentText: 'Dnes',
		monthNames: ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'],
		monthNamesShort: ['Led', 'Úno', 'Bře', 'Dub', 'Kvě', 'Čer', 'Čec', 'Srp', 'Zář', 'Říj', 'Lis', 'Pro'],
		dayNames: ['Neděle', 'Pondělí', 'Úterý', 'Středa', 'Čtvrtek', 'Pátek', 'Sobota'],
		dayNamesShort: ['Ned', 'Pon', 'Úte', 'Stř', 'Čtv', 'Pát', 'Sob'],
		dayNamesMin: ['Ne', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So'],
		weekHeader: 'Tý',
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''
	};
	$.datepicker.setDefaults($.datepicker.regional['cs']);
	$.timepicker.regional['cs'] = {
		currentText: 'Nyní',
		closeText: 'Hotovo',
		amNames: ['AM', 'A'],
		pmNames: ['PM', 'P'],
		timeFormat: 'HH:mm:ss',
		timeSuffix: '',
		timeOnlyTitle: 'Vyberte čas',
		timeText: 'Čas',
		hourText: 'Hodina',
		minuteText: 'Minuta',
		secondText: 'Sekunda',
		millisecText: 'Milisekunda',
		microsecText: 'Mikrosekunda',
		timezoneText: 'Časová zóna',
		isRTL: false
	};
	$.timepicker.setDefaults($.timepicker.regional['cs']);
	var myControl = {
		create: function (tp_inst, obj, unit, val, min, max, step) {
			$('<input class="ui-timepicker-input" value="' + val + '" style="width:50%">')
					.appendTo(obj)
					.spinner({
						min: min,
						max: max,
						step: step,
						change: function (e, ui) { // key events
							// don't call if api was used and not key press
							if (e.originalEvent !== undefined)
								tp_inst._onTimeChange();
							tp_inst._onSelectHandler();
						},
						spin: function (e, ui) { // spin events
							tp_inst.control.value(tp_inst, obj, unit, ui.value);
							tp_inst._onTimeChange();
							tp_inst._onSelectHandler();
						}
					});
			return obj;
		},
		options: function (tp_inst, obj, unit, opts, val) {
			if (typeof (opts) == 'string' && val !== undefined)
				return obj.find('.ui-timepicker-input').spinner(opts, val);
			return obj.find('.ui-timepicker-input').spinner(opts);
		},
		value: function (tp_inst, obj, unit, val) {
			if (val !== undefined)
				return obj.find('.ui-timepicker-input').spinner('value', val);
			return obj.find('.ui-timepicker-input').spinner('value');
		}
	};
	$.timepicker.setDefaults({'controlType': myControl});
	$('#content a#saveandback').click(function () {
		if ($(this).hasClass('callPostBeforeSave') && (typeof postEdit != 'undefined'))
			postEdit.BeforeSave();
		$('#content input[name="save"]').trigger('click');
		return false;
	});
	var makeDateInput = function (object) {
		object.dateinput({
			datetime: {
				dateFormat: 'd.m.yy',
				timeFormat: 'H:mm:ss',
				options: {
					changeYear: true
				}
			},
			'datetime-local': {
				dateFormat: 'd.m.yy',
				timeFormat: 'H:mm:ss'
			},
			date: {
				dateFormat: 'd.m.yy'
			},
			month: {
				dateFormat: 'MM yy'
			},
			week: {
				dateFormat: "w. 'week of' yy"
			},
			time: {
				timeFormat: 'H:mm:ss'
			}
		});
	};

	var jsControls = function () {
		$('select.select2:not(.select2-offscreen)').select2();
		makeDateInput($('input[data-dateinput-type]'));
		$('input.daterange').dateRange();
	};
	jsControls();
	$.nette.ext('jsControls', {
		success: jsControls
	});
	$.nette.init();
	$('#content').on('change', 'input.change-submit, select.change-submit', function (e) {
		$(this).closest('form').submit();
	});
	$('#content').on('change', 'input.change-ajax-submit, select.change-ajax-submit', function (e) {
		var self = $(this);
		var form = self.closest('form');
		form.netteAjax(e);
	});
	$('#content').on('change', 'input.change-ajax-submit-click, select.change-ajax-submit-click', function (e) {
		var self = $(this);
		var form = self.closest('form');
		form.find('input[type=submit]').click();
	});
	$('[custom-confirm]').click(function () {
		var self = $(this);
		var message = self.attr('custom-confirm');
		return confirm(message);
	});

	$('#content').on('click', 'a.ajax-click', function (e) {
		var self = $(this);
		var form = self.closest('form');
		form.netteAjax(e);
	});

	/**
	 * Modul Admin:Article:Article:edit
	 */
	$(document).on('click', "#content a.opencloseBlock", function () {
		var self = $(this);
		var target = self.next('#content div.openableBlock');

		if (self.hasClass('closed')) {

			target.slideDown(function () {
				self.text(self.data('texthide'));
			});

		} else {

			target.slideUp(function () {
				self.text(self.data('textshow'));
			});
		}

		self.toggleClass('closed');

		return false;
	});


	$('.grido tr.forPageMove').hover(function () {
		$('.pageMoveIconsWrapper', $(this)).css({'visibility': 'visible'});
	}, function () {
		$('.pageMoveIconsWrapper', $(this)).css({'visibility': 'hidden'});
	});

	$('#content input[data-tags]').each(function () {
		$(this).dataTags();
	});


	var activateAddingToAnotherInput = function (sourceSelector, destinationSelector, buttonSelector) {
		$('#content').on('click', buttonSelector, function () {
			var values = $(sourceSelector).val();
			if (typeof values === 'string') {
				values = [values];
			}

			var targetSB = $(destinationSelector);
			var result = targetSB.select2('val');

			var destinationOptions = $.map($(destinationSelector).children('option'), function (item) {
				return item.value;
			});

			$(sourceSelector).children().each(function () {
				var self = $(this);
				var value = self.attr('value');
				var text = self.text();
				if (value === '') {
					return;
				}

				if ($.inArray(value, values) > -1) {
					if ($.inArray(value, result) == -1) {
						result.push(value);

						if ($.inArray(value, destinationOptions) === -1) {
							var option = $(document.createElement('option'));
							option.attr('value', value);
							option.text(text);
							targetSB.append(option);
						}
					}
				}
			});

			targetSB.select2('val', result).trigger('change');

			return false;
		});
	};

	$('body').on('mousemove', 'img.zoomable', function (event) {
		var div = $('#editorZoom');
		var img = $('#editorZoomImg');
		var span = $('#editorZoomText');
		var source = $(this);
		var textDivider = source.data('text').length && source.data('size').length ? '/' : '';

		img.hide();
		div.css('left', event.pageX + 64);
		div.css('top', event.pageY - (div.outerWidth() / 2));

		span.text(source.data('text') + textDivider + source.data('size'));
		img.attr('src', '/min.php?file=' + source.data('big') + '&w=400&h=400');
		img.show();
		div.show();
	}).mouseout(function () {
		$('#editorZoom').hide();
	});

	/**
	 * Admin:Article:edit
	 */
	$(document).on('click', '#addMainPhoto', function () {
		fromTinyMce = false;
		$('#photo-picker-wrapper').modal('show');
		photoPickerObject.FocusSearchField();
		return false;
	});

	/**
	 * Admin:Article:edit
	 */
	$(document).on('click', '#deleteMainPhoto', function () {
		$('#mainPhotoHidden').removeAttr('value');
		$('#deleteMainPhoto').hide();
		$('#mainPhotoWrapper').hide();
		return false;
	});

	/**
	 * Pro button upload file, ze šablony uploadFormGroup
	 */
	$('.upload-file input').change(function() {
		$(this).closest('.form-group').find('.upload-file-name').text($(this).val());
	});

});