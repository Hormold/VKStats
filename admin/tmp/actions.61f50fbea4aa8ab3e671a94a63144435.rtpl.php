<?php if(!class_exists('raintpl')){exit;}?><?php if( $actions ){ ?>

<table class="table">
<thead><th>#</th><th>Название</th><th>Тип действия</th><th>Значание / Целевое</th><th>Удалить</th></thead>
<?php $counter1=-1; if( isset($actions) && is_array($actions) && sizeof($actions) ) foreach( $actions as $key1 => $value1 ){ $counter1++; ?>

<tr>
	<td><?php echo $value1["action_id"];?></td>
	<td><?php echo htmlspecialchars( $value1["name"] );?></td>
	<td><?php echo htmlspecialchars( $value1["type"] );?></td>
	<td><?php if( $value1["type"]=='redirect' ){ ?><?php echo htmlspecialchars( $value1["value"] );?><?php }else{ ?><?php if( $value1["target_action"]==1 ){ ?>Целевое<?php }else{ ?>Не целевое<?php } ?><?php } ?></td>
	<td><a href="?page=actions.delete&id=<?php echo $value1["action_id"];?>" class="btn btn-danger btn-xs">Удалить</a></td>
</tr>
<?php } ?>

</table>
<?php }else{ ?>

<div class="alert alert-info">Действий еще нет, Вы можете добавить их прямо сейчас.</div>
<?php } ?>

<a href="?page=actions.new&id=<?php echo $pid;?>" class="btn btn-lg btn-success">Создать действие</a>