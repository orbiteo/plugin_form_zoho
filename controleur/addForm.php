<?php
require_once($_SERVER["DOCUMENT_ROOT"] .'/wp-config.php');
global $wpdb;

$content = $_POST["formDetails"];
$name = $_POST["formName"];
if(isset($_POST["formId"])) {
	$formId = $_POST["formId"];
}

// These files need to be included as dependencies when on the front end.
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );

//Regarder combien de shortcode existent déjà:
$table_prefix = $wpdb->prefix;
$prefix = $table_prefix.'posts';
$result = $wpdb->get_results("SELECT * FROM `$prefix` WHERE `post_type` = 'orbiteoFormZoho'");
if(!empty($result)) {
	$nbDeShortcodeDejaExistants = count($result);
	$numeroDeShortcodeACree = $nbDeShortcodeDejaExistants+1;
}
else {
	$numeroDeShortcodeACree = 1;
}

if(isset($formId) && !empty($formId)) { //Modif shortcode
	// Create post shortcode
	$shortcode = array(
		'ID'						=> $formId,
		'post_type'    => 'orbiteoFormZoho',
		'post_content'  => $content, //Type de shortcode
		'post_title'    => 'shortcode'.$numeroDeShortcodeACree,
		'post_name'			=> $name,
		'post_status'   => 'publish',
		'post_author'   => 1
	);
}
else { // Create post shortcode
	$shortcode = array(
		'post_type'    => 'orbiteoFormZoho',
		'post_content'  => $content, //Type de shortcode
		'post_title'    => 'shortcode'.$numeroDeShortcodeACree,
		'post_name'			=> $name,
		'post_status'   => 'publish',
		'post_author'   => 1
	);
}
// Insert the post into the database
$shortcode_post_id = wp_insert_post( $shortcode);
//if tout est bien enregistré:
if($shortcode_post_id != 0) {
	wp_redirect(get_site_url().'/wp-admin/options-general.php?page=admin-manage-form_zoho');
	exit;
}
