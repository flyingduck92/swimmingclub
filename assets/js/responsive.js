// Drop Down Menu for Small Screen 
var handle = document.getElementById('handle'); 
var menu = document.getElementById('menu'); 

handle.onclick = function() {
  menu.classList.toggle('showMenu');
};