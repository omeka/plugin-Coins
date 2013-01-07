<?php
/**
 * COinS
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The COinS plugin.
 * 
 * @package Omeka\Plugins\Coins
 */
class CoinsPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'initialize',
        'public_items_show',
        'admin_items_show',
        'public_items_browse',
        'admin_items_browse',
    );
    
    /**
     * Initialize the plugin.
     */
    public function hookInitialize()
    {
        // Add the view helper directory to the stack.
        try {
            get_view()->addHelperPath(dirname(__FILE__) . '/views/helpers', 'Coins_View_Helper_');
        } catch (Zend_Exception $e) {
            // view not loaded, do nothing
        }
    }
    
    /**
     * Print out the COinS span on the public items show page.
     */
    public function hookPublicItemsShow()
    {
        echo get_view()->coins(get_current_record('item'));
    }
    
    /**
     * Print out the COinS span on the admin items show page.
     */
    public function hookAdminItemsShow()
    {
        echo get_view()->coins(get_current_record('item'));
    }
    
    /**
     * Print out the COinS span on the public items browse page.
     */
    public function hookPublicItemsBrowse()
    {
        echo get_view()->coins(get_loop_records('items'));
    }
    
    /**
     * Print out the COinS span on the admin items browse page.
     */
    public function hookAdminItemsBrowse()
    {
        echo get_view()->coins(get_loop_records('items'));
    }
}
