/* 
 * Implementace Jquery.InputMask-3.x na textovy input
 * - vytvori input pro mm:ss, prepocte sekundovy cas a vlozi
 * - puvodni input skyje a pri kazde zmene aktualizuje
 * 
 * autor: Ondra Machala
 */

$.fn.timeInput = function () {

	// className of included input (with mask)
	var inputClassName = 'input-time-mask';

	var inputSelector = this.selector.replace('.', '');

	var maskTimeToInt = function (text) {
		var parse = function (string) {
			if (typeof string === 'undefined') {
				return 0;
			}
			return parseInt(string.replace("_", "")) || 0;
		};

		var parts = text.split(":");
		var minutes = parse(parts[0]);
		var seconds = parse(parts[1]);
		return 60 * minutes + seconds;
	}

	var intToTimeMask = function (text) {
		if (!text.length || parseInt(text) <= 0) {
			return "";
		}

		var timeReadable = function (t) {
			return ('0' + Math.floor(t / 60)).slice(-2) + ('0' + t % 60).slice(-2);
		};

		return timeReadable(parseInt(text));
	}


	this.each(function () {
		var element = $(this);
		var name = element.attr('name');
		var classes = inputClassName + ' ' + element.attr('class').replace(inputSelector, '');

		element.hide();
		$(this).after($('<input>').attr({'type': 'text', 'data-target': name, 'class': classes}));

		var value = this.value;
		var mask = intToTimeMask(value);
		$('input[data-target="' + name + '"]').val(mask);

	});

	$('.' + inputClassName).keyup(function () {
		var target = $(this).attr('data-target');
		var seconds = maskTimeToInt(this.value);
		$('input[name="' + target + '"]').val(seconds);
	});

	$('.' + inputClassName).inputmask("99:99");
};

