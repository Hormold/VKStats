<?php
include("inc/db.php");
include("inc/functions.php");

$g=fixInput($_GET);
$host=parseReferer();
$output=null;
if(isset($g["act"]) AND isset($g["cookie"]) AND preg_match("/^[0-9a-zA-Z]{9}$/i", $g["cookie"]) AND isset($g["project_id"]) AND preg_match("/^[0-9]/i", $g["project_id"])){
	if($host!==false){
		switch ($g["act"]) {
			case 'debug':
				$output=$host;
				break;

			case 'getProjects':
				$db=db::getInstance();
				$host=$db->select("projects","domain='{$host}'");
				if($host){
					foreach($host as &$v){
						unset($v["uid"],$v["activate"]);
					}
					$output=$host;
				}else{
					$output=errorGenerator(1,"Bad hostname");
				}
				break;

			case 'isAllowed':
				$db=db::getInstance();
				$host=$db->select("projects","id='".$db->secure($g["project_id"])."'",1);
				if($host){
					$output=isAllowed();
				}else{
					$output=errorGenerator(5,"Bad project id");
				}
				break;

			case 'getActions':
				$db=db::getInstance();
				$host=$db->select("projects","id='".$g["project_id"]."' AND domain='{$host}'",1);
				if($host AND isAllowed()){
					$actions=$db->select("actions","pid=".intval($host["id"])." AND active=1");
					if($actions){
						foreach($actions as &$v){
							unset($v["pid"],$v["action_id"]);
							if($v["type"]!=="redirect"){unset($v["value"]);}else{unset($v["target_action"]);}
						}
						$output=$actions;
					}
				}else{
					$output=errorGenerator(1,"Bad hostname / Access denied");
				}
				break;

			case 'trackAction':
				$db=db::getInstance();
				$host=$db->select("projects JOIN actions ON projects.id=actions.pid","projects.id='".$g["project_id"]."' AND projects.domain='{$host}' AND actions.name='".$db->secure($g["name"])."' AND actions.active=1",1); //TODO: 1 запрос
				
				if($host AND isAllowed()){
					if($host["target_action"]==1){
						if(!$db->select("actions_log","aid=".$host["action_id"]." AND user='".$db->secure($g["cookie"])."'",1)){
							$output=$db->insert(Array("pid"=>$host["id"],"aid"=>$host["action_id"],"user"=>$db->secure($g["cookie"]),"its"=>time()),"actions_log");
						}else{
							$output=false;
						}
					}else{
						$output=$db->insert(Array("pid"=>$host["id"],"aid"=>$host["action_id"],"user"=>$db->secure($g["cookie"]),"its"=>time()),"actions_log");
					}
				}else{
					$output=errorGenerator(3,"Bad hostname or action name / Access denied");
				}
				break;

			case 'isCompleted':
				$db=db::getInstance();
				$host=$db->select("projects","domain='{$host}'",1);
				if($host){
					$action=$db->select("actions","name='".$db->secure($g["name"])."' AND active=1",1);
					if($action){
						$output = false;
						if($db->select("actions_log","user='".$g["cookie"]."' AND aid=".$action["action_id"],1)){
							$output = true;
						}
					}else{
						$output=errorGenerator(3,"Bad Action Name");
					}
				}else{
					$output=errorGenerator(1,"Bad hostname");
				}
				break;

			default:
				$output=Array("error_code"=>2,"error_msg"=>"Unknown Action","request_params"=>$g);
				break;
		}
	}else{
		$output=Array("error_code"=>1,"error_msg"=>"Hostname not passed","request_params"=>$g);
	}
}else{
	$output=Array("error_code"=>2,"error_msg"=>"Unknown Action or cookie/project_id is missed","request_params"=>$g);
}

echo prepareOutput($output);