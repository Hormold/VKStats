<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Панель Администратора</title>
	<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<link rel="stylesheet" href="tpl/css/main.css">
  <meta charset="UTF-8">
</head>
<body onLoad="">
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
<div class="container-fluid">
<div class="navbar-header">
  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
    <span class="sr-only">Включить навигацию</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
  <a class="navbar-brand" href="#">VKStats</a>
</div>
<div class="navbar-collapse collapse">
  <ul class="nav navbar-nav navbar-right">
    <li><a href="?">Список проектов</a></li>
    <li><a href="?page=logout">Выйти</a></li>
  </ul>
</div>
</div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        <li <?php if( $page=='main' ){ ?>class="active"<?php } ?>><a href="?page=main">Список проектов</a></li>
        <li <?php if( $page=='new' ){ ?>class="active"<?php } ?>><a href="?page=new">Создать новый проект</a></li>
      </ul>
      <?php if( isset($pid) ){ ?>

      <ul class="nav nav-sidebar">
      	<li <?php if( $page=='log' ){ ?>class="active"<?php } ?>><a href="?page=log&id=<?php echo $pid;?>">Последние действия</a></li>
        <li <?php if( $page=='actions' ){ ?>class="active"<?php } ?>><a href="?page=actions&id=<?php echo $pid;?>">Действия</a></li>
        <li <?php if( $page=='actions.stats' ){ ?>class="active"<?php } ?>><a href="?page=actions.stats&id=<?php echo $pid;?>">Суммарная статистика</a></li>
        <li <?php if( $page=='actions.stats2' ){ ?>class="active"<?php } ?>><a href="?page=actions.stats2&id=<?php echo $pid;?>">Статистика по целевым действиям</a></li>
        <li <?php if( $page=='actions.daily' ){ ?>class="active"<?php } ?>><a href="?page=actions.daily&id=<?php echo $pid;?>">Статистика по дням</a></li>
      </ul>
      <?php } ?>

    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header"><?php if( isset($title) ){ ?><?php echo $title;?><?php }else{ ?>Ошибка<?php } ?></h1>
      <?php if( isset($error) ){ ?><div class="alert alert-danger"><?php echo $error;?></div><?php } ?>

      <?php if( isset($page) ){ ?> <?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("".$page."") . ( substr("".$page."",-1,1) != "/" ? "/" : "" ) . basename("".$page."") );?> <?php }else{ ?> Ошибка <?php } ?>

      </div>
    </div>
  </div>
</div>
</body>
</html>
