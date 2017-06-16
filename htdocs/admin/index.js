function init() {
    var i;
    var elts = document.getElementsByClassName("autocenter");
    for (i = 0; i < elts.length; i++) {
        var elt = elts[i];
        var w = elt.offsetWidth, h = elt.offsetHeight;
        elt.style.width = w;
        elt.style.height = h;
        elt.style.marginLeft = (-w / 2) + "px";
        elt.style.marginTop  = (-h / 2) + "px";
        elt.style.position = "absolute";
        elt.style.left = "50%";
        elt.style.top  = "50%";
        elt.style.visibility = "visible";
        elt.style.cssFloat = "none";
    }
}
