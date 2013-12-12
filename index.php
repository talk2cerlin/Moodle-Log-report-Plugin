<?php
/**
*
* @author 	: Cerlin ST - https://github.com/tak2cerlin
* @version 	: 0.0.1
**/

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
	
	$fromXaxis = '[]';
	$reportdata = array();
	$title = '';
	
	// Excel report generation starts here
	if(is_siteadmin($USER->id) && isset($_POST['reportfor']) && trim($_POST['reportfor']) != null){
		switch(trim($_POST['reportfor'])){
			case "loginrep":
				if(isset($_POST['reportype']) && trim($_POST['reportype']) != null && isset($_POST['fromdate']) && trim($_POST['fromdate']) != null && isset($_POST['todate']) && trim($_POST['todate']) != null && isset($_POST['activity']) && trim($_POST['activity']) != null && isset($_POST['reportformat']) && trim($_POST['reportformat']) != null){
					$activity = '';$module = "user";
					switch(trim($_POST['activity'])){
						case "login":
							$activity = "login";
							break;
						case "logout":
							$activity = "logout";
							break;
						case "loginattempt":
							$module = "login";
							$activity = "error";
							break;
						default:
							$activity = "login";
					}
					$fromunix = strtotime(trim($_POST['fromdate']));
					$tounix = strtotime(trim($_POST['todate']));
					if(trim($_POST['reportype']) == "daily"){
						$reportdata = array();
						$error = null;
						$flag = 0;
						$oneday = 86400; // Unix value for one day
						$i = 1;
						if($fromunix <= $tounix){
							$fromXaxis = "[";
							while($i == 1){
								if($fromunix <= $tounix){
									if($fromXaxis != "[")
										$fromXaxis .= ",";
									$fromday = date('j',$fromunix);
									$frommonth = date('n',$fromunix);
									$fromyear = date('Y',$fromunix);
									$today = date('j',$tounix);
									$tomonth = date('n',$tounix);
									$toyear = date('Y',$tounix);
									$query = "SELECT id ,count(*) as day FROM `mdl_log` WHERE `module` = '".$module."' and `action` = '".$activity."' and year(FROM_UNIXTIME(time)) = ".$fromyear." and month(FROM_UNIXTIME(time)) = ".$frommonth." and day(FROM_UNIXTIME(time)) = ".$fromday;
									$returndata = $DB->get_records_sql($query);
									$fromXaxis .= "\"".date('D M j',$fromunix)."\"";
									$exceldata['date'][] = date('D M j',$fromunix);
									foreach($returndata as $dat){
										$exceldata['count'][] = $dat->day;
										$reportdata[] = $dat->day;
									}
									$fromunix = $fromunix + $oneday;
								}
								else
									$i = 0;
							}
							$fromXaxis .= "]";
						}
						$title = "Daily Report for ".ucfirst(trim($_POST['activity']))." Activity";
						if(trim($_POST['reportformat']) == "excel"){
							$exceltitle[] = "Dates";
							$exceltitle[] = "Count";
							$excelwrite = new LogReporting();
							$excelwrite->excelReportGenerator($exceltitle,$exceldata);
						}
					}
					elseif(trim($_POST['reportype']) == "weekly"){
						$reportdata = array();
						$oneday = 86400; // Unix value for one day
						if($fromunix <= $tounix){
							$fromXaxis = "[";
							$fromweek = date('N',$fromunix);
							$toweek = date('N',$tounix);
							if($fromweek != 1){
								while($fromweek != 1){
									$fromunix = $fromunix - $oneday;
									$fromweek--;
								}
							}
							if($toweek != 1){
								while($toweek != 7){
									$tounix = $tounix + $oneday;
									$toweek++;
								}
							}
							$i = 1;
							while($i == 1){
								if($fromunix <= $tounix){
									if($fromXaxis != "[")
										$fromXaxis .= ",";	
									$fromday = date('j',$fromunix);
									$frommonth = date('n',$fromunix);
									$fromyear = date('Y',$fromunix);
									$today = date('j',$tounix);
									$tomonth = date('n',$tounix);
									$toyear = date('Y',$tounix);
									$formunixbackup = $fromunix;
									$j = 1;
									$weeklycount = 0;
									$fromXaxis .= "\"".date('M jS Y',$fromunix)."\"";
									$exceldata['date'][] = date('M jS Y',$fromunix);
									for($k=1;$k<8;$k++){
										$query = "SELECT id ,count(*) as weeklycount FROM `mdl_log` WHERE `module` = '".$module."' and `action` = '".$activity."' and year(FROM_UNIXTIME(time)) = ".$fromyear." and month(FROM_UNIXTIME(time)) = ".$frommonth." and day(FROM_UNIXTIME(time)) = ".$fromday;
										$returncount = $DB->get_records_sql($query);
										$fromunix = $formunixbackup + ($oneday * $k);
										$fromday = date('j',$fromunix);
										$frommonth = date('n',$fromunix);
										$fromyear = date('Y',$fromunix);
										$today = date('j',$tounix);
										foreach($returncount as $rc){
											$weeklycount = $weeklycount + $rc->weeklycount;
										}
									}
									$reportdata[] = $weeklycount;
									$exceldata['count'][] = $weeklycount;
								}
								else
									$i = 0;
							}
							$fromXaxis .= "]";
						}
						$title = "Weekly Report for ".ucfirst(trim($_POST['activity']))." Activity";
						if(trim($_POST['reportformat']) == "excel"){
							$exceltitle[] = "Dates";
							$exceltitle[] = "Count";
							$excelwrite = new LogReporting();
							$excelwrite->excelReportGenerator($exceltitle,$exceldata);
						}
					}
					elseif(trim($_POST['reportype']) == "monthly"){
						$getmon = new LogReporting();
						$frommonth = date('n',$fromunix);
						$fromyear = date('Y',$fromunix);
						$tomonth = date('n',$tounix);
						$toyear = date('Y',$tounix);
						if($fromunix < $tounix && $frommonth < 13 && $tomonth < 13 && $frommonth > 0 && $tomonth > 0){
							$fromXaxis = "[";
							$i = 1;
							while($i == 1){
								if($fromXaxis != "[")
									$fromXaxis .= ",";
								$query = "SELECT id ,count(*) as month FROM `mdl_log` WHERE `module` = '".$module."' and `action` = '".$activity."' and year(FROM_UNIXTIME(time)) = ".$fromyear." and month(FROM_UNIXTIME(time)) = ".$frommonth;
								$returndata = $DB->get_records_sql($query);
								$fromXaxis .= "\"".$getmon->getMonths($frommonth)." ".$fromyear."\"";
								$exceldata['date'][] = $getmon->getMonths($frommonth)." ".$fromyear;
								foreach($returndata as $rd){
									$reportdata[] = $rd->month;
									$exceldata['count'][] = $rd->month;
								}
								$frommonth++;
								if($frommonth == 13){
									$frommonth = 1;
									$fromyear = (int)$fromyear + 1;
								}
								if($fromyear > $toyear){
									$i = 0;
								}
								if($frommonth > $tomonth && $fromyear == $toyear){
									$i = 0;
								}
							}
							$fromXaxis .= "]";
						}
						$title = "Monthly Report for ".ucfirst(trim($_POST['activity']))." Activity";
						if(trim($_POST['reportformat']) == "excel"){
							$exceltitle[] = "Dates";
							$exceltitle[] = "Count";
							$excelwrite = new LogReporting();
							$excelwrite->excelReportGenerator($exceltitle,$exceldata);
						}
					}
					elseif(trim($_POST['reportype']) == "yearly"){
						$fromyear = date('Y',$fromunix);
						$toyear = date('Y',$tounix);
						if($fromyear <= $toyear){
							$fromXaxis = "[";
							$i = 1;
							while($i == 1){
								if($fromyear <= $toyear){
									if($fromXaxis != "[")
										$fromXaxis .= ",";
									$query = "SELECT id ,count(*) as year FROM `mdl_log` WHERE `module` = '".$module."' and `action` = '".$activity."' and year(FROM_UNIXTIME(time)) = ".$fromyear;
									$returndata = $DB->get_records_sql($query);
									$fromXaxis .= "\"".$fromyear."\"";
									$exceldata['date'][] = $fromyear;
									foreach($returndata as $rd){
										$reportdata[] = $rd->year;
										$exceldata['count'][] = $rd->year;
									}
									$fromyear = $fromyear + 1;
								}
								else
									$i = 0;
							}
							$fromXaxis .= "]";
						}
						$title = "Yearly Report for ".ucfirst(trim($_POST['activity']))." Activity";
						if(trim($_POST['reportformat']) == "excel"){
							$exceltitle[] = "Dates";
							$exceltitle[] = "Count";
							$excelwrite = new LogReporting();
							$excelwrite->excelReportGenerator($exceltitle,$exceldata);
						}
					}
				}
				break;
			case "courserep":
					if(isset($_POST['fromdate']) && trim($_POST['fromdate']) != null && isset($_POST['todate']) && trim($_POST['todate']) != null && isset($_POST['activity']) && trim($_POST['activity']) != null){
						$activity = trim($_POST['activity']);
						if($activity == 'top10courses'){
							$fromdate = strtotime(trim($_POST['fromdate']));
							$todate = strtotime(trim($_POST['todate']));
							// echo $fromdate." , ".$todate." , ".$activity;
							$returndata = $DB->get_records_sql("
								SELECT ml.id,COUNT( * ) AS TotalCount, ml.`course` AS CourseID, mc.`fullname` AS CourseName
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
									SELECT ml.id,COUNT( * ) AS TotalCount, ml.`course` AS CourseID, mc.`fullname` AS CourseName
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
										SELECT ml.id,ml.userid,COUNT( * ) AS TotalCount,mc.`fullname` AS CourseName,CONCAT(mu.firstname,' ',mu.lastname) as username
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
									$exceltitle[] = "User ID";
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
										$excel->getActiveSheet()->setCellValue($alphas[3] . $increment,$userdata->userid);
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
	}
	
	// Moodle View starts here
	
    echo $OUTPUT->header();
	if(is_siteadmin($USER->id)){
		echo $OUTPUT->heading("Custom Reports from logs");
		$lgrp = new LogReporting();
		$lgrp->loadview('reportview');
		if(!empty($exceldata)){
			$lgrp->loadview('graphview',array('fromXaxis'=>$fromXaxis,'reportdata'=>$reportdata,'title'=>$title));
		}
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
		
		public function getMonths($index){
			$monthsArray = array(
				"January",
				"February",
				"March",
				"April",
				"May",
				"June",
				"July",
				"August",
				"September",
				"October",
				"November",
				"December"
			);
			return $monthsArray[$index - 1];
		}
		
		private function getYearlyReport($fromunix,$tounix,$activity){ // From and to in unixtimestamp
			$exceltitle = $exceldata = array();
			$error = null;
			$flag = 0;
			$fromyear = date('Y',$fromunix);
			$toyear = date('Y',$tounix);
			if($fromyear <= $toyear){
				$fromXaxis = "[";
				$i = 1;
				while($i == 1){
					if($fromyear <= $toyear){
						if($fromXaxis != "[")
							$fromXaxis .= ",";
						$selectarray = 'count(*) as year';
						$where = 'year(FROM_UNIXTIME(dateandtime)) = '.$fromyear;
						$returndata = $this->generalcrud->getReport($activity,$selectarray,$where);
						$fromXaxis .= "\"".$fromyear."\"";
						$exceldata['date'][] = $fromyear;
						if(!empty($returndata)){
							$this->reportdata[] = $returndata[0]['year'];
							$exceldata['count'][] = $returndata[0]['year'];
						}
						$fromyear = $fromyear + 1;
					}
					else
						$i = 0;
				}
				$fromXaxis .= "]";
			}
		}
	}