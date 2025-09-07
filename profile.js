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
    
});