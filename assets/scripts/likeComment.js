// Déclaration d'une classe JavaScript appelée "Like"
export default class LikeComment {
  constructor(likeType) {// Constructeur de la classe avec un paramètre "likeType"
      this.likeType = likeType; // Attribut "likeType" de la classe pour stocker les éléments HTML à liker
      console.log(likeType);
        if (this.likeType) { // Vérification si des éléments à liker sont présents
          this.init();// Si oui, appeler la méthode "init()" pour initialiser les écouteurs d'événements
        }
  }

  init() {// Méthode "init()" pour ajouter les écouteurs d'événements sur les éléments à liker
        this.likeType.map(element => { // Pour chaque élément, ajouter un écouteur d'événement "click" avec la méthode "onClick" comme gestionnaire
          element.addEventListener('click', this.onClick)
        })
  }

  onClick(event){ // Méthode "onClick" pour gérer l'action de liker/unliker lors du clic sur un élément
    console.log(event);
    event.preventDefault(); // Empêcher le comportement par défaut du clic (par exemple, le suivi d'un lien)
    const url = this.href; // Récupérer l'URL de l'action de like depuis l'élément (peut être stockée dans un attribut "href" par exemple)

    axios.get(url).then(res => { // Faire une requête GET à l'URL spécifiée en utilisant Axios (bibliothèque pour les requêtes HTTP)
      console.log(res);
      const nb = res.data.nbLike;
      const span = this.querySelector('span');
      this.dataset.nb = nb;
      span.innerHTML = nb + ' likes';


      // Récupérer les boutons représentant le coeur rempli et le coeur vide
      const thumbFilled = this.querySelector('.fillin');
      const emptythumb = this.querySelector('.fillout');
  
      // Alterner la classe CSS "d-none" pour afficher/cacher les coeurs rempli et vide
      thumbFilled.classList.toggle('d-none');
      emptythumb.classList.toggle('d-none');
         
    })
        
  }
}