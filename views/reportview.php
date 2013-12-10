<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<script>$ = jQuery;</script>
<script src="js/jquery-ui.js"></script>
<script>
 $(function() {
    $( "#tabs" ).tabs();
  });
  </script>
<div id="tabs">
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
				console.log(selectedDate);
				$('#loginreptodate').datepicker( "option", "minDate", selectedDate );
				// $('#todate').datepicker( "option", "maxDate", "31" );
			}
		});	
		$( "#loginreptodate" ).datepicker({
			dateFormat: "dd-mm-yy",
			maxDate: 0
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
				console.log(selectedDate);
				$('#coursereptodate').datepicker( "option", "minDate", selectedDate );
				// $('#todate').datepicker( "option", "maxDate", "31" );
			}
		});	
		$( "#coursereptodate" ).datepicker({
			dateFormat: "dd-mm-yy",
			maxDate: 0
		});
		$('body').animate({scrollTop:0},'slow');
	  });
	  </script>
  </div>
  <div id="tabs-3">
    <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
    <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
  </div>
</div>