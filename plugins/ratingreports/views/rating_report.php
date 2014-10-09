<?php
$url = url::site()."obras/view/".$incident_id;
$opt_nunca = (isset($monitoreos["Nunca se hizo"])) ? $monitoreos["Nunca se hizo"] : 0 ;
$opt_mal = (isset($monitoreos["Se hizo mal"])) ? $monitoreos["Se hizo mal"] : 0 ;
$opt_frenada = (isset($monitoreos["Está frenada"])) ? $monitoreos["Está frenada"] : 0 ;
$opt_terminada = (isset($monitoreos["Está terminada"])) ? $monitoreos["Está terminada"] : 0 ;
echo "<hr style='margin-top: 10px;padding: 3px;border-top: 1px dotted #C0C2B8;'>";
echo "<h3>Monitoreá la obra</h3>";
if($logged_in != 1) { echo "<a href="<?php echo url::site();?>login" type='button' class='btn btn-md btn-danger upper bold-italic'>Ingresa para monitorear la obra</a>"; }
if($logged_in != 1 ||  $user_vote != 0){
?>
<div class="box-graph">
	<?php if($user_vote != 0) { echo "<p>Ya realizaste tu voto</p>"; } ?>
	<h3>Resultados</h3>
	<div id="chart_div" style="width: 100%; height: 200px;"></div>
</div>
<?php
} else {
?>
<div class="box-options">	
	<ul id="monitoreo" class="list-inline">
		<li><a id="cat_1" href="#" type="button" class="btn btn-danger btn-cons">Nunca se hizó</a></li>
		<li><a id="cat_2" href="#" type="button" class="btn btn-warning btn-cons">Se hizó mal</a></li>
		<li><a id="cat_3" href="#" type="button" class="btn btn-info btn-cons">Está frenada</a></li>
		<li><a id="cat_4" href="#" type="button" class="btn btn-success btn-cons">Está terminada</a></li>
	</ul>	
	<h3>Resultados</h3>
	<div id="chart_div" style="width: 100%; height: 200px;"></div>
</div>
<?php
}
?>
<script type="text/javascript">
	function reload() {
	    window.location = '<?php echo $url;?>';
	}
	$("ul#monitoreo li > a").click(function(e) {
		$('#monitoreo').html('<img src="<?php echo url::file_loc('img')."media/img/loading_g.gif"; ?>">');
		var catid = this.id.substring(4);
		var id = <?php echo $incident_id; ?>;

		$.post("<?php echo url::site().'rating_reports/monitorear/' ?>" + id, { cat: catid },
			function(data){
				if (data.status == 'saved'){
					$('#monitoreo').html('<li><p>Tu voto ha sido guardo con exito</p></li>');					
					 setTimeout('reload()', 800);
				} else {
					if(typeof(data.message) != 'undefined') {
						$('#monitoreo').html('<li><p>'+data.message+'</p></li>');											
					}
				}
				$('#monitoreo').html('');
		  	}, "json");


		e.stopPropagation();
		return false;
	});
</script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawChart);
	function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['La obra ..', 'Votos', { role: 'style' }],
			['Nunca se hizó', <?php echo $opt_nunca ?>, '#d9354f'],           
			['Se hizó mal', <?php echo $opt_mal ?>, '#f0ad4e'],          
			['Está frenada', <?php echo $opt_frenada ?>, '#5bc0de'],
			['Está terminada', <?php echo $opt_terminada ?>, '#5cb85c' ], 
		]);
		
		var view = new google.visualization.DataView(data);
		view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);
                       
		var options = {
			chartArea: {left:0,top:0,width:'100%',height:'75%'},
			title: 'Estado de la obra',   
			legend: { position: 'none' },
			bar: { groupWidth: '90%' },
			vAxis: {gridlines: { color:'#FFF', count: '0'}},
			tooltip: { trigger: 'none' }
		};
		var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
		chart.draw(view, options);
	}
</script>
