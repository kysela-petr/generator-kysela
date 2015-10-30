/* 
 * Rozsireni datepicker filteru u Grido o posun datumu (zpet | dnes | dalsi)
 * - je postaven na miru Datepickeru
 * 
 * autor: Ondra Machala
 */

var fullDateFormat = function (d) {
	var month = d.getMonth() + 1 + "";
	var date = d.getDate() + "";
	var year = d.getFullYear();

	if (month.length == 1) {
		month = "0" + month;
	}

	if (date.length == 1) {
		date = "0" + date;
	}

	return year + "-" + month + "-" + date;
}

var dateSwitcher = function () {
	$('.dateinput-buttons[type=hidden]').each(function () {

		var hiddenInput = $(this);

		var createInput = function (textValue) {
			return $('<input>').attr('type', 'button').val(textValue);
		}

		// Casovy posun: dateValue (string yyyy-mm-dd) posune o shift (milisekundy)
		var timeShift = function (dateValue, shift) {
			return new Date(dateValue.getTime() + shift);
		}

		var bindClick = function (element, date) {
			element.on('click', function (e) {
				hiddenInput.val(fullDateFormat(date));
				submitForm()
			});
		};

		var submitForm = function () {
			var form = hiddenInput.closest('form');
			$(form.find('input[type=submit]')[0]).click();
		}

		var backButton = createInput('\u25c0');
		var forButton = createInput('\u25b6');
		var todayLink = createInput('dnes');

		var selectedDate = new Date(hiddenInput.val());
		var forDay = timeShift(selectedDate, (24 * 60 * 60 * 1000));
		var backDay = timeShift(selectedDate, -(24 * 60 * 60 * 1000));
		var today = new Date;

		bindClick(forButton, forDay);
		bindClick(backButton, backDay);
		bindClick(todayLink, today);

		var visibleInput = hiddenInput.parent().find('.hasDatepicker');

		hiddenInput.before(backButton).before(todayLink).after(forButton);

		visibleInput.on('change', function () {
			submitForm();
		});
	});
};