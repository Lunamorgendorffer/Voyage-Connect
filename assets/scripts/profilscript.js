document.addEventListener("DOMContentLoaded", function() {
    const links = document.querySelectorAll(".title a");
    const sections = document.querySelectorAll(".section");
    console.log('hello')
    // Ajoutez un gestionnaire d'événement de clic à chaque lien
    links.forEach(function(link) {
        link.addEventListener("click", function(e) {
            e.preventDefault();

            // Supprimez la classe "active" de tous les liens et sections
            links.forEach(function(link) {
                link.classList.remove("active");
            });

            sections.forEach(function(section) {
                section.classList.remove("active");
            });

            // Ajoutez la classe "active" au lien et à la section correspondants
            const target = this.getAttribute("data-target");
            this.classList.add("active");
            document.getElementById(target).classList.add("active");
        });
    });
});
