/* jshint esversion:6 */

function Search(event) {
  // Diese Funktion gibt nur den Value als $_GET weiter! Alles andere läuft über PHP.
  URLRequest = location.search;
  if (event.key === "Enter") {
    if (URLRequest != '' || !URLRequest.startsWith('?search=')) {
        if (URLRequest.includes("&search")) {
            old_search = URLRequest.substring(URLRequest.indexOf("&search"));
            URLRequest = URLRequest.replace(old_search, ''); // ?filter... oder ?type...
        }
        URLRequest += '&search='+searchbox.value;

    } else {URLRequest = '?search='+searchbox.value;}
    location.search = URLRequest;
  }
}

function SuggestType() {
  inputValue = document.getElementById('searchbox').value;
  devider = inputValue.search(':');
  searchword = inputValue.substr(devider+1);

  query = document.querySelector('.suggest-option#query-option');
  query.value = 'query:'+searchword;
  query.innerHTML = 'query:'+searchword;

  title = document.querySelector('.suggest-option#title-option');
  title.value = 'title:'+searchword;
  title.innerHTML = 'title:'+searchword;

  IssueStatus = document.querySelector('.suggest-option#status-option');
  IssueStatus.value = 'status:'+searchword;
  IssueStatus.innerHTML = 'status:'+searchword;

  author = document.querySelector('.suggest-option#author-option');
  author.value = 'author:'+searchword;
  author.innerHTML = 'author:'+searchword;

  id = document.querySelector('.suggest-option#id-option');
  id.value = 'id:'+searchword;
  id.innerHTML = 'id:'+searchword;

  label = document.querySelector('.suggest-option#label-option');
  label.value = 'label:'+searchword;
  label.innerHTML = 'label:'+searchword;
}


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

function enableNewSubmit () {
  button = document.getElementById('submitNewIssue');
  title = document.getElementById('title-NewIssue');
  if (title.value != '') {
    button.disabled = false;
  }

  if (title.value == '') {
    button.disabled = true;
  }
}

function toggleIssueHeader (action) {
  if (action == 'edit') {
    // Bearbeiten: Titel verstecken, Input zeigen
    document.querySelector('.issue-title-show').style.display = "none";
    document.querySelector('.issue-title-edit').style.display = "flex";
  }

  if (action == 'show') {
    // Bearbeiten: Titel verstecken, Input zeigen
    document.querySelector('.issue-title-show').style.display = "flex";
    document.querySelector('.issue-title-edit').style.display = "none";
  }
}

function confirmDangerZone(index,confirmationText) {
  btn = document.getElementById('submit'+index);
  input = document.getElementById('confirm'+index);

  if (input.value == confirmationText) {
    btn.disabled = false;
  }
}

function toggleNewChannel(value) {
  // Neuer Channel in new-topic.php
  if (value == 'newChannel') {document.getElementById('newChannelName').style.display = 'block';} 
  else {document.getElementById('newChannelName').style.display = 'none';}
}

function toggleNewChannelWrapper() {
  // Neuer Channel in topic-settings.php
  document.getElementById('input-wrapper-newChannel').style.display = 'block';
}

function untoggleNewChannelWrapper() {
  // Neuer Channel in topic-settings.php VERBERGEN
  document.getElementById('input-wrapper-newChannel').style.display = 'none';
}

function toggleCommentMenu(id) {
  popover = document.getElementById('commentMenu'+id);
  popover.classList.toggle('hideCommentMenu');
}

function toggleDeleteCommit(commitid, action) {
  if (confirm
      ("Möchtest du diesen Kommentar wirklich löschen?")) {
        location="?deleted="+commitid+"&action="+action;
      }
}

function confirmDeleteAction(id) {
  if (confirm
    ("Möchtest du diesen Aktions-Hinweis wirklich löschen?")) {
      location="?deleteAction="+id;
    }
}

function toggleEditCommit(id, mode) {
  editCard = document.getElementById('edit-card'+id);
  showCard = document.getElementById('show-card'+id);

  if (mode == 'edit') {
    showCard.style.display = 'none';
    editCard.style.display = 'block';
    toggleCommentMenu(id);
  }

  if (mode == 'cancel') {
    if (editCard.querySelector('textarea').value == showCard.innerHTML.trim()) {
      showCard.style.display = 'block';
      editCard.style.display = 'none';
    } else if (confirm ("Sollen deine Änderungen an diesem Commit wirklich gelöscht werden?")) {
      showCard.style.display = 'block';
      editCard.style.display = 'none';
      editCard.querySelector('textarea').value = showCard.innerHTML.trim();
    }
  }
}

function toggleSidebarPopover(menu) {
  popover = document.getElementById(menu+'-sidebarPopover');
  popover.classList.toggle('hideSidebarMenu');
}

function toggleCheck(id) {
  checkbox = document.getElementById('label'+id);
  checkbox.checked = !checkbox.checked;
}

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))