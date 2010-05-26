<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/
class Coins_Test_AppTestCase extends Omeka_Test_AppTestCase
{
    const PLUGIN_NAME = 'Coins';
    
    public function setUp()
    {
        parent::setUp();
        
        // Authenticate and set the current user 
        $this->user = $this->db->getTable('User')->find(1);
        $this->_authenticateUser($this->user);
        Omeka_Context::getInstance()->setCurrentUser($this->user);
                
        // Add the plugin hooks and filters (including the install hook)
        $pluginBroker = get_plugin_broker();
        $this->_addPluginHooksAndFilters($pluginBroker, self::PLUGIN_NAME);
        
        // Install the plugin
        $plugin = $this->_installPlugin(self::PLUGIN_NAME);
        $this->assertTrue($plugin->isInstalled());
        
        // Initialize the core resource plugin hooks and filters (like the initialize hook)
        $this->_initializeCoreResourcePluginHooksAndFilters($pluginBroker, self::PLUGIN_NAME);
    }
        
    public function _addPluginHooksAndFilters($pluginBroker, $pluginName)
    {   
        // Set the current plugin so the add_plugin_hook function works
        $pluginBroker->setCurrentPluginDirName($pluginName);
        
        // Add plugin hooks
        add_plugin_hook('install', 'coins_install');
        add_plugin_hook('uninstall', 'coins_uninstall');
        add_plugin_hook('public_append_to_items_show', 'coins');
        add_plugin_hook('admin_append_to_items_show_primary', 'coins');
        add_plugin_hook('public_append_to_items_browse_each', 'coins');
        add_plugin_hook('admin_append_to_items_browse_primary', 'coins_multiple');
    }
    
    public function assertPreConditions()
    {
    }
}