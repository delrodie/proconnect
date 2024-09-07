document.addEventListener('DOMContentLoaded', function () {
  AOS.init({
    duration: 3000,
    easeing: 'ease-in-out',
    mirror: true,
    anchorPlacement: 'top-bottom'
  });

  //scrollCue
  scrollCue.init();

// Atropos
const myAtropos = Atropos({
    el: '.my-atropos',
    // rest of parameters
});

// Bouton de soumission
  var btnSoumission = document.querySelector(".btnSoumission");

  btnSoumission.addEventListener("click", function() {
    btnSoumission.classList.add("disabled");
    btnSoumission.innerHTML = "Soumission en cours...";
  });

})


/*  Button Go Top */
const btnGoTop = document.querySelector('.btn-gotop');

window.addEventListener('scroll', () => {
  if (window.scrollY > 100) { 
    btnGoTop.style.display = 'block';
  } else {
    btnGoTop.style.display = 'none';
  }
});

btnGoTop.addEventListener('click', () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});