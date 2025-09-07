const toggleButton = document.querySelector('.toggle_button')
const toggleButtonIcon = document.querySelector('.toggle_button i')
const mediaMenu = document.querySelector('.mediamenu')

toggleButton.onclick = function (){

    mediaMenu.classList.toggle('open')
    const isOpen = mediaMenu.classList.contains('open')

    toggleButtonIcon.classList = isOpen
    ? 'fa-solid fa-xmark'
    : 'fa-solid fa-bars'

}