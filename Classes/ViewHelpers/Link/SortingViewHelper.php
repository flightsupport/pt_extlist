<?php
namespace PunktDe\PtExtlist\ViewHelpers\Link;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt
 *  All rights reserved
 *
 *  For further information: http://extlist.punkt.de <extlist@punkt.de>
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

use PunktDe\PtExtlist\ViewHelpers\Namespaces\GPArrayViewHelper;
use \TYPO3\CMS\Fluid\ViewHelpers\Link\ActionViewHelper;

/**
 * ViewHelpers renders link for given sorting.
 *
 * Generates a link, that sorts a column as a whole.
 *
 * @package ViewHelpers
 * @subpackage Link
 * @author Michael Knoll
 */
class  SortingViewHelper extends ActionViewHelper
{
    /**
     * Holds instance of session persistence manager builder
     *
     * @var \Tx_PtExtbase_State_Session_SessionPersistenceManagerBuilder
     */
    protected $sessionPersistenceManagerBuilder;



    /**
     * Injects session persistence manager factory (used by DI)
     *
     * @param \Tx_PtExtbase_State_Session_SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder
     */
    public function injectSessionPersistenceManagerBuilder(\Tx_PtExtbase_State_Session_SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder)
    {
        $this->sessionPersistenceManagerBuilder = $sessionPersistenceManagerBuilder;
    }

    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('header', 'Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn', 'Header', true);
    }


    /**
     * @param string $action Target action
     * @param array $arguments Arguments
     * @param string $controller Target controller. If NULL current controllerName is used
     * @param string $extensionName Target Extension Name (without "tx_" prefix and no underscores). If NULL the current extension name is used
     * @param string $pluginName Target plugin. If empty, the current plugin name is used
     * @param int $pageUid target page. See TypoLink destination
     * @param int $pageType type of the target page. See typolink.parameter
     * @param bool $noCache set this to disable caching for the target page. You should not need this.
     * @param bool $noCacheHash set this to suppress the cHash query parameter created by TypoLink. You should not need this.
     * @param string $section the anchor to be added to the URI
     * @param string $format The requested format, e.g. ".html
     * @param bool $linkAccessRestrictedPages If set, links pointing to access restricted pages will still link to the page even though the page cannot be accessed.
     * @param array $additionalParams additional query parameters that won't be prefixed like $arguments (overrule $arguments)
     * @param bool $absolute If set, the URI of the rendered link is absolute
     * @param bool $addQueryString If set, the current query parameters will be kept in the URI
     * @param array $argumentsToBeExcludedFromQueryString arguments to be removed from the URI. Only active if $addQueryString = TRUE
     * @param string $addQueryStringMethod Set which parameters will be kept. Only active if $addQueryString = TRUE
     * @return string Rendered link
     * @throws \Tx_PtExtbase_Exception_Assertion
     */
    public function render($action = null, array $arguments = [], $controller = null, $extensionName = null, $pluginName = null, $pageUid = null, $pageType = 0, $noCache = false, $noCacheHash = false, $section = '', $format = '', $linkAccessRestrictedPages = false, array $additionalParams = [], $absolute = false, $addQueryString = false, array $argumentsToBeExcludedFromQueryString = [], $addQueryStringMethod = null)
    {
        /** @var \Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $header */
        $header = $this->arguments['header'];
        if ($action === null) {
            $action = 'sort';
        }
        $sortingFieldParams = [];

        // We generate sorting parameters for every sorting field configured for this column
        foreach ($header->getColumnConfig()->getSortingConfig() as $sortingFieldConfig) { /* @var $sortingFieldConfig \Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig */
            $newSortingDirection = (
                ($header->getSortingDirectionForField($sortingFieldConfig->getField()) != 0) ?
                        \Tx_PtExtlist_Domain_QueryObject_Query::invertSortingState($header->getSortingDirectionForField($sortingFieldConfig->getField()))
                        : $sortingFieldConfig->getDirection()
            );
            $sortingFieldParams[] = $sortingFieldConfig->getField() . ':' . $newSortingDirection;
        }

        $sortingFieldParam = implode(';', $sortingFieldParams);

        // We set sortingDirectionParameter for children of this viewHelper
        $this->templateVariableContainer->add('sortingDirection', $this->getSortingDirectionForHeader($header));

        #echo "Sorting field param for " . $header->getColumnConfig()->getColumnIdentifier() . " = " . $sortingFieldParam . "<br>";
        #echo "Sorting direction for " . $header->getColumnConfig()->getColumnIdentifier() . " = ". $this->getSortingDirectionForHeader($header) . "<br>";

        $gpArrayViewHelper = new GPArrayViewHelper();
        $argumentArray = $gpArrayViewHelper->buildObjectValueArray($header, 'sortingFields', $sortingFieldParam);
        
        $this->sessionPersistenceManagerBuilder->getInstance()->addSessionRelatedArguments($argumentArray);

        $output = parent::render($action, $argumentArray, null, null, null, $pageUid, $pageType, $noCache, $noCacheHash, $section, $format, $linkAccessRestrictedPages, $additionalParams, $absolute, $addQueryString, $argumentsToBeExcludedFromQueryString, $addQueryStringMethod);

        $this->templateVariableContainer->remove('sortingDirection');
        return $output;
    }



    /**
     * Returns sorting state for header
     *
     * We loop over each sorting field of header and take first sorting field
     * that has no forced direction and has a current sorting set in header.
     *
     * @param \Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $header
     * @return integer Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_NONE | Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC | Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC
     */
    protected function getSortingDirectionForHeader(\Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $header)
    {
        $sortingFieldConfigForHeader = $header->getColumnConfig()->getSortingConfig();
        foreach ($sortingFieldConfigForHeader as $sortingFieldConfig) { /* @var $sortingFieldConfig \Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig */
            if (!$sortingFieldConfig->getForceDirection()
                && $header->getSortingDirectionForField($sortingFieldConfig->getField()) != \Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_NONE) {
                return $header->getSortingDirectionForField($sortingFieldConfig->getField());
            }
        }
        return \Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_NONE;
    }
}
