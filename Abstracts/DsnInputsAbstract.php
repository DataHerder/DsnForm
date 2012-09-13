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

namespace DsnForm\Abstracts;

/**
* Abstract class that extends each input class
*/
abstract class DsnInputsAbstract {

	/**
	* The input attributes array
	* 
	* @access protected
	* @var array
	*/
	protected $input_attributes = array();

	/**
	* Array of special html5 data attributes
	* 
	* @access protected
	* @var array
	*/
	protected $data_attributes = array();

	/**
	* The type of input variable
	* 
	* @access protected
	* @var string
	*/
	protected $input_type = '';

	/**
	* Raw strings for attributes
	* 
	* @deprecated
	* @access protected
	* @var string
	*/
	protected $_rawAtts = array();

	/**
	* The current class
	* 
	* @access public
	* @var string
	*/
	public $class_name = __CLASS__;

	/**
	* Construct DsnInputs
	* 
	* @access public
	* @param string $type
	* @param array $attr
	* @return DsnInputsAbstract
	*/
	public function __construct($type, $attr = array()){
		$this->input_type = $type;
		if (!is_array($attr)) {
			$attr = array();
		}
		foreach ($attr as $a => $b) {
			if (preg_match("@^data_.*@", $a)) {
				$this->data_attributes[$a] = (is_bool($b)) ? (($b)?'true':'false') : $b;
			} else {
				if ($a == 'type') continue;
				$this->input_attributes[$a] = (is_bool($b)) ? (($b)?'true':'false') : $b;
			}
		}
		return $this;
	}


	/**
	* Set attributes 
	* 
	* @access public
	* @param string $attr
	* @param string $val
	* @return DsnInputsAbstract
	*/
	public function setAttribute($attr, $val){
		if (preg_match('@^data_.*@', $attr)) {
			$attr = preg_replace("/_/", '-', $attr);
			$this->data_attributes[$attr] = (is_bool($val)) ? (($val)?'true':'false') : $val;
		} else {
			if ($attr == 'type') {
				return $this;
			}
			$this->input_attributes[$attr] = (is_bool($val)) ? (($val)?'true':'false') : $val;
		}
		return $this;
	}

	/**
	* Force a raw attribute, better
	* to feed in with setAttribute or in $param
	* from the Master Class
	* 
	* @deprecated
	* @param string $raw
	* @return DsnInputs
	*/
	public function setRawAttributes($raw)
	{
		// it's forced in // this should be taken out eventually
		$this->_rawAtts[] = $raw;
		return $this;
	}
}
