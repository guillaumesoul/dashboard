function diaporama() {
  
// 'tab' est un tableau contenant les objets JavaScript
// correspondants aux elements <li> de l'element <div>
// definissant le slideshow
var tab;

// 'image' est l'objet JavaScript correspondant a
// l'element <img> de l'element <div> definissant
// le slideshow
var image;

// 'index' est l'indice de l'element du tableau 'tab'
// a utiliser pour afficher la prochaine image du
// slideshow
var index;

// Affiche la nouvelle image dans l'objet 'image' et
// incremente la valeur de 'index'
//
function next() {
	if ( index == tab.length )
		index = 0;
	image.src = tab[index].innerHTML;
	index++;
}

// le corps de la fonction :
// - initialise la variable globale 'image' a l'objet
//   JavaScript correspondant a l'element <img> de
//   l'element <div> definissant le slideshow
// - affiche la premiere image dans l'objet 'image' et
//   lance les appels periodiques a la fonction 'next'
//   a l'aide de 'setInterval'
// 

var div = document.getElementById('diaporama');
tab = div.getElementsByTagName('li');
index = 1;
image = div.getElementsByTagName('img')[0];
image.src = tab[0].innerHTML;
setInterval(next,5000);

}


