<?php
// D'abord, on commence par lire le fichier de configuration config.json
define('CONFIG', 'config.json');			//définition d'une variable prenant la valeur du fichier

//$config = fopen(CONFIG, 'r');				//ouverture en lecture du fichier
//$data = fread($config, filesize(CONFIG));	//lecture des données du fichier
$data = file_get_contents(CONFIG);
$data = json_decode($data);					//décodage des données .json

// S'il n'y a pas de données, on renvoie une erreur indiquant que le fichier .json n'est pas correct
if (!$data) die('Erreur de syntaxe JSON dans votre fichier "'.CONFIG.'" !');

//--------------------------------------
// Cette fonction permet d'interpréter le contenu de chaque module lu dans le fichier de configuration
//--------------------------------------
function render($module, $zindex) {
	
	// Gestion des arguments de module
    $args = isset($module->args) ? $module->args : NULL;	//un tableau d'arguments 'args' est-il associé au module ?
    $argstr = array();										//on récupère les arguments dans un tableau
    foreach($args as $key => $val) {
        $argstr[] = "$key=" . urlencode($val);
    }
    $argstr = implode("&", $argstr);
    
    //
    // Gestion des données css associées au module
    //
    // Taille :
    $style = isset($module->width) ? "width: {$module->width};" : NULL;		//largeur du module
    $style .= isset($module->height) ? "height: {$module->height};" : NULL;	//hauteur du module
    // Placement :
    $style .= isset($module->top) ? "top: {$module->top};" : NULL;				//placement haut du module
    $style .= isset($module->left) ? "left: {$module->left};" : NULL;			//placement gauche du module
    // Nom & classe :
    if (!isset($module->type)) $module->type = $module->name;					//pour compatabilité (à vérifier ?)
    $class = "module";
    $class .= isset($module->class) ? " ".$module->class : '';						//classe du module
    // Gestion des z-index : fonction de l'ordre des modules dans le fichier .json par défaut
    $style .= "z-index: ".$zindex.";";
    // Infobulle :
    $infobulle = $module->name;
    if (isset($module->width) or isset($module->height)) {
    	$infobulle .= " : ";
	    $infobulle .= isset($module->width) ? $module->width : '?px';
    	$infobulle .= "*";
    	$infobulle .= isset($module->height) ? $module->height : '?px';
	}
	//
	// Retour du module sous forme d'une div css et du code javascript pour sa mise à jour
	//
    return "<div class='$class' id='$module->name' style='$style' title='$infobulle'>
    <script type='text/javascript'>activate_module('$module->name', '$module->type', $module->update, '$argstr');</script>
    </div>";
}



/* GOOGLE TASKS */

require_once __DIR__ . '/vendor/autoload.php';


define('APPLICATION_NAME', 'Google Tasks API PHP Quickstart');
define('CREDENTIALS_PATH', '~/.credentials/tasks-php-quickstart.json');
define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');

$aa = CLIENT_SECRET_PATH;
// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/tasks-php-quickstart.json
define('SCOPES', implode(' ', array(
        Google_Service_Tasks::TASKS_READONLY)
));

/*if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}*/

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient() {
    $client = new Google_Client();
    $client->setApplicationName(APPLICATION_NAME);
    $client->setScopes(SCOPES);
    $client->setAuthConfig(CLIENT_SECRET_PATH);
    $client->setAccessType('offline');

    // Load previously authorized credentials from a file.
    $credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
    if (file_exists($credentialsPath)) {
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
    } else {
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        $authCode = trim(fgets(STDIN));

        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Store the credentials to disk.
        if(!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
        printf("Credentials saved to %s\n", $credentialsPath);
    }
    $client->setAccessToken($accessToken);

    // Refresh the token if it's expired.
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path) {
    $homeDirectory = getenv('HOME');
    if (empty($homeDirectory)) {
        $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
    }
    return str_replace('~', realpath($homeDirectory), $path);
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Tasks($client);

$results = $service->tasklists->listTasklists(array(
    'maxResults' => 10,
));

$tasks = $service->tasks->listTasks('MDA4MjU1MDc3MDA5ODQ1ODAzMTA6MDow');


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="fr" xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <!-- on récupère le titre de la page dans le fichier .json, ou on lui donne une valeur fixe -->
    <title><?php echo (isset($data->title) ? $data->title : 'Tableau de bord-RPi') ?></title>
    <!-- on importe une feuille de style permettant de réduire les incohérences entre navigateurs -->
    <link rel='stylesheet' type='text/css' href='ressources/reset.css' />
    <!-- on importe la feuille de style générale au tableau de bord -->
    <link rel='stylesheet' type='text/css' href='ressources/style.css' />
    <!-- on récupère la taille du fond du tableau de bord dans le fichier .json, ou on la fixe à 100% -->
    <style type='text/css'>
        #fond {
        	width: <?php echo isset($data->width) ? $data->width : '100%' ?>;
        	height: <?php echo isset($data->height) ? $data->height : '100%' ?>;
        }


        #tasks
        {
            margin-left: 20px;
            border: solid 1 px;
            border-color: black;
            padding: 10px;
        }

        label {
            color: white;
            font-size: 36px;
        }

        ul {
            margin-top: 35px;
            margin-left: 20px;
        }

        #tasks ul li {
            list-style-type: square;
            font-size: 28px;
            color: white;
        }

    </style>
    <!-- on récupère enfin les éventuelles feuilles de style de chaque module -->
    <?php
        foreach($data->modules as $module) {
            $filename = "./modules/";
            $filename .= isset($module->type) ? $module->type."/".$module->type.".css" : $module->name."/".$module->name.".css";
            if (file_exists($filename)) echo "<link rel='stylesheet' type='text/css' href='$filename'/>";
        }
    ?>
    <script type='text/javascript' src='ressources/jquery.js'></script>
    <!-- on récupère le fichier javascript qui va gérer l'actualisation de chaque module -->
    <script type='text/javascript' src='ressources/javascript.js'></script>
</head>
<body>
	<!-- Division générale du tableau de bord -->
    <div id='fond'>
    	<!-- Division propre à chaque module présent dans le fichier .json -->
        <?php
        $i = 0;								//compteur pour z-index
        foreach($data->modules as $module)
        {
            $i++;
            if($i == 5) {
                $za = 'dqs';
            } else {
                echo render($module, $i);
            }
        }
        ?>
    </div>

    <div id="tasks" name="tasks" style="position: absolute; top: 300px;">
        <label for="">TO DO</label>
        <ul>
            <?php
            foreach ($tasks->getItems() as $tasklist) {
                //printf("%s (%s)\n", $tasklist->getTitle(), $tasklist->getId());
                echo '<li>'.$tasklist->getTitle().'</li>';
            }
            ?>
        </ul>
    </div>
</body>
</html>