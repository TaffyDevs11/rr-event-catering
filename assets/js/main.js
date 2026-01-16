// main.js
// Path: C:\xampp\htdocs\RRCatering\assets\js\main.js
// Small interactivity for nav toggle and basic behaviors

document.addEventListener('DOMContentLoaded', function () {
    // Responsive nav toggle
    var navToggle = document.getElementById('navToggle');
    var mainNav = document.querySelector('.main-nav');

    if (navToggle && mainNav) {
        navToggle.addEventListener('click', function () {
            if (mainNav.style.display === 'block') {
                mainNav.style.display = '';
            } else {
                mainNav.style.display = 'block';
            }
        });
    }

    // Simple accessible focus outline for keyboard users (small enhancement)
    document.body.addEventListener('keydown', function (e) {
        if (e.key === 'Tab') {
            document.body.classList.add('user-is-tabbing');
        }
    }, { once: true });
});
// main.js
document.addEventListener('DOMContentLoaded', function () {
    const navToggle = document.getElementById('navToggle');
    const mainNav = document.querySelector('.main-nav');

    if (!navToggle || !mainNav) return;

    navToggle.addEventListener('click', function () {
        mainNav.classList.toggle('open');
    });
});
