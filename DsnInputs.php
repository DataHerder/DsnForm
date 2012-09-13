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



namespace DsnForm;
use DsnForm\DsnInputsException as DsnInputsException;



/**
 * DsnInputs Final Class is a fluency class
 * that generates inputs OOP style rather than hardcoding
 * 
 * $Input = new DsnInputs('input', array('maxlength'=>5));
 * print $Input;
 * 
 * $Input->select(array('onchange'=>"alert('hi')"))
 *   ->addOptions(array(
 *    'value1', 'value2', 'value3'
 *   )
 *   ->setDefault('value2')
 * );
 * 
 * print $Input;
 * 
 * $Input->button(array('name'=>'hi'))
 *   ->setAttribute('onblur', 'return false;')
 *   ->setAttribute('onclick', '')
 *   ->setAttribute('data_size', '2000')
 * ;
 * 
 * print $Input;
 * 
 */
final class DsnInputs {

	/**
	* Holds the form object
	* 
	* @access private
	* @var Inputs classes
	*/
	private $form_object = null;

	/**
	* The allowable elements
	* 
	* @access private
	* @var array
	*/
	private $elements = array(
		'input'=>array('text', 'password', 'radio', 'checkbox', 'submit', 'input-button', 'reset', 'hidden'), 
		'select'=>array('select'), 
		'button'=>array('button'), 
		'textarea'=>array('textarea'), 
	);

	/**
	* Construct the Master Class
	* 
	* @access public
	* @param string $form_element_type
	* @param array $attr
	* @return DsnInputs
	*/
	public function __construct($form_element_type = null, $attr = array())
	{
		if (is_null($form_element_type)) {
			return $this;
		}
		if ( !is_null($attr) && (!is_array($attr)) || (!empty($attr) && !((bool)count(array_filter(array_keys($attr), 'is_string')))) ) {
			throw new DsnInputsException('Attributes must be an associative array');
		}
		else {
			if (!$this->_loadCallObject($form_element_type, $attr)) {
				throw new DsnInputsException('Not a viable form element found.');
			}
			return $this;
		}
	}

	/**
	 * Get the instance, forcing new object instantiation
	 * Very useful for multiple inputs in an array
	 * 
	 * ex:
	 * $inputs = array(
	 *   'input_1' => DsnInputs::gi()->input(array('name'=>'hi1', 'size'=>25)), 
	 *   'input_2' => DsnInputs::gi()->input(array('name'=>'hi2', 'size'=>15)), 
	 * );
	 * 
	 * foreach ($inputs as $a => $b) {
	 *   print $b; // utilizes __toString()
	 * }
	 * 
	 * @access public
	 * @param null
	 * @return DsnInputs
	 */
	public static function gi()
	{
		$class = __CLASS__;
		$fi = new $class;
		return $fi;
	}

	/**
	* Shorthand for quick form element instantiation
	* 
	* @access public
	* @return DsnInputs
	*/
	public function __invoke()
	{
		$arg_num = func_num_args();
		if ($arg_num == 0) {
			return $this;
		}
		$args = func_get_args();
		$this->__call('type', $args);
		return $this;
	}

	/**
	* Create new select, input etc... element
	* 
	* Example: $DsnInputs->textarea(array('arguments', etc...))
	* 
	* @access public
	* @param mixed $name
	* @param mixed $arguments
	* @return DsnInputs
	*/
	public function __call($name, $arguments)
	{
		if (!is_object($this->form_object) && $name == 'type') {
			if (!isSet($arguments[1])) {
				$arguments[1] = null;
			}
			$this->_loadCallObject($arguments[0], $arguments[1]);
		} elseif (!is_object($this->form_object)) {
			throw new DsnInputsException('When instantiating DsnInputs without type and attributes, invalid method calls are places on a non object.  Please specify ->type()');
		} elseif (is_object($this->form_object) && $name == 'type') {
			$this->_loadCallObject($arguments[0], $arguments[1]);
			if ($arguments[0] == 'select' && isSet($arguments[2])) {
				$this->form_object->addOptions($arguments[2]);
			}
		}
		if (is_callable($this->form_object, $name)) {
			if (!$this->form_object->$name($arguments[0])) {
				throw new DsnInputsException('Not a viable form element found.');
			}
		}
		else {
			// probably a fluency call
			$cn = get_class($this->form_object);
			if ($cn == 'DsnInputsInput' || $cn == 'DsnInputs') {
				if (method_exists($this->form_object, $name)) {
					call_user_func_array(array($this->form_object, $name), $arguments);
				} else {
					$this->form_object->setAttribute($name, $arguments[0]);
				}
			} elseif ($cn == 'DsnInputsSelect') {
				if ($name == 'type') {
					return $this;
				}
				if ($name == 'option') {
					$this->form_object->addOption($arguments[0], $arguments[1]);
				} elseif ($name == 'options') {
					$this->form_object->addOptions($arguments[0]);
				} else {
					if (method_exists($this->form_object, $name)) {
						call_user_func_array(array($this->form_object, $name), $arguments);
					} else {
						$this->form_object->setAttribute($name, $arguments[0]);
					}
				}
			} else {
				if (method_exists($this->form_object, $name)) {
					call_user_func_array(array($this->form_object, $name), $arguments);
				} else {
					if ($name == 'type') {
						return $this;
					}
					$this->form_object->setAttribute($name, $arguments[0]);
				}
			}
		}
		return $this;
	}

	/**
	* Load the correct object into the master class
	* 
	* @access private
	* @param string $type
	* @param array $attr
	* @return boolean
	*/
	private function _loadCallObject($type, $attr)
	{
		print $type;
		unset($this->form_object);
		foreach ($this->elements as $element_name => $element_values) {
			for ($i = 0; $i < count($element_values); $i++) {
				if (strtolower($type) == strtolower($element_values[$i])) {
					$classname = 'DsnForm\DsnInputs\DsnInputs'.ucwords(strtolower($element_name));
					$this->form_object = new $classname($element_values[$i], $attr);
					return true;
				}
			}
		}
		return false;
	}

	/**
	* Convert the input class into a string
	* 
	* @access public
	* @return string
	*/
	public function __toString()
	{
		return $this->form_object->__toString();
	}
}
