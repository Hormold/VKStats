<?php
function parseReferer(){
	if(isset($_SERVER["HTTP_REFERER"])){
		$req_from = $_SERVER["HTTP_REFERER"];
		$domain=parse_url($req_from);
		return $domain["host"];
	}
	return false;
}

function isAllowed(){
	global $g,$host;
	$rand=ord(str_split($g["cookie"])[0]);
	if(($rand+$host["id"])%2==0){
		return true;
	}
	return false;
}

function prepareOutput($output){
	if(isset($output["error_code"])){
		$output=Array("error"=>$output);
	}else{
		$output=Array("response"=>$output);
	}
	return json_encode($output);
}

function fixInput($input){
	foreach($input as $v){
		if(is_array($v)){
			dir("Array not allowed");
		}
	}
	return $input;
}

function errorGenerator($code,$msg){
	global $g;
	return Array("error_code"=>$code,"error_msg"=>$msg,"request_params"=>$g);
}

?>