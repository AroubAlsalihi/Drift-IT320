<?php
include 'signup.php';
include 'login.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- CSS and Icon Links -->
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
    <!-- Sign Up Form -->
    <div class="form-container sign-up">
      <form id="signup-form" method="POST" action="signup.php">
        <h1>Create Account</h1>
        <input type="text" name="name" id="name" placeholder="Name" required>
        <span class="error-message" id="name-error"></span>
        
        <input type="email" name="email" id="email" placeholder="Email" required>
        <span class="error-message" id="email-error"></span>
        
     
        <input type="number" name="age" id="age" placeholder="Age" required>
        <span class="error-message" id="age-error"></span>
        
        <input type="text" name="phone" id="phone" placeholder="Phone Number" required>
        <span class="error-message" id="phone-error"></span>
        
        <input type="password" name="password" id="password" placeholder="Password" required>
        <span class="error-message" id="password-error"></span>
        
        <button type="submit">Sign Up</button>
      </form>
    </div>
    
    <!-- Log Form -->
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
    
    <!-- Toggle Container -->
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

    // Toggle between Sign Up and Sign In forms
    registerBtn.addEventListener('click', () => {
      container.classList.add("active");
    });
    loginBtn.addEventListener('click', () => {
      container.classList.remove("active");
    });

    // Sign Up Form Validation
    signupForm.addEventListener('submit', (e) => {
      clearErrors();

      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const age = document.getElementById('age').value.trim();
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
      // Validate age field: ensure it's not empty and is a positive number
      if (!age || isNaN(age) || parseInt(age) <= 0) {
        showError('age-error', 'Please enter a valid age.');
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
      
      if (!isValid) {
        e.preventDefault();
      }
      // If valid, the form submits naturally to signup.php
    });

    // Sign In Form Validation
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
      // If valid, the form submits naturally to login.php
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
  </script>
  <script src="home_js/main.js"></script>
</body>
</html>
