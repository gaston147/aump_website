//Ensemble du code JQuery à écrire dans la fonction.
// On s’assure que le code sera exécute une fois la page chargée
$(function() {
	
	// on va déclencher une fonction lorsque l'internaute utilise sa molette
	$(window) .scroll(function() {
		// si l'internaute descend la fenetre de plus de 260px
		if ($(window).scrollTop() > 260 ) {
			// on va remplacer menu global par menu-fixed
			$("#nav-slider").addClass("menu-fixed");
			// on ajoute un padding top de 85px
			$("#main").css("padding-top" , "130px");
	
	
		} else {
			// on va supprimer la position fixed quand l'internautes remonte dans la page		
			$("#nav-slider").removeClass("menu-fixed");

			// on oublie pas de ramener le padding a sa valeur initiale
			$("#main").css("padding-top" , "40px");
		}
	});
	
// Fin du code Jquery
});
