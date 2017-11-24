<?php
function ccl_maker_front_view( $atts ) {
	$atts = shortcode_atts( array(
		'style' => 'style1',
		'posts_per_page' => 10,
		'order' => 'ASC',
		'orderby' => 'ID', //menu_order
	), $atts, 'ccl-list-maker' );
	global $wpdb;
    $table_country = $wpdb->prefix . 'country';
	$table_levels = $wpdb->prefix . 'levels';
	
	$query_qntry = "SELECT * FROM $table_country";
	$result_qntry = $wpdb->get_results($query_qntry) or die(mysql_error());

	$query_lvl = "SELECT * FROM $table_levels";
	$result_lvl = $wpdb->get_results($query_lvl) or die(mysql_error());
	//print_r($result_qntry);
	
	 ob_start(); 
	 include dirname( __FILE__ ) . '/templates/view.php';
	 $content = ob_get_clean();
	 return $content;
	 
}
add_shortcode( 'ccl-list-maker', 'ccl_maker_front_view' );


function ccl_script_plugin() {

	wp_enqueue_style( 'ccl-style', plugins_url('templates/css/ccl-style.css', __FILE__), array() );
    wp_register_script( 'ccl_maker_script', plugins_url('templates/js/script.js', __FILE__), array('jquery'));
    wp_enqueue_script( 'ccl_maker_script' );
	wp_localize_script( 'ccl_maker_script', 'ajax_object', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));
}
add_action( 'wp_enqueue_scripts', 'ccl_script_plugin' );

// Ajax get versity by country
add_action( 'wp_ajax_get_ccl_versity', 'get_ccl_versity' );
add_action( 'wp_ajax_nopriv_get_ccl_versity', 'get_ccl_versity' );
function get_ccl_versity(){
	global $wpdb;
    $table_versity_list = $wpdb->prefix . 'versity_list';
	$cntry_id=$_POST['cntry_id'];

	$query = "SELECT * FROM $table_versity_list where country_id='$cntry_id'";

	$result = $wpdb->get_results($query);
	//print_r($result);
	//exit;
	$data = array();
	$i=0;
	if(count($result)>0){
		foreach ($result as $row) {
		        //$cat_id=$row->id;
		        $data[$i]['versityId']=$row->versity_id;
		        $data[$i]['versityTilte']=$row->versity_tilte;
		        $i++;
		}
		$results['type']="success";
		$results['data']=$data;
	}else{
		$results['type']="fail";
		$results['data']="";
	}
	
	echo json_encode($results);
	//print_r($results);
	exit;
}
// Ajax get versity by country
add_action( 'wp_ajax_get_ccl_course_data', 'get_ccl_course_data' );
add_action( 'wp_ajax_nopriv_get_ccl_course_data', 'get_ccl_course_data' );
function get_ccl_course_data(){
	global $wpdb;
	$versity_id=$_POST['versity_id'];
	$level_id=$_POST['level_id'];
    $table_course_list = $wpdb->prefix . 'course_list';
    $table_versity_list = $wpdb->prefix . 'versity_list';
    $table_levels = $wpdb->prefix . 'levels';
	if($level_id!==""){
		
		$query = "SELECT $table_course_list.*,$table_versity_list.versity_tilte, $table_levels.title as level_title FROM $table_course_list Left join $table_versity_list on $table_versity_list.versity_id=$table_course_list.course_university left join $table_levels on  $table_levels.id=$table_course_list.course_level where course_university='$versity_id' and course_level='$level_id'";
	}else{
		$query = "SELECT $table_course_list.*,$table_versity_list.versity_tilte, $table_levels.title as level_title FROM $table_course_list Left join $table_versity_list on $table_versity_list.versity_id=$table_course_list.course_university left join $table_levels on  $table_levels.id=$table_course_list.course_level where course_university='$versity_id'";	
	}

	$result = $wpdb->get_results($query);
	$data = array();
	$ielts_requirements_arr = array();
	$academic_requirements_arr = array();
	$i=0;
	if(count($result)>0){
		foreach ($result as $row) {
			$ielts_requirements=json_decode($row->ielts_requirements, true);
			foreach ($ielts_requirements as $key => $value) {
				$ielts_requirements_arr[$key]=$value;
			}
			$academic_requirements=json_decode($row->academic_requirements, true);
			foreach ($academic_requirements as $key2 => $value2) {
				$academic_requirements_arr[$key2]=$value2;
			}
	        $data[$i]['courseId']=$row->course_id;
	        $data[$i]['courseName']=$row->course_name;
	        $data[$i]['courseAwardingBody']=$row->course_awarding_body;
	        $data[$i]['courseCode']=$row->course_code;
	        $data[$i]['courseDuration']=$row->course_duration;
	        $data[$i]['courseFaculty']=$row->course_faculty;
	        $data[$i]['levelId']=$row->course_level;
	        $data[$i]['levelTitle']=$row->level_title;
	        $data[$i]['courseUrl']=$row->course_url;
	        $data[$i]['versityId']=$row->course_university;
	        $data[$i]['versityName']=$row->versity_tilte;
	        $data[$i]['currencyName']=$row->currency_name;
	        $data[$i]['payable']=$row->payable;
	        $data[$i]['tutionFee']=$row->tution_fee;
	        $data[$i]['ieltsRequirements']=json_encode($ielts_requirements_arr);
	        $data[$i]['academicRequirements']=json_encode($academic_requirements);
	        $i++;
		}
		$results['type']="success";
		$results['data']=$data;
	}else{
		$results['type']="fail";
		$results['data']="";
	}
	$results['qrt']=$query;
	echo json_encode($results);
	exit;
}
?>