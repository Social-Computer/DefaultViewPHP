<?php

$id = $_GET['id'];

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://localhost:3215/RecoinRestController/TaskRun/'.$id,
    CURLOPT_USERAGENT => 'A1 Task View'
));
// Send the request & save response to $resp
$taskrun = curl_exec($curl);
$taskrun_json = json_decode($taskrun);
// Close request to clear up some resources
curl_close($curl);

$headers = getallheaders();

if(strpos($headers['Accept'],"application/json") >= 0 && !(strpos($headers['Accept'],"application/json")===false)){
	echo("json");
	die();
}

if(strpos($headers['Accept'],"application/rdf+xml") >= 0 && !(strpos($headers['Accept'],"application/rdf+xml")===false)){
	echo("rdf");
	die();
}

?>

<!DOCTYPE html>
<html lang="de-DE">
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
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

		#instruction{
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

	</style>
  	<script>
	</script>
</head>
<body>
<div id="wrapper">
	<div id="instruction" class="ui-widget-content ui-corner-all ui-corner-bottom ui-corner-right ui-corner-br">
		<h1>Instruction details</h1>
		<?php
			echo("<a href='http://recoin.cloudapp.net/social-computer/task/".$taskrun_json->taskRuns[0]->task_id."'><< back to task</a>");
		?>
		<ul><li>
		<?php
			if(strpos($taskrun_json->taskRuns[0]->task_run_text,"PRIO") >= 0 && !(strpos($taskrun_json->taskRuns[0]->task_run_text,"PRIO") === false)) echo('<img class="li" src="../icons/resize-height-2x.png" />');
			if(strpos($taskrun_json->taskRuns[0]->task_run_text,"ENRICH") >= 0 && !(strpos($taskrun_json->taskRuns[0]->task_run_text,"ENRICH") === false)) echo('<img class="li" src="../icons/link-intact-2x.png" />');
			if(strpos($taskrun_json->taskRuns[0]->task_run_text,"SHARE") >= 0 && !(strpos($taskrun_json->taskRuns[0]->task_run_text,"SHARE") === false)) echo('<img class="li" src="../icons/share-boxed-2x.png" />');
			if(strpos($taskrun_json->taskRuns[0]->task_run_text,"TRANS") >= 0 && !(strpos($taskrun_json->taskRuns[0]->task_run_text,"TRANS") === false)) echo('<img class="li" src="../icons/random-2x.png" />');
			if(strpos($taskrun_json->taskRuns[0]->task_run_text,"RESOLVE") >= 0 && !(strpos($taskrun_json->taskRuns[0]->task_run_text,"RESOLVE") === false)) echo('<img class="li" src="../icons/flash-2x.png" />');
			echo($taskrun_json->taskRuns[0]->task_run_text);
		?>
	</li></ul>
	</div>
</div>

</body>
</html>  