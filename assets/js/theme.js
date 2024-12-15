const body = document.body;
const savedTheme = localStorage.getItem('theme');
if (savedTheme)
    body.classList.add(savedTheme);

function changeColor() {
    if (body.classList.contains('green-theme')) {
        body.classList.remove('green-theme');
        localStorage.setItem('theme', '');
    }
    else {
        body.classList.add('green-theme');
        localStorage.setItem('theme', 'green-theme');
    }
}