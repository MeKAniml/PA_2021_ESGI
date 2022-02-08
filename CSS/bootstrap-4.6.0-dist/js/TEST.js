
/*
window.onload = function() {
    let get = document.getElementById('changeColor').style.color = '#f00';
    console.log(get);
    return get;
};

function picker() {
  console.log("ok");
  let blason = document.getElementById('changeColor');
  console.log(blason);
  let color = document.getElementById('exampleColorInput');
  console.log(color);
  console.log(color.value);
  //blason.style.fill = event.target.value;
  //blason.style.color = event.target.value;
  blason.style.color = color.value;

}


var preview = function(input) {
   var reader = new FileReader();
   reader.onload = function(){
     var imgChange = document.getElementById('changeColor');
     imgChange.src = reader.result;
   };
   reader.readAsDataURL(event.target.files[0]);
 };*/

 function test() {
   const color = document.getElementById("colorPicker");
   const teamName = document.getElementById("changeColor");
   teamName.style.color = color.value;
 }
