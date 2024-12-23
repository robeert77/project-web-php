const themeKey = 'theme';

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const email = document.querySelector('input[type="email"]');
            const linkedin = document.querySelector('input[name="linkedin_profile"]');

            if (email && !validateEmail(email.value)) {
                e.preventDefault();
                alert('Please enter a valid email address');
            }

            if (linkedin && !validateLinkedIn(linkedin.value)) {
                e.preventDefault();
                alert('Please enter a valid LinkedIn URL');
            }
        });
    }

    // Aplicare tema
    const savedTheme = localStorage.getItem(themeKey) || 'light';
    document.getElementById('bodyElement').setAttribute('data-bs-theme', savedTheme);
    if (savedTheme === 'dark') {
        document.getElementById('darkMode').setAttribute('checked', 'checked');
    }
});
    
function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validateLinkedIn(url) {
    return url.includes('linkedin.com/');
}

document.getElementById('darkMode').addEventListener('click', () => {
    const currentTheme = document.getElementById('bodyElement').getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';

    localStorage.setItem(themeKey, newTheme);
    document.getElementById('bodyElement').setAttribute('data-bs-theme', newTheme);
});