window.onload = function () {
    let district = findGetParameter("distrito");
    let county = findGetParameter("concelho");
    let url = "";
    if (district) {
        if (county) {
            url = "/storage/data/stats_" + encodeURI(district) + "_" + encodeURI(county) + ".json"
        } else {
            county = null;
            url = "/storage/data/stats_" + encodeURI(district) + ".json";
        }
    } else {
        district = "Portugal";
        county = null;
        url = "/storage/data/stats_global.json";
    }
    renderChartsGlobalStats(url);
};
