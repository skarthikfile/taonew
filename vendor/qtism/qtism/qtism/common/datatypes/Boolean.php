<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2014 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts, <jerome@taotesting.com>
 * @license GPLv2
 * @package qtism
 * 
 *
 */

namespace qtism\common\datatypes;

use qtism\common\enums\Cardinality;
use qtism\common\enums\BaseType;
use \InvalidArgumentException;

class Boolean extends Scalar implements QtiDatatype {
    
    protected function checkType($value) {
        if (is_bool($value) !== true) {
            $msg = "The Boolean Datatype only accepts to store boolean values.";
            throw new InvalidArgumentException($msg);
        }
    }
    
    public function getBaseType() {
        return BaseType::BOOLEAN;
    }
    
    public function getCardinality() {
        return Cardinality::SINGLE;
    }
    
    public function __toString() {
        return ($this->getValue() === true) ? 'true' : 'false';
    }
}