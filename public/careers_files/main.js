let hamburger = document.querySelector('.hamburger');
let loadingPopups = document.querySelectorAll('.loading-pop-up');
let scrollPopups = document.querySelectorAll('.scroll-pop-up');
let joinUsBtns = document.querySelectorAll('.join-us-btn'); 
let joinUsModal = document.querySelector('.join-us-modal'); 
let joinUsModalForm = document.querySelector('.join-us-modal form'); 
let joinUsModalSubmitBtn = document.querySelector('.join-us-modal form .submit-btn'); 
let cloaseJoinUsModalBtn = document.querySelector('.join-us-modal .close-btn'); 
let calendlyModal = document.querySelector('.calendly-modal'); 
let cloasecalendlyModalBtn = document.querySelector('.calendly-modal .close-btn'); 

window.addEventListener('scroll', scrolled)

loadingPopups.forEach(popup => popup.classList.add('animat-popup'))

scrollPopups.forEach(popup => { 
        
    let triggerPopUpTop = popup.getBoundingClientRect().top - window.innerHeight - 100

    if (triggerPopUpTop < 0) {
        popup.classList.add('animat-popup')
       
    }

})

function scrolled() {

    
    scrollPopups.forEach(popup => { 
        
        let triggerPopUpTop = popup.getBoundingClientRect().top - window.innerHeight /1.1

        if (triggerPopUpTop < 0) {
            popup.classList.add('animat-popup')
            // console.log(popup);
        }

})
}

hamburger.addEventListener('click', hamburgerClicked)

function hamburgerClicked() {
    document.body.classList.toggle('hamburger-Clicked')
}


joinUsBtns.forEach(btn => btn.addEventListener('click', () => {
    joinUsModal.classList.add('exist')
}))
cloaseJoinUsModalBtn.addEventListener('click', () => {
    joinUsModal.classList.remove('exist')
})
joinUsModalSubmitBtn.addEventListener('click', () => {
    joinUsModal.classList.remove('exist')
    calendlyModal.classList.add('exist')
    document.querySelector('.calendly-inline-widget').scrollTo(0, 900)
})
joinUsModalForm.addEventListener("submit",function(e)
{e.preventDefault();
    
})

cloasecalendlyModalBtn.addEventListener('click', () => {
    calendlyModal.classList.remove('exist')
})
