#!/bin/bash

pat=/var/www/tdb314/ressources/xplanet/img/	# chemin des images
tmp=$pat"tmp_clouds_2048.jpg"		# fichier image temporaire
img=$pat"clouds_2048.jpg"			# fichier image final

# suppression du fichier temporaire s'il existe
if [ -e $tmp ]; then
  rm $tmp
fi

# téléchargement de la nouvelle image de nuages en temporaire
#wget -O $tmp http://xplanet.sourceforge.net/clouds/clouds_2048.jpg
wget -O $tmp http://xplanetclouds.com/free/local/clouds_2048.jpg

# si téléchargement OK (fichier existe et de taille non nulle)...
if [ -s $tmp ]; then
  
  #...on redimensionne l'image reçue...
  mogrify -resize 2000x1000 $tmp
  
  #...on supprime l'ancienne image finale si elle existe...
  if [ -e $img ]; then
    rm $img
  fi
  
  #...on déplace l'image temporaire vers l'image finale...
  mv $tmp $img
  
  #...et on modifie ses droits d'accès
  chown -R ftpuser:ftpgroup $pat && chmod -R 775 $pat
fi
