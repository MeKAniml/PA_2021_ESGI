const divResult = document.getElementById("resultat");

let tabJeu =[
  [0,0,0,0,],
  [0,0,0,0,],
  [0,0,0,0,],
  [0,0,0,0,],
];

let timer = 0;

let oldSelection = [];
let nbAffiche = 0;
let verif = 1;

function Start(option) {
  const resultChrono = document.getElementById("timer");
  let seconde = '';
  let minute = '';

  if (verif == 2) {
    location.reload();
  }
  if (verif == 1) {

    const reload = document.getElementById("Start");
    reload.innerHTML = '<button id="Start" type="button" class="btn btn-secondary" onclick="Start()">Relancer</button>';

    let clock = setInterval(function() {
      timer++;
      dixieme = timer;
      seconde = timer%10;
      minute = timer%600;

      if (timer<10) {
        dixieme = timer;
        resultChrono.innerHTML = '00:00:'+dixieme;

      } else if (timer>=10 && timer<600) {
        dixieme = timer%10;
        seconde = Math.trunc(timer/10);

        if (seconde<10) {
          resultChrono.innerHTML = '00:0'+seconde+':'+dixieme;
        } else {
          resultChrono.innerHTML = '00:'+seconde+':'+dixieme;
        }


      } else if (timer>=600) {
        dixieme = timer%10;
        minute = Math.trunc(timer/600);
        seconde = Math.trunc((timer/10))-(minute*60);


        if (minute<10 && seconde < 10) {
          resultChrono.innerHTML = '0'+minute+':0'+seconde+':'+dixieme;
        } else if (minute<10 && seconde >= 10) {
          resultChrono.innerHTML = '0'+minute+':'+seconde+':'+dixieme;
        } else if (minute>=10 && seconde >= 10) {
          resultChrono.innerHTML = minute+':'+seconde+':'+dixieme;
        }

      }

    },100)

    afficherTableau();

    return verif++;
  }
}






let tabTest = rndTab();

function rndTab() {
  let result = [];
  let verifnbr = [0,0,0,0,0,0,0,0];

  for (var i = 0; i < 4; i++) {
    let ligne = [];
    for (var j = 0; j < 4; j++) {
      let end = false;
      while(!end){
        let rndImage = Math.floor(Math.random()*8); // nombre aléatoire entre 0 et 7

        if (verifnbr[rndImage] < 2 ) {

          ligne.push(rndImage+1);
          verifnbr[rndImage]++;
          end = true;
        }
      }
    }
    result.push(ligne);
  }
  return result;
}



function afficherTableau() {
  let txt = "";

  for(var i = 0; i < tabJeu.length; i++){
    txt += "<div>";
    for (var j = 0; j < tabJeu[i].length; j++) {
      if (tabJeu[i][j] === 0 ) {
        txt += "<button class='btn btn-primary m-1' style='width:100px; height:100px; border-radius: 1;' onclick='check("+i+","+j+")'>Afficher</button>";
      } else {
        txt += "<img src='"+ImagePath(tabJeu[i][j])+"'style='width:100px; height:100px; border-radius: 0;' class='m-1'>";
      }
    }
    txt += "</div>"
  }

  divResult.innerHTML = txt;
}

function ImagePath(n) {
  let img = "";
  switch (n) {
    case 1: img+="Images/image-game/DeusVult.png";
    break;
    case 2: img+="Images/image-game/Dofus.png";
    break;
    case 3: img+="Images/image-game/KCmex.png";
    break;
    case 4: img+="Images/image-game/Legolon.png";
    break;
    case 5: img+="Images/image-game/Milxico.png";
    break;
    case 6: img+="Images/image-game/sard.png";
    break;
    case 7: img+="Images/image-game/sarounanes.png";
    break;
    case 8: img+="Images/image-game/Yonanes.png";
    break;
    default:
  }
  return img;
}

function check(ligne, colonne) {
  nbAffiche++;

  tabJeu[ligne][colonne] = tabTest[ligne][colonne];
  afficherTableau()

  if (nbAffiche > 1) {
    //Vérification
    if (tabJeu[ligne][colonne] !== tabTest[oldSelection[0]][ oldSelection[1]]) {
      tabJeu[ligne][colonne] = 0;
      tabJeu[oldSelection[0]][oldSelection[1]] = 0;
    }
    nbAffiche = 0;
  }

  oldSelection = [ligne,colonne];

  let sameTable = true;

  for (var i = 0; i < tabJeu.length; i++) {
    for (var j = 0; j < tabJeu.length; j++) {
      if (tabJeu[i][j] != tabTest[i][j]) {
        sameTable = false;

      }
    }
  }

  if (sameTable === true) {
    leaderboard();
    //location.reload();
  }

}


function leaderboard() {

  let pseudo = prompt("Pour entrer dans le classement entrez votre Pseudo");
  let time = document.getElementById("timer");

  console.log(pseudo);

  if (pseudo!=null && pseudo!="") {


        req = new XMLHttpRequest();  
        req.onreadystatechange = function(){  
          if(req.readyState === 4){
            const res = req.responseText;
            const count = document.getElementById("leaderboard");
            count.innerHTML = res;  
          }  
        };  
        req.open("GET","Ajax.php?leaderboard=1&time="+time.innerHTML+"&pseudo="+pseudo);  
        req.send(); 
  }


  let tmp = time.innerHTML;

  const del = time.parentNode;
  del.removeChild(time);

  const add = document.getElementById("ici");

  const n = document.createElement("h3");
  n.innerHTML = tmp;

  add.appendChild(n);



}
