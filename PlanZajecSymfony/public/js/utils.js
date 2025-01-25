function toggleSpinner() {
    var x = document.getElementById("spinner-overlay");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
    console.log('toggling spinner')
}