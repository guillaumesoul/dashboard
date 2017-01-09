<?php
	
	// Récupération du flux rss à afficher via config.json ou une valeur par défaut (lemonde.fr ici)
	if(!empty($_GET['url'])) {
		$url = $_GET['url'];
	} else {
		$url = 'http://www.lemonde.fr/rss/une.xml';
	}
	
	// Récupération du nb d'articles à afficher via config.json ou une valeur par défaut
	if(!empty($_GET['nb_items'])) {
		$nb_items_a_afficher = $_GET['nb_items'];
	} else {
		$nb_items_a_afficher = 3;
	}
	
	// Gestion de la hauteur des items en fonction de leur nombres (en %)
	$hauteur_items = round((100 / $nb_items_a_afficher), 0);
	echo '<style type="text/css">.rss_item { height: '.$hauteur_items.'%; }</style>';
	
	// Gestion d'une image logo du flux rss lu
    $domain = parse_url($url, PHP_URL_HOST);			// Extraction du nom de domaine du flux rss
    if($domain{3} == ".") {								// Suppression du www. si besoin
    	$domain = substr($domain, 4);
    }
    $domain = substr($domain, 0, strpos($domain, '.'));	// Suppression du .extension
    $logo = $domain.'.png';								// Création du nom du logo à associer au flux rss
    if(!file_exists($logo)){							// Remplacement par un logo standard si le fichier n'existe pas
    	$logo = 'rss.png';
    }
    echo '<img src="./modules/rss/'.$logo.'" title="Rss" alt="Rss">';
	
	// Récupération des articles Rss
	$rss = @simplexml_load_file($url);
	$i = 0;
	foreach($rss->channel->item as $item) {
		if($i == $nb_items_a_afficher) break;
		$i++;
        echo '<div class="rss_item">';
        echo '<div class="rss_titre">';
        echo '<a href="'.$item->link.'" title="'.$item->title.'">'.$item->title.'</a>';
        echo '</div>';
        echo '<div class="rss_texte">'.strip_tags($item->description).'</div>';
        echo '</div>';
	}

?>

<!-- 
	// Version sans doute plus sérieuse et universelle en utilisant rss_php.php :
    $rss = new rss_php;
    $rss->load($url);
    $items = $rss->getItems();

    foreach($items as $index => $item) {
    	if($index == $nb_items_a_afficher) {
    	break;
    	}
        $html .= '<div class="rss_titre"><a href="'.$item['link'].'" title="'.$item['title'].'">'.$item['title'].'</a></div>
        <div class="rss_texte">'.strip_tags($item['description']).'</div>';
    }

 -->