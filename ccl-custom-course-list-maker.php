<?php
/**
* Plugin Name: Custom Course List Maker
* Plugin URI: https://github.com/tjthouhid/edd-license-activator-notifier
* Description: This is a plugin for make coustom course list shorted by country,versity and level.
* Version: 1.0.1
* Author: Tj Thouhid
* Author URI: https://www.tjthouhid.com
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */


/**
 *  Creating Database on active
 */

global $jal_db_version;
$jal_db_version = '1.0';

function ccl_maker_tbl_jal_install() {

	global $wpdb;
	$installed_ver = get_option( "ccl_notification_plugin_db_version" );

	if ( $installed_ver != $jal_db_version ) {
		
		make_ccl_maker_db();
		update_option( 'ccl_notification_plugin_db_version', $jal_db_version );
	}else{
		
		make_ccl_maker_db();
		add_option( 'ccl_notification_plugin_db_version', $jal_db_version );

	}
}
register_activation_hook( __FILE__, 'ccl_maker_tbl_jal_install' );

function make_ccl_maker_db(){
	global $wpdb;
	//$table_name = $wpdb->prefix . 'edd_notification_tbl';
	$table_country = $wpdb->prefix . 'country';
	$table_course_list = $wpdb->prefix . 'course_list';
	$table_levels = $wpdb->prefix . 'levels';
	$table_versity_list = $wpdb->prefix . 'versity_list';
	
	$charset_collate = $wpdb->get_charset_collate();
	$sql="";

// -- Create Country list table
$sql.="
	CREATE TABLE $table_country (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `title` varchar(255) NOT NULL,
	  PRIMARY KEY  (id)
	)$charset_collate;";

// -- Create course list table
$sql.="CREATE TABLE $table_course_list (
  `course_id` int(25) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(255) NOT NULL,
  `course_awarding_body` varchar(255) NOT NULL,
  `course_code` varchar(50) NOT NULL,
  `course_duration` varchar(100) NOT NULL,
  `course_faculty` varchar(100) NOT NULL,
  `course_level` int(25) NOT NULL,
  `course_url` varchar(255) NOT NULL,
  `course_university` int(25) NOT NULL,
  `currency_name` varchar(100) NOT NULL,
  `payable` varchar(100) NOT NULL,
  `tution_fee` varchar(100) NOT NULL,
  `ielts_requirements` text NOT NULL,
  `academic_requirements` text NOT NULL,
  PRIMARY KEY  (course_id)
) $charset_collate;";

// -- Create Levels table
$sql.="CREATE TABLE $table_levels (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY  (id)
)$charset_collate;";

// -- Create versity list table
$sql.="CREATE TABLE $table_versity_list (
  `versity_id` int(11) NOT NULL AUTO_INCREMENT,
  `versity_tilte` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY  (versity_id)
) $charset_collate;";

// -- Constraints for tables 
$sql.= "
ALTER TABLE $table_course_list ADD CONSTRAINT `versity_id_const` FOREIGN KEY (`course_university`) REFERENCES $table_versity_list(`versity_id`) ON DELETE RESTRICT ON UPDATE RESTRICT; ALTER TABLE $table_course_list ADD CONSTRAINT `level_id_const` FOREIGN KEY (`course_level`) REFERENCES $table_levels(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;ALTER TABLE $table_versity_list` ADD CONSTRAINT `country_id_cost` FOREIGN KEY (`country_id`) REFERENCES $table_country`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
";


// -- inserting initial data for country table
$sql.= "
INSERT INTO $table_country (`id`, `title`) VALUES
(null, 'India'),
(null, 'Australia'),
(null, 'New Zealand'),
(null, 'Canada'),
(null, 'UK'),
(null, 'USA'),
(null, 'Ireland'),
(null, 'France'),
(null, 'Germany'),
(null, 'Slovenia'),
(null, 'Cyprus'),
(null, 'Singapore'),
(null, 'Spain'),
(null, 'Czech Republic'),
(null, 'Hungary'),
(null, 'Italy'),
(null, 'Malaysia'),
(null, 'Netherland'),
(null, 'Russia'),
(null, 'Switzerland'),
(null, 'United Arab Emirates'),
(null, 'Latvia'),
(null, 'Malta'),
(null, 'China'),
(null, 'Denmark'),
(null, 'Bulgaria'),
(null, 'Thailand'),
(null, 'Brazil'),
(null, 'Philippines'),
(null, 'Poland'),
(null, 'Lithuania'),
(null, 'Sweden'),
(null, 'Nepal'),
(null, 'Austria'),
(null, 'outh Kore'),
(null, 'Portugal');";

// -- inserting initial data for levels table
$sql.= "
INSERT INTO $table_levels (`id`, `title`) VALUES
(null, 'Masters'),
(null, 'Bachelors'),
(null, 'Diplomas'),
(null, 'Advance Diploma'),
(null, 'Graduate Diploma'),
(null, 'Post Graduate Diploma'),
(null, 'Certificate'),
(null, 'Schooling'),
(null, 'Associate Degree'),
(null, 'Foundation or Preparatory Programs'),
(null, 'Graduate Certificate');";



	

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
/**
 * Delete Database On Deactive
 */
register_deactivation_hook( __FILE__, 'ccl_maker_plugin_remove_database' );
function ccl_maker_plugin_remove_database() {
    global $wpdb;
    $table_country = $wpdb->prefix . 'country';
 	$table_course_list = $wpdb->prefix . 'course_list';
 	$table_levels = $wpdb->prefix . 'levels';
 	$table_versity_list = $wpdb->prefix . 'versity_list';
    $sql = "DROP TABLE IF EXISTS $table_country, $table_course_list, $table_levels,$table_versity_list;";
 
     $wpdb->query($sql);
     delete_option("ccl_notification_plugin_db_version");
}   



// Adding functions
include "function.php";