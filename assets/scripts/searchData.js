//--------- Partie de la barre de Recherche---------//
var postRoute = "/post/";

$(document).ready(function() {

    $('#live_search').keyup(function(){

        var input = $(this).val();
        // alert(input);

        if(input != ""){
            $.ajax({

                method: 'GET',
                url: '/post/search',
                data: {searchTerm:input},

                success: function(data){

                    var result = data.results || data;
                    console.log(result);
                    var resultHtml = "";

                    for(var i = 0; i < result.length; i++){

                        resultHtml += "<a href=" + postRoute + result[i].id + "><p>" + result[i].title + "</p></a>"
                    }

                    $('#result').html(resultHtml);
                    $('#result').css('display', 'block');
                }
            });
        } else {
            $('#result').css('display', 'none');
        }
    });
});