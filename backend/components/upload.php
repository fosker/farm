<?php

function outputJSON($msg, $status = 'error'){
	die(json_encode(array(
		'data' => $msg,
		'status' => $status
	)));
}

outputJSON(var_dump($_POST));

