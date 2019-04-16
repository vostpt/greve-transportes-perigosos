$(function() {
    $("#dtBasicExample").tablesorter();

    console.log($("#js-search"));
    console.log($(".js-table tbody tr"));
    $("#js-search").keyup(function(){
        _this = this;
        console.log(_this);
        $.each($(".js-table tbody tr"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                $(this).hide();
            else
                $(this).show();
        });
    });
});