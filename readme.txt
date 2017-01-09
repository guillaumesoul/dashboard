TdB_314 : kézako ?
====================
Il s'agit d'un petit programme en PHP/javascript permettant d'afficher un tableau de bord (dash board en anglais) le plus simplement possible. Et "314/Pi" parce que ce programme est d'abord destiné à tourner sur un nano-ordinateur "Raspberry Pi" pour créer un tableau de bord indépendant, mais il peut être utilisé sur n'importe quel serveur web.

Configuration
====================
Les principales options de configuration sont rassemblées dans le fichier 'config.json', au format [JSON] (http://fr.wikipedia.org/wiki/JavaScript_Object_Notation). Ce fichier est pré-rempli pour vous donner une idée de comment il doit se présenter. Des arguments peuvent être passés à un module via une requête de type GET si on lui ajoute un tableau 'args'.

Une valeur 'update' réglée à 0 indique que le module n'a pas besoin d'être mis à jour : il apparaît donc tel qu'il est chargé pour la première fois.

Comment ça marche ?
====================
Les modules sont chargés sur la page d'accueil à son ouverture. De là, la fonction .load() est appelée pour mettre à jour chacun d'entre eux toutes les x secondes, la valeur de x étant spécifiée dans le fichier config.json pour chaque module. Certains modules peuvent ainsi être mis à jour toutes les minutes, et d'autres seulement une fois par semaine. Notez que 60 secondes = 1 minute, 3600 = 1 heure, 86400 = 1 jour et 604800 = 1 semaine.

Ecrire un module
====================
Le programme cherche les modules en se basant sur leur "nom" qui doit être un dossier se trouvant dans le répertoire /modules. Si c'est le cas, il charge /modules/%nom%/?arg1=foo&arg2=bar pour chaque module, tel que défini dans son JSON, et, si besoin, inclus un fichier de mise en forme css qu'il cherche dans /modules/%nom%/%nom%.css. Les modules peuvent utiliser des données provenant de n'importe quelle source pouvant être manipulée par programmation.

Les données pouvant être utilisées par plusieurs modules, comme les scripts PHP ou javascript communs, les images générales etc..., sont à placer de préférence dans le dossier /ressources.

Contribuer
====================
Pour contribuer, rejoignez simplement ce projet, apportez-y vos changements et soumettez-les !

A faire...
====================
+ ajouter des modules
+ créer une page de démo
+ permettre au module ical de gérer les événements récurrents (ou passez à PHPiCalendar ?)
