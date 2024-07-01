var swiper = new Swiper(".swiper", {
    effect: "coverflow",
    grabCursor: true,
    centeredSlides: true, 
    initialSlide: 2,
    speed: 600,
    preventClicks: true,
    slidesPerView: "auto",
    coverflowEffect: {
        rotate: 0,
        stretch: 80,
        depth: 350,
        modifier: 1,
        slideShadows: true,
    },
    on: {
        click(event) {
            swiper.slideTo(this.clickedIndex);
        },
    },
    pagination: {
        el: ".swiper-pagination", // Agregado el punto (.) al selector
    },
});

particlesJS("particles-js", {
    particles: {
        number: {
            value: 160,
            density: {
                enable: true,
                value_area: 800,
            },
        },
        color: {
            value: ["#ffffff", "#192bce", "#08a06b"],
        },
        shape: {
            type: "circle",
        },
        opacity: {
            value: 0.5,
            random: false,
            anim: {
                enable: false,
                speed: 10,
                opacity_min: 0.1,
                sync: false,
            },
        },
        size: {
            value: 3,
            random: true,
            anim: {
                enable: false,
                speed: 40,
                size_min: 0.1,
                sync: false,
            },
        },
        line_linked: {
            enable: false,
        },
        move: {
            enable: true,
            speed: 1,
            direction: "none",
            random: true,
            straight: false,
            out_mode: "none",
            bounce: false,
            attract: {
                enable: false,
                rotateX: 600,
                rotateY: 1200,
            },
        },
    },
    retina_detect: true,
});
    
    