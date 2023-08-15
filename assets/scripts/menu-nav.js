// var settingsmenu = document.querySelector('.settings-menu'); // Sélectionne l'élément avec la classe 'settings-menu' dans le document
var settingsmenu = document.querySelector('.nav-user-icon'); // Sélectionne l'élément avec la classe 'settings-menu' dans le document
var darkBtn = document.getElementById('dark-btn');  // Sélectionne l'élément avec l'ID 'dark-btn' dans le document
var settingsmenuOpen = document.querySelector('.settings-menu')
var gradientStartColor = getComputedStyle(document.documentElement).getPropertyValue('--gradient-start-color');
var gradientMidColor = getComputedStyle(document.documentElement).getPropertyValue('--gradient-mid-color');
var gradientEndColor = getComputedStyle(document.documentElement).getPropertyValue('--gradient-end-color');

if(settingsmenu){

    settingsmenu.addEventListener('click', () =>{
        settingsmenuOpen.classList.toggle('settings-menu-height')
       
    })
    
    darkBtn.onclick=function(){ // Fonction de gestionnaire d'événements pour le clic sur le bouton sombre (darkBtn)
        darkBtn.classList.toggle('dark-btn-on'); // Ajoute ou supprime la classe 'dark-btn-on' pour activer ou désactiver l'apparence du bouton sombre
        document.body.classList.toggle('dark-theme'); // Ajoute ou supprime la classe 'dark-theme' au corps du document pour activer ou désactiver le thème sombre
    
        if(localStorage.getItem("theme") == "light"){ // Vérifie si le thème actuel est "light" en consultant la valeur stockée dans le localStorage
            localStorage.setItem("theme", "dark"); // Si le thème actuel est "light", le remplace par "dark" dans le localStorage
        } else{
            localStorage.setItem("theme", "light"); // Sinon, si le thème actuel est "dark", le remplace par "light" dans le localStorage
        }
        updateGradientColors();
    }
    
    if (localStorage.getItem("theme") == "light"){ // Vérifie si le thème stocké dans le localStorage est "light"
        darkBtn.classList.remove('dark-btn-on'); // Supprime la classe 'dark-btn-on' du bouton sombre pour désactiver l'apparence du bouton sombre
        document.body.classList.remove('dark-theme'); // Supprime la classe 'dark-theme' du corps du document pour désactiver le thème sombre
    } else if (localStorage.getItem("theme") == "dark"){ // Si le thème stocké dans le localStorage est "dark"
        darkBtn.classList.add('dark-btn-on'); // Ajoute la classe 'dark-btn-on' au bouton sombre pour activer l'apparence du bouton sombre
        document.body.classList.add('dark-theme'); // Ajoute la classe 'dark-theme' au corps du document pour activer le thème sombre
    } else{
        localStorage.setItem("theme", "light"); // Si aucune valeur de thème n'est stockée dans le localStorage, définir le thème sur "light"
    }
    
    function updateGradientColors() {
        var theme = localStorage.getItem("theme");
        if (theme === "light") {
            document.body.style.backgroundImage = "var(--bg-img)";
            document.body.style.backgroundColor = "var(--body-color)";
            document.body.style.setProperty('--before-bg-color', 'rgba(123, 150, 186, 0.5)'); // Set original background color for body::before
        } else if (theme === "dark") {
            document.body.style.backgroundImage = "none";
            document.body.style.backgroundColor = "#2a2a2a";
            document.body.style.setProperty('--before-bg-color', 'transparent'); // Set transparent background color for body::before
        }
    }
    
    
    
}



// settingsMenuToggle()