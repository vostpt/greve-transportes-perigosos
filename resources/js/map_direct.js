let helping = false;
function consult() {
    $("#map").css({
        "border": "0"
    });
    helping = false;
    $("#selector_view").css("visibility", "hidden");
    $("#map_view").css("visibility", "visible");
}

function help() {
    $("#warning").css("visibility", "visible");
    $("#map").css({
        "border-color": "#2f86ca",
        "border-width": "3px",
        "border-style": "solid"
    });
    helping = true;
    $("#selector_view").css("visibility", "hidden");
    $("#map_view").css("visibility", "visible");
}

function selector() {
    $("#warning").css("visibility", "hidden");
    $("#map_view").css("visibility", "hidden");
    $("#selector_view").css("visibility", "visible");
}

function isHelping() {
    return helping;
}