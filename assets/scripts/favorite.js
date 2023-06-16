export default class Favorite {

    // list of the like in the current page
    constructor(favoriteElements){
  
        this.favoriteElements = favoriteElements

        if(this.favoriteElements){

            // call a method
            this.init();
        }
    }

    init(){

        // for each like element we map the element 
        this.favoriteElements.map(element => {

            // when the element is clicked we call a onClick method
            element.addEventListener('click', this.onClick);
        })
    }

    onClick(e){

        e.preventDefault(); // doesn't sent the info to data (remove the logic behind the element)
        const url = this.href; // get the url of the link

        // axios to get the url of a controller route
        axios.get(url).then(response => {
            console.log(response);

            // get the buttons
            const  starFilled = this.querySelector('.filled');
            const emptystar = this.querySelector('.unfilled');

            // toggle the display of the filled and empty buttons
            starFilled.classList.toggle('d-none');
            emptystar.classList.toggle('d-none');
        })
    }
}