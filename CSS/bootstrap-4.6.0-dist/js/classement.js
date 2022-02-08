function classement(){

let game = document.getElementById('inputGroupSelect01').value;

if (game != null) {
  req = new XMLHttpRequest();  
  req.onreadystatechange = function(){  
    if(req.readyState === 4){
      const res = req.responseText;

      const count = document.getElementById('lad1');

      count.innerHTML = res;  
    }  
  };  
  req.open("GET","Ajax.php?ladder="+game, true);  
  req.send(); 
}






}
