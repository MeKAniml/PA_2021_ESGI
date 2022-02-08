function searchEquipes(){  
  let searchTeams = document.getElementById("searchTeams").value;
    if (searchTeams != null) {
      req = new XMLHttpRequest();  
      req.onreadystatechange = function(){  
        if(req.readyState === 4){
          const res = req.responseText;
          const count = document.getElementById("selectTeams");
          count.innerHTML = res;  
        }  
      };  
      req.open("GET","Ajax.php?searchTeams="+searchTeams, true);  
      req.send(); 
    }
}

function searchMembres(){  
  let searchMembers = document.getElementById("searchMembers").value;
    if (searchMembers != null) {
      req = new XMLHttpRequest();  
      req.onreadystatechange = function(){  
        if(req.readyState === 4){
          const res = req.responseText;
          const count = document.getElementById("selectMembers");
          count.innerHTML = res;  
        }  
      };  
      req.open("GET","Ajax.php?searchMembers="+searchMembers, true);  
      req.send(); 
    }
}



function messages(){  
  let search = document.getElementById("searchMessages").value;
  req = new XMLHttpRequest();  
  req.onreadystatechange = function(){  
    if(req.readyState === 4){
      const res = req.responseText;
      const count = document.getElementById("selectMessages");
      console.log(res);
      count.innerHTML = res;  
    }  
  };  
  req.open("GET","Ajax.php?messages="+search, true);  
  req.send();   
}   


function changeStatus(id,idTable,type){

  const req = new XMLHttpRequest();
  req.onreadystatechange = function() {
    if (req.readyState ===4) {
const result = req.responseText;

if (type==1) {
  searchMembres();
}else if (type==2) {
  searchEquipes();
}



    }

  };

  req.open("GET", "Ajax.php?idTable="+idTable+"&type="+type+"&id="+id);
  req.send();



  //searchTeams();
}
