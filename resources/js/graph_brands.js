window.onload = function () {
    let data = JSON.parse(Get("/storage/data/stats_brands.json"));
    data = data['Prio'];
    renderChartsBrand(data);
};
