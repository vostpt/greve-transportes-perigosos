window.onload = function () {
    let data = JSON.parse(Get("/storage/data/stats_brands.json"));
    let brandsOfficialData = ['OZ Energia', 'Ecobrent', 'Prio','Bxpress'];
    let brandParam = window.findGetParameter('marca');
    if(!brandsOfficialData.includes(brandParam)){
        brandParam = 'Prio';
    }
    data = data[brandParam];
    renderChartsBrand(data);
    document.getElementById('brand_name').textContent = `${brandParam} (Dados Oficiais)`;
};
