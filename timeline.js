// pour la timeline

let elDates = Array.from(document.querySelectorAll(".dateBox"));
let timeline = document.querySelector(".timeline");
let rectTimeline = timeline.getClientRects()[0];
let dates = [];

function convertTovw(size) {
  // mettre en vw évite d'avoir des problèmes de positionnement
  return (size * 100) / window.innerWidth;
}

const etat = ["before", "going", "passed", false];

let state = [];
let dateNow = new Date();
let exceptionDate = new Date("August 16, 2024 15:00:00 GMT+0300");

elDates.forEach((el) => {
  // on récupère les dates de chaque élément et on regarde son avancement
  // - si c'est passé, on aura du vert
  // - si c'est en cours, on aura du bleu
  // - si c'est pas encore passé, on aura du violet
  let etatIndice = 0;
  let date = new Date(el.getAttribute("date"));
  let bis = el.getAttribute("datebis");
  if (date <= exceptionDate && date >= exceptionDate) {
    // si c'est la date de séparation
    state.push(false);
  } else if (bis !== null) {
    dateBis = new Date(bis);
    if (dateNow < date) {
      state.push(etat[0]); // l'élément n'est pas encore passé
      etatIndice = 0;
    } else if (dateBis < dateNow) {
      state.push(etat[2]); // l'élément est passé
      etatIndice = 2;
    } else {
      state.push(etat[1]); // l'élément est en cours
      etatIndice = 1;
    }
  } else {
    if (dateNow < date) {
      state.push(etat[0]); // l'élément n'est pas encore passé
      etatIndice = 0;
    } else {
      state.push(etat[2]); // l'élément est passé
      etatIndice = 2;
    }
  }
  el.classList.toggle(etat[0]);
  el.classList.toggle(etat[etatIndice]);
});

let decal = {
  // pour mettre au bon endroit la timeline
  x: 5.9,
  y: -2,
};

for (let i = 0; i < elDates.length - 1; i++) {
  let color = "--gray-02";
  if (elDates[i].classList[1] === elDates[i + 1].classList[1]) {
    switch (elDates[i].classList[1]) {
      case etat[0]:
        color = "--purple";
        break;
      case etat[1]:
        color = "--blue";
        break;
      case etat[2]:
        color = "--green";
        break;
      default:
        break;
    }
  }
  if (!state[i]) {
    continue;
  } else {
    // barre de couleur
    let el = document.createElement("div");
    let width = 0.8;
    rectA = elDates[i].getClientRects()[0];
    rectB = elDates[i + 1].getClientRects()[0];
    let height = rectB.y - rectA.y;
    el.setAttribute(
      "style",
      `height:${convertTovw(height)}vw;
    width:${width}vw;
     background-color:rgb(var(${color}));
     top: ${convertTovw(rectA.y - rectTimeline.y) - decal.y}vw;
     left:${convertTovw(rectA.x - rectTimeline.x) - decal.x}vw;
    position: absolute;
    z-index:-1;`
    );
    // on enregistre l'élément
    timeline.appendChild(el);
    // barre grise derrière
    let elBis = document.createElement("div");
    let widthbis = width + 0.8;
    elBis.setAttribute(
      "style",
      `height:${convertTovw(height)}vw;
    width:${widthbis}vw; background-color:rgb(var(--gray-02));
    top: ${convertTovw(rectA.y - rectTimeline.y) - decal.y}vw;
    left:${convertTovw(rectA.x - rectTimeline.x) - decal.x - 0.4}vw;
    position: absolute;
    z-index:-2;`
    );
    timeline.appendChild(elBis);
  }
}

// pour afficher les language requirements

seeLang = Array.from(document.querySelectorAll(".language .see-lang h4"));

function afficheCache(el) {
  el.addEventListener("click", function () {
    // el.classList.toggle("cache");
    parent = this.parentElement;
    parent.classList.toggle("rotate");
    cache = parent.getElementsByClassName("cache")[0];
    cache.classList.toggle("affiche");
  });
}

seeLang.forEach((el) => {
  afficheCache(el);
});
