document.addEventListener('DOMContentLoaded', init, false);

function init() {

	var inp = document.getElementById('value');
	// inp.addEventListener('keydown', onKeyDown);

	inp.addEventListener('keyup', onKeyUp);
	inp.addEventListener('focus', onFocus);
	inp.addEventListener('blur', onBlur);
	inp.addEventListener('invalid', onInvalid);

	inp.focus();

	function onKeyUp(e) {
		// inp.value = inp.value.replace(/\,/, '.');
		// inp.value = inp.value.replace(/[^0-9\.]/, '');
		return false;
	}

	function onFocus() {
		this.select();
	}

	function onBlur(e) {

		var 
			v = parseFloat(inp.value),
			s = v.toFixed(2);

		inp.value = s;
	}

	function onInvalid(e) {
		onBlur();
	}

	// Date picker
	var dateInput = document.getElementById('date');
	var picker = new Pikaday({ 
		field: dateInput,
		firstDay: 1,
		format: 'D MMM YYYY',
		i18n: {
			previousMonth: 'Vorheriger Monat',
			nextMonth: 'Nächster Monat',
			months: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',  'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
			weekdays: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
			weekdaysShort: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa']
		}
	});
};


