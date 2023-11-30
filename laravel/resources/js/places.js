document.getElementById("location-pin")

function positionRandomly() {
    const element = document.getElementById('randomly-positioned-element');
    const parent = document.querySelector('.parent-container');

    const parentWidth = parent.clientWidth;
    const parentHeight = parent.clientHeight;

    const elementWidth = element.offsetWidth;
    const elementHeight = element.offsetHeight;

    const randomX = Math.floor(Math.random() * (parentWidth - elementWidth));
    const randomY = Math.floor(Math.random() * (parentHeight - elementHeight));

    element.style.left = randomX + 'px';
    element.style.top = randomY + 'px';
}