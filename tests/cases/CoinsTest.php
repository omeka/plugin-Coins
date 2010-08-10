<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class CoinsTest extends Omeka_Test_AppTestCase
{
    public function setUp()
    {
        parent::setUp();
        
        $pluginHelper = new Omeka_Test_Helper_Plugin;
        $pluginHelper->setUp('Coins');
    }

    public function testCoinsOnItemPage()
    {
        $titleText = 'Wow';
        $item = $this->_addItem($titleText);
        $this->assertTrue($item->exists());

        $_SERVER['HTTP_HOST'] = 'localhost';

        $this->dispatch('/items/show/1');

        $identifierUrl = 'http://localhost/items/show/1';

        $coinsSpanExpected = '<span class="Z3988" title="ctx_ver=Z39.88-2004&amp;rft_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Adc&amp;rfr_id=info%3Asid%2Fomeka.org%3Agenerator&amp;rft.title=' . urlencode($titleText) . '&amp;rft.type=document&amp;rft.identifier=' . urlencode($identifierUrl) . '"></span>';
 
        ob_start();
        coins();
        $coinsSpanActual = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($coinsSpanExpected, $coinsSpanActual);
    }
    
    protected function _addItem($titleText)
    {
        $metadata = array(
          'public' => true,
          'featured' => false,
          /*
          'collection_id' => '',
          'item_type_id' => '',
          'item_type_name' => '',
          'tags' => '',
          'tag_entity' => '', 
          'overwriteElementTexts' => '',
          */
        );
        
        $elementTexts = array(
            'Dublin Core' => array(
                'Title' => array(
                    array('text' => $titleText, 'html' => false)
                 ),
            ),
        );
        
        $fileMetadata = array(
        );
        
        $item = insert_item($metadata, $elementTexts, $fileMetadata);
        return $item;      
    }
}