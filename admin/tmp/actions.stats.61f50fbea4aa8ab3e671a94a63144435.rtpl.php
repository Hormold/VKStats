<?php if(!class_exists('raintpl')){exit;}?><?php if( $actions ){ ?>

<table class="table">
<thead><th>Название</th><th>Количество</th><th>Уникальных</th></thead>
<?php $counter1=-1; if( isset($actions) && is_array($actions) && sizeof($actions) ) foreach( $actions as $key1 => $value1 ){ $counter1++; ?>

<tr>
	<td><?php echo $value1["name"];?></td>
	<td><?php echo $value1["total"];?></td>
	<td><?php echo $value1["uniqe"];?></td>
</tr>
<?php } ?>

</table>
<?php }else{ ?>

<div class="alert alert-info">Совершенный действий еще нет, подождите немного.</div>
<?php } ?>