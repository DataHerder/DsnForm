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
* DsnInputsInput class
* creates an input element
* ex:
* <input type='text' maxlength='5' name='hi' size='5' id='hi' data-required='true' />
*
*/
class DsnInputsInput extends DsnInputsAbstract {

	public function __toString()
	{
		$str = '<input type="'.$this->input_type.'" ';
		$atts = array();
		foreach ($this->input_attributes as $att => $val) {
			$atts[] = $att.'="'.$val.'"';
		}
		foreach ($this->data_attributes as $att => $val) {
			$atts[] = $att.'="'.$val.'"';
		}
		$str.= join(' ', $atts);
		$str.= ' '.join(' ', $this->_rawAtts);
		$str.= ' />';
		return $str;
	}
}

class DsnInputsInputException extends \Exception {}
