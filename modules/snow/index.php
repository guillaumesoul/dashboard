<!--
Ce module affiche des flocons de neige sur toute la page
-->

<!-- Fonction Javascript d'affichage de flocons de neige -->
<script type='text/javascript'>

	var nb_flakes = 20;      // nombre de flocons
	var speed     = 15000;   // vitesse en ms +- 5 secondes
	var size      = 1;       // taille des flocons
	var W = window.innerWidth - 201;
	var H = window.innerHeight - 20;
	
	function snowFall (id) {
		var duration = Math.floor((Math.random()*5)+1)*1000+speed;
		var left = Math.floor((Math.random()*200)+1);
		id.animate({
		//$("#"+id).animate({

			top: "+="+H,
			left : "+="+left
		},
		duration,
		function() {
			var flake_size = Math.floor((Math.random()*20)+10*size);
			var left = Math.floor((Math.random()*W)+1);
			$(this).css('top', -20).css('left', left).css('font-size', flake_size);
			snowFall(id);
		});
	}
	
	for(var i=0;i<=nb_flakes;i++) {
		var left = Math.floor((Math.random()*W)+1);
		var flake_size = Math.floor((Math.random()*20)+10*size);
		$("#fond").append('<div class="snow" id="s'+i+'" style="left : '+left+'px; font-size : '+flake_size+'px; ">*</div>');
		snowFall($("#s"+i));
		//snowFall("s"+i);
	}

</script>





