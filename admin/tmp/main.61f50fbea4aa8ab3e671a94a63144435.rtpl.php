<?php if(!class_exists('raintpl')){exit;}?><?php if( $projects ){ ?>

<table class="table">
<thead><th>#</th><th>Название</th><th>Основной домен</th><th>Действия</th><th>Удалить</th></thead>
<?php $counter1=-1; if( isset($projects) && is_array($projects) && sizeof($projects) ) foreach( $projects as $key1 => $value1 ){ $counter1++; ?>

<tr>
	<td><?php echo $value1["id"];?></td>
	<td><?php echo htmlspecialchars( $value1["title"] );?></td>
	<td><?php echo htmlspecialchars( $value1["domain"] );?></td>
	<td><a href="?page=log&id=<?php echo $value1["id"];?>" class="btn btn-info btn-xs">Последние действия</a></td>
	<td><a href="?page=delete&id=<?php echo $value1["id"];?>" class="btn btn-danger btn-xs">Удалить</a></td>
</tr>
<?php } ?>

</table>
<?php }else{ ?>

<div class="alert alert-info">Проектов еще нету, Вы можете добавить их прямо сейчас.</div>
<form method="POST" action="?page=new" class="form-horizontal">
<fieldset>

<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Название проекта</label>  
  <div class="col-md-4">
  <input id="textinput" name="title" type="text" placeholder="ВКонтакте" class="form-control input-md">
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Ссылка на сайт</label>  
  <div class="col-md-4">
  <input id="textinput" name="link" type="text" placeholder="http://vk.com/" class="form-control input-md">
  </div>
</div>

<!-- Button -->
<div class="form-group"> <label class="col-md-4 control-label" for="textinput">&nbsp;</label>  
  <div class="col-md-4">
    <button id="singlebutton" name="singlebutton" class="btn btn-primary">Создать проект</button>
  </div>
</div>

</fieldset>
</form>

<?php } ?>

