const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');
const signupForm = document.getElementById('signup-form');
const signinForm = document.getElementById('signin-form');

// Toggle between Sign Up and Sign In forms
registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

// Sign Up Form Validation
signupForm.addEventListener('submit', (e) => {
    e.preventDefault();
    clearErrors();

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const password = document.getElementById('password').value.trim();

    let isValid = true;

    if (!name) {
        showError('name-error', 'Name is required.');
        isValid = false;
    }

    if (!email || !validateEmail(email)) {
        showError('email-error', 'Please enter a valid email address.');
        isValid = false;
    }

    if (!phone || !validatePhone(phone)) {
        showError('phone-error', 'Please enter a valid phone number.');
        isValid = false;
    }

    if (!password || password.length < 8) {
        showError('password-error', 'Password must be at least 8 characters long.');
        isValid = false;
    }

    if (isValid) {
        alert('Sign Up Successful! Redirecting to Home Page...');
        window.location.href = 'home.html'; // Redirect to home page
    }
});

// Sign In Form Validation
signinForm.addEventListener('submit', (e) => {
    e.preventDefault();
    clearErrors();

    const email = document.getElementById('signin-email').value.trim();
    const password = document.getElementById('signin-password').value.trim();

    let isValid = true;

    if (!email || !validateEmail(email)) {
        showError('signin-email-error', 'Please enter a valid email address.');
        isValid = false;
    }

    if (!password) {
        showError('signin-password-error', 'Password is required.');
        isValid = false;
    }

    if (isValid) {
        alert('Sign In Successful! Redirecting to Home Page...');
        window.location.href = 'home.html'; // Redirect to home page
    }
});

// Helper Functions
function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    errorElement.textContent = message;
    errorElement.style.display = 'block';
}

function clearErrors() {
    const errors = document.querySelectorAll('.error-message');
    errors.forEach(error => {
        error.textContent = '';
        error.style.display = 'none';
    });
}

function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function validatePhone(phone) {
    const regex = /^\d{10}$/; // Example: 10-digit phone number
    return regex.test(phone);
}
