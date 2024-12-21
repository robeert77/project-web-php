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
});
    
function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validateLinkedIn(url) {
    return url.includes('linkedin.com/');
}