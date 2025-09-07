document.addEventListener("DOMContentLoaded",()=>{

    const resetpasspopup=document.getElementById('resetpasspopup');
    const resetpasspopupclose=document.getElementById('resetpasspopupclose');

    function openresetpass(){

        resetpasspopup.style.display='block';
        setTimeout(()=>{

            resetpasspopup.classList.add('show');

        },10);

    }

    function closeresetpass(){

        resetpasspopup.classList.remove('show');
        setTimeout(()=>{

            resetpasspopup.style.display='none';

        },800);

    }

    resetpasspopupclose.onclick=closeresetpass;

    window.onclick=function(event){

        if(event.target == resetpasspopup){
            closeresetpass();
        }

    };

    window.openresetpass=openresetpass;

    document.getElementById('resetpassdetails').onsubmit = function(event){

        const passcontain=/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
        const newpass=document.getElementById('newpass').value;
        const confirmpass=document.getElementById('confirmpass').value;
        
        if(!passcontain.test(newpass)){

            alert('Password must be at least 8 characters long and include at least one number, one lowercase and one uppercase letter.');
            event.preventDefault();

        }
        else if(newpass !== confirmpass){

            alert('New password and confirm password do not match.');
            event.preventDefault();

        }
        
    };


});