function getMessages(){

    // Creation d'un requête AJAX pour se connecter au serveur et au fichier handler.php
    const requeteAjax = new XMLHttpRequest();
    requeteAjax.open("GET", "message.php");

    //Traitement des données ( en utilisant JSON) + affichage des données au format HTML
    requeteAjax.onload = function(){
        const resultat = JSON.parse(requeteAjax.responseText);

        const html = resultat.reverse().map(function(message){
            return `
                <div class="message">
                <span class="date">${message.creationTime.substring(11,16)}</span>
                <b><span  style="color:${message.color}" class="author">${message.author}</span></b> :
                <span class="conten">${message.content}</span>
                </div>
            `
        }).join('');

        const messages = document.querySelector('.messages');

        messages.innerHTML = html;
        messages.scrollTop = messages.scrollHeight; // Fais en sorte que la barre de scroll défile toute seul vers le bas

    }

    //Envoie de la requête
    requeteAjax.send();
}


function postMessage(event){
    console.log('test');
    // Stoppage du submit du formulaire
    event.preventDefault();

    event.stopImmediatePropagation();


    // Récupération des données du formulaire
    const author = document.querySelector('#author');
    const content = document.querySelector('#content');

    //Conditionnement des données
    const data = new FormData();
    data.append('author', author.value);
    data.append('content', content.value);

    // Configuration d'une requête ajax en POST et envoi  des données
    const requeteAjax = new XMLHttpRequest();
    requeteAjax.open("POST", "message.php?task=write");

    console.log('ajax');
// plutot onrequestate change
    requeteAjax.onload = function(){
        content.value = '';
        content.focus();
        getMessages();
    }

    requeteAjax.send(data);
  }

    //document.querySelector("form").addEventListener("submit", postMessage, false);

  const interval = window.setInterval(getMessages, 3000);

  getMessages();
