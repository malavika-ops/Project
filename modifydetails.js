document.addEventListener("DOMContentLoaded",()=>{

    const popupwindow=document.getElementById('popupwindow');
    const popupclose=document.getElementById('popupclose');

    function openModifyDetails(){

        popupwindow.style.display='block';

        setTimeout(()=>{
            popupwindow.classList.add('show');
        },10);

    }

    function closePopup(){

        popupwindow.classList.remove('show');

        setTimeout(()=>{
            popupwindow.style.display='none';
        },800);

    }

    popupclose.onclick=closePopup;

    window.onclick=function(event){
        if(event.target == popupwindow){
            closePopup();
        }
    };

    window.openModifyDetails=openModifyDetails;
});