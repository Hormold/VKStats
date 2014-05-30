<?php if(!class_exists('raintpl')){exit;}?><?php if( $log ){ ?>

<table class="table">
<thead><th>Название</th><th>Тип действия</th><th>Время</th><th>User ID</th></thead>
<?php $counter1=-1; if( isset($log) && is_array($log) && sizeof($log) ) foreach( $log as $key1 => $value1 ){ $counter1++; ?>

<tr>
  <td><?php echo htmlspecialchars( $value1["name"] );?></td>
  <td><?php echo htmlspecialchars( $value1["type"] );?></td>
  <td><?php echo time_format( $value1["its"] );?></td>
  <td><?php echo $value1["user"];?></td>
</tr>
<?php } ?>

</table>
<?php }else{ ?>

Действия еще не записывались!
<?php } ?>