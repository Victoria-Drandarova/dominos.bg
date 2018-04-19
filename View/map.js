/* When the user clicks on the button,
   toggle between hiding and showing the dropdown content */
function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

function filterFunction() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    div = document.getElementById("myDropdown");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
        if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }
}

var map;
function initMap(a, b) {
    map = new google.maps.Map(document.getElementById('map'), {
        center: new google.maps.LatLng(a, b),
        zoom: 20
    });

    var icon = '../View/assets/images/pizzaMap1.png';

    var newMarker = new google.maps.Marker({
        position: new google.maps.LatLng(a, b),
        icon: icon,
        map: map
    });

}