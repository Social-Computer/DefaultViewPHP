<?php

$id = $_GET['id'];

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://localhost:3215/RecoinRestController/Task/project?project_id='.$id,
    CURLOPT_USERAGENT => 'A1 State View'
));
// Send the request & save response to $resp
$tasks = curl_exec($curl);
$tasks_json = json_decode($tasks);
// Close request to clear up some resources
curl_close($curl);

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://localhost:3215/RecoinRestController/Project/'.$id,
    CURLOPT_USERAGENT => 'A1 State View'
));
// Send the request & save response to $resp
$prj = curl_exec($curl);
$prj_json = json_decode($prj);
// Close request to clear up some resources
curl_close($curl);

?>

<!DOCTYPE html>
<html lang="de-DE">
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  	<script src="../js/Chart.js"></script>
  	<style type="text/css">
		body{
			font-family: Verdana,Arial,sans-serif;
			font-size: 0.9em;
		}

		#wrapper{
			width: 900px;
			margin: 20px auto;
			padding: 20px;
			border: 1px solid #ccc;
			border-radius: 15px;
		}

		.space{
			margin: 5px 0px 5px 0px;
			text-align: center;
		}

		.ui-widget {
		    font-size: 0.9em;
		}

		#tasks{
			padding: 15px;
		}

		ul{
			padding: 0;
			margin: 5px 0 5px 0;
		}

		li{
			list-style-type: none;
			padding: 5px 0 5px 0;
		}

		.li{
			padding: 0 5px 0 0;
			vertical-align: -3px;
		}

		#details_title{
			font-size: 1.2em;
			padding-top: 10px;
			padding-bottom: 10px;
		}

	</style>
	<script type="text/javascript">
		$(function() {
		    var dialog
			dialog = $( "#dialog" ).dialog({
		      autoOpen: false,
		      height: 800,
		      width: 600,
		      modal: true
		    });

		    $( ".prj_details" ).click(function(button) {
		      $( "#dialog" ).dialog( "open" );
		      var jsonObj = $.parseJSON(button.target.value);

		      var xhr = new XMLHttpRequest();
				xhr.open("GET", "http://recoin.cloudapp.net:3215/RecoinRestController/Task/"+jsonObj.task_id+"/Responses", true);
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4) {
						var resp = JSON.parse(xhr.responseText);
						console.log(resp);
						var result = "<ul>";
						if((resp.message!=undefined)){
							result = result+"<li><img class='li' src='../icons/media-pause-2x.png' />No instructions have been applied yet.</li>";
						} else{
							resp.taskRuns.forEach(function(item, index){
								result = result+"<li><img class='li' src='../icons/media-play-2x.png' />"+item.task_run_text+"<br /><a href='http://recoin.cloudapp.net/social-computer/instruction/"+item.task_run_id+"'>go to instruction details >></a></li>";
							});
						}

						result = result+"</ul>";
						$("#details_body").html(result);
					}
				}
				xhr.send();


		      $("#details_title").html("Task content: "+jsonObj.content+"<br /><a href='http://recoin.cloudapp.net/social-computer/task/"+jsonObj.task_id+"'>go to task details >></a>");

var data = {
    labels: ["Retrieved instructions"],
    datasets: [
        {
            label: "Retrieved instructions",
            fillColor: "rgba(151,187,205,0.5)",
            strokeColor: "rgba(151,187,205,0.8)",
            highlightFill: "rgba(151,187,205,0.75)",
            highlightStroke: "rgba(151,187,205,1)",
            data: [jsonObj.taskRuns_count]
        }]
};

var options = {
    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
    scaleBeginAtZero : true,

    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines : true,

    //String - Colour of the grid lines
    scaleGridLineColor : "rgba(0,0,0,.05)",

    //Number - Width of the grid lines
    scaleGridLineWidth : 1,

    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,

    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines: true,

    //Boolean - If there is a stroke on each bar
    barShowStroke : true,

    //Number - Pixel width of the bar stroke
    barStrokeWidth : 2,

    //Number - Spacing between each of the X value sets
    barValueSpacing : 5,

    //Number - Spacing between data sets within X values
    barDatasetSpacing : 1,

    //String - A legend template
    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

};
		      var ctx = $("#details_chart").get(0).getContext("2d");
		      var myLineChart = new Chart(ctx, {
				    type: 'bar',
				    data: data,
				    options: options
				});
		});
	});
	</script>
