<?php
/**
 * Template Name: LGM participants list
 * Description: A Page Template that shows entries from grafity forms' form for LGM registration
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

function debug($label, $value) {
    echo("<p>$label<br /><pre>".htmlentities(print_r($value, 1))."</pre></p>");
}
?>

<?php
// ==== OUTPUT ====
?>


<?php get_header(); ?>
<?php get_sidebar(); ?>

<div id="content" role="main">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; ?>
<?php endif; ?>

<?php
/** read the list of fields */
$row = $wpdb->get_results( "SELECT display_meta FROM ".$wpdb->prefix."rg_form_meta WHERE form_id = 2", ARRAY_A );
// debug('row', $row);
$field = array();
foreach ($row as $item) {
    // debug('item', $item);
    $iitem = unserialize($item['display_meta']);
    // debug('entry', $entry);
    foreach ($iitem['fields'] as $iiitem) {
        $field[$iiitem['id']] = $iiitem['label'];
    }
}
// debug('field', $field);

/*
   [19] => First name
    [20] => Last name
    [5] => Nickname
    [31] => Project or organization
    [12] => Email
    [22] => Website
    [26] => List of participants
    [24] => Organizing team
    [11] => I will be attending LGM on:
    [10] => I am interested in the following post-LGM activities:
    [13] => Travelling from:
    [14] => I will travel (mostly) by:
    [15] => Distance
    [21] => What would you like to happen at LGM 2014?
    [9] => Comments
    [29] => Status of participant
    [30] => Notes about status of participant
*/

/** read the entries */
$row = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."rg_lead_detail WHERE form_id = 2 AND field_number IN (19, 20, 31, 5, 22, 26) ORDER BY lead_id DESC", ARRAY_A  );
// debug('row', $row);

$entry = array();
foreach ($row as $item) {
    if (!array_key_exists($item['lead_id'], $entry)) {
        $entry[$item['lead_id']] = array (
            'firstname' => '',
            'lastname' => '',
            'nickmane' => '',
            'project' => '',
            'role' => '',
            'url' => '',
            'show' => '',
        );
    }
    // debug('item', $item);
    if ((($item['field_number'] != 31) || (strlen($item['value']) > 8)) && ($item['value'] == strtoupper($item['value']))) {
        $item['value'] = ucwords(strtolower($item['value']));
    }
    switch ($item['field_number']) {
        case 19 :
            $entry[$item['lead_id']]['firstname'] = $item['value'];
        break;
        case 20 :
            $entry[$item['lead_id']]['lastname'] = $item['value'];
        break;
        case 5 :
            $entry[$item['lead_id']]['nickmane'] = $item['value'];
        break;
        case 31 :
            $entry[$item['lead_id']]['project'] = $item['value'];
        break;
        case 4 :
            $entry[$item['lead_id']]['role'] = $item['value'];
        break;
        case 22 :
            $entry[$item['lead_id']]['url'] = str_replace('http://', '', $item['value']);
        break;
        case 26 :
            $entry[$item['lead_id']]['show'] = substr($item['value'], 0, 3) == 'Yes';
        break;
    }
}
// debug('entry', $entry);

// OUTPUT OF THE CONTENT

foreach ($entry as $item) {
    if ($item['show']) {
        echo('<p>'.(!empty($item['url']) ? '<a href="http://'.$item['url'].'">': '').$item['firstname'].' '.$item['lastname'].(!empty($item['url']) ? '</a>': '').(!empty($item['nickmane']) ? ' ('.$item['nickmane'].')' : '').'<br />'.$item['project'].(!empty($item['role']) ? ' ('.$item['role'].')' : '').'</p>');
    }
}


?>

</div> <?php // #content ?>


<?php get_footer(); ?>
