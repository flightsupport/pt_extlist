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
 * Testcase for ExtBase Data Backend
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_ExtBaseDataBackend_ExtBaseBackendTest extends Tx_PtExtlist_Tests_BaseTestcase {
	
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend'));
    }
    
    
    
    public function testInjectDataSource() {
    	$dataSourceMock = $this->getMock('Tx_Extbase_Domain_Repository_FrontendUserGroupRepository', array(), array(), '', FALSE);
    	$extBaseDataBackend = new Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend();
    	$extBaseDataBackend->injectDataSource($dataSourceMock);
    }
    
    
    
    public function testThrowExceptionOnInjectingWrongDataSource() {
    	$dataSourceMock = $this->getMock('Tx_PtExtlist_Tests_Domain_DataBackend_ExtBaseDataBackend_ExtBaseBackendTest', array(), array(), '', FALSE);
    	$extBaseDataBackend = new Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseDataBackend();
    	try {
    		$extBaseDataBackend->injectDataSource($dataSourceMock);
    	} catch(Exception $e) {
    		return;
    	}
    	$this->fail('No exception has been thrown when trying to inject wrong type of data source object');
    }
	
}

?>