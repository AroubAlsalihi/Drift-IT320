<?php
include 'signup.php';
include 'login.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="SignupStyle.css">
  <link rel="stylesheet" href="home_css/styles.css">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
  <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" class="favicon">
  <title>Drift | Sign Up / Log In</title>
</head>
<body>
  <a href="home.php" class="nav__logo">
    <i class="ri-steering-fill"></i>
    Drift
  </a>
  <div class="container" id="container">
    <div class="form-container sign-up">
      <form id="signup-form" method="POST" action="signup.php">
        <h1>Create Account</h1>
        <input type="text" name="name" id="name" placeholder="Name" required>
        <span class="error-message" id="name-error"></span>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <span class="error-message" id="email-error"></span>
        <input type="number" name="age" id="age" placeholder="Age" required>
        <span class="error-message" id="age-error"></span>
        <input type="text" name="phone" id="phone" placeholder="XXXXXXXXX" value="+966" required>
        <span class="error-message" id="phone-error"></span>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <span class="error-message" id="password-error"></span>
        <button type="submit">Sign Up</button>
      </form>
    </div>
    <div class="form-container sign-in">
      <form id="signin-form" method="POST" action="login.php">
        <h1>Log In</h1>
        <input type="email" name="email" id="signin-email" placeholder="Email" required>
        <span class="error-message" id="signin-email-error"></span>
        <input type="password" name="password" id="signin-password" placeholder="Password" required>
        <span class="error-message" id="signin-password-error"></span>
        <button type="submit">LOG IN</button>
      </form>
    </div>
    <div class="toggle-container">
      <div class="toggle">
        <div class="toggle-panel toggle-left">
          <h1>Welcome Back!</h1>
          <p>Enter your personal details to use all of site features</p>
          <button class="hidden" id="login">Sign In</button>
        </div>
        <div class="toggle-panel toggle-right">
          <h1>Hello, Friend!</h1>
          <p>Register with your personal details to use all of site features</p>
          <button class="hidden" id="register">Sign Up</button>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');
    const signupForm = document.getElementById('signup-form');
    const signinForm = document.getElementById('signin-form');
    const passwordInput = document.getElementById('password');
    const passwordError = document.getElementById('password-error');
    const ageInput = document.getElementById('age');
    const ageError = document.getElementById('age-error');
    const phoneInput = document.getElementById('phone');
    const phoneError = document.getElementById('phone-error');

    // Ensure +966 stays at the start
    phoneInput.addEventListener('input', function() {
      let value = this.value.trim();
      if (!value.startsWith('+966')) {
        this.value = '+966' + value.replace(/^\+966/, '');
      }
      
      const cleanPhone = value.replace('+966', '').replace(/[\s\-\(\)]/g, '');
      const isValidNumber = /^\d{9}$/.test(cleanPhone);

      if (value.length > 4) {  
        if (!isValidNumber) {
          showError('phone-error', 'Enter a valid phone number');
        } else {
          phoneError.textContent = '';
          phoneError.style.display = 'none';
        }
      } else {
        phoneError.textContent = '';
        phoneError.style.display = 'none';
      }
    });

    // Prevent deleting +966
    phoneInput.addEventListener('keydown', function(e) {
      const cursorPosition = this.selectionStart;
      if (cursorPosition <= 4 && (e.key === 'Backspace' || e.key === 'Delete')) {
        e.preventDefault();
      }
    });

    registerBtn.addEventListener('click', () => {
      container.classList.add("active");
    });
    loginBtn.addEventListener('click', () => {
      container.classList.remove("active");
    });

    // Real-time password validation
    passwordInput.addEventListener('input', function() {
      const password = this.value;
      const isLengthValid = password.length >= 8;
      const hasUpperCase = /[A-Z]/.test(password);
      const hasLowerCase = /[a-z]/.test(password);
      const hasNumber = /\d/.test(password);
      const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
      const isValid = isLengthValid && hasUpperCase && hasLowerCase && hasNumber && hasSpecial;

      if (password.length > 0) {
        if (!isValid) {
          showError('password-error', 'Password must be 8+ characters with uppercase, lowercase, number, and special character');
        } else {
          passwordError.textContent = '';
          passwordError.style.display = 'none';
        }
      } else {
        passwordError.textContent = '';
        passwordError.style.display = 'none';
      }
    });

    // Real-time age validation
    ageInput.addEventListener('input', function() {
      const age = this.value;
      if (age.length > 0) {
        if (isNaN(age) || parseInt(age) < 18) {
          showError('age-error', 'You must be 18 or older to register');
        } else {
          ageError.textContent = '';
          ageError.style.display = 'none';
        }
      } else {
        ageError.textContent = '';
        ageError.style.display = 'none';
      }
    });

    signupForm.addEventListener('submit', (e) => {
      clearErrors();

      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const age = document.getElementById('age').value.trim();
      let phone = document.getElementById('phone').value.trim();
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
      if (!age || isNaN(age) || parseInt(age) < 18) {
        showError('age-error', 'You must be 18 or older to register');
        isValid = false;
      }
      const cleanPhone = phone.replace('+966', '').replace(/[\s\-\(\)]/g, '');
      const isValidNumber = /^\d{9}$/.test(cleanPhone);
      if (!phone || !isValidNumber) {
        showError('phone-error', 'Enter a valid phone number ');
        isValid = false;
      }
      if (!password || !(/[A-Z]/.test(password) && /[a-z]/.test(password) && /\d/.test(password) && /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password) && password.length >= 8)) {
        showError('password-error', 'Password must be 8+ characters with uppercase, lowercase, number, and special character');
        isValid = false;
      }

      if (!isValid) {
        e.preventDefault();
      }
    });

    signinForm.addEventListener('submit', (e) => {
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
      
      if (!isValid) {
        e.preventDefault();
      }
    });

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
  </script>
  <script src="home_js/main.js"></script>
</body>
</html>
