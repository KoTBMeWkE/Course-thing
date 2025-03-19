document.addEventListener('DOMContentLoaded', function() {
    const menu = document.getElementById('menu');
    const btnMenu = document.getElementById('btn-menu');
    const overlay = document.getElementById('overlay');
    const menuItems = menu.children;


    for (let item of menuItems) {
        item.style.display = 'none';
    }
    btnMenu.addEventListener('click', function() {
        const isVisible = menu.style.left === '0px';

        menu.style.left = isVisible ? '-380px' : '0px'; 
        overlay.style.opacity = isVisible ? '0' : '1'; 
        overlay.style.visibility = isVisible ? 'hidden' : 'visible'; 

        for (let item of menuItems) {
            item.style.display = isVisible ? 'none' : 'block';
        }
    });
    overlay.addEventListener('click',function() {
        menu.style.left = '-380px' ; 
        overlay.style.opacity = '0';
        overlay.style.visibility = 'hidden';

        for (let item of menuItems) {
            item.style.display = 'none';
        }
    });
});