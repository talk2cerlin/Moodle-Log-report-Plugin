<?php if(isset($reportdata) && !empty($reportdata) && !isset($error)) {?>
<link rel="stylesheet" type="text/css" href="./css/demo_page.css">
<link rel="stylesheet" type="text/css" href="./css/demo_table.css">
<link rel="stylesheet" type="text/css" href="./css/demo_table_jui.css">
<script src="./js/jquery.dataTables.min.js"></script>
<script src="./js/highcharts.js"></script>
<script src="./js/themes/gray.js"></script>
		<div class="row-fluid">
			<br />
			<div class="span12" id="container"></div>
			<div class="span11" id="showreport"></div>
			<script>
				function showreports(monthdata,reportdata){
					var data = '';
						data += '<table class="table" id="datareport" style="margin-top: 10px">';
						data += '<thead><tr><th>Date</th><th>Count</th></tr></thead><tbody>';
						for(i=0;i<monthdata.length;i++){
							//returndata.push(months[i]);
							data += '<tr><td>'+monthdata[i]+'</td><td>'+reportdata[i]+'</td></tr>';
						}
						data += '</tbody></table>';
						$('#showreport').append(data);	
						$('#datareport').dataTable();
						$('[name="datareport_length"]').css('width',60);
						$('#datareport_wrapper').css('margin-top','28px');
						$('#datareport').css('background-color','rgb(111, 185, 204);');
				}
				
				function returnMonths(fstmth,lstmth){
					if(fstmth > 0 && fstmth < 13 && fstmth <= lstmth && lstmth > 0 && lstmth < 13){
						var months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
						var returndata= [];
						for(i=fstmth-1;i<lstmth;i++){
							returndata.push(months[i]);
						}
						return returndata;
					}
				}
				$(function () {
					$('#container').highcharts({
						chart: {
							type: 'line',
							height: 300,
							width: 760
						},
						title: {
							text: '<?php echo (isset($title))? $title:'Report'?>',
							x: -20
						},
						subtitle: {
							text: ''
						},
						xAxis: {
							categories: <?php echo $fromXaxis; ?>
						},
						yAxis: {
							min: 0,
							title: {
								text: 'Number Of Login Hits'
							}
						},
						tooltip: {
							enabled: true,
							formatter: function() {
								return '<b>'+ this.series.name +'</b><br/>'+
									this.x +': '+ this.y +'°C';
							}
						},
						plotOptions: {
							line: {
								dataLabels: {
									enabled: true
								},
								enableMouseTracking: false
							}
						},
						series: [{
							name: 'Time Period',
							data: [<?php echo implode(',',$reportdata) ?>]
						}]
					});
				});
				eval(showreports(<?php echo $fromXaxis; ?>,[<?php echo implode(',',$reportdata) ?>]));
			</script>
		</div>
	<?php } ?>
	<div style="height:100px"></div>