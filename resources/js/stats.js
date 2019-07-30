function updateStats() {
    $.getJSON( "/storage/data/stats.json", (data) => {
        $("#entries_last_hour").html(data["entries_last_hour"]);
        $("#entries_last_day").html(data["entries_last_day"]);
        $("#entries_total").html(data["entries_total"]);
        $("#stations_none").html(data["stations_none"]);
        $("#stations_no_gasoline").html(data["stations_no_gasoline"]);
        $("#stations_no_diesel").html(data["stations_no_diesel"]);
        $("#stations_no_lpg").html(data["stations_no_lpg"]);
        var date = new Date;
        var seconds = date.getSeconds();
        var minutes = date.getMinutes();
        var hour = date.getHours();
        $("#last_update").html(("0" + hour).slice(-2)+'h'+("0" + minutes).slice(-2)+'m'+("0" + seconds).slice(-2)+'s');
    });
}
updateStats();
setInterval(updateStats,30000);