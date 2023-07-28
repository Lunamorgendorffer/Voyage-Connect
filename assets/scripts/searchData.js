//--------- Partie de la barre de Recherche---------//
// Déclaration de la variable postRoute qui contient le chemin de base pour les liens vers les posts
var postRoute = "/post/";

// La fonction $(document).ready() permet d'exécuter le code à l'intérieur lorsque le DOM est chargé et prêt à être manipulé
$(document).ready(function() {

    // Lorsque l'utilisateur tape dans l'élément avec l'id 'live_search'
    $('#live_search').keyup(function(){

        // Récupère la valeur saisie par l'utilisateur dans l'input et la stocke dans la variable 'input'
        var input = $(this).val();
        // alert(input);  // Cette ligne affiche la valeur saisie par l'utilisateur dans une boîte d'alerte (peut être utilisée pour le débogage)

        // Vérifie si l'input n'est pas vide
        if(input != ""){

            // Effectue une requête AJAX pour récupérer les résultats de la recherche
            $.ajax({

                method: 'GET',  // Utilise la méthode GET pour la requête AJAX
                url: '/post/search',  // Indique l'URL de l'endpoint qui traitera la recherche
                data: {searchTerm:input},  // Les données envoyées dans la requête AJAX, ici 'searchTerm' sera accessible côté serveur

                success: function(data){  // La fonction qui sera exécutée en cas de succès de la requête AJAX

                    // Récupère les résultats de la recherche (dans l'objet 'data') et les stocke dans la variable 'result'
                    var result = data.results || data;
                    console.log(result);  // Cette ligne affiche les résultats de la recherche dans la console du navigateur (peut être utilisée pour le débogage)
                    var resultHtml = "";

                    // Parcourt les résultats de la recherche et génère du HTML pour chaque résultat
                    for(var i = 0; i < result.length; i++){

                        // Crée un lien vers le post en utilisant le chemin de base 'postRoute' et l'ID du post
                        resultHtml += "<a href=" + postRoute + result[i].id + "><p>" + result[i].title + "</p></a>"
                    }

                    // Affiche les résultats de la recherche en ajoutant le HTML généré dans l'élément avec l'id 'result'
                    $('#result').html(resultHtml);
                    // Affiche l'élément avec l'id 'result' en utilisant la propriété CSS 'display' avec la valeur 'block' (visible)
                    $('#result').css('display', 'block');
                }
            });
        } else {
            // Si l'input est vide, masque l'élément avec l'id 'result' en utilisant la propriété CSS 'display' avec la valeur 'none' (invisible)
            $('#result').css('display', 'none');
        }
    });
});
