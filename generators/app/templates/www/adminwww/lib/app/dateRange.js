(function($){

	var parseDate = function(datetime) {
		// 2011-05-08
		if (datetime.length < 10) {
			return null;
		}
		return new Date(
			parseInt(datetime.substr(0, 4), 10), //year
			parseInt(datetime.substr(5, 2), 10) - 1, //month
			parseInt(datetime.substr(8, 2), 10) //day
			);
	};	

	$.fn.dateRange = function() {
		this.each(function(){
			var original = $(this);
			original.hide();
			var wrapper = original.parent();
			var values = original.val().split(/ [^0-9]*/);
			var fromValue = values[0];
			var toValue   = values[1];
			var fromTo = {from: fromValue, to: toValue};
			for(var key in fromTo){
				var value = fromTo[key];
				var input = $('<input type="text"></input>');
				input.addClass(original.attr('class'));
				var date = new Date(value);
				if(!isNaN(date)){
					var formatted = $.datepicker.formatDate($.datepicker._defaults.dateFormat, date);
					input.val(formatted);
				}
				fromTo[key] = input;
				var inputWrapper = $('<div><span>' + original.attr('data-' + key + '-text') + '</span></div>');
				inputWrapper.append(input);
				wrapper.append(inputWrapper);
			}
			var from = fromTo['from'];
			var to = fromTo['to'];
			var joinSymbol = original.attr('data-join-symbol');
			
			var redrawOriginal = function(){
				var result = [];
				for(var key in fromTo){
					var value = fromTo[key].datetimepicker('getDate');
					if(value === null || (typeof value === 'string' && value.trim() === '')){
						result.push('');
					}else{
						result.push($.datepicker.formatDate('yy-mm-dd', value));
					}
				}
				original.val(result.join(joinSymbol !== undefined ? joinSymbol : ' - '));
			};
			
			from.datepicker({
				onClose: function(dateText, inst) {
					if (to.val() != '') {
						var testStartDate = from.datetimepicker('getDate');
						var testEndDate = to.datetimepicker('getDate');
						if (testStartDate != null && testStartDate > testEndDate)
							to.datetimepicker('setDate', testStartDate);
					}
					else {
						to.val(dateText);
					}
					redrawOriginal();
				},
				onSelect: function (selectedDateTime){
					console.log(selectedDateTime);
					if(from.val() != ''){
						to.datetimepicker('option', 'minDate', from.datetimepicker('getDate') );
					}
				}
			});
			to.datepicker({ 
				onClose: function(dateText, inst) {
					if (from.val() != '') {
						var testStartDate = from.datetimepicker('getDate');
						var testEndDate = to.datetimepicker('getDate');
						if (testEndDate != null && testStartDate > testEndDate)
							from.datetimepicker('setDate', testEndDate);
					}
					else {
						from.val(dateText);
					}
					redrawOriginal();
				},
				onSelect: function (selectedDateTime){
					if(to.val() != ''){
						from.datetimepicker('option', 'maxDate', to.datetimepicker('getDate') );
					}
				}
			});
		});
	};
})(jQuery);