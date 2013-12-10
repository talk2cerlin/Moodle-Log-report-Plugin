<?php

    require_once('../../../config.php');
    require_once($CFG->dirroot.'/lib/statslib.php');
    require_once($CFG->libdir.'/adminlib.php');
	// error_reporting(E_ALL);
	// ini_set ('display_errors', 'on');
	// ini_set ('log_errors', 'on');
	// ini_set ('display_startup_errors', 'on');
	// ini_set ('error_reporting', E_ALL);
	// $CFG->debug = DEBUG_ALL;
	require_login();
	global $USER;
	
	if(is_siteadmin($USER->id) && isset($_POST['reportfor']) && trim($_POST['reportfor']) != null){
		switch(trim($_POST['reportfor'])){
			case "loginrep":
				break;
			case "courserep":
					if(isset($_POST['fromdate']) && trim($_POST['fromdate']) != null && isset($_POST['todate']) && trim($_POST['todate']) != null && isset($_POST['activity']) && trim($_POST['activity']) != null){
						$activity = trim($_POST['activity']);
						if($activity == 'top10courses'){
							$fromdate = strtotime(trim($_POST['fromdate']));
							$todate = strtotime(trim($_POST['todate']));
							// echo $fromdate." , ".$todate." , ".$activity;
							$returndata = $DB->get_records_sql("
								SELECT COUNT( * ) AS TotalCount, ml.`course` AS CourseID, mc.`fullname` AS CourseName
								FROM  `mdl_log` ml
								JOIN  `mdl_course` mc ON mc.id = ml.course
								WHERE  `module` =  'course'
								AND  `action` =  'view'
								AND TIME >".$fromdate."
								AND TIME <".$todate."
								GROUP BY course
								ORDER BY TotalCount DESC 
								LIMIT 0 , 10"
							);
							$exceldata['totalcount'] = array();
							$exceldata['courseid'] = array();
							$exceldata['coursename'] = array();
							foreach($returndata as $returnobj){
								$exceldata['totalcount'][] = $returnobj->totalcount;
								$exceldata['courseid'][] = $returnobj->courseid;
								$exceldata['coursename'][] = $returnobj->coursename;
							}
							$exceltitle[] = "Total Number of Visits";
							$exceltitle[] = "Course ID";
							$exceltitle[] = "Course Name";
							$excelwrite = new LogReporting();
							$excelwrite->excelReportGenerator($exceltitle,$exceldata);
						}
						elseif($activity == 'top10courseswithuserdetails'){
							$reportingobj = new LogReporting();
							$reportingobj->loadLibrary('PHPExcel');
							$excel = new PHPExcel();
							$fromdate = strtotime(trim($_POST['fromdate']));
							$todate = strtotime(trim($_POST['todate']));
							// echo $fromdate." , ".$todate." , ".$activity;
							
							for($i=0;$i<10;$i++){
								$returndata = $DB->get_records_sql("
									SELECT COUNT( * ) AS TotalCount, ml.`course` AS CourseID, mc.`fullname` AS CourseName
									FROM  `mdl_log` ml
									JOIN  `mdl_course` mc ON mc.id = ml.course
									WHERE  `module` =  'course'
									AND  `action` =  'view'
									AND TIME >".$fromdate."
									AND TIME <".$todate."
									GROUP BY course
									ORDER BY TotalCount DESC 
									LIMIT ". $i ." , 1"
								);
								foreach($returndata as $returnobj){
									$newreturndata = $DB->get_records_sql("
										SELECT COUNT( * ) AS TotalCount,mc.`fullname` AS CourseName,CONCAT(mu.firstname,' ',mu.lastname) as username
										FROM  `mdl_log` ml
										JOIN  `mdl_course` mc ON mc.id = ml.course
										JOIN `mdl_user` mu ON mu.id = ml.userid
										WHERE  `module` =  'course'
										AND  `action` =  'view'
										AND TIME >".$fromdate."
										AND TIME <".$todate."
										AND course = ".$returnobj->courseid."
										GROUP BY USERID
										ORDER BY totalcount DESC"
									);
									$alphas = range('A', 'Z');
									if($i == 0){
										$excel->setActiveSheetIndex($i);
										$excel->getActiveSheet()->setTitle(substr($returnobj->coursename,0,29));
									}
									else{
										$excel->createSheet($i);
										$excel->setActiveSheetIndex($i);
										$excel->getActiveSheet()->setTitle(substr($returnobj->coursename,0,29)); // Since the maximum length of sheet name is 30
									}
									$exceltitle = array();
									$exceltitle[] = "Total Number of Visits";
									$exceltitle[] = "Course Name";
									$exceltitle[] = "User Name";
									$increment = 0;
									foreach($exceltitle as $title){
										$excel->getActiveSheet()->setCellValue($alphas[$increment] . 1,$title);
										$increment++;
									}
									$increment = 2;
									foreach($newreturndata as $userdata){
										$excel->getActiveSheet()->setCellValue($alphas[0] . $increment,$userdata->totalcount);
										$excel->getActiveSheet()->setCellValue($alphas[1] . $increment,$userdata->coursename);
										$excel->getActiveSheet()->setCellValue($alphas[2] . $increment,$userdata->username);
										$increment++;
									}									
								}
							}
							$excel->setActiveSheetIndex(0);
							$filename='Report Export.xls'; 
							header('Content-Type: application/vnd.ms-excel'); 
							header('Content-Disposition: attachment;filename="'.$filename.'"'); 
							header('Cache-Control: max-age=0');
							$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');  
							$objWriter->save('php://output');
						}
					}
				break;
			default:
				break;
		}
		// and post then generate excel report and exit;
	}
	
    echo $OUTPUT->header();
	if(is_siteadmin($USER->id)){
		echo $OUTPUT->heading("Custom Reports from logs");
		$lgrp = new LogReporting();
		$lgrp->loadview('reportview');
	}
	else{
		redirect($CFG->wwwroot);
	}
    echo $OUTPUT->footer();

	class LogReporting{
		public function showmsg(){
			echo "This is inside showmsg function";
		}
		
		public function loadview($filename,$vars = array()){
			foreach($vars as $key => $value){
				$$key = $value;
			}
			if(file_exists('./views/'.$filename.".php")){
				include './views/'.$filename.".php";
			}
			else{
				echo "Not Working";
			}
		}
		
		public function excelReportGenerator($title_arr = array(),$data_arr = array()){
			$this->loadLibrary('PHPExcel');
			$excel = new PHPExcel();
			$alphas = range('A', 'Z');
			$excel->setActiveSheetIndex(0);
			$increment = 0;
			foreach($title_arr as $title){
				$excel->getActiveSheet()->setCellValue($alphas[$increment] . 1,$title);
				$increment++;
			}
			$alphaincre = 0;
			foreach($data_arr as $dataarr){
				$increment = 2;
				foreach($dataarr as $coldata){
					$excel->getActiveSheet()->setCellValue($alphas[$alphaincre] . $increment,$coldata);
					$increment++;
				}
				$alphaincre++;
			}
			$filename='Report Export.xls'; 
			header('Content-Type: application/vnd.ms-excel'); 
			header('Content-Disposition: attachment;filename="'.$filename.'"'); 
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');  
			$objWriter->save('php://output');
		}
		
		public function loadLibrary($libname){
			if(file_exists('./libraries/'.$libname.".php")){
				include_once './libraries/'.$libname.".php";
			}
			else{
				return false;
			}
		}
	}