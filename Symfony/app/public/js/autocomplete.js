$("#product_search").keyup(
    function(){
        var text=$('#product_search').val();
        var DATA =  {'query' : text};
        $.ajax({
            type: "POST",
            url: "http://localhost:8080/product/get-products",
            data:DATA,
            success: function (data) {
                $.each(data,function(k,el) {
                    $( "#div" ).html("<ul></ul>");
                    $('ul').append(
                        "<li>"+el.libelle+"</li>"+
                        "<li>"+el.pays+"</li>"
                    );
                });
            },
            error:function ()
            {
                console.log("error "+DATA);
            }
        });
    });