<?php

$id = $_GET['id'];

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://localhost:3215/RecoinRestController/Task/'.$id,
    CURLOPT_USERAGENT => 'A1 Task View'
));
// Send the request & save response to $resp
$task = curl_exec($curl);
$task_json = json_decode($task);
// Close request to clear up some resources
curl_close($curl);


// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://localhost:3215/RecoinRestController/Task/'.$id.'/Responses',
    CURLOPT_USERAGENT => 'A1 Task View'
));
// Send the request & save response to $resp
$taskruns = curl_exec($curl);
$taskruns_json = json_decode($taskruns);
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

		#task, #state{
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
		$(function() {
			$( "#tabs" ).tabs();
		});

		function performForm1(){
			event.preventDefault();

			var resp_text = document.getElementById('username').value;
			var sys = document.getElementById('system').value;

			if(sys == "twitter"){
				resp_text = "SHARE http://twitter.com/"+resp_text;
			}

			var task_id = document.getElementById('task_id').value;
			var project_id = document.getElementById('project_id').value;

		    var xhr = new XMLHttpRequest();
			xhr.open("GET", "http://recoin.cloudapp.net:3215/RecoinRestController/sendTaskRun?text="+encodeURI(resp_text)+"&task_id="+task_id+"&project_id="+project_id+"&contributor_name=TaskView&source=TaskView", true);
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4) {
					var resp = JSON.parse(xhr.responseText);
					location.reload();
				}
			}
			xhr.send();

		}

		function performForm2(){
			event.preventDefault();

			var resp_text = document.getElementById('trans').value;
			resp_text = "TRANS "+resp_text;

			var task_id = document.getElementById('task_id').value;
			var project_id = document.getElementById('project_id').value;

		    var xhr = new XMLHttpRequest();
			xhr.open("GET", "http://recoin.cloudapp.net:3215/RecoinRestController/sendTaskRun?text="+encodeURI(resp_text)+"&task_id="+task_id+"&project_id="+project_id+"&contributor_name=TaskView&source=TaskView", true);
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4) {
					var resp = JSON.parse(xhr.responseText);
					location.reload();
				}
			}
			xhr.send();

		}

		function performForm3(){
			event.preventDefault();

			var resp_text = document.getElementById('enrich').value;
			resp_text = "ENRICH "+resp_text;

			var task_id = document.getElementById('task_id').value;
			var project_id = document.getElementById('project_id').value;

		    var xhr = new XMLHttpRequest();
			xhr.open("GET", "http://recoin.cloudapp.net:3215/RecoinRestController/sendTaskRun?text="+encodeURI(resp_text)+"&task_id="+task_id+"&project_id="+project_id+"&contributor_name=TaskView&source=TaskView", true);
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4) {
					var resp = JSON.parse(xhr.responseText);
					location.reload();
				}
			}
			xhr.send();

		}

		function performForm4(){
			event.preventDefault();

			var resp_text = "";
			var prios = document.getElementsByName("prio");

			for(var i = 0; i < prios.length; i++) {
			   if(prios[i].checked == true) {
			       resp_text = prios[i].value;
			   }
			 }
			resp_text = "PRIO "+resp_text;

			var task_id = document.getElementById('task_id').value;
			var project_id = document.getElementById('project_id').value;

		    var xhr = new XMLHttpRequest();
			xhr.open("GET", "http://recoin.cloudapp.net:3215/RecoinRestController/sendTaskRun?text="+encodeURI(resp_text)+"&task_id="+task_id+"&project_id="+project_id+"&contributor_name=TaskView&source=TaskView", true);
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4) {
					var resp = JSON.parse(xhr.responseText);
					location.reload();
				}
			}
			xhr.send();

		}

		function performForm5(){
			event.preventDefault();

			var resp_text = document.getElementById('resolve').value;
			resp_text = "RESOLVE "+resp_text;

			var task_id = document.getElementById('task_id').value;
			var project_id = document.getElementById('project_id').value;

		    var xhr = new XMLHttpRequest();
			xhr.open("GET", "http://recoin.cloudapp.net:3215/RecoinRestController/sendTaskRun?text="+encodeURI(resp_text)+"&task_id="+task_id+"&project_id="+project_id+"&contributor_name=TaskView&source=TaskView", true);
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4) {
					var resp = JSON.parse(xhr.responseText);
					location.reload();
				}
			}
			xhr.send();

		}


		// When the popup HTML has loaded
		window.addEventListener('load', function(evt) {
		    document.getElementById('form-1').addEventListener('submit', performForm1);
		    document.getElementById('form-2').addEventListener('submit', performForm2);
		    document.getElementById('form-3').addEventListener('submit', performForm3);
		    document.getElementById('form-4').addEventListener('submit', performForm4);
		    document.getElementById('form-5').addEventListener('submit', performForm5);
		});
	</script>
</head>
<body>
<div id="wrapper">
	<div id="task" class="ui-widget-content ui-corner-all ui-corner-bottom ui-corner-right ui-corner-br">
		Task content:<br />
		<?php
			echo($task_json->tasks[0]->embed->html);
		?>
		<br /><br />
		<?php
			echo("<a href='http://recoin.cloudapp.net/social-computer/process/".$task_json->tasks[0]->project_id."'><< back to parent process</a>");
		?>
	</div>
	<div class="space">
		<img src="help.jpg" />
	</div>
	<div id="apply">
		<div id="tabs">
		  <ul>
		    <li><a href="#tabs-1">Share this message</a></li>
		    <li><a href="#tabs-2">Translate this message</a></li>
		    <li><a href="#tabs-3">Enrich this message</a></li>
		    <li><a href="#tabs-4">Prioritize this message</a></li>
		    <li><a href="#tabs-5">Resolve this message</a></li>
		  </ul>
		  <input type="hidden" id="task_id" name="task_id" value=<?php echo($task_json->tasks[0]->task_id); ?> /><input type="hidden" id="project_id" name="project_id" value=<?php echo($task_json->tasks[0]->project_id); ?> />
		  <div id="tabs-1">
			<form id="form-1">
          		<select name="system" id="system">
				  <option value="twitter">on Twitter with @</option>
				  <!--<option value="facebook">on facebook</option>-->
				</select>
          		<input type="text" id="username" name="username" /><br />
          		<input id="save-1" type="submit" value="Submit" />
        	</form>
		  </div>
		  <div id="tabs-2">
		    <form id="form-2">
          	Your translation:<br>
          		<textarea rows="4" cols="50" id="trans" name="trans"></textarea><br />
          		<input id="save-2" type="submit" value="Submit" />
        	</form>
		  </div>
		  <div id="tabs-3">
		    <form id="form-3">
          	Add links or hashtags to add context to the message:<br>
          		<textarea rows="4" cols="50" id="enrich" name="enrich"></textarea><br />
          		<input id="save-3" type="submit" value="Submit" />
        	</form>
		  </div>
		  <div id="tabs-4">
		    <form id="form-4">
          		<input type="radio" name="prio" value="+1" checked> +1<br>
  				<input type="radio" name="prio" value="-1"> -1<br />
          		<input id="save-4" type="submit" value="Submit" />
        	</form>
		  </div>
		  <div id="tabs-5">
		    <form id="form-5">
          	Your statement why this message should be resolved:<br>
          		<textarea rows="4" cols="50" id="resolve" name="resolve"></textarea><br />
          		<input id="save-5" type="submit" value="Submit" />
        	</form>
		  </div>
		</div>
	</div>
	<div class="space">
		<img src="see-help.jpg" />
	</div>
	<div id="state" class="ui-widget-content ui-corner-all ui-corner-bottom ui-corner-right ui-corner-br">
		<ul>
		<?php
			foreach ($taskruns_json->taskRuns as $run){
				echo('<li>');
				if(strpos($run->task_run_text,"PRIO") >= 0 && !(strpos($run->task_run_text,"PRIO") === false)) echo('<img class="li" src="../icons/resize-height-2x.png" />');
				if(strpos($run->task_run_text,"ENRICH") >= 0 && !(strpos($run->task_run_text,"ENRICH") === false)) echo('<img class="li" src="../icons/link-intact-2x.png" />');
				if(strpos($run->task_run_text,"SHARE") >= 0 && !(strpos($run->task_run_text,"SHARE") === false)) echo('<img class="li" src="../icons/share-boxed-2x.png" />');
				if(strpos($run->task_run_text,"TRANS") >= 0 && !(strpos($run->task_run_text,"TRANS") === false)) echo('<img class="li" src="../icons/random-2x.png" />');
				if(strpos($run->task_run_text,"RESOLVE") >= 0 && !(strpos($run->task_run_text,"RESOLVE") === false)) echo('<img class="li" src="../icons/flash-2x.png" />');
				echo ($run->task_run_text."<br /><a href='http://recoin.cloudapp.net/social-computer/instruction/".$run->task_run_id."'>go to instruction details >></a><br />");
				echo('</li>');
			}			
		?>
	</ul>
	</div>
</div>

</body>
</html>  