<?php
session_start();
ini_set('log_errors', 'On');
include("inc/db.php");
include "inc/rain.tpl.class.php";
raintpl::configure("base_url", null);
raintpl::configure("tpl_dir", "tpl/");
raintpl::configure("cache_dir", "tmp/");
$tpl = new RainTPL;
if (isset($_SESSION["login"])) {
	$user = $_SESSION["login"];
	if (!isset($_GET["page"])) {
		$_GET["page"] = "index";
	}
	$tpl->assign("page", $_GET["page"]);
	switch ($_GET["page"]) {
		case 'logout':
			session_destroy();
			header("Location: /admin/");
			break;
		
		case 'new':
			if (isset($_POST["title"]) AND !empty($_POST["title"]) AND $_POST["link"] !== "") {
				$db     = db::getInstance();
				$domain = parse_url($_POST["link"]);
				if (isset($domain["host"])) {
					$check = $db->select("projects", "domain='" . $db->secure($domain["host"]) . "' AND uid!=" . $user["id"]);
					if ($check == false) {
						$db->insert(Array(
							"title" => $db->secure($_POST["title"]),
							"domain" => $domain["host"],
							"uid" => $user["id"],
							"its" => time()
						), "projects");
						header("Location: /admin/");
					}
				} else {
					$tpl->assign("error", "Некорректная ссылка");
				}
			}
			$tpl->assign("title", "Создать новый проект");
			$tpl->assign("page", "new");
			break;
		
		case 'delete':
			if (isset($_GET["id"])) {
				$db    = db::getInstance();
				$check = $db->select("projects", "id=" . intval($_GET["id"]) . " AND uid=" . $user["id"], 1);
				if ($check) {
					if ($db->update(Array(
						"activate" => 0
					), "projects", "id=" . intval($_GET["id"]))) {
						header("Location: /admin/");
					}
				}
			}
			break;
		
		case 'log':
			if (isset($_GET["id"])) {
				$db    = db::getInstance();
				$check = $db->select("projects", "id=" . intval($_GET["id"]) . " AND uid=" . $user["id"], 1);
				if ($check) {
					$tpl->assign("title", "100 последних действий");
					$tpl->assign("pid", $_GET["id"]);
					$tpl->assign("log", $db->select("actions_log JOIN actions ON actions_log.aid=actions.action_id", "actions_log.pid=" . $_GET["id"] . " ORDER by actions_log.id DESC", 100));
					$tpl->assign("page", "log");
				} else {
					$tpl->assign("error", "Доступ запрещен.");
				}
			}
			break;
		
		case 'actions':
			if (isset($_GET["id"])) {
				$db    = db::getInstance();
				$check = $db->select("projects", "id=" . intval($_GET["id"]) . " AND uid=" . $user["id"], 1);
				if ($check) {
					$tpl->assign("title", "Доступные действия");
					$tpl->assign("pid", $_GET["id"]);
					$tpl->assign("actions", $db->select("actions", "pid=" . $_GET["id"] . " AND active=1 ORDER by action_id DESC"));
					$tpl->assign("page", "actions");
				} else {
					$tpl->assign("error", "Доступ запрещен.");
				}
			}
			break;
		
		case 'actions.stats':
			if (isset($_GET["id"])) {
				$db    = db::getInstance();
				$check = $db->select("projects", "id=" . intval($_GET["id"]) . " AND uid=" . $user["id"], 1);
				if ($check) {
					$tpl->assign("title", "Суммарная статистика");
					$tpl->assign("pid", $_GET["id"]);
					$actions      = $db->select("actions", "pid=" . $_GET["id"] . " AND active=1");
					$actions_list = Array();
					$byActions    = Array();
					foreach ($actions as $v) {
						$uniqe       = $db->sql("SELECT COUNT(DISTINCT user) FROM actions_log WHERE aid=" . $v["action_id"], 1);
						$byActions[] = Array(
							"name" => $v["name"],
							"uniqe" => $uniqe[0]["COUNT(DISTINCT user)"],
							"total" => $db->count("actions_log", "aid=" . $v["action_id"])
						);
					}
					//SELECT actions_log.*, actions.name FROM actions_log JOIN actions ON actions.action_id=actions_log.id WHERE actions_log.pid=1 ORDER by actions_log.its
					$tpl->assign("actions", $byActions);
					$tpl->assign("page", "actions.stats");
				} else {
					$tpl->assign("error", "Доступ запрещен.");
				}
			}
			break;
		
		case 'actions.stats.list':
			if (isset($_GET["id"])) {
				$db           = db::getInstance();
				$actions_list = Array();
				$check        = $db->select("projects", "id=" . intval($_GET["id"]) . " AND uid=" . $user["id"], 1);
				$actions      = $db->select("actions", "pid=" . intval($_GET["id"]) . " AND target_action=1 AND active=1"); // Загрузить список таргетированных действий
				$actions = array_map(function($val)
				{
					return $val["action_id"];
				}, $actions);
				
				if ($check) {
					$tpl->assign("title", "Список пользователей по всем целевым действиям");
					$tpl->assign("pid", $_GET["id"]);
					$tpl->assign("page", "actions.stats.list");
					$tpl->assign("data", $db->sql("SELECT * FROM actions_log WHERE aid IN (" . implode(",", $actions) . ") GROUP BY user ", 1));
				} else {
					$tpl->assign("error", "Доступ запрещен.");
				}
			}
			
			break;
		
		case 'actions.stats.list.single':
			if (isset($_GET["id"]) AND isset($_GET["aid"])) {
				$db           = db::getInstance();
				$actions_list = Array();
				$check        = $db->select("projects", "id=" . intval($_GET["id"]) . " AND uid=" . $user["id"], 1);
				$actions      = $db->select("actions", "pid=" . intval($_GET["id"]) . " AND action_id=" . intval($_GET["aid"]) . " AND target_action=1 AND active=1", 1); // Если действие принадлежит проекту и оно активно				
				if ($check AND $actions) {
					$tpl->assign("title", "Пользователи по целевому действию: " . htmlspecialchars($actions["name"]));
					$tpl->assign("pid", $_GET["id"]);
					$tpl->assign("page", "actions.stats.list.single");
					$tpl->assign("data", $db->sql("SELECT * FROM actions_log WHERE aid=" . intval($_GET["aid"]) . " GROUP BY user ", 1));
				} else {
					$tpl->assign("error", "Доступ запрещен.");
				}
			}
			
			break;
		
		case 'actions.stats2':
			if (isset($_GET["id"])) {
				$db    = db::getInstance();
				$check = $db->select("projects", "id=" . intval($_GET["id"]) . " AND uid=" . $user["id"], 1);
				if ($check) {
					$tpl->assign("title", "Статистика по целевым действиям");
					$tpl->assign("pid", $_GET["id"]);
					$actions      = $db->select("actions", "pid=" . $_GET["id"] . " AND target_action=1  AND active=1");
					$actions_list = Array();
					$byActions    = Array();
					if ($actions) {
						foreach ($actions as $v) {
							$uniqe       = $db->sql("SELECT COUNT(DISTINCT user) FROM actions_log WHERE aid=" . $v["action_id"], 1);
							$byActions[] = Array(
								"name" => $v["name"],
								"uniqe" => $uniqe[0]["COUNT(DISTINCT user)"],
								"total" => $db->count("actions_log", "aid=" . $v["action_id"]),
								"action_id" => $v["action_id"]
							);
						}
					}
					$tpl->assign("actions", $byActions);
					$tpl->assign("page", "actions.stats2");
				} else {
					$tpl->assign("error", "Доступ запрещен.");
				}
			}
			break;
		
		case 'actions.daily':
			if (isset($_GET["id"])) {
				$db    = db::getInstance();
				$check = $db->select("projects", "id=" . intval($_GET["id"]) . " AND uid=" . $user["id"], 1);
				if ($check) {
					$tpl->assign("title", "Статистика по дням");
					$tpl->assign("pid", $_GET["id"]);
					$d = array();
					for ($i = 0; $i < 10; $i++) {
						$d[]          = date("'d M'", strtotime('-' . $i . ' days'));
						$table_days[] = date("d.m", strtotime('-' . $i . ' days'));
						$range[]      = date("U", strtotime('-' . $i . ' days 00:00')) . "<its AND its<" . date("U", strtotime('-' . $i . ' days 23:59:59'));
					}
					$d          = array_reverse($d);
					$range      = array_reverse($range);
					$table_days = array_reverse($table_days);
					$tpl->assign("table_days", $table_days);
					$tpl->assign("days", implode(", ", $d));
					
					$actions = $db->select("actions", "pid=" . $_GET["id"] . " AND active=1");
					$data    = Array();
					$data2   = Array();
					foreach ($actions as $action) {
						$query  = Array();
						$query2 = Array();
						foreach ($range as $v) {
							$count    = intval($db->count("actions_log", "aid=" . $action["action_id"] . " AND " . $v));
							$query[]  = $count;
							$uniqe    = $db->sql("SELECT COUNT(DISTINCT user) FROM actions_log WHERE aid=" . $action["action_id"] . " AND " . $v, 1);
							$query2[] = $count . " / " . intval($uniqe[0]["COUNT(DISTINCT user)"]);
						}
						$data[]  = Array(
							"name" => $action["name"],
							"data" => $query,
							"data2" => $query2
						);
						$data2[] = Array(
							"name" => $action["name"],
							"data" => $query2
						);
					}
					$tpl->assign("data", $data);
					$tpl->assign("data_table", $data2);
					$tpl->assign("page", "actions.daily");
				} else {
					$tpl->assign("error", "Доступ запрещен.");
				}
			}
			break;
		
		case 'actions.delete':
			if (isset($_GET["id"])) {
				$db     = db::getInstance();
				$check  = $db->select("actions", "action_id=" . intval($_GET["id"]), 1);
				$check2 = $db->select("projects", "id=" . $check["pid"] . " AND uid=" . $user["id"], 1);
				if ($check && $check2) {
					if ($db->update(Array(
						"active" => 0
					), "actions", "action_id=" . intval($_GET["id"]))) {
						header("Location: /admin/?page=actions&id=" . $check["pid"]);
					}
				}
			}
			break;
		
		case 'actions.new':
			if (isset($_GET["id"]) AND isset($_POST["title"]) AND !empty($_POST["title"]) AND $_POST["type"] !== "") {
				$db   = db::getInstance();
				$type = $db->secure($_POST["type"]);
				if ($type == "redirect" OR $type == "action") {
					if ($db->select("actions", "active=1 AND name='" . $db->secure($_POST["title"]) . "'", 1) == false AND $db->select("projects", "uid=" . $user["id"] . " AND id='" . $db->secure($_GET["id"]) . "'", 1) !== false) {
						$check2 = $db->select("projects", "id=" . intval($_GET["id"]), 1);
						if ($check2) {
							$db->insert(Array(
								"name" => $db->secure($_POST["title"]),
								"type" => $type,
								"pid" => $_GET["id"],
								"value" => $db->secure($_POST["value"]),
								"target_action" => (isset($_POST["target_action"]) AND $_POST["target_action"] == "on") ? 1 : 0
							), "actions");
							header("Location: /admin/?page=actions&id=" . $check2["id"]);
						}
					} else {
						$tpl->assign("error", "Такое действие уже существует или вы пытаетесь взломать админку!");
					}
				} else {
					$tpl->assign("error", "Некорректное действие");
				}
			}
			(isset($_GET["id"]) ? $tpl->assign("pid", intval($_GET["id"])) : "");
			
			$tpl->assign("title", "Создать новое действие");
			$tpl->assign("page", "actions.new");
			break;
		
		default:
			$db = db::getInstance();
			$tpl->assign("title", "Список проектов");
			$tpl->assign("projects", $db->select("projects", "activate=1 AND uid=" . $user["id"]));
			$tpl->assign("page", "main");
			break;
	}
	$tpl->draw('page');
} else {
	if (isset($_POST["login"]) AND isset($_POST["password"])) {
		$db    = db::getInstance();
		$check = $db->select("users", "login='" . $_POST["login"] . "'", 1);
		if ($check AND $check["password"] == $_POST["password"]) {
			$_SESSION["login"] = $check;
			header("Location: /admin/");
		} else {
			$tpl->assign("error", true);
		}
	}
	$tpl->draw('login');
}

function time_format($its)
{
	return date("H:i:s (d/m)", $its);
}
?>