<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
*
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Testcase for array mapper object. 
 * 
 * TODO for some curious reason, this testcase fails if it is run without the other tests... check for dependencies!!!
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_ArrayMapper_testcase extends Tx_Extbase_BaseTestcase {

	protected $arrayData;
	
	
	
    protected $fieldSettings;
    
    
    
    protected $wrongFieldSettings;

    
	
	public function setup() {
		$this->arrayData = array(
            array('t1.f1' => 'v1_1', 't1.f2' => 'v1_2', 't1.f3' => 'v1_3','t2.f1' => 'v1_4', 't2.f2' => 'v1_5'),
            array('t1.f1' => 'v2_1', 't1.f2' => 'v2_2', 't1.f3' => 'v2_3','t2.f1' => 'v2_4', 't2.f2' => 'v2_5'),
            array('t1.f1' => 'v3_1', 't1.f2' => 'v3_2', 't1.f3' => 'v3_3','t2.f1' => 'v3_4', 't2.f2' => 'v3_5'),
            array('t1.f1' => 'v4_1', 't1.f2' => 'v4_2', 't1.f3' => 'v4_3','t2.f1' => 'v4_4', 't2.f2' => 'v4_5')
        );
        
        $this->fieldSettings = array(
            'field1' => array( 
                'table' => 't1',
                'field' => 'f1'
            ),
            'field2' => array( 
                'table' => 't2',
                'field' => 'f1'
            )
        );
        
        $this->wrongFieldSettings = array(
            'field1' => array( 
                'table' => 't5',
                'field' => 'f1'
            ),
            'field2' => array( 
                'table' => 't2',
                'field' => 'f1'
            )
        );
        
	}
	
	
	
	public function testSetUp() {
		$arrayMapper = new Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper();
	}
	
	
	
	public function testSetMappingConfiguration() {
	   	$arrayMapper = new Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper();
	   	$mockFieldConfigCollection = $this->getMock(
            'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection',
            array(),array(),'',FALSE,FALSE,FALSE);
	   	$arrayMapper->setMapperConfiguration($mockFieldConfigCollection);
	}
	
	
	
	public function testGetMappedListDataWithoutConfiguration() {
		$arrayMapper = new Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper();
		$mappedListData = $arrayMapper->getMappedListData($this->arrayData);
		$this->assertEquals($mappedListData[0]['t1.f1'], 'v1_1');
	}
	
	
	
	/**
	 * TODO For some curious reason, this test fails, if the testcase is run alone 
	 */
	public function testGetMappedListDataWithMappingConfiguration() {
		$arrayMapper = new Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper();
		/**
		 * TODO think about better way to test this without need of dependent object!
		 */
		$fieldConfigCollection = Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollectionFactory::getFieldConfigCollection($this->fieldSettings);
		$arrayMapper->setMapperConfiguration($fieldConfigCollection);
		$mappedListData = $arrayMapper->getMappedListData($this->arrayData);
		$this->assertEquals($mappedListData[0]['field1'], 'v1_1');
		$this->assertEquals($mappedListData[3]['field2'], 'v4_4');
	}
	
	
	
	/**
     * TODO For some curious reason, this test fails, if the testcase is run alone 
     */
	public function testThrowExceptionOnNonExistingFieldName() {
		$arrayMapper = new Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper();
        $fieldConfigCollection = Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollectionFactory::getFieldConfigCollection($this->wrongFieldSettings);
        $arrayMapper->setMapperConfiguration($fieldConfigCollection);
        try {
            $mappedListData = $arrayMapper->getMappedListData($this->arrayData);
        } catch(Exception $e) {
        	return;
        }
        $this->fail();
	}
	
	
	
	public function testMappingOnNonDottedFieldnames() {
		$this->markTestIncomplete('Not yet implemented!');
	}
	
}

?>