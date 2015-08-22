<?php
/*
Plugin Name: Pricing
Plugin URI: http://fishinglounge.com
Description: This plugin adds custom pricing fields to the Events Calendar.
Version: 1.0
Author: Michael Church
Author URI: http://fishinglounge.com
License: GPLv2
*/

// Add the Meta Box
function add_event_cost_meta_box() {
  $types = array( 'tribe_events' );
  foreach( $types as $type ) {
    add_meta_box(
    'custom_meta_box', // $id
    'Event Cost', // $title
    'show_event_cost_meta_box', // $callback
    $type, // $page
    'side', // $context
    'high'); // $priority
  }
}
add_action('add_meta_boxes', 'add_event_cost_meta_box');



// Field Array
$prefix = 'custom_';
$custom_event_cost_meta_fields = array(
  array(
    'label'=> 'Adult Pricing',
    'desc'  => '',
    'id'  => $prefix.'adult',
    'type'  => 'text'
  ),
  array(
    'label'=> 'Children Pricing',
    'desc'  => '',
    'id'  => $prefix.'children',
    'type'  => 'text'
  ),
    array(
    'label'=> 'Other Pricing',
    'desc'  => '',
    'id'  => $prefix.'other',
    'type'  => 'text'
  ),
);

// The Callback
function show_event_cost_meta_box() {
global $custom_event_cost_meta_fields, $post;
// Use nonce for verification
echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
// Begin the field table and loop
echo '<table>';
foreach ($custom_event_cost_meta_fields as $field) {
  // get value of this field if it exists for this post
  $meta = get_post_meta($post->ID, $field['id'], true);
  // begin a table row with
  echo '<tr>
      <td>
      <label for="'.$field['id'].'" style="display:block; font-weight:bold">'.$field['label'].'</label>';
      switch($field['type']) {
      
      // Text
      case 'text':
        echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" style="width:100%;" /><br /><span class="description"><small>'.$field['desc'].'</small></span>';
      break;
        
      } //end switch
  echo '</td></tr>';
} // end foreach
echo '</table>'; // end table
}

// Save the Data
function save_event_cost_meta($post_id) {
    global $custom_event_cost_meta_fields;
  // verify nonce
  if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
    return $post_id;
  // check autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
    return $post_id;
  // check permissions
  if ('page' == $_POST['post_type']) {
    if (!current_user_can('edit_page', $post_id))
      return $post_id;
    } elseif (!current_user_can('edit_post', $post_id)) {
      return $post_id;
  }
  // loop through fields and save the data
  foreach ($custom_event_cost_meta_fields as $field) {

    $old = get_post_meta($post_id, $field['id'], true);
    $new = $_POST[$field['id']];
    if ($new && $new != $old) {
      update_post_meta($post_id, $field['id'], $new);
    } elseif ('' == $new && $old) {
      delete_post_meta($post_id, $field['id'], $old);
    }
  } // end foreach
}
add_action('save_post', 'save_event_cost_meta');


// Get and return the values for the URL and description
function get_event_cost_meta() {
  global $post;
  $custom_adult = get_post_meta($post->ID, 'custom_adult', true); //0
  $custom_children = get_post_meta($post->ID, 'custom_children', true); //1
  $custom_other = get_post_meta($post->ID, 'custom_other', true); //1

  return array(
    $custom_adult,
    $custom_children,
    $custom_other
  );
}