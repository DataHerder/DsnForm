<?php 
/**
 * DsnForm
 * A simple class structure for creating inputs in OOP
 * 
 * Copyright (C) 2012  Paul Carlton
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @author      Paul Carlton
 * @category    DsnForm
 * @package     Library
 * @license     GNU license
 * @version     1.0
 * @link        my.public.repo
 * @since       File available since
 */

namespace DsnForm\DsnInputs;

use \DsnForm\Abstracts\DsnInputsAbstract as DsnInputsAbstract;


/**
* DsnInputsSelect object that creates a select element
* eg: 
*  <select>
*   <option>hi</option>
*  </select>
* 
*/
class DsnInputsSelect extends DsnInputsAbstract {

	/**
	* Associative array holding key=>value pairs
	* of options
	* 
	* @access private
	* @var array
	*/
	private $options = array();

	/**
	* Array holding the disabled options, or a string
	* of a single option to disable
	* 
	* @access private
	* @var array|string
	*/
	private $disable_options = array();


	/**
	* Adds options plurally
	* 
	* @access public
	* @param array $options
	* @return
	*/
	public function addOptions($options = array(), $forceKeyValues = false) {
		if (!is_array($options) || empty($options)) {
			throw new DsnInputsSelectException('Form select requires options to be an array when setting multiple options at once');
		}
		$is_associative = !ctype_digit(implode('', array_keys($options)));
		foreach ($options as $key => $val) {
			if ($is_associative || $forceKeyValues) {
				$this->options[$key] = $val;
			} else {
				$this->options[$val] = $val;
			}
		}
	}

	public function setDisabled($disable_options = array())
	{
		if (!is_array($disable_options) && !is_string($disabled_options)) {
			return false;
		}
		$this->disable_options = $disable_options;
	}

	/**
	* Adds one option to the option array
	* 
	* @access public
	* @param array $arg
	* @param string $val
	*/
	public function addOption($arg = array(), $val = '')
	{
		if (is_array($arg) && empty($arg)) {
			return $this;
		}
		elseif (is_string($arg)) {
			$this->options[$arg] = $val;
		}
		else { 
			$key = key($arg);
			$this->options[$key]=$arg[$key];
		}
	}

	/**
	* Wrapper to setDefault, for language preference
	* 
	* @access public
	* @param string|null $value
	*/
	public function setSelected($value = null)
	{
		setDefault($value);
	}

	/**
	* Default value to be set for
	* 
	* @var string
	*/
	private $default_val = null;
	/**
	* Set the default selected option
	* 
	* Be careful, sets the selected element by it's value not "textContent"
	* If there is no value set, as in <option>Text1</option> then addOptions and
	* addOption will automatically set <option value="Text1">Text1</option>
	* 
	* @access public
	* @param string|null $value
	* @return null
	*/
	public function setDefault($option_value = null)
	{
		if (!is_null($option_value) && (is_string($option_value) || is_int($option_value))) {
			$this->default_val = $option_value;
			foreach ($this->options as $key => $value_) {
				if ($option_value === $key) {
					$this->options[$key]= array($value_, true);
				}
			}
		}
	}



	/**
	* Creates the select string
	* 
	* This appends the options as they were given, so sort
	* the data first before passing the options to the class
	* 
	* @access public
	* @return string
	*/
	public function __toString()
	{
		$str = '';
		$str.= "\n"."<select ";
		$atts = array();
		foreach ($this->input_attributes as $att => $val) {
			if ($att == 'type')continue;
			$atts[] = $att.'="'.$val.'"';
		}
		foreach ($this->data_attributes as $att => $val) {
			$atts[] = $att.'="'.$val.'"';
		}
		$options = array();
		foreach ($this->options as $key => $val) {
			$disabled = '';
			$selected = '';
			if (!is_array($val)) {
				if ($this->default_val == $key) {
					$selected = ' selected="selected"';
				} elseif ((is_string($this->disable_options) && $this->disable_options == $key) || (is_array($this->disable_options) && in_array($key, $this->disable_options))) {
					$disabled = ' disabled="disabled"';
				}
				$options[] = "\t".'<option value="'.$key.'"'.$disabled.$selected.'>'.$val.'</option>';
			} else {
				// selected overrides disabled option
				$options[] = "\t".'<option value="'.$key.'" selected="selected">'.$val[0].'</option>';
			}
		}
		$str.=join(' ', $atts);
		$str.= ' '.join(' ', $this->_rawAtts);
		$str.=">\n".join("\n", $options)."\n</select>\n";
		return $str;
	}
}

class DsnInputsSelectException extends \Exception {}
