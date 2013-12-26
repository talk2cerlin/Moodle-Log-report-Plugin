<?php 
$errormod = 0;
require_once('../../../config.php');
require_once($CFG->dirroot.'/lib/statslib.php');
require_once($CFG->libdir.'/adminlib.php');
if($errormod == 1){
	error_reporting(E_ALL);
	ini_set ('display_errors', 'on');
	ini_set ('log_errors', 'on');
	ini_set ('display_startup_errors', 'on');
	ini_set ('error_reporting', E_ALL);
	$CFG->debug = DEBUG_ALL;
}
	
global $USER;
if(is_siteadmin($USER->id)){
	if(isset($_POST['query']) && trim($_POST['query']) != null){
		$searchquery = $_POST['query'];
		$query = "SELECT id,firstname,lastname,username,email FROM `mdl_user` WHERE firstname COLLATE UTF8_GENERAL_CI LIKE '".$searchquery."%'";
		$returndata = $DB->get_records_sql($query);
		$increment = 0;
		foreach($returndata as $finaldata){
			$userdata[$increment]['id'] = $finaldata->id;
			$userdata[$increment]['firstname'] = $finaldata->firstname;
			$userdata[$increment]['lastname'] = $finaldata->lastname;
			$userdata[$increment]['username'] = $finaldata->username;
			$userdata[$increment]['email'] = $finaldata->email;
			$increment++;
		}
		$jsonresponse = json_encode($userdata);
		if($jsonresponse == 'null' || $jsonresponse == null)
			echo "empty";
		else
			echo $jsonresponse;
	}
	elseif(isset($_POST['userid']) && trim($_POST['userid']) != null){
		
	}
}
