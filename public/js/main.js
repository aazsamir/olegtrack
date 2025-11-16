var theme = localStorage.getItem('theme') || 'dark';
document.documentElement.setAttribute('data-bs-theme', theme);

function toggleTheme() {
    theme = theme === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-bs-theme', theme);
    localStorage.setItem('theme', theme);
}