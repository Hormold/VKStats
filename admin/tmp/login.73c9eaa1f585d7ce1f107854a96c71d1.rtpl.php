<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Панель Администратора</title>
	<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="tpl/css/login.css">
  <meta charset="UTF-8">
</head>
<body onLoad="loaded();">

 <div class="container">

      <form class="form-signin" role="form" method="POST">
        <h2 class="form-signin-heading">Войти</h2>
        <?php if( isset($error) ){ ?><div class="alert alert-danger">Неправильный логин или пароль!</div><?php } ?>

        <input type="text" name="login" class="form-control" placeholder="Логин" required autofocus>
        <input type="password" name="password" class="form-control" placeholder="Пароль" required>
        <button class="btn btn-lg btn-success btn-primary btn-block" type="submit">Войти</button>
      </form>

    </div>

</body>
</html>
