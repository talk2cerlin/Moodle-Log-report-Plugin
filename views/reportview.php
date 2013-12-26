
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="./css/demo_page.css">
<link rel="stylesheet" type="text/css" href="./css/demo_table.css">
<link rel="stylesheet" type="text/css" href="./css/demo_table_jui.css">
<script>
	$ = jQuery;
	if(typeof jQuery == "undefined"){
		var script = document.createElement('script');
		script.src = 'js/jquery-1.9.1.js';
		script.type = 'text/javascript';
		document.getElementsByTagName('head')[0].appendChild(script);
	}
</script>
<script src="js/jquery-ui.js"></script>
<script src="./js/jquery.dataTables.min.js"></script>
<script>
 $(function() {
	$( "#tabs" ).css('display','block');
    $( "#tabs" ).tabs();// other effects blind,bounce,clip,drop,explode,fade,fold,highlight,puff,pulsate,scale,shake,size,slide,transfer [{ show: { effect: "bounce"}}]
  });
  function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
  </script>
<div id="tabs" style="display:none">
  <ul>
    <li><a href="#tabs-1">Login Report</a></li>
    <li><a href="#tabs-2">Course Report</a></li>
    <li><a href="#tabs-3">User Report</a></li>
  </ul>
  <div id="tabs-1">
    <form action="#tabs-1" method="post"><br />
		<input type="hidden" name="reportfor" value="loginrep"/>
      <input type="radio" name="reportype" value="daily" id="daily" <?php if(isset($_POST['reportype']) && $_POST['reportype'] == "daily") echo "checked" ; ?>>
      <span class="label label-info"> Daily Report</span>
      <input type="radio" name="reportype" value="weekly" id="weekly" <?php if(isset($_POST['reportype']) && $_POST['reportype'] == "weekly") echo "checked" ; ?>>
      <span class="label label-info"> Weekly Report</span>
      <input type="radio" name="reportype" value="monthly" id="monthly" <?php if(isset($_POST['reportype']) && $_POST['reportype'] == "monthly") echo "checked" ; ?>>
      <span class="label label-info"> Monthly Report</span>
      <input type="radio" name="reportype" value="yearly" id="yearly" <?php if(isset($_POST['reportype']) && $_POST['reportype'] == "yearly") echo "checked" ; ?>>
      <span class="label label-info"> Yearly Report</span> <br /><br />
	  <table class="table">
		<tr>
			<td><label class="label label-info"> From Date </label></td>
			<td> : </td>
			<td><input type="text" name="fromdate" id="loginrepfromdate" readonly style="cursor:pointer" value="<?php if(isset($_POST['fromdate']) && trim($_POST['fromdate']) != null) echo $_POST['fromdate'] ; ?>"></td>
		<tr/>
		<tr>
			<td><label class="label label-info"> To Date </label></td>
			<td> : </td>
			<td><input type="text" name="todate" id="loginreptodate" readonly style="cursor:pointer" value="<?php if(isset($_POST['todate']) && trim($_POST['todate']) != null) echo $_POST['todate'] ; ?>"></td>
		</tr>
		<tr>
			<td><label class="label label-info"> Report Type </label></td>
			<td> : </td>
			<td>
				<select name="activity">
					<option value="login" <?php if(isset($_POST['activity']) && trim($_POST['activity']) == "login") echo "selected" ; ?>> Login Report</option>
					<option value="logout" <?php if(isset($_POST['activity']) && trim($_POST['activity']) == "logout") echo "selected" ; ?>> Logout Report</option>
					<option value="loginattempt" <?php if(isset($_POST['activity']) && trim($_POST['activity']) == "loginattempt") echo "selected" ; ?>> Login Attempt Report</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><label class="label label-info"> Report Format </label></td>
			<td> : </td>
			<td>
				<select name="reportformat">
					<option value="graph" <?php if(isset($_POST['reportformat']) && trim($_POST['reportformat']) == "graph") echo "selected" ; ?>> Graph</option>
					<option value="excel" <?php if(isset($_POST['reportformat']) && trim($_POST['reportformat']) == "excel") echo "selected" ; ?>> Excel</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><input type="submit" class="btn" id="loginrepsubmit"></td>
			<td></td>
			<td></td>
		</tr>
      </table>
    </form>
    <script>
	for (i = new Date().getFullYear(); i > 1900; i--){
		$('#year').append($('<option />').val(i).html(i));
	}
	for (i = 1; i < 12; i++){
		$('#frommonth').append($('<option />').val(i).html(i));
	}
	for (i = 2; i < 32; i++){
		$('#reportduration').append($('<option />').val(i).html(i+" Days"));
	}
	
	$('#frommonth').change(function(){
		fromval = $(this).val();
		$('#tomonth').empty();
		for (i = parseInt(fromval)+1; i <= 12; i++){
			$('#tomonth').append($('<option />').val(i).html(i));
		}
	})
	
	  $(function() {
		$( "#loginrepfromdate" ).datepicker({
			dateFormat: "dd-mm-yy",
			maxDate: 0,
			onClose: function( selectedDate ) {
				$('#loginreptodate').datepicker( "option", "minDate", selectedDate );
				// $('#todate').datepicker( "option", "maxDate", "31" );
			}
		});	
		$( "#loginreptodate" ).datepicker({
			dateFormat: "dd-mm-yy",
			maxDate: 1
		});
	  });
	  
	function addDays(unixtimestamp,days){
		
	}
	</script> 
  </div>
  <div id="tabs-2">
  <form action="#tabs-2" method="post"><br />
	<input type="hidden" name="reportfor" value="courserep"/>
	  <table class="table">
		<tr>
			<td><label class="label label-info"> From Date </label></td>
			<td> : </td>
			<td><input type="text" name="fromdate" id="courserepfromdate" readonly style="cursor:pointer" value="<?php if(isset($_POST['fromdate']) && trim($_POST['fromdate']) != null) echo $_POST['fromdate'] ; ?>"></td>
		<tr/>
		<tr>
			<td><label class="label label-info"> To Date </label></td>
			<td> : </td>
			<td><input type="text" name="todate" id="coursereptodate" readonly style="cursor:pointer" value="<?php if(isset($_POST['todate']) && trim($_POST['todate']) != null) echo $_POST['todate'] ; ?>"></td>
		</tr>
		<tr>
			<td><label class="label label-info"> Report Type </label></td>
			<td> : </td>
			<td>
				<select name="activity">
					<option value="top10courses" <?php if(isset($_POST['activity']) && trim($_POST['activity']) == "top10course") echo "selected" ; ?>> Top 10 Courses</option>
					<option value="top10courseswithuserdetails" <?php if(isset($_POST['activity']) && trim($_POST['activity']) == "top10courseswithuserdetails") echo "selected" ; ?>> Top 10 Courses with user details</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><input type="submit" class="btn" id="courserepsubmit"></td>
			<td></td>
			<td></td>
		</tr>
      </table>
    </form>
	<script>  
	$(function() {
		$( "#courserepfromdate" ).datepicker({
			dateFormat: "dd-mm-yy",
			maxDate: 0,
			onClose: function( selectedDate ) {
				$('#coursereptodate').datepicker( "option", "minDate", selectedDate );
				// $('#todate').datepicker( "option", "maxDate", "31" );
			}
		});	
		$( "#coursereptodate" ).datepicker({
			dateFormat: "dd-mm-yy",
			maxDate: 1
		});
		$('body').animate({scrollTop:0},'slow');
	  });
	  </script>
  </div>
  <div id="tabs-3">
    <?php $alphabets = range('A','Z');?>
	<p>
	<?php
		foreach($alphabets as $alphs){
			echo '<a href="#" id="'.strtolower($alphs).'" class="nameselect">'.$alphs.'</a> &nbsp;';
		}
	?>
	</p>
	<input type="hidden" value="<?php echo isset($url)? $url.'/report/logreports/check.php':'#' ?>" id="userinfourl">
	<input type="hidden" value="<?php echo isset($url)? $url:'#' ?>" id="baseurlval">
	<div id="loading" align="center">
	</div>
	<div id="usersecondstage" style="display:none">
		<button id="backbtn" class="btn btn-danger"><< Back</button><br/><br/>
		<form action="">
			<input type="hidden" value="" id="hiddenusrid">
			<table class="table">
				<tr>
					<td>From date</td>
					<td><input type="text" readonly id="userfromdate" name="userfromdate"></td>
				</tr>
				<tr>
					<td>To date</td>
					<td><input type="text" readonly id="usertodate" name="usertodate"></td>
				</tr>
				<tr>
					<td>Report Type</td>
					<td>
						<select id="reportype" name="reportype">
							<option value="login">Login</option>
							<option value="logout">Logout</option>
							<option value="course">Course</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Report Format</td>
					<td>
						<select id="reporformat" name="reporformat">
							<option value="graph">Graph</option>
							<option value="excel">Excel</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<input type="button" value="Submit" class="btn">
					</td>
					<td></td>
				</tr>
			</table>
			
			<script>
				$(function() {
					$( "#userfromdate" ).datepicker({
						dateFormat: "dd-mm-yy",
						maxDate: 0,
						onClose: function( selectedDate ) {
							$('#usertodate').datepicker( "option", "minDate", selectedDate );
							// $('#todate').datepicker( "option", "maxDate", "31" );
						}
					});	
					$( "#usertodate" ).datepicker({
						dateFormat: "dd-mm-yy",
						maxDate: 1
					});
					$('#backbtn').click(function(){
						$('#usersecondstage').hide();
						$('#loading').show();
					});
				});
			</script>
		</form>
	</div>
  </div>
	<script>
		$('.nameselect').click(function(event){
			$('#usersecondstage').hide();
			$('#loading').show();
			URLval = $('#userinfourl').val();
			xmlhttp=new XMLHttpRequest();
			xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					//console.log(xmlhttp.responseText);
					if(xmlhttp.responseText != "" && typeof xmlhttp.responseText != "undefined"){
						if(xmlhttp.responseText == "empty"){
							$('#loading').html('<p>No Users found</p>');
						}
						else if(IsJsonString(xmlhttp.responseText)){
							$('#loading').empty();
							data = '<table class="table" id="usernametable" style="word-break: break-all;width:600px">';
							jsonresponse = xmlhttp.responseText;
							var obj = jQuery.parseJSON(jsonresponse);
							data += "<thead><tr>";
							data += '<th style="width:28px">ID';
							data += "</th>";
							data += '<th style="width:86px">Firstname';
							data += "</th>";
							data += '<th style="width:86px">Lastname';
							data += "</th>";
							data += "<th>Username";
							data += "</th>";
							data += "<th>Email";
							data += "</th>";
							data += "</tr></thead><tbody>";
							$(obj).each(function(index,element){
								data += "<tr>";
								data += '<td name="elemid"><span>'+element.id+'</span>';
								data += "</td>";
								data += "<td>"+element.firstname;
								data += "</td>";
								data += "<td>"+element.lastname;
								data += "</td>";
								data += "<td>"+element.username;
								data += "</td>";
								data += "<td>"+element.email;
								data += "</td>";
								data += "</tr>";
							});
							data += "</tbody></table><style>#tabs-3{margin-bottom: 20px;}#usernametable_paginate{width:30%}</style>";
							$('#loading').html(data);
							$('[name="elemid"]').css('cursor','pointer');
							$('[name="elemid"]').css('text-decoration','underline');
							$('[name="elemid"]').click(function(){
								$('#loading').hide();
								$('#hiddenusrid').val($(this).children().html());
								$('#usersecondstage').show();
							});
							$('#usernametable').dataTable({"aLengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50,100]],"iDisplayLength": 5});
						}
					}
				}
				else if(xmlhttp.readyState==4){
					if (xmlhttp.status==500 || xmlhttp.status==501 || xmlhttp.status==502 || xmlhttp.status==503 || xmlhttp.status==504 || xmlhttp.status==505){
						$('#loading').empty();
						alert("Server Error. Error Code : " + xmlhttp.status );
					}
				}
			}
			xmlhttp.open("POST",URLval,true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send('query='+$(this).attr('id'));
			$('#loading').html('<img src="'+$('#baseurlval').val()+'/report/logreports/images/ajax_loader_gray.gif'+'" width="50px">');
			event.preventDefault();
		})
	</script>
</div>
 
