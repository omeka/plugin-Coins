<?php

define('COINS_VERSION', '0.2');

add_plugin_hook('install', 'coins_install');
add_plugin_hook('uninstall', 'coins_uninstall');
add_plugin_hook('public_append_to_items_show', 'coins');
add_plugin_hook('admin_append_to_items_show_primary', 'coins');
add_plugin_hook('public_append_to_items_browse_each', 'coins');
add_plugin_hook('admin_append_to_items_browse_primary', 'coins_multiple');

function coins_install()
{
	set_option('coins_version', COINS_VERSION);
}

function coins_uninstall()
{
	delete_option('coins_version');
}

function coins()
{
	$item = get_current_item();
	$coins = new Coins($item);
	echo $coins->getCoinsSpan();
}
 
function coins_multiple()
{
	while(loop_items()) {
		coins();
	}
}
 
class Coins
{
    const COINS_SPAN_CLASS = 'Z3988';
    
    const CTX_VER = 'Z39.88-2004';
    
    const RFT_VAL_FMT = 'info:ofi/fmt:kev:mtx:dc';
    
    const RFR_ID = 'info:sid/omeka.org:generator';
    
    const ELEMENT_SET_DUBLIN_CORE = 'Dublin Core';
    
    const ELEMENT_TEXT_INDEX = 0;
    
    private $_item;
    
    private $_coins = array();
    
    private $_coinsSpan;
    
    public function getCoinsSpan()
    {
        return $this->_coinsSpan;
    }
    
    public function __construct($item)
    {
        $this->_item = $item;
        
        $this->_coins['ctx_ver']     = self::CTX_VER;
        $this->_coins['rft_val_fmt'] = self::RFT_VAL_FMT;
        $this->_coins['rfr_id']      = self::RFR_ID;
        
        $this->_setTitle();
        $this->_setCreator();
        $this->_setSubject();
        $this->_setDescription();
        $this->_setPublisher();
        $this->_setContributor();
        $this->_setDate();
        $this->_setType();
        $this->_setFormat();
        $this->_setIdentifier();
        $this->_setSource();
        $this->_setLanguage();
        $this->_setCoverage();
        $this->_setRights();
        $this->_setRelation();
        
        $this->_buildCoinsSpan();
    }
    private function _setTitle()
    {
        $this->_coins['rft.title'] = $this->_getElementText('Title');
    }
    private function _setCreator()
    {
        $this->_coins['rft.creator'] = $this->_getElementText('Creator');
    }
    private function _setSubject()
    {
        $this->_coins['rft.subject'] = $this->_getElementText('Subject');
    }
    private function _setDescription()
    {
        // Truncate long descriptions.
        $this->_coins['rft.description'] = substr($this->_getElementText('Description'), 0, 500);
    }
    private function _setPublisher()
    {
        $this->_coins['rft.publisher'] = $this->_getElementText('Publisher');
    }
    private function _setContributor()
    {
        $this->_coins['rft.contributor'] = $this->_getElementText('Contributor');
    }
    private function _setDate()
    {
        $this->_coins['rft.date'] = $this->_getElementText('Date');
    }
    /**
     * Use the type from the Item Type name, not the Dublin Core type name.
     * @todo: devise a better mapping scheme between Omeka and COinS/Zotero
     */
    private function _setType()
    {
        switch ($this->_item->Type->name) {
            case 'Oral History':
                $type = 'interview';
                break;
            case 'Moving Image':
                $type = 'videoRecording';
                break;
            case 'Sound':
                $type = 'audioRecording';
                break;
            case 'Email':
                $type = 'email';
                break;
            case 'Website':
            case 'Hyperlink':
                $type = 'webPage';
                break;
            case 'Document':
            case 'Event':
            case 'Lesson Plan':
            case 'Person':
            case 'Interactive Resource':
            case 'Still Image':
            default:
                $type = 'document';
                break;
        }
        $this->_coins['rft.type'] = $type;
    }
    private function _setFormat()
    {
        $this->_coins['rft.format'] = $this->_getElementText('Format');
    }
    /**
     * Use the current script URI instead of the Dublin Core identifier.
     */
    private function _setIdentifier()
    {
        // Set the identifier as the absolute URL of the current page.
        // @todo Put this, or something like this, in a abs_uri() helper 
        // function.
        $serverProtocol = strtolower(substr($_SERVER['SERVER_PROTOCOL'], 
                                            0, 
                                            strpos($_SERVER['SERVER_PROTOCOL'], '/')));
        $serverName = $_SERVER['SERVER_NAME'];
        $identifier = "$serverProtocol://$serverName" . uri();
        $this->_coins['rft.identifier'] = $identifier;
    }
    private function _setSource()
    {
        $this->_coins['rft.source'] = $this->_getElementText('Source');
    }
    private function _setLanguage()
    {
        $this->_coins['rft.language'] = $this->_getElementText('Language');
    }
    private function _setCoverage()
    {
        $this->_coins['rft.coverage'] = $this->_getElementText('Coverage');
    }
    private function _setRights()
    {
        $this->_coins['rft.rights'] = $this->_getElementText('Rights');
    }
    private function _setRelation()
    {
        $this->_coins['rft.relation'] = $this->_getElementText('Relation');
    }
    
    private function _getElementText($elementName)
    {
        return item(self::ELEMENT_SET_DUBLIN_CORE, $elementName);
    }
    
    private function _buildCoinsSpan()
    {
        $this->_coinsSpan = '<span class="' . self::COINS_SPAN_CLASS . '" title="' . http_build_query($this->_coins, '', '&amp;') . '"></span>';
    }
}