<?php

use qtism\common\enums\BaseType;
use qtism\common\datatypes\Boolean;
use qtism\common\datatypes\String;
use qtism\data\expressions\operators\CustomOperator;
use qtism\data\expressions\ExpressionCollection;
use qtism\data\expressions\BaseValue;
use qtism\runtime\expressions\operators\OperandsCollection;
use qtism\runtime\common\MultipleContainer;
use qti\customOperators\CsvToMultiple;

/*  
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
 * Copyright (c) 2013-2016 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *               
 */

 
/**
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 */
class CsvToMultipleTest extends PHPUnit_Framework_TestCase {
	
    public function testSimple() {
        $baseValue = new BaseValue(BaseType::STRING, 'Boba,Fett');
        $customOperator = new CustomOperator(
            new ExpressionCollection(array($baseValue)),
            '<customOperator class="qti.customOperators.csvToMultiple"><baseValue baseType="string">Boba,Fett</baseValue></customOperator>'
        );
        $operands = new OperandsCollection(array(new String('Boba,Fett')));
        $operator = new CsvToMultiple($customOperator, $operands);
        $result = $operator->process();
        
        $expected = new MultipleContainer(
            BaseType::STRING, array(
                new String('Boba'),
                new String('Fett')
            )
        );
        
        $this->assertInstanceOf('qtism\\runtime\\common\\MultipleContainer', $result);
        $this->assertTrue($expected->equals($result));
    }
    
    /**
     * @depends testSimple
     */
    public function testSingleStringValue() {
        $baseValue = new BaseValue(BaseType::STRING, 'Boba');
        $customOperator = new CustomOperator(
            new ExpressionCollection(array($baseValue)),
            '<customOperator class="qti.customOperators.csvToMultiple"><baseValue baseType="string">Boba</baseValue></customOperator>'
        );
        $operands = new OperandsCollection(array(new String('Boba')));
        $operator = new CsvToMultiple($customOperator, $operands);
        $result = $operator->process();
        
        $expected = new MultipleContainer(
            BaseType::STRING, array(
                new String('Boba')
            )
        );
        
        $this->assertInstanceOf('qtism\\runtime\\common\\MultipleContainer', $result);
        $this->assertTrue($expected->equals($result));
    }
    
    /**
     * @depends testSimple
     */
    public function testReturnsNull() {
        $baseValue = new BaseValue(BaseType::BOOLEAN, false);
        $customOperator = new CustomOperator(
            new ExpressionCollection(array($baseValue)),
            '<customOperator class="qti.customOperators.csvToMultiple"><baseValue baseType="boolean">false</baseValue></customOperator>'
        );
        $operands = new OperandsCollection(array(new Boolean(false)));
        $operator = new CsvToMultiple($customOperator, $operands);
        $result = $operator->process();
        
        $this->assertSame(null, $result);
    }
    
    /**
     * @depends testSimple
     */
    public function testEmptyStringValue() {
        $baseValue = new BaseValue(BaseType::STRING, '');
        $customOperator = new CustomOperator(
            new ExpressionCollection(array($baseValue)),
            '<customOperator class="qti.customOperators.csvToMultiple"><baseValue baseType="string"></baseValue></customOperator>'
        );
        $operands = new OperandsCollection(array(new String('')));
        $operator = new CsvToMultiple($customOperator, $operands);
        $result = $operator->process();
        
        $expected = new MultipleContainer(
            BaseType::STRING, array(
                new String('')
            )
        );
        
        $this->assertInstanceOf('qtism\\runtime\\common\\MultipleContainer', $result);
        $this->assertTrue($expected->equals($result));
    }
}
