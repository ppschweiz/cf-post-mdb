<?php
/**
 * Plugin Name: Caldera Forms - Post to MDB
 * Plugin URI:  
 * Description: Posts to MDB
 * Version:	0.0.1
 * Author:      Stefan Thoeni
 * Author URI:	https://stefanthoeni.ch
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Translation Domain: cf-post-mdb
 */


add_filter('caldera_forms_get_form_processors', 'cf_post_mdb_register_processor');
/**
 * Add processor
 *
 * @uses "caldera_forms_get_form_processors" filter
 *
 * @since 1.0.0
 *
 * @return array Processors
 */
function cf_post_mdb_register_processor($pr){
	$pr['post_mdb'] = array(
		"name"              =>  __('Post to MDB'),
		"description"       =>  __("Post to MDB on submission"),
		"author"            =>  'Stefan Thoeni',
		"author_url"        =>  'https://stefanthoeni.ch',
		"pre_processor"     =>  'cf_post_mdb_pre_process',
		"processor"         =>  'cf_post_mdb_process',
		"post_processor"    =>  'cf_post_mdb_post_process',
		"template"          =>  plugin_dir_path(__FILE__) . "config.php",
	);

	return $pr;
}

/**
 * Callback function for the pre processor
 *
 * @since 1.0.0
 *
 * @param array $config Processor settings. Key 'action' has action name.
 * @param array $form Form submission data.
 */
function cf_post_mdb_pre_process( $config, $form){
	
	$data = array();
	foreach($form['fields'] as $field_id=>$field){
		$data[$field['slug']] = Caldera_Forms::get_field_data($field_id, $form);
	}
}


/**
 * Callback function for the processor
 *
 * @since 1.0.0
 *
 * @param array $config Processor settings. Key 'action' has action name.
 * @param array $form Form submission data.
 */
function cf_post_mdb_process( $config, $form){

	$data = array();
	foreach($form['fields'] as $field_id=>$field){
		$data[$field['slug']] = Caldera_Forms::get_field_data($field_id, $form);
	}

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_URL, "https://registration.piratenpartei.ch/Post.aspx");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	$result = curl_exec($curl);

	curl_close($curl);
}


/**
 * Callback function for the post processor
 *
 * @since 1.0.0
 *
 * @param array $config Processor settings. Key 'action' has action name.
 * @param array $form Form submission data.
 */
function cf_post_mdb_post_process( $config, $form){
	
	$data = array();
	foreach($form['fields'] as $field_id=>$field){
		$data[$field['slug']] = Caldera_Forms::get_field_data($field_id, $form);
	}
}

