<?php
add_plugin_hook('append_to_item_show', 'COinS');

function COinS($item)
{
    $coins = new COinS($item);
    echo $coins->coinsSpan;
}

class COinS
{
    private $item;
    
    private $coins = array();
    
    const ctx_ver     = 'Z39.88-2004';
    const rft_val_fmt = 'info:ofi/fmt:kev:mtx:dc';
    const rfr_id      = 'info:sid/omeka.org:generator';
    
    public $coinsSpan;
    
    public function __construct($item)
    {
        $this->item = $item;
        
        $this->coins['ctx_ver']     = self::ctx_ver;
        $this->coins['rft_val_fmt'] = self::rft_val_fmt;
        $this->coins['ctx_ver']     = self::ctx_ver;
        $this->coins['rfr_id']      = self::rfr_id;
        
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
        
        $this->buildCoinsSpan();
    }
    private function setTitle()
    {
        $this->coins['rft.title'] = $this->item->title;
    }
    private function setCreator()
    {
        $this->coins['rft.creator'] = $this->item->creator;
    }
    private function setSubject()
    {
        $this->coins['rft.subject'] = $this->item->subject;
    }
    private function setDescription()
    {
        // Truncate to avoid long descriptions.
        $this->coins['rft.description'] = substr($this->item->description, 0, 500);
    }
    private function setPublisher()
    {
        $this->coins['rft.publisher'] = $this->item->publisher;
    }
    private function setContributor()
    {
        $this->coins['rft.contributor'] = $this->item->contributor;
    }
    private function setDate()
    {
        $this->coins['rft.date'] = $this->item->date;
    }
    private function setType()
    {
        // @todo: devise a better mapping scheme between omeka and COinS/Zotero
        switch ($this->item->Type->name) {
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
        $this->coins['rft.type'] = $type;
    }
    private function setFormat()
    {
        // @todo: include format when Omeka is updated to include it.
        $this->coins['rft.format'] = ''; //$this->item->format;
    }
    private function setIdentifier()
    {
        $this->coins['rft.identifier'] = $_SERVER['SCRIPT_URI'];
    }
    private function setSource()
    {
        $this->coins['rft.source'] = $this->item->source;
    }
    private function setLanguage()
    {
        $this->coins['rft.language'] = $this->item->language;
    }
    private function setCoverage()
    {
        // @todo: somehow include spacial coverage?
        $this->coins['rft.coverage'] = $this->item->temporal_coverage_start . '–' . $this->item->temporal_coverage_end;
    }
    private function setRights()
    {
        $this->coins['rft.rights'] = $this->item->rights;
    }
    private function buildCoinsSpan()
    {
        $this->coinsSpan = '<span class="Z3988" title="' . http_build_query($this->coins, '', '&amp;') . '"></span>';
    }
}

/*
// Zotero item types as of 2007-11-01
note
book
bookSection
journalArticle
magazineArticle
newspaperArticle
thesis
letter
manuscript
interview
film
artwork
webpage
attachment
report
bill
case
hearing
patent
statute
email
map
blogPost
instantMessage
forumPost
audioRecording
presentation
videoRecording
tvBroadcast
radioBroadcast
podcast
computerProgram
conferencePaper
document
encyclopediaArticle
dictionaryEntry
*/

/*
// Omeka item types (bundled) as of 2007-11-01
Oral History
Moving Image
Sound
Email
Website
Hyperlink
Document
Event
Lesson Plan
Person
Interactive Resource
Still Image
*/

?>