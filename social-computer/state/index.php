<?php

$id = $_GET['id'];

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://localhost:3215/RecoinRestController/Project/',
    CURLOPT_USERAGENT => 'A1 State View'
));
// Send the request & save response to $resp
$projects = curl_exec($curl);
$projects_json = json_decode($projects);
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
  	<script src="js/Chart.js"></script>
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

		#projects, #state{
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
				xhr.open("GET", "http://recoin.cloudapp.net:3215/RecoinRestController/Task/project?project_id="+jsonObj.project_id, true);
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4) {
						var resp = JSON.parse(xhr.responseText);
						var result = "<ul>";

						resp.tasks.forEach(function(item, index){
							result = result+"<li><img class='li' src='../icons/media-play-2x.png' />"+item.embed_nomedia.html;+"<br /><a href='http://recoin.cloudapp.net/social-computer/task/"+item.task_id+"'>go to task details >></a></li>";
						});

						result = result+"</ul>";
						$("#details_body").html(result);
					}
				}
				xhr.send();


		      $("#details_title").html("Identifier set: "+jsonObj.identifiers+"<br /><a href='http://recoin.cloudapp.net/social-computer/process/"+jsonObj.project_id+"'>go to process details >></a>");

var data = {
    labels: [<?php echo("'".implode("','",array_keys($project_tasks))."'"); ?>],
    datasets: [
        {
            label: "Published tasks",
            fillColor: "rgba(220,220,220,0.5)",
            strokeColor: "rgba(220,220,220,0.8)",
            highlightFill: "rgba(220,220,220,0.75)",
            highlightStroke: "rgba(220,220,220,1)",
            data: [jsonObj.task_count]
        },
        {
            label: "Retrieved instructions",
            fillColor: "rgba(151,187,205,0.5)",
            strokeColor: "rgba(151,187,205,0.8)",
            highlightFill: "rgba(151,187,205,0.75)",
            highlightStroke: "rgba(151,187,205,1)",
            data: [jsonObj.task_run_count]
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
	<div id="dialog" title="Process details">
		<div id="details_title"></div>
		<canvas id="details_chart" width="600" height="200"></canvas>
		<div id="details_body">
		</div>
	</div>
	<div id="projects" class="ui-widget-content ui-corner-all ui-corner-bottom ui-corner-right ui-corner-br">
		<h1>Running processes</h1>
		<canvas id="overview" width="800" height="200"></canvas>
		<?php
			$project_tasks = array();
			$project_runs = array();

			foreach ($projects_json->project_list as $project){
				// Get cURL resource
				$curl = curl_init();
				// Set some options - we are passing in a useragent too here
				curl_setopt_array($curl, array(
				    CURLOPT_RETURNTRANSFER => 1,
				    CURLOPT_URL => 'http://localhost:3215/RecoinRestController/Project/'.$project->project_id,
				    CURLOPT_USERAGENT => 'A1 State View'
				));
				// Send the request & save response to $resp
				$next_prj = curl_exec($curl);
				$next_prj_json = json_decode($next_prj);
				// Close request to clear up some resources
				curl_close($curl);


				echo('<li>');
				if(strpos($project->project_status,"completed") >= 0 && !(strpos($project->project_status,"completed") === false)) echo('<img class="li" src="../icons/media-stop-2x.png" />');
				else echo('<img class="li" src="../icons/media-play-2x.png" />');
				echo('Identifer set: ');
				foreach ($project->identifiers as $identifier){
					echo ($identifier.' ');
				}

				echo('<br /><ul>');
				echo('<li>Burst observed: '.$project->observed.'</li>');
				echo('<li>Published tasks: '.$next_prj_json->tasks_count.'</li>');
				$project_tasks[implode(", ",$project->identifiers)] = $next_prj_json->tasks_count;
				echo('<li>Retrieved instructions: '.$next_prj_json->taskRuns_count.'</li>');
				$project_runs[implode(", ",$project->identifiers)] = $next_prj_json->taskRuns_count;
				echo('<li><button class="prj_details" value="'.htmlentities(json_encode(array('project_id'=>$project->project_id, 'observed'=>$project->observed, 'identifiers'=>implode(", ",$project->identifiers), 'task_count'=>$next_prj_json->tasks_count, 'task_run_count'=>$next_prj_json->taskRuns_count)), ENT_QUOTES, 'UTF-8').'">Details</button></li>');
				echo('</ul></li>');
			}
		?>
		<script type="text/javascript">
			var data = {
    labels: [<?php echo("'".implode("','",array_keys($project_tasks))."'"); ?>],
    datasets: [
        {
            label: "Published tasks",
            fillColor: "rgba(220,220,220,0.5)",
            strokeColor: "rgba(220,220,220,0.8)",
            highlightFill: "rgba(220,220,220,0.75)",
            highlightStroke: "rgba(220,220,220,1)",
            data: [<?php echo(implode(", ",$project_tasks)); ?>]
        },
        {
            label: "Retrieved instructions",
            fillColor: "rgba(151,187,205,0.5)",
            strokeColor: "rgba(151,187,205,0.8)",
            highlightFill: "rgba(151,187,205,0.75)",
            highlightStroke: "rgba(151,187,205,1)",
            data: [<?php echo(implode(", ",$project_runs)); ?>]
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