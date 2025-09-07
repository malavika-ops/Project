document.addEventListener("DOMContentLoaded", ()=>{
    const lightdarkmodetoggle = document.querySelector(".lightdarkmodetoggle");
    const body = document.querySelector("body");
    const bodyheadcontent = document.querySelector(".bodyheadcontent");
    const sidemenu = document.querySelector(".sidemenu");
    const lightanddarkswitch = document.querySelector(".lightanddarkswitch");
    const togglebutton = document.getElementById("togglebtn");
    const userwelcome = document.getElementById('userwelcome');
    const userdropdown = document.getElementById('userdropdown');


    const savedMode = localStorage.getItem("darkMode");

    if(savedMode === "enabled"){
        enableDarkMode();
    }

    lightdarkmodetoggle.addEventListener("click", ()=>{
        const isDarkModeEnabled = body.classList.contains("darkbody");

        if(isDarkModeEnabled){
            disableDarkMode();
        }
        else{
            enableDarkMode();
        }
    });

    togglebutton.addEventListener("click", ()=>{
        sidemenu.classList.toggle("collapsed");
    });

    userwelcome.addEventListener('click', ()=>{
        userdropdown.style.display = userdropdown.style.display === 'block' ? 'none' : 'block';
    });

    window.onclick = function(event){
        if(!event.target.matches('.userwelcome')){
            if(userdropdown.style.display === 'block'){
                userdropdown.style.display = 'none';
            }
        }
    }

    function enableDarkMode(){
        body.classList.add("darkbody");
        sidemenu.classList.add("darksidemenu");
        lightanddarkswitch.classList.add("switch-right");
        bodyheadcontent.classList.add("darkbodyheadcontent");
        localStorage.setItem("darkMode", "enabled");
    }

    function disableDarkMode(){
        body.classList.remove("darkbody");
        sidemenu.classList.remove("darksidemenu");
        lightanddarkswitch.classList.remove("switch-right");
        bodyheadcontent.classList.remove("darkbodyheadcontent");
        localStorage.setItem("darkMode", "disabled");
    }

    function filterWords(){

        const query = document.getElementById('searchbar').value.toLowerCase();
        const items = document.querySelectorAll('#words li');

        items.forEach(item =>{
            const word = item.textContent.toLowerCase();
            if(word.includes(query)){
                item.style.display = 'block';
            }
            else{
                item.style.display = 'none';
            }
        });
    }

    document.getElementById('searchbar').addEventListener('input', filterWords);

    document.querySelectorAll('.items').forEach(item =>{

        item.addEventListener('click', function (event){
            event.preventDefault();
            const meaning = this.getAttribute('data-meaning');
            const display = document.querySelector('.meaning-display');
            display.innerHTML = `<p>${meaning}</p>`;
        });
        
    });
});