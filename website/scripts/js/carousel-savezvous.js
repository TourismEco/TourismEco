class ElCarousel {
    constructor(title,text,img,id_pays) {
        this.title = title
        this.text = text
        this.img = img
        this.id_pays = id_pays
    }
}

var liste = []

function addListe(title,text,img,id_pays) {
    liste.push(new ElCarousel(title,text,img,id_pays))
    if (liste.length == 1) {
        currentOptionImage.style.backgroundImage = "url(" + image_options[i] + ")";
        currentOptionText1.innerText = title;
        currentOptionText2.innerText = text;
    }
}

function delListe(id_pays) {
    liste = liste.filter((el) => el.id_pays != id_pays)
    currentOptionImage.style.backgroundImage = "url(" + image_options[i] + ")";
    currentOptionText1.innerText = liste[0].title;
    currentOptionText2.innerText = liste[0].text;
}

const image_options = [
    "https://images.unsplash.com/photo-1524721696987-b9527df9e512?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1190&q=80",
    "https://images.unsplash.com/photo-1556656793-08538906a9f8?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1050&q=80",
    "https://images.unsplash.com/photo-1506073828772-2f85239b6d2d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1189&q=80",
    "https://images.unsplash.com/photo-1523800503107-5bc3ba2a6f81?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=80"
];

var i = 0;
const currentOptionText1 = document.getElementById("current-option-text1");
const currentOptionText2 = document.getElementById("current-option-text2");
const currentOptionImage = document.getElementById("image");
const carousel = document.getElementById("carousel-wrapper");
const mainMenu = document.getElementById("menu");
const optionPrevious = document.getElementById("previous-option");
const optionNext = document.getElementById("next-option");

mainMenu.style.background = "#52796F";

optionNext.onclick = function () {
    i = i + 1;
    i = i % liste.length;

    carousel.classList.add("anim-next");
    
    setTimeout(() => {
        currentOptionImage.style.backgroundImage = "url(" + image_options[i] + ")";
    }, 455);
    
    setTimeout(() => {
        currentOptionText1.innerText = liste[i].title;
        currentOptionText2.innerText = liste[i].text;
        carousel.classList.remove("anim-next");
    }, 650);
};

optionPrevious.onclick = function () {
    if (i === 0) {
        i = liste.length;
    }
    i = i - 1;

    carousel.classList.add("anim-previous");

    setTimeout(() => {
        currentOptionImage.style.backgroundImage = "url(" + image_options[i] + ")";
    }, 455);
    
    setTimeout(() => {
        currentOptionText1.innerText = liste[i].title;
        currentOptionText2.innerText = liste[i].text
        carousel.classList.remove("anim-previous");
    }, 650);
};
