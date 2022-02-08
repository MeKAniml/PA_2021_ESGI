let cpt = 0; //variable qui va permettre de swap en evitant de faire 2 fonction
let swap; // contiendra l'emplacement de l'image 1 (C://files/img par ex)
let id1; // contiendra id de la premiere img pour pouvoir intervetir l'img1 avec l'img2
let verifx = [1,2,3,4,5,6,7,8,9] //tableaux de chiffre allant de 1 a X selon x nombre d'image du captcha , utilisé pour la verification
let randimg = document.getElementById('rdm').value;

function init() {

  for (var i = 0; i < 10; i++) {
    let randchange= Math.floor((Math.random() * 9) + 1);
    Swaps(randchange);
  }
}

//fonction qui va gerer le deplacement de deux images
function Swaps(nb) {
  if (cpt==0) {
    swap= document.getElementById(nb).src; //id est unique pour chaque image et est choisi pour le fait qu'il retourne une valeaur via getelement plutot qu'un tableau
    border=document.getElementById(nb);
    border.style.border = "5px solid #87cb28";
    id1=nb; // vu que l'on a manuellement mis en input de Swaps() de la div , l'id de l'image quelle contient , on retient l'id 1
    cpt++; //permet que le prochain "click" mettent en place un swap plutot que de tourner dans le vide

  }else if (cpt==1) {
    document.getElementById(id1).src=document.getElementById(nb).src; // en clair C://files/img1.png est maitenant C://files/img1.pngC://files/imgNb.png
    document.getElementById(nb).src=swap; // ensuite on swap L'imgNb avec la source de la premiere img clicker
    cpt=0; // on remet le cpt a 0 pour etre sur de relancer la function
    border.style.border ="";

  }

}

function verif() { // ça peut paraitre surprenant mais elle va verifier que tout est bon
  let verifcpt=0; // un chtite variable qui agit comme un statut de "pas d'erreur alors 0"
//let x = verifx.length; //
for (let i = 0; i < verifx.length; i++) { // bouvle qui va verifier que chaque id image a bien une image avec le nom qui correspond à l'id ; d'autres moyens peuvent remplacer ça
  if (document.getElementById(verifx[i]).src=="https://Play.kccorp.fr/"+randimg+verifx[i]+".png"){ // donc id=1 doit avoir 1.png mais on peut imaginer d'autre moyen de verif ? id/2 = .png ?
    

} else {

  verifcpt++; // augment verifcpt si pas bonne place pour etre sur que l'on ne change pas de page
  break;
}
}
if (verifcpt==0) { // si la boucle a bein trouver les img au bon endroit

//redirection vers la page de validation de captcha avec 1 = vrai ou 2 = pas bon
   window.location.assign('capvalid.php?vald=1');


}else {

window.location.assign('capvalid.php?vald=2');

}

}
