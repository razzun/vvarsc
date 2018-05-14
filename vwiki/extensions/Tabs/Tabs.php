<?php
/**
 * This file is part of the Tabs Extension to MediaWiki
 * http://example.com
 *
 * @author Pim (Joeytje50)
 *
 * @section LICENSE
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

$wgExtensionCredits['parserhook'][] = array(
	'path'           => __FILE__,
	'name'           => 'Tabs',
	'author'         => 'Joeytje50',
	'url'            => 'https://www.mediawiki.org/wiki/Extension:Tabs',
	'descriptionmsg' => 'tabs-desc',
	'version'        => '1.3.2',
	'license-name'   => 'GPL-2.0+'
);

$dir = __DIR__ . '/';
$wgMessagesDirs['Tabs'] = __DIR__ . '/i18n';
$wgAutoloadClasses['Tabs'] = $dir . 'Tabs.body.php';
$wgExtensionMessagesFiles['Tabs'] =  $dir . 'Tabs.i18n.php';
$wgExtensionMessagesFiles['TabsMagic'] =  $dir . 'Tabs.i18n.magic.php';
$wgHooks['ParserFirstCallInit'][] = 'Tabs::init';
$wgResourceModules['ext.tabs'] = array(
	'scripts' => 'ext.tabs.js',
	'styles' => 'ext.tabs.css',
	'messages' => array(
		'tabs-tab-label',
		'tabs-toggle-open',
		'tabs-toggle-close',
		'tabs-dropdown-label',
		'tabs-dropdown-bgcolor',
	),
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'Tabs',
	'position' => 'top',
);