</head>
<body>
<div id="wrapper">
	<div id="dialog" title="Task details">
		<div id="details_title"></div>
		<canvas id="details_chart" width="600" height="200"></canvas>
		<div id="details_body">
		</div>
	</div>
	<div id="tasks" class="ui-widget-content ui-corner-all ui-corner-bottom ui-corner-right ui-corner-br">
		<h1>Tasks on this process</h1>
		<div><a href="http://recoin.cloudapp.net/social-computer/state"><< back to state overview</a></div>
		<canvas id="overview" width="800" height="200"></canvas>
		<?php
			$task_runs = array();

			foreach ($tasks_json->tasks as $task){
				// Get cURL resource
				$curl = curl_init();
				// Set some options - we are passing in a useragent too here
				curl_setopt_array($curl, array(
				    CURLOPT_RETURNTRANSFER => 1,
				    CURLOPT_URL => 'http://localhost:3215/RecoinRestController/Task/'.$task->task_id."/Responses",
				    CURLOPT_USERAGENT => 'A1 State View'
				));
				// Send the request & save response to $resp
				$next_task = curl_exec($curl);
				$next_task_json = json_decode($next_task);
				// Close request to clear up some resources
				curl_close($curl);


				echo('<li>');
				if(strpos($task->task_status,"completed") >= 0 && !(strpos($task->task_status,"completed") === false)) echo('<img class="li" src="../icons/media-stop-2x.png" />');
				else echo('<img class="li" src="../icons/media-play-2x.png" />');
				echo('Task content: ');
				echo ($task->embed_nomedia->html);

				echo('<br /><ul>');
				echo('<li>Task observed: '.$task->publishedAt.'</li>');
				echo('<li>Retrieved instructions: '.count($next_task_json->taskRuns).'</li>');
				$task_runs[$task->task_id] = count($next_task_json->taskRuns);
				echo('<li><button class="prj_details" value="'.htmlentities(json_encode(array('task_id'=>$task->task_id, 'observed'=>$task->publishedAt, 'content'=>$task->embed->html, 'taskRuns_count'=>count($next_task_json->taskRuns))), ENT_QUOTES, 'UTF-8').'">Details</button></li>');
				echo('</ul></li>');
			}
		?>
		<script type="text/javascript">
			var data = {
    labels: [<?php echo("'".implode("','",array_keys($task_runs))."'"); ?>],
    datasets: [
        {
            label: "Retrieved instructions",
            fillColor: "rgba(151,187,205,0.5)",
            strokeColor: "rgba(151,187,205,0.8)",
            highlightFill: "rgba(151,187,205,0.75)",
            highlightStroke: "rgba(151,187,205,1)",
            data: [<?php echo(implode(", ",$task_runs)); ?>]
        }]
};

var options = {
    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
    scaleBeginAtZero : true,

    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines : true,

    //String - Colour of the grid lines
    scaleGridLineColor : "rgba(0,0,0,.05)",

    //Number - Width of the grid lines
    scaleGridLineWidth : 1,

    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,

    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines: true,

    //Boolean - If there is a stroke on each bar
    barShowStroke : true,

    //Number - Pixel width of the bar stroke
    barStrokeWidth : 2,

    //Number - Spacing between each of the X value sets
    barValueSpacing : 5,

    //Number - Spacing between data sets within X values
    barDatasetSpacing : 1,

    //String - A legend template
    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

};

var ctx = $("#overview").get(0).getContext("2d");
		      var myLineChart = new Chart(ctx, {
				    type: 'bar',
				    data: data,
				    options: options
				});
		</script>
	</div>
	<div class="space">
	</div>
</div>

</body>
</html>  