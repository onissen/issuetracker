searchbox = document.querySelector("#search-issues");

searchbox.addEventListener("keyup", function(event) {
    event.preventDefault();
    if (event.key === "Enter") {
        // Hier wird der Query Inhalt zur URL hinzugefügt:
        location.search += "&"+searchbox.name+"="+searchbox.value;       
    }
});