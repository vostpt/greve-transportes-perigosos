function updateStats() {
    document.getElementById('global_Stats').src = "/graphs/stats";
    $.getJSON( "/storage/data/stats.json", (data) => {
        $("#entries_last_hour").html(data["entries_last_hour"]);
        $("#entries_last_day").html(data["entries_last_day"]);
        $("#entries_total").html(data["entries_total"]);
    });
}
updateStats();
setInterval(updateStats,30000);