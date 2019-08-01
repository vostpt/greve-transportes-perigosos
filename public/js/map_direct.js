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

function swapIcon(obj) {
    let img = $(obj).find('img');
    if (img.hasClass("no-gas")) {
        img.removeClass("no-gas");
    } else {
        img.addClass("no-gas");
    }
}

function submitEntry(obj, id) {
    let gasoline = Number(!($(obj).parent().parent().find('.gasoline img').hasClass('no-gas')));
    let diesel = Number(!($(obj).parent().parent().find('.diesel img').hasClass('no-gas')));
    let lpg = Number(!($(obj).parent().parent().find('.lpg img').hasClass('no-gas')));
    validateCaptcha((token) => {
        let data = {
            "fuel_station": id,
            "gasoline": gasoline,
            "diesel": diesel,
            "lpg": lpg,
            "captcha": token
        }
        popup.remove();
        $.post("/entries/add", data, function (reply) {
            console.log("Entrada adicionada: " + reply.success + " (0 -> falha, 1 -> sucesso)");
        }, "json");
    });
}