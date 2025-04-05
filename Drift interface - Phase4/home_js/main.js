document.addEventListener("DOMContentLoaded", function () {

    /*=============== SHOW MENU ===============*/
    const navMenu = document.getElementById('nav-menu'),
        navToggle = document.getElementById('nav-toggle'),
        navClose = document.getElementById('nav-close');

    if (navToggle) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.add('show-menu');
        });
    }

    if (navClose) {
        navClose.addEventListener('click', () => {
            navMenu.classList.remove('show-menu');
        });
    }

    const navLink = document.querySelectorAll('.nav__link');

    function linkAction() {
        const navMenu = document.getElementById('nav-menu');
        navMenu.classList.remove('show-menu');
    }
    navLink.forEach(n => n.addEventListener('click', linkAction));

    /*=============== CHANGE BACKGROUND HEADER ===============*/
    function scrollHeader() {
        const header = document.getElementById('header');
        if (this.scrollY >= 50) header.classList.add('scroll-header');
        else header.classList.remove('scroll-header');
    }
    window.addEventListener('scroll', scrollHeader);

    /*=============== JS FOR VIDEO SLIDER ===============*/
    const btns = document.querySelectorAll(".slider__bg-navBtn");
    const slides = document.querySelectorAll(".video__slide");

    const sliderNav = function(manual) {
        btns.forEach((btn) => btn.classList.remove("active"));
        slides.forEach((slide) => slide.classList.remove("active"));

        btns[manual]?.classList.add("active");
        slides[manual]?.classList.add("active");
    };

    btns.forEach((btn, i) => {
        btn.addEventListener("click", () => {
            sliderNav(i);
        });
    });

    /*=============== POPULAR SWIPER ===============*/
    let swiperPopular = new Swiper(".popular__container", {
        loop: false,
        spaceBetween: 24,
        slidesPerView: 'auto',
        centeredSlides: false,
        grabCursor: true,

        pagination: {
            el: ".swiper-pagination",
            dynamicBullets: true,
        },

        breakpoints: {
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3, spaceBetween: 48 },
        },
    });

    /*=============== MIXITUP FILTER FEATURED ===============*/
    let mixerFeatured = mixitup('.featured__content', {
        selectors: {
            target: '.featured__card'
        },
        animation: {
            duration: 300
        }
    });

    /* Link active color featured */
    const linkFeatured = document.querySelectorAll('.featured__item');

    function activeFeatured() {
        linkFeatured.forEach(l => l.classList.remove('active-featured'));
        this.classList.add('active-featured');
    }

    linkFeatured.forEach(l => l.addEventListener('click', activeFeatured));

    /*=============== SHOW SCROLL UP ===============*/
    function scrollUp() {
        const scrollUp = document.getElementById('scroll-up');
        if (this.scrollY >= 350) scrollUp.classList.add('show-scroll');
        else scrollUp.classList.remove('show-scroll');
    }
    window.addEventListener('scroll', scrollUp);

    /*=============== SCROLL SECTIONS ACTIVE LINK ===============*/
    const sections = document.querySelectorAll('section[id]');

    function scrollActive() {
        const scrollY = window.pageYOffset;

        sections.forEach(current => {
            const sectionHeight = current.offsetHeight,
                  sectionTop = current.offsetTop - 58,
                  sectionId = current.getAttribute('id');

            const link = document.querySelector('.nav__menu a[href*=' + sectionId + ']');
            if (!link) return;

            if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                link.classList.add('active-link');
            } else {
                link.classList.remove('active-link');
            }
        });
    }
    window.addEventListener('scroll', scrollActive);

    /*=============== DATE ===============*/
    const d = new Date();
    const dateElem = document.getElementById("date");
    if (dateElem) {
        dateElem.innerHTML = d;
    }

    /*=============== SCROLL REVEAL ANIMATION ===============*/
    const sr = ScrollReveal({
        origin: 'top',
        distance: '60px',
        duration: 2500,
        delay: 400,
        //reset: true
    });

    sr.reveal(`.home__title, .popular__container, .features__img, .featured__filters`);
    sr.reveal(`.home__subtitle`, { delay: 500 });
    sr.reveal(`.home__elec`, { delay: 600 });
    sr.reveal(`.home__img`, { delay: 800 });
    sr.reveal(`.home__car-data, .footer__copy`, { delay: 900, interval: 100, origin: 'bottom' });
    sr.reveal(`.home__button`, { delay: 1000, origin: 'bottom' });

    sr.reveal(`.about__group, .offer__data`, { origin: 'left' });
    sr.reveal(`.about__data, .offer__img, .home__social-icon`, { origin: 'right' });

    sr.reveal(`.features__map, .slider__bg`, { delay: 600, origin: 'bottom' });
    sr.reveal(`.features__card`, { interval: 300 });
    sr.reveal(`.featured__card, .logos__content, .footer__content`, { interval: 100 });
});



///////search in header///////////

const searchInput = document.getElementById('searchInput');
const dropdown = document.getElementById('searchDropdown');

// When user types:
searchInput.addEventListener('keyup', function() {
  const query = this.value.trim();
  
  if (query.length === 0) {
    // Hide dropdown if empty
    dropdown.innerHTML = '';
    dropdown.style.display = 'none';
    return;
  }
  
  // AJAX request to searchAjax.php
  fetch(`searchAjax.php?q=${encodeURIComponent(query)}`)
    .then(response => response.text())
    .then(data => {
      // data is the HTML from searchAjax
      dropdown.innerHTML = data;
      dropdown.style.display = 'block';
    })
    .catch(err => console.error(err));
});