<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\Helper\FormHelper;

class MyFormHelper extends FormHelper {

	public function html5DateTime($fieldName, $options = []) {
		$options = $this->_initInputField($fieldName, $options) + array('type' => 'datetime');

		return $this->Html->useTag(
			'input',
			$options['name'],
			array_diff($options, array('name' => null))
		);
	}
}
