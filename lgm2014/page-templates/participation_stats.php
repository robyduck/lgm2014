<?php
/**
 * Template Name: LGM participations stats
 * Description: A Page Template that shows the current stats about the call for participation
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
$row = $wpdb->get_results( "SELECT display_meta FROM ".$wpdb->prefix."rg_form_meta WHERE form_id = 4", ARRAY_A );
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
   [25] => Status for reimbursement
   [15] => Comments on status
   [7] => Title of your presentation
   [18] => Preferred day
   [23] => Time slot
   [24] => Duration
   [2] => First name
   [1] => Last name
   [4] => Email
   [16] => Additional speakers
   [6] => Summary of your presentation
   [5] => Short biography
   [17] => Website
   [8] => Preferred format
   [9] => Slides
   [21] => Slides (PDF)
   [10] => Travel support
   [11] => Travelcosts (if you need support)
   [12] => Currency
   [13] => Comments, questions, other needs
   [14] => Status of proposal
*/

$row = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."rg_lead_detail WHERE form_id = 4 AND field_number IN (2, 1, 8) ORDER BY lead_id DESC", ARRAY_A  );
// debug('row', $row);

$entry = array();
$n = 0;
$people = array();
$type = array();
$current_1 = "";
$current_2 = "";
function increment($name, $people) {
    if (array_key_exists($name, $people)) {
        $people[$name]++;
    } else {
        $people[$name] = 1;
    }
    return $people;
}
foreach ($row as $item) {
    switch ($item['field_number']) {
        case 1:
            if (!empty($current_2)) {
                $name = $item['value'].' '.$current_2;
                $people = increment($name, $people);
                $current_2 = "";
            } else {
                $current_1 = $item['value'];
            }
        break;
        case 2:
            if (!empty($current_1)) {
                $name = $current_1.' '.$item['value'];
                $people = increment($name, $people);
                $current_1 = "";
            } else {
                $current_2 = $item['value'];
            }
        break;
        case 8:
            $n++;
            if (!array_key_exists($item['value'], $type)) {
                $type[$item['value']] = 0;
            }
            $type[$item['value']]++;
        break;

    }
}
// debug('people', $people);
// debug('type', $type);

$datetime1 = new DateTime();
$datetime2 = new DateTime('2014-01-15');
$interval = $datetime1->diff($datetime2);
echo "<p>Only ".$interval->format('%R%a days')." days left to submit your talk!</p>";
echo sprintf(
    "<p>So far, %d proposals have been submitted by %d people. There are currently %d lightning talks, %d short talks, %d presentations, %d workshops and %d discussions, meetings or hackathons submitted.</p>\n",
    $n,
    count($people),
    array_key_exists('Lightning talk', $type) ? $type['Lightning talk'] : 0,
    $type['Short talk'],
    $type['Presentation'],
    $type['Workshop'],
    $type['Meeting / BOF']
);


?>

</div> <?php // #content ?>


<?php get_footer(); ?>

