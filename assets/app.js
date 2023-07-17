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

import './scripts/profilscript.js';
import './scripts/menu-nav.js';
import './scripts/scrollreveal.min.js';
import'./scripts/searchData.js';

import Like from './scripts/like.js';
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

/*=============== SHOW MENU ===============*/
const navMenu = document.getElementById('nav-menu');
const navToggle = document.getElementById('nav-toggle');
const navClose = document.getElementById('nav-close');

if(navToggle){
    navToggle.addEventListener('click',() =>{
        navMenu.classList.add('show-menu');
    })
}

if(navClose){
    navClose.addEventListener('click',() =>{
        navMenu.classList.remove('show-menu');
    })
}

/*=============== REMOVE MENU MOBILE ===============*/

const navLink = document.querySelectorAll('.nav_link');

const linkAction = ()  =>{
    const NavMenu = document.getElementById('nav_menu');
    navMenu.classList.remove('show-menu');
}

navLink.forEach(n=>navMenu.addEventListener('click', n, linkAction))
/*=============== ADD BLUR TO HEADER ===============*/
const blurHeader = () => {
    const header = document.getElementById('header');
    window.scrollY >= 50 ? header.classList.add('blur-header') : header.classList.remove('blur-header');
};

window.addEventListener('scroll', blurHeader);

/*=============== SCROLL REVEAL ANIMATION ===============*/
const sr = ScrollReveal({
    origin:'top',
    distance:'600px',
    duration: 300,
    delay:400,
})

sr.reveal ('.home_data', '.explore_data')
sr.reveal ('.home_card', {delay:600, distance: '100px', interval:500})
sr.reveal ('.post-card', {interval:800})
sr.reveal ('home_data', {origin:'right'})


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

    // //--------- Ban User ---------//
    // const switchElements = document.querySelectorAll('input[id^=switch]');
    // console.log(switchElements.length);
    // const formElement = document.getElementById('banForm');

})
