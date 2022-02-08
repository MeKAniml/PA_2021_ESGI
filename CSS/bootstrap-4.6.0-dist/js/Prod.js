function changeColor() {
  const color = document.getElementById("colorPicker");
  const teamName = document.getElementById("pathblason");
  const finalColor = document.getElementById("color");
  const textColor = document.getElementById("changeColor");
  console.log(textColor);
  console.log(finalColor);
  textColor.style.color = color.value
  teamName.style.fill = color.value;
  finalColor.value = color.value;
}

function EditTeamMembres(Team, Username, status) {
  //modif la partie membres
  const membres = document.getElementById("selectMembers");
  console.log(membres);
  const req = new XMLHttpRequest();
  req.onreadystatechange = function() {
    if (req.readyState === 4) {
      const result = req.responseText;
      membres.innerHTML = result;
    }
  };
  req.open("GET", "Ajax.php?Team="+Team+"&Username="+Username+"&status="+status);
  req.send();
}

function EditBoutton(Team, id_user) {

    //modif les boutton
    const boutton = document.getElementById("boutton");
    console.log(boutton);

    const req = new XMLHttpRequest();
    req.onreadystatechange = function() {
      if (req.readyState === 4) {
        const result = req.responseText;
        boutton.innerHTML = result;
      }
    };
    req.open("GET", "Ajax.php?Team="+Team+"&id_user="+id_user+"&Boutton=1");
    req.send();
}


function EditTeam(Team, Username, status, id_user){

  EditTeamMembres(Team, Username, status);
  EditBoutton(Team, id_user);

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
      req.open("GET","searchMembers.php?searchMembers="+searchMembers, true);  
      req.send(); 
    }
}


function searchMembresTeam(){  
  let searchMembers = document.getElementById("searchMembers").value;
    if (searchMembers != null) {
      req = new XMLHttpRequest();  
      req.onreadystatechange = function(){  
        if(req.readyState === 4){
          const res = req.responseText;
          const count = document.getElementById("modalMembres");
          count.innerHTML = res;  
        }  
      };  
      req.open("GET","searchMembers.php?searchMembers="+searchMembers, true);  
      req.send(); 
    }
}

function verifDate() {
  const date = document.getElementById("date");
  console.log(date);
}

/*
function testTrim(str){

    console.log(str);
}*/
