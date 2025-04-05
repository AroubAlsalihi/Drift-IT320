<?php
include 'connection.php'; // Include your connection script
include ('handleLogOut.php');

// Query to get customizable cars
$sql = "SELECT Car.*, Car_Photo.Photo_URL 
        FROM Car 
        LEFT JOIN Car_Photo ON Car.Car_Id = Car_Photo.Car_Id 
        WHERE Car.`Condition` = 'customizable'
          AND Car_Photo.Photo_URL LIKE 'home_img%'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drift | Car List</title>

    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" class="favicon">

    <!--=============== REMIX ICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet"> 

    <!-- Link to External CSS -->
    <link rel="stylesheet" href="home_css/styles.css">
</head>
<body>

<header class="header" id="header">
        <nav class="nav container">
            <a href="home.php" class="nav__logo">
                <i class="ri-steering-fill"></i>
                Drift
            </a>
            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="home.php" class="nav__link">Home</a>
                    </li>
                    <li class="nav__item">
                        <a href="NewCar.php" class="nav__link">New Cars</a>
                    </li>
                    <li class="nav__item">
                        <a href="UsedCar.php" class="nav__link">Used Cars</a>
                    </li>
                    <li class="nav__item">
                        <a href="ListOfCars.php" class="nav__link active-link">Customize</a>
                    </li>
                    <li class="nav__item">
                        <a href="Compare.php" class="nav__link">Compare</a>
                    </li>
                </ul>
                <div class="nav__search">
  <input 
    type="text" 
    placeholder="Search cars..." 
    class="search__input" 
    id="searchInput"
  />
  <div id="searchDropdown" class="search-dropdown" style="display:none;"></div>
</div>

                <!-- Authentication Buttons -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="handleLogOut.php?logout=true" class="auth__link">Log Out</a>
                <?php else: ?>
                    <a href="signup1.php" class="auth__link">Sign Up / Log In</a>
                <?php endif; ?>
                <div class="nav__close" id="nav-close">
                    <i class="ri-close-line"></i>
                </div>
            </div>
            <!-- Toggler Button -->
            <div class="nav__toggle" id="nav-toggle">
                <i class="ri-menu-line"></i>
            </div>
        </nav>
    </header>

<!--==================== CATEGORY SECTION ====================-->
<section class="category section">
    <h2 class="section__title">List Of Cars</h2>
    <div class="category__container">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<article class="category__card">
                        <h1 class="category__title">' . htmlspecialchars($row['Brand']) . '</h1>
                        <h3 class="category__subtitle">' . htmlspecialchars($row['Model']) . '</h3>
                        <img src="' . htmlspecialchars($row['Photo_URL']) . '" alt="' . htmlspecialchars($row['Model']) . '" class="category__img">
                        <h3 class="category__subtitle">' . htmlspecialchars($row['Year']) . '</h3>
                        <h3 class="category__price">$' . number_format($row['Price'], 2) . '</h3>
                        <a href="newcustom.php?car_id=' . $row['Car_Id'] . '" class="button">Customize</a>
                      </article>';
            }
        }
        $conn->close();
        ?>
    </div>
</section>

<!--==================== FOOTER ====================-->
<footer class="footer section">
    <div class="shapeX shape__big"></div>
    <div class="shapeX shape__small"></div>

    <div class="footer__container container grid">
        <div class="footer__content">
            <a href="#" class="footer__logo">
                <i class="ri-steering-line"></i> Drift
            </a>
            <p class="footer__description">
                We offer the best cars of <br>
                the most recognized brands in <br>
                the world.
            </p>
        </div>

        <div class="footer__content">
            <h3 class="footer__title">Company</h3>
            <ul class="footer__links">
                <li><a href="#" class="footer__links">About</a></li>
                <li><a href="#" class="footer__links">Cars</a></li>
            </ul>
        </div>

        <div class="footer__content">
            <h3 class="footer__title">Information</h3>
            <ul class="footer__links">
                <li><a href="#" class="footer__links">Contact us</a></li>
            </ul>
        </div>

        <div class="footer__content">
            <h3 class="footer__title">Follow us</h3>
            <ul class="footer__social">
                <a href="#" target="_blank" class="footer__social-link"><i class="ri-facebook-fill"></i></a>
                <a href="#" target="_blank" class="footer__social-link"><i class="ri-instagram-line"></i></a>
                <a href="#" target="_blank" class="footer__social-link"><i class="ri-twitter-line"></i></a>
            </ul>
        </div>
    </div>
    <span class="footer__copy">
        &#169; <a target="_blank" href="Layan.aln" class="footer__dev-link">Drift</a> All rights reserved
        <p id="date"></p>
    </span>
</footer>
<script src="home_js/main.js"></script>
</body>
</html>
