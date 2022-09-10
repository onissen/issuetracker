searchbox = document.querySelector("#search-issues");
URLRequest=location.search;
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