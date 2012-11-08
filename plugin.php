<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2007-2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Coins
 */

add_plugin_hook('public_items_show', 'coins');
add_plugin_hook('admin_items_show', 'coins');
add_plugin_hook('public_items_browse_each', 'coins');
add_plugin_hook('admin_items_browse_simple_each', 'coins');
add_plugin_hook('admin_items_browse_detailed_each', 'coins');

require_once dirname(__FILE__) . '/functions.php';
