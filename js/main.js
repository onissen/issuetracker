searchbox = document.querySelector("#search-issues");
URLRequest = location.search;
searchbox.addEventListener("keyup", function(event) {
    event.preventDefault();
    if (event.key === "Enter") {
        if (URLRequest != '' || !URLRequest.startsWith('?search=')) {
            console.log(URLRequest);
            if (URLRequest.includes("&search")) {
                old_search = URLRequest.substring(URLRequest.indexOf("&search"));
                URLRequest = URLRequest.replace(old_search, ''); // ?filter...
            }
            URLRequest += '&search='+searchbox.value;

        } else {URLRequest = '?search='+searchbox.value;}
        location.search = URLRequest;
        searchIssues();
    }
});

// SearchInput = document.getElementById('search-issues');


// function searchIssues () {
//     var input, filter, listDiv, itemDiv, td, i, txtValue;
    
//     input = document.getElementById('search-issues');
//     filter = input.value.toLowerCase();
    
//     listDiv = document.getElementById('issuelist-list'); // FIXME: Das wäre der alles umschließende div ?!
//     itemDiv = listDiv.querySelectorAll('issuelist-item'); // FIXME: Wäre jeder weitere DIv Eintrag...

//     for (i = 0; i < itemDiv.length; i++) {
//         // td = itemDiv[i].getElementsByTagName('td')  [0];
//         title = itemDiv[i].querySelector('.title-link');
//         id = itemDiv[i].querySelector('.issuelist-meta.id');
//         author = itemDiv[i].querySelector('.issuelist-meta.author');
//         //TODO: HIer noch LAbels, Milestones... 
        
//         if (title) {
//             txtValue = title.textContent || title.innerText;
//             if (txtValue.toLowerCase().indexOf(filter) > -1) {
//                 itemDiv[i].style.display = "";
//             } else {
//                 itemDiv[i].style.display = "none";
//             }
//         }
//     }
// }