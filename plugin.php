<?php
add_plugin_hook('append_to_item_show', 'COinS');
add_plugin_hook('append_to_items_browse', 'COinSMultiple');

function COinS($item)
{
    $coins = new COinS($item);
    echo $coins->getCoinsSpan();
}

function COinSMultiple($items)
{
    foreach ($items as $item) {
        COinS($item);
    }
}

class COinS
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
        
        $this->setTitle();
        $this->setCreator();
        $this->setSubject();
        $this->setDescription();
        $this->setPublisher();
        $this->setContributor();
        $this->setDate();
        $this->setType();
        $this->setFormat();
        $this->setIdentifier();
        $this->setSource();
        $this->setLanguage();
        $this->setCoverage();
        $this->setRights();
        $this->setRelation();
        
        $this->buildCoinsSpan();
    }
    private function setTitle()
    {
        $this->_coins['rft.title'] = item('Title', 
                                          array('element_set' => self::ELEMENT_SET_DUBLIN_CORE, 
                                                'index'       => self::ELEMENT_TEXT_INDEX));
    }
    private function setCreator()
    {
        $this->_coins['rft.creator'] = item('Creator', 
                                            array('element_set' => self::ELEMENT_SET_DUBLIN_CORE, 
                                                  'index'       => self::ELEMENT_TEXT_INDEX));
    }
    private function setSubject()
    {
        $this->_coins['rft.subject'] = item('Subject', 
                                            array('element_set' => self::ELEMENT_SET_DUBLIN_CORE, 
                                                  'index'       => self::ELEMENT_TEXT_INDEX));
    }
    private function setDescription()
    {
        // Truncate to avoid long descriptions.
        $this->_coins['rft.description'] = substr(item('Description', 
                                                       array('element_set' => self::ELEMENT_SET_DUBLIN_CORE, 
                                                             'index'       => self::ELEMENT_TEXT_INDEX)), 0, 500);
    }
    private function setPublisher()
    {
        $this->_coins['rft.publisher'] = item('Publisher', 
                                              array('element_set' => self::ELEMENT_SET_DUBLIN_CORE, 
                                                    'index'       => self::ELEMENT_TEXT_INDEX));
    }
    private function setContributor()
    {
        $this->_coins['rft.contributor'] = item('Contributor', 
                                                array('element_set' => self::ELEMENT_SET_DUBLIN_CORE, 
                                                      'index'       => self::ELEMENT_TEXT_INDEX));
    }
    private function setDate()
    {
        $this->_coins['rft.date'] = item('Date', 
                                         array('element_set' => self::ELEMENT_SET_DUBLIN_CORE, 
                                               'index'       => self::ELEMENT_TEXT_INDEX));
    }
    private function setType()
    {
        /**
         * Get the type from the Item Type name, not the Dublin Core type name.
         * @todo: devise a better mapping scheme between Omeka and COinS/Zotero
         */
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
    private function setFormat()
    {
        $this->_coins['rft.format'] = item('Format', 
                                           array('element_set' => self::ELEMENT_SET_DUBLIN_CORE, 
                                                 'index'       => self::ELEMENT_TEXT_INDEX));
    }
    private function setIdentifier()
    {
        $this->_coins['rft.identifier'] = $_SERVER['SCRIPT_URI'];
    }
    private function setSource()
    {
        $this->_coins['rft.source'] = item('Source', 
                                           array('element_set' => self::ELEMENT_SET_DUBLIN_CORE, 
                                                 'index'       => self::ELEMENT_TEXT_INDEX));
    }
    private function setLanguage()
    {
        $this->_coins['rft.language'] = item('Language', 
                                             array('element_set' => self::ELEMENT_SET_DUBLIN_CORE, 
                                                   'index'       => self::ELEMENT_TEXT_INDEX));
    }
    private function setCoverage()
    {
        $this->_coins['rft.coverage'] = item('Coverage', 
                                              array('element_set' => self::ELEMENT_SET_DUBLIN_CORE, 
                                                    'index'       => self::ELEMENT_TEXT_INDEX));
    }
    private function setRights()
    {
        $this->_coins['rft.rights'] = item('Rights', 
                                           array('element_set' => self::ELEMENT_SET_DUBLIN_CORE, 
                                                 'index'       => self::ELEMENT_TEXT_INDEX));
    }
    private function setRelation()
    {
        $this->_coins['rft.relation'] = item('Relation', 
                                             array('element_set' => self::ELEMENT_SET_DUBLIN_CORE, 
                                                   'index'       => self::ELEMENT_TEXT_INDEX));
    }
    private function buildCoinsSpan()
    {
        $this->_coinsSpan = '<span class="' . self::COINS_SPAN_CLASS . '" title="' . http_build_query($this->_coins, '', '&amp;') . '"></span>';
    }
}