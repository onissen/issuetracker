/* jshint esversion:6 */

// searchbox = document.querySelector("#searchbox");
// URLRequest = location.search;
// searchbox.addEventListener("keyup", function(event) {
//   event.preventDefault();
//   if (event.key === "Enter") {
//       if (URLRequest != '' || !URLRequest.startsWith('?search=')) {
//           if (URLRequest.includes("&search")) {
//               old_search = URLRequest.substring(URLRequest.indexOf("&search"));
//               URLRequest = URLRequest.replace(old_search, ''); // ?filter...
//           }
//           URLRequest += '&search='+searchbox.value;

//       } else {URLRequest = '?search='+searchbox.value;}
//       location.search = URLRequest;
//   }
// });

function toggleDelete(labelid) {
    if (confirm
        (
"Soll dieses Label wirklich gelöscht werden? Es wird dann von allen Inhalten entfernt."
        )) { location="?deleted="+labelid;}
}

function DisplayMeta (value, id) {
const hideElements = document.getElementsByClassName('js-hide'+id);

  for (const hiddenElement of hideElements) {
    hiddenElement.style.display = value;
  }
}

function toggleColorPicker(id) {
  document.getElementById('popover-color'+id).style.visibility = 'visible';
}

function chooseColor(color, id) {
  document.getElementById('input-color'+id).value = color;
  document.getElementById('popover-color'+id).style.visibility = 'hidden';
  document.getElementById('button-color'+id).style.backgroundColor = color;
  document.getElementById('badge-color'+id).style.backgroundColor = color;
}

function dynamicBtnColor (id) {
  color = document.getElementById('input-color'+id).value;
  if (color == '') {
    document.getElementById('input-color'+id).value = '#';
  }

  if (!color.startsWith('#')) {
    document.getElementById('input-color'+id).value = '#'+color;
  }

  if (color.length == 4 || color.length == 7) {
    document.getElementById('button-color'+id).style.backgroundColor = color;
    document.getElementById('badge-color'+id).style.backgroundColor = color;
    document.getElementById('input-color'+id).style.color = 'black';
  } else {
    document.getElementById('input-color'+id).style.color = '#cf222e';
  }
}

function randomBtnColor (id) {
  const Colors = Array(
    '#b60205',
    '#D93F0B',
    '#FBCA04',
    '#0E8A16',
    '#006B75',
    '#1D76DB',
    '#0052CC',
    '#5319E7',
    '#E99695',
    '#F9D0C4',
  '#FEF2C0',
  '#C2E0C6',
  '#BFDADC',
  '#C5DEF5',
  '#BFD4F2',
  '#D4C5F9'
  );
    
    
  
  const pickRandom = Colors[Math.floor(Math.random()*Colors.length)];

  document.getElementById('button-color'+id).style.backgroundColor = pickRandom;
  document.getElementById('badge-color'+id).style.backgroundColor = pickRandom;
  document.getElementById('input-color'+id).value = pickRandom;
}

function dynamicPreviewText (id) {
  text = document.getElementById('input-name'+id).value;

  if (text != '') {document.getElementById('badge-color'+id).innerHTML = text;} 
  else {document.getElementById('badge-color'+id).innerHTML = 'Label Vorschau';}
}

function changeStatus(status) {
  document.getElementById('input-action').value = status;
  document.getElementById('newcommentForm').action = '?commentAction='+status;
  document.getElementById('newcommentForm').submit();
}

function enableSubmit () {
  button = document.getElementById('submitNewComment');
  textarea = document.getElementById('text-newComment');
  if (textarea.value != '') {
    button.disabled = false;
  }

  if (textarea.value == '') {
    button.disabled = true;
  }
}

function toggleNewChannel() {
  document.getElementById('newChannelName').style.display = 'block';
  // document.getElementById('selectChannel').disabled = true;
}