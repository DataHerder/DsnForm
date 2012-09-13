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
* Creates textarea object
* eg:
*   <textarea name='hi'>Default Value</textarea>
*/
class DsnInputsTextarea extends DsnInputsAbstract {

	public function __toString()
	{
		$str = '<textarea ';
		$atts = array();
		foreach ($this->input_attributes as $att => $val) {
			if ($att == 'value') continue;
			$atts[] = $att.'="'.$val.'"';
		}
		foreach ($this->data_attributes as $att => $val) {
			$atts[] = $att.'="'.$val.'"';
		}
		$str.= join(' ', $atts);
		if (!isSet($this->input_attributes['value'])) {
			$this->input_attributes['value'] = '';
		}
		$str.= ' '.join(' ', $this->_rawAtts);
		$str.= '>'.$this->input_attributes['value'].'</textarea>';
		return $str;
	}
}
