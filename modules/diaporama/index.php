
<script src='./modules/diaporama/diaporama.js'></script>

<?php

//--------------------------------
// DIAPORAMA
// Il s'agit ici d'afficher des images toutes les x secondes.
//--------------------------------

	
// Récupération du répertoire de stockage des photos
if(!empty($_GET['dossier'])) {
	$dir = $_GET['dossier'];
} else {
	$dir = './img/';
}
	
// Récupération du délai d'affichage de chaque photo
if(!empty($_GET['delai'])) {
	$delai = $_GET['delai'];
} else {
	$delai = 5; // 5 secondes par défaut
}

// liste des extensions images affichables
$types = '*.{gif,jpg,jpeg,JPG,png}';

// récupération des fichiers associés dans un tableau
// ne pas oublier GLOB_BRACE qui permet de lister plusieurs patterns de recherche !
$diapo=glob($dir.$types, GLOB_BRACE);
// Mise à jour des chemins d'accès relatifs
$diapo=str_replace("./", "./modules/diaporama/", $diapo);
// si on veut trier le tableau dans l'ordre naturel
// = 10 après 2 et pas avant !
//usort($diapo, "strnatcmp");

// on compte les images à afficher
$nb=count($diapo);

// affichage des images
$img=0;
echo '<img width="100%" src="'.$diapo[0].'" alt="diaporama" />';
echo '<ul style="display:none">';
while ($img < $nb) {
    echo '<li>'.$diapo[$img].'</li>';
    $img++;
}
echo '</ul>';
?>

<script>
window.monDiaporama = diaporama();
</script>
