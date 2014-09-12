<?php
$opt_nunca = 0;
$opt_mal = 10;
$opt_frenada = 5;
$opt_terminada = 21;

if ($logged_in != '0' && $user_vote != '1') {	
?>
<h2>Resultados del monitoreo</h2>
<div id="chart_div" style="width: 100%; height: 200px;"></div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawChart);
	function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['La obra ..', 'Votos', { role: 'style' }],
			['Nunca se hizo', <?php echo $opt_nunca ?>, '#d9534f'],           
			['Se hizo mal', <?php echo $opt_mal ?>, '#f0ad4e'],          
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
<?php
} else {
?>
<h2>Monitorea la obra</h2>
<div id="options" class="">
	<button type="button" class="btn btn-danger btn-cons">Nunca se hizo</button>
	<button type="button" class="btn btn-warning btn-cons">Se hizo mal</button>
	<button type="button" class="btn btn-info btn-cons">Está frenada</button>
	<button type="button" class="btn btn-success btn-cons">Está terminada</button>
</div>
<script type="text/javascript">
	function ratear(id,action,type,loader) {
	$('#' + loader).html('<img src="<?php echo url::file_loc('img')."media/img/loading_g.gif"; ?>">');
	$.post("<?php echo url::site().'reports/rating/' ?>" + id, { action: action, type: type },
		function(data){
			if (data.status == 'saved'){
				if (type == 'original') {
					$('#oup_' + id).attr("src","<?php echo url::file_loc('img').'media/img/'; ?>gray_up.png");
					$('#odown_' + id).attr("src","<?php echo url::file_loc('img').'media/img/'; ?>gray_down.png");
					$('#orating_' + id).html(data.rating);
				}
				else if (type == 'comment')
				{
					$('#cup_' + id).attr("src","<?php echo url::file_loc('img').'media/img/'; ?>gray_up.png");
					$('#cdown_' + id).attr("src","<?php echo url::file_loc('img').'media/img/'; ?>gray_down.png");
					$('#crating_' + id).html(data.rating);
				}
			} else {
				if(typeof(data.message) != 'undefined') {
					alert(data.message);
				}
			}
			$('#' + loader).html('');
	  	}, "json");
	}
</script>
<?php
}
?>
