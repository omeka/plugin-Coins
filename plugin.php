<?php
add_plugin_hook('append_to_item_show', 'COinS');


function COinS($item)
{
    // @todo: devise a better mapping scheme between omeka and COinS/Zotero
    switch ($item->Type->name) {
        case 'Oral History':
             $rft_type = 'interview';
             break;
        case 'Moving Image':
            $rft_type = 'videoRecording';
            break;
        case 'Sound':
            $rft_type = 'audioRecording';
            break;
        case 'Website':
            $rft_type = 'webPage';
            break;
        case 'Email':
            $rft_type = 'e-mail';
            break;
        case 'Hyperlink':
            $rft_type = 'webPage';
            break;
        case 'Document':
        case 'Event':
        case 'Lesson Plan':
        case 'Person':
        case 'Interactive Resource':
        case 'Still Image':
        default:
            $rft_type = 'document';
            break;
    }
    
    $rft_title          = $item->title;
    $rft_creator        = $item->creator;
    $rft_subject        = $item->subject;
    $rft_description    = $item->description;
    $rft_publisher      = $item->publisher;
    $rft_contributor    = $item->contributor;
    $rft_date           = $item->date;
    // @todo: include format
    $rft_format         = '';//$item->format;
    $rft_identifier     = WEB_ROOT . DIRECTORY_SEPARATOR . 'item' . DIRECTORY_SEPARATOR . 'show' . DIRECTORY_SEPARATOR . $item->id;
    $rft_source         = $item->source;
    // @todo: somehow include spacial coverage?
    $rft_coverage       = $item->temporal_coverage_start . '–' . $item->temporal_coverage_end;
    $rft_rights         = $item->rights;
    
    $COinS = array(
        'ctx_ver'           => 'Z39.88-2004', 
        'rft_val_fmt'       => 'info:ofi/fmt:kev:mtx:dc', 
        'rfr_id'            => 'info:sid/omeka.org:generator', 
        'rft.title'         => $rft_title, 
        'rft.creator'       => $rft_creator, 
        'rft.subject'       => $rft_subject, 
        'rft.description'   => $rft_description, 
        'rft.publisher'     => $rft_publisher, 
        'rft.contributor'   => $rft_contributor, 
        'rft.date'          => $rft_date, // yyyy-mm-dd
        'rft.type'          => $rft_type, 
        'rft.format'        => $rft_format, 
        'rft.identifier'    => $rft_identifier, 
        'rft.source'        => $rft_source, 
        'rft.language'      => $rft_language, 
        'rft.coverage'      => $rft_coverage, 
        'rft.rights'        => $rft_rights
    );
?>
<span 
    class="Z3988" 
    title="<?php echo http_build_query($COinS, '', '&amp;'); ?>">
</span>
<?php
}
?>