// JS для переключения тёмной и светлой темы
const themeToggleBtn = document.getElementById('theme-toggle');
const body = document.body;

// Проверка сохранённой темы в localStorage
if(localStorage.getItem('theme') === 'light') {
    body.classList.add('light-theme');
}

themeToggleBtn.addEventListener('click', () => {
    body.classList.toggle('light-theme');
    if(body.classList.contains('light-theme')) {
        localStorage.setItem('theme', 'light');
    } else {
        localStorage.setItem('theme', 'dark');
    }
});