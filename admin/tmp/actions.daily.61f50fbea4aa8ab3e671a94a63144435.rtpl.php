<?php if(!class_exists('raintpl')){exit;}?><script src="tpl/./charts/js/highcharts.js"></script>
<script src="tpl/./charts/js/modules/exporting.js"></script>

<table class="table">
<thead>
	<th>Имя</th>
	<?php $counter1=-1; if( isset($table_days) && is_array($table_days) && sizeof($table_days) ) foreach( $table_days as $key1 => $value1 ){ $counter1++; ?><th><?php echo $value1;?></th><?php } ?>

</thead>
<?php $counter1=-1; if( isset($data_table) && is_array($data_table) && sizeof($data_table) ) foreach( $data_table as $key1 => $value1 ){ $counter1++; ?>

	<tr>
		<td><?php echo htmlspecialchars( $value1["name"] );?></td>
		<?php $counter2=-1; if( isset($value1["data"]) && is_array($value1["data"]) && sizeof($value1["data"]) ) foreach( $value1["data"] as $key2 => $value2 ){ $counter2++; ?>

		<td><?php echo $value2;?></td>
		<?php } ?>

	</tr>
<?php }else{ ?>

Данных еще нету.
<?php } ?>

</table>
( Всего / Уникальных )
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        title: {
            text: 'Статистика по действиям',
            x: -20 //center
        },
        xAxis: {
            categories: [<?php echo $days;?>]
        },
        yAxis: {
            title: {
                text: 'Количество'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ' действий'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: <?php echo json_encode( $data );?>

    });
});
</script>