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
 * Testcase for pt_extlist data backend factory 
 * 
 * @author Michael Knoll <knoll@punkt.de>, Daniel Lienert <lienert@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_DataBackendFactory_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $mockConfigurationBuilder;
	
	
	
	public function setUp() {
		$this->mockConfigurationBuilder = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
	}
	
	
	
    public function testCreateDataBackend() {
        $dataBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->mockConfigurationBuilder);
        $this->assertTrue(is_a($dataBackend, 'Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend'));
    }
    
    
    
    public function testGetSingletonInstances() {
    	$mockConfigurationBuilderForTest1 = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance($this->getConfigForFirstDbe());
    	$mockConfigurationBuilderForTest2 = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance($this->getConfigForSecondDbe());
    	
    	$dataBackendForTest1 = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($mockConfigurationBuilderForTest1);
    	$dataBackendForTest2 = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($mockConfigurationBuilderForTest2);
    	
    	$this->assertTrue($dataBackendForTest1 != $dataBackendForTest2);
    	
    	$duplicatedDataBackendForTest1 = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($mockConfigurationBuilderForTest1);
    	  	
    	$this->assertTrue($dataBackendForTest1 === $duplicatedDataBackendForTest1);
    }
    
    
    
    protected function getConfigForFirstDbe() {
    	return array(
                'listIdentifier' => 'test1',
                'abc' => '1',
                'listConfig' => array(
                     'test1' => array(
                         'abc' => '2',
                         'def' => '3',
                         'fields' => array(
                             'field1' => array( 
                                 'table' => 'tableName1',
                                 'field' => 'fieldName1',
                                 'isSortable' => '0',
                                 'access' => '1,2,3,4'
                             ),
                             'field2' => array( 
                                 'table' => 'tableName2',
                                 'field' => 'fieldName2',
                                 'isSortable' => '0',
                                 'access' => '1,2,3,4'
                             )
                        ),
                        'columns' => array(
                            10 => array( 
                                'columnIdentifier' => 'column1',
                                'fieldIdentifier' => 'field1',
                                'label' => 'Column 1'
                            ),
                            20 => array( 
                                'columnIdentifier' => 'column2',
                                'fieldIdentifier' => 'field2',
                                'label' => 'Column 2'
                            )
                        ),
                        'filters' => array(
                             'testfilterbox' => array(
                                 '10' => array(
                                    'filterIdentifier' => 'filter1',
                                    'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                    'fieldDescriptionIdentifier' => 'field1',
                                    'partialPath' => 'partialPath'
                                 ),
                                 '20' => array(
                                    'filterIdentifier' => 'filter2',
                                    'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                    'fieldDescriptionIdentifier' => 'field2',
                                    'partialPath' => 'partialPath'
                                 )
                             )
                        ),
                        'pager' => array(
	                    	'pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager',
                            'itemsPerPage'   => '10'
	                    ),
                    )
                )
            );
    }
    
    
    
    protected function getConfigForSecondDbe() {
    	return array(
                'listIdentifier' => 'test2',
                'abc' => '1',
                'listConfig' => array(
                     'test2' => array(
                         'abc' => '2',
                         'def' => '3',
                         'fields' => array(
                             'field1' => array( 
                                 'table' => 'tableName1',
                                 'field' => 'fieldName1',
                                 'isSortable' => '0',
                                 'access' => '1,2,3,4'
                             ),
                             'field2' => array( 
                                 'table' => 'tableName2',
                                 'field' => 'fieldName2',
                                 'isSortable' => '0',
                                 'access' => '1,2,3,4'
                             )
                        ),
                        'columns' => array(
                            10 => array( 
                                'columnIdentifier' => 'column1',
                                'fieldIdentifier' => 'field1',
                                'label' => 'Column 1'
                            ),
                            20 => array( 
                                'columnIdentifier' => 'column2',
                                'fieldIdentifier' => 'field2',
                                'label' => 'Column 2'
                            )
                        ),
                        'filters' => array(
                             'testfilterbox' => array(
                                 '10' => array(
                                    'filterIdentifier' => 'filter1',
                                    'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                    'fieldDescriptionIdentifier' => 'field1',
                                    'partialPath' => 'partialPath'
                                 ),
                                 '20' => array(
                                    'filterIdentifier' => 'filter2',
                                    'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                    'fieldDescriptionIdentifier' => 'field2',
                                    'partialPath' => 'partialPath'
                                 )
                             )
                        ),
                        'pager' => array(
	                    	'pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager',
                            'itemsPerPage'   => '10',
	                    ),
                    )
                )
            );
    }
}

?>