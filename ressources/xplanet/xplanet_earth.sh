#!/bin/bash

# Mise à jour de la carte de la terre chaque 1er du mois

MOIS=$(date +%m)
CARTE_MOIS="earth_$MOIS.jpg"
unlink /var/www/tdb314/ressources/xplanet/img/earth.jpg
ln -s /var/www/tdb314/ressources/xplanet/img/$CARTE_MOIS /var/www/tdb314/ressources/xplanet/img/earth.jpg
#echo "$(date): Carte de la Terre mise à jour !"
