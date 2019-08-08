function updateStats() {
    document.getElementById('global_stats').src = "/graphs/stats";
    setTimeout(function() {
        resizeIframe(document.getElementById('global_stats'));
    }, 500);
    $.getJSON( "/storage/data/stats_entries.json", (data) => {
        $("#entries_last_hour").html(data["entries_last_hour"]);
        $("#entries_last_day").html(data["entries_last_day"]);
        $("#entries_total").html(data["entries_total"]);
    });
}
updateStats();
setInterval(updateStats,30000);