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
		var v = parseFloat(inp.value.replace(/\,/, '.')),
		s = v.toFixed(2);
		inp.value = !isNaN(s) ? s : '0,00';
		// onKeyUp();
	}

	function onInvalid(e) {
		onBlur();
	}
};
