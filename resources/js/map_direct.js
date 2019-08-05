let helping = false;
let popup = null;
let nav_control = {
    obj: null,
    position: 'bottom-right',
    options: {
        visualizePitch: true,
        showZoom: true,
        showCompass: true
    }
};

grecaptcha.ready(function () {
    console.log("RECAPTCHA LOADED");
});

function validateCaptcha(callback) {
    grecaptcha.execute('6LcD9rAUAAAAAIn4-wNkOpAmr49ItnAZnBtroGCX', {
        action: 'validate_captcha'
    }).then(function (token) {
        callback(token);
    });
}

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
        "border-color": "#6bd7fc",
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
        $(obj).parent().parent().find('.popup_submit_text').html("VALIDADO");
        setTimeout(function(obj) {
            popup.remove();
        },1250, obj);
        $.post("/entries/add", data, function (reply) {
            console.log("Entrada adicionada: " + reply.success + " (0 -> falha, 1 -> sucesso)");
        }, "json");
    });
}


function toggleLegends(force_hide = false) {
    let isLegendShowing = $("#legend").css("left") == "0px";
    if (isLegendShowing || force_hide) {
        $("#legend").animate({
            left: "-21em",
        }, 500);
        $("#legend-icon").animate({
            left: "0px",
        }, 500);
    } else {
        toggleFeatures(true);
        $("#legend").animate({
            left: "0px",
        }, 500);
        $("#legend-icon").animate({
            left: "21em",
        }, 500);
    }
}

function toggleFeatures(force_hide = false) {
    let isFeaturesShowing = $("#features").css("right") == "0px";
    if (isFeaturesShowing || force_hide) {
        if(map) {
            nav_control.obj = new mapboxgl.NavigationControl(nav_control.options);
            map.addControl(nav_control.obj, nav_control.position);
        }
        $("#features").animate({
            right: "-19em",
        }, 500);
        $("#features-icon").animate({
            right: "0px",
        }, 500);
    } else {
        toggleLegends(true);
        if(map) {
            map.removeControl(nav_control.obj);
            delete nav_control.obj;
            nav_control.obj = null;
        }
        $("#features").animate({
            right: "0px",
        }, 500);
        $("#features-icon").animate({
            right: "19em",
        }, 500);
    }
}
