/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/register.css';
import './styles/home.css';
import './styles/userProfile.css';
import './scripts/menu-nav.js';

import Like from './scripts/like.js';
import Favorite from './scripts/favorite.js';
// import './scripts/profilscript.js';

document.addEventListener('DOMContentLoaded', () => {
    
    // Like's system
    const likeElements = [].slice.call(document.querySelectorAll('a[data-action="like"]'));
    if (likeElements) {
      new Like(likeElements);
    }

    const favoriteElements = [].slice.call(document.querySelectorAll('a[data-action="favorite"]'));
    if (favoriteElements) {
        new Favorite(favoriteElements);
    }

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
    
})
  

const menuBtn = document.querySelector('.menu-btn');
const navigation = document.querySelector('.nav');
// console.log('nav')

// menuBtn.addEventListener('click', () => {
//     menuBtn.classList.toggle('active');
//     navigation.classList.toggle('active');
// })

// script for video slider 

const btns = document.querySelectorAll('.nav-btn');
const slides = document.querySelectorAll('.video-slide');
const contents = document.querySelectorAll('.content');;
console.log('nav-btn')
console.log('video-slide')

var sliderNav= function(manual){
    btns.forEach((btn) => {
        btn.classList.remove('active');
    });

    slides.forEach((slide) => {
        slide.classList.remove('active');
    });

    contents.forEach((content) => {
        content.classList.remove('active');
    });


    btns[manual].classList.add('active');
    slides[manual].classList.add('active');
    contents[manual].classList.add('active');
}

btns.forEach((btn,i) => {
    btn.addEventListener('click', () => {
        sliderNav(i);
    })
})