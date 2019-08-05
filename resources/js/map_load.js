function inIframe () {
    try {
        return window.self !== window.top;
    } catch (e) {
        return true;
    }
}

function removeElementsByClass(className){
    var elements = document.getElementsByClassName(className);
    while(elements.length > 0){
        elements[0].parentNode.removeChild(elements[0]);
    }
}

if(inIframe()) {
    document.getElementById("map_view").style.visibility = "visible"; 
    removeElementsByClass("iframe-remove");
    document.getElementById("map").style.top = 0;
    document.getElementById("map").style.height = "100%";
    document.getElementById("features").style.height = "22em";
    document.getElementById("features-icon").style.top = "1%";
    document.getElementById("features").style.top = "1%";
    document.getElementById("legend-icon").style.top = "1%";
    document.getElementById("legend").style.top = "1%";
    document.getElementById("warning").style.top = "1%";
}
else {
    document.getElementById("selector_view").style.visibility = "visible"; 
}