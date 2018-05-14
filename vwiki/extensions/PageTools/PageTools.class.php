<?php
/**
 * File holding the PageTools class
 *
 * @copyright (C) 2013, Stephan Gambke
 * @license       http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 (or later)
 *
 * This file is part of the MediaWiki extension PageTools.
 * The PageTools extension is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The PageTools extension is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @file
 * @ingroup       PageTools
 */

/**
 * Class PageTools contains parser function and hook handlers of the PageTools extension
 */
class PageTools {

	static private $titleAddition = '';

	/**
	 * Render {{#pageincategory:}}. Returns 1 if the current page is in the given category.
	 *
	 * @param Parser $parser
	 * @param String $catname
	 *
	 * @return string
	 */
	static public function renderPageInCategory( Parser &$parser, $catname ) {

		$cat = Title::newFromText( (string)$catname, NS_CATEGORY );

		return is_null( $cat ) ? '' : array_key_exists( $cat->getNsText() . ':' . $cat->getDBkey(), $parser->getTitle()->getParentCategories() );
	}

	/**
	 * Render {{#pagenumcategories:}}. Returns the number of categories the current page is in.
	 *
	 * @param Parser $parser
	 *
	 * @return int
	 */
	static public function renderPageNumCategories( Parser &$parser ) {

		return count( $parser->getTitle()->getParentCategories() );
	}

	/**
	 * Render {{#pagecategory:}}. Returns the full name of the category at the given index number from the list of
	 * categories of the current page. Indexes start at 0.
	 *
	 * @param Parser $parser
	 * @param string $index
	 *
	 * @return mixed
	 */
	static public function renderPageCategory( Parser &$parser, $index ) {

		if ( is_numeric( $index ) ) {

			$categories = array_keys( $parser->getTitle()->getParentCategories() );

			if ( array_key_exists( (int)$index, $categories ) ) {
				return str_replace( '_', ' ', $categories[ (int)$index ] );
			}
		}

		return '';
	}

	/**
	 * Render {{#pagetitle:}}. Sets the page title of the current page to the given new title.
	 *
	 * @param Parser $parser
	 * @param string $newTitle
	 */
	static public function renderPageTitle( Parser &$parser, $newTitle ) {

		$parser->getOutput()->setTitleText( (string)$newTitle );
	}

	/**
	 * Render {{#pagetitleadd:}}. Adds the given title add-on to the page title of the current page.
	 *
	 * @param Parser $parser
	 * @param string $titleAddon
	 */
	static public function renderPageTitleAdd( Parser &$parser, $titleAddon ) {

		self::$titleAddition .= ' ' . (string)$titleAddon;

		global $wgHooks;
		$wgHooks[ 'BeforePageDisplay' ][ 'extension.PageTools' ] = 'PageTools::handleBeforePageDisplay';
	}

	/**
	 * Render {{#pagesubtitle:}}. Adds the given subtitle to the page subtitle of the current page.
	 *
	 * @param Parser $parser
	 * @param string $subtitle
	 */
	static public function renderPageSubtitle( Parser &$parser, $subtitle ) {

		RequestContext::getMain()->getOutput()->addSubtitle( (string)$subtitle );
	}

	/**
	 * Handles the BeforePageDisplay hook. Used to add an add-on to the current page's title.
	 *
	 * @param OutputPage $out
	 * @param Skin       $skin
	 *
	 * @return bool
	 */
	static public function handleBeforePageDisplay( OutputPage &$out, Skin &$skin ) {

		$out->setPageTitle( $out->getPageTitle() . self::$titleAddition );

		return true; // continue chain.
	}
}
