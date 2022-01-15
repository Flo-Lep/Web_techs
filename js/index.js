/*function nav_over(){
  element = document.getElementsByClassName("nav_bar_link");
  element.style.textDecoration = "underline";
}*/

function nav_over(string){
  element = document.getElementById(string);
  element.style.textDecoration = "underline";
}

function end_nav_over(string){
  element = document.getElementById(string);
  element.style.decoration = "none";
}
