/**on évènement change color ou
/** function (color) {
document.queryselector('.fill'). attr('fill',color
)}*/

function changeColor() {
  const color = document.getElementById("colorPicker");
  const teamName = document.getElementById("changeColor");
  teamName.style.color = color.value;
}


var preview = function(input) {
   var reader = new FileReader();
   reader.onload = function(){
     var imgChange = document.getElementById('changeColor');
     imgChange.src = reader.result;
   };
   reader.readAsDataURL(event.target.files[0]);
 };

 console.log(preview);

 function searchMembres(){  
   let searchMembers = document.getElementById("searchMembers").value;
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


function searchMembresTest() {
  let searchMembers = document.getElementById("searchMembers").value;
  let result=document.getElementById("suggestions");
  searchMembers.addEventListener("input",function () {
    let result.innerHTML = " ";
    autocomplete(searchMembers);
  })
}

function autocomplete(s) {
  //callback
  req = new XMLHttpRequest();  
  req.onreadystatechange = function(){  
    if(req.readyState === 4){
      const res = req.responseText;
      for (let i = 0; i < res.length; i++) {
        let data = res[i]+"";
                data = data.replace(s, "<b>"+s+"</b>");
                result.innerHTML += "<div class='result'>"+data+"</div>";
      //
        //count.innerHTML = res;  
      }
    }  
  };  

  req.open("GET","Ajax.php?searchMembers="+searchMembers+s, true);  
  req.send(); 

}
