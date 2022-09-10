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
    }
});

function searchIssues () {
    var input, filter, table, tr, td, i, txtValue;
    
    input = document.getElementById('search-issues');
    filter = input.value.toLowerCase();
    
    table = document.getElementById('myTable') // FIXME: Das wäre der alles umschließende div ?!
    tr = table.getElementsByTagName('tr'); // FIXME: Wäre jeder weitere DIv Eintrag...

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName('td')[0];
        
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}