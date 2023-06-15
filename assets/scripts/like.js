// import axios from 'axios';

export default class Like {
  constructor(likeElements) {
      this.likeElements = likeElements;
      console.log(likeElements);
        if (this.likeElements) {
          this.init();
        }
  }

  init() {
        this.likeElements.map(element => {
          element.addEventListener('click', this.onClick)
        })
  }

  onClick(event){
    console.log(event);
    event.preventDefault();
    const url = this.href;

    axios.get(url).then(res => {
      console.log(res);

      // get the buttons
      const heartFilled = this.querySelector('.filled');
      const emptyHeart = this.querySelector('.unfilled');
  
      heartFilled.classList.toggle('d-none');
      emptyHeart.classList.toggle('d-none');
         
    })
        
  }
}