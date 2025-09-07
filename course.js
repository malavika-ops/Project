const courseoffered = document.querySelector('.courseoffered');
const left = document.querySelector('.left');
const right = document.querySelector('.right');
const courseitems = document.querySelectorAll('.courseitems').length;
let index = 0;

left.addEventListener('click', ()=>{
    if(index > 0){
        index--;
        courseoffered.style.transform = `translateX(-${index * 100}%)`;
    }
});

right.addEventListener('click', ()=>{
    if(index < courseitems - 1){
        index++;
        courseoffered.style.transform = `translateX(-${index * 100}%)`;
    }
});