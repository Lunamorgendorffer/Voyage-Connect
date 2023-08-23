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
// import './styles/_responsive.css';

import './scripts/profilscript.js';
import './scripts/menu-nav.js';
import './scripts/scrollreveal.min.js';
import'./scripts/searchData.js';

import Like from './scripts/like.js';
import LikeComment from './scripts/likeComment.js';
import Favorite from './scripts/favorite.js';
import ScrollReveal from 'scrollreveal';

//--------- partie pour le switch du panel Admin pour ban un user ---------//
document.addEventListener('DOMContentLoaded', function() {
    const switchElements = document.querySelectorAll('input[id^=switch]');
    console.log(switchElements.length);
    const formElement = document.getElementById('banForm');
});

//--------- partie pour le switch du panel Admin pour lock un event ---------//
document.addEventListener('DOMContentLoaded', function() {
    const switchElements = document.querySelectorAll('input[id^=switch]');
    console.log(switchElements.length);
    const formElement = document.getElementById('lockForm');
});

//Burger menu
const burgerMenu = document.getElementById("burger-menu");
const links = document.querySelector(".nav_links");

if(burgerMenu){
    burgerMenu.addEventListener("click", function () {
        this.classList.toggle("close");
        links.classList.toggle("show");
    });
}


/*=============== SHOW MENU ===============*/
const navMenu = document.getElementById('nav-menu');
const navToggle = document.getElementById('nav-toggle');
const navClose = document.getElementById('nav-close');
const menu = document.getElementById('menu');

if(navToggle){
    navToggle.addEventListener('click',() =>{
        navMenu.classList.add('show-menu');
        menu.style.display = 'none';
        // navToggle.classList.toggle('d-none');
        
    })
}

/*=============== MENU HIDDEN ===============*/
if(navClose){
    navClose.addEventListener('click',() =>{
        navMenu.classList.remove('show-menu');
        menu.style.display = 'block';
    })
}

/*=============== REMOVE MENU MOBILE ===============*/

const navLink = document.querySelectorAll('.nav_link');

const linkAction = ()  =>{
    const NavMenu = document.getElementById('nav_menu');
    navMenu.classList.remove('show-menu');
}

navLink.forEach(n=>navMenu.addEventListener('click', n, linkAction))
// navLink.forEach(n => n.addEventListener('click', linkAction));
/*=============== ADD BLUR TO HEADER ===============*/
const blurHeader = () => {
    const header = document.getElementById('header');
    if(header){
        window.scrollY >= 50 ? header.classList.add('blur-header') : header.classList.remove('blur-header');
    }
};

window.addEventListener('scroll', blurHeader);

// //////////////////////////Désactiver ScrollReveal en version mobile /////////////////////////

/*=============== SCROLL REVEAL ANIMATION ===============*/
// var screenWidth = window.screen.width
// console.log (screenWidth)
// if (screenWidth > 768){
//     const sr = ScrollReveal({
//         origin:'top',
//         distance:'600px',
//         duration: 300,
//         delay:400,
//     })
    
//     sr.reveal ('.home_data', '.explore_data')
//     sr.reveal ('.home_card', {delay:600, distance: '100px', interval:500})
//     sr.reveal ('.post-card', {interval:800})
//     sr.reveal ('home_data', {origin:'right'})
// }


document.addEventListener('DOMContentLoaded', () => {
    
    // Like's system
    const likeElements = [].slice.call(document.querySelectorAll('a[data-action="like"]'));
    if (likeElements) {
      new Like(likeElements);
    }

    // LikeComment system
    const likeType = [].slice.call(document.querySelectorAll('a[data-action="likeComment"]'));
    if (likeType) {
      new LikeComment(likeType);
    }


    const favoriteElements = [].slice.call(document.querySelectorAll('a[data-action="favorite"]'));
    if (favoriteElements) {
        new Favorite(favoriteElements);
    }


})
