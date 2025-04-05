<?php
session_start();
include('handleLogOut.php');
include 'connection.php';

// Turn on error reporting:
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" class="favicon">

    <!--=============== REMIX ICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <!--=============== SWIPER CSS ===============-->
    <link rel="stylesheet" href="home_css/swiper-bundle.css" />

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="home_css/styles.css">

    <title>DRIFT | Home</title>
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
                        <a href="home.php" class="nav__link active-link">Home</a>
                    </li>
                    <li class="nav__item">
                        <a href="NewCar.php" class="nav__link">New Cars</a>
                    </li>
                    <li class="nav__item">
                        <a href="UsedCar.php" class="nav__link">Used Cars</a>
                    </li>
                    <li class="nav__item">
                        <a href="ListOfCars.php" class="nav__link">Customize</a>
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

    <!--************* MAIN CODE ****************-->
    <main class="main">
        <!--==================== HOME ====================-->
        <section class="home section" id="home">
            <div class="shapeX shape__big"></div>
            <div class="shapeX shape__small"></div>
            <div class="home__container container grid">
                <div class="home__data">
                    <h1 class="home__title">
                        Pick Your Best Car
                    </h1>
                    <h2 class="home__subtitle">
                        Porsche 911
                    </h2>
                    <h3 class="home__elec">
                        <i class="ri-flashlight-fill"></i>
                        RELENTLESS FORCE
                    </h3>
                </div>
                <div class="home__car">
                    <div class="home__car-data">
                        <div class="home__car-icon">
                            <i class="ri-temp-cold-line"></i>
                        </div>
                        <h2 class="home__car-number">3.0s</h2>
                        <h3 class="home__car-name">0-100 km/h</h3>
                    </div>
                    <div class="home__car-data">
                        <div class="home__car-icon">
                            <i class="ri-dashboard-2-line"></i>
                        </div>
                        <h2 class="home__car-number">419</h2>
                        <h3 class="home__car-name">POWER (KW)</h3>
                    </div>
                    <div class="home__car-data">
                        <div class="home__car-icon">
                            <i class="ri-flashlight-fill"></i>
                        </div>
                        <h2 class="home__car-number">325 km/h</h2>
                        <h3 class="home__car-name">MAX. SPEED</h3>
                    </div>
                </div>
                <a href="#" class="home__button">START</a>
                <div class="slider__bg">
                    <div class="slider__bg-navBtn active"></div>
                    <div class="slider__bg-navBtn"></div>
                    <div class="slider__bg-navBtn"></div>
                    <div class="slider__bg-navBtn"></div>
                    <div class="slider__bg-navBtn"></div>
                </div>
            </div>
            <div class="home__social">
                <a href="#" class="home__social-icon"><i class="ri-facebook-fill"></i></a>
                <a href="#" class="home__social-icon"><i class="ri-instagram-fill"></i></a>
                <a href="#" class="home__social-icon"><i class="ri-twitter-fill"></i></a>
            </div>
        </section>

        <!--==================== GALLERY BANNER ====================-->
        <section class="gallery section">
            <div class="gallery__container container grid">
                <video class="video__slide active" src="video/vid_1.mp4" autoplay muted loop></video>
                <video class="video__slide" src="video/vid_4.mp4" autoplay muted loop></video>
                <video class="video__slide" src="video/vid_3.mp4" autoplay muted loop></video>
                <video class="video__slide" src="video/vid_2.mp4" autoplay muted loop></video>
                <video class="video__slide" src="video/vid_5.mp4" autoplay muted loop></video>
            </div>
        </section>

        <!--==================== ABOUT ====================-->
        <section class="about section" id="about">
            <div class="about__container container grid">
                <div class="about__group">
                    <img src="img/about.png" alt="" class="about__img">
                    <div class="about__card">
                        <h3 class="about__card-title">2.500+</h3>
                        <p class="about__card-description">Supercharges placed along popular routes</p>
                    </div>
                </div>
                <div class="about__data">
                    <h2 class="section__title about__title">Machines With <br> Future Technology</h2>
                    <p class="about__description">
                        See the future with high-performance cars produced by
                        renowned brands. They feature futuristic builds and designs with
                        new and innovative platforms that last a long time.
                    </p>
                    <a href="#" class="button">Know More</a>
                </div>
            </div>
        </section>

        <!--==================== POPULAR ====================-->
<section class="popular section" id="popular">
    <h2 class="section__title">Choose Your Electric Car <br> of Porsche Brand</h2>
    <div class="popular__container container swiper">
        <div class="swiper-wrapper">
            <?php
            // Pull Porsche cars dynamically, including Condition
            $sql = "
            SELECT 
                c.Car_Id, 
                c.Brand, 
                c.Model, 
                c.Speed, 
                c.Price, 
                `c`.`Condition`, 
                cp.Photo_URL
            FROM car c
            JOIN (
                SELECT Car_Id, MIN(Photo_URL) AS Photo_URL 
                FROM car_photo 
                GROUP BY Car_Id
            ) cp ON c.Car_Id = cp.Car_Id
            WHERE c.Brand = 'Porsche'
        ";
        
                    
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Determine URL based on Condition
                    $redirectUrl = ($row['Condition'] === "customizable") 
                        ? "newcustom.php?id=" . $row['Car_Id'] 
                        : "ProductPage.php?id=" . $row['Car_Id'];
            ?>
            <article class="popular__card swiper-slide">
                <div class="shapeX shape__smaller"></div>
                <h1 class="popular__title"><?php echo htmlspecialchars($row['Brand']); ?></h1>
                <h3 class="popular__subtitle"><?php echo htmlspecialchars($row['Model']); ?></h3>

                <!-- Wrap image in anchor tag with conditional redirect -->
                <a href="<?php echo $redirectUrl; ?>">
                    <img src="<?php echo htmlspecialchars($row['Photo_URL']); ?>"
                         alt="<?php echo htmlspecialchars($row['Model']); ?>"
                         class="popular__img">
                </a>

                <div class="popular__data">
                    <div class="popular__data-group">
                        <i class="ri-dashboard-2-line"></i> 3.7 sec
                    </div>
                    <div class="popular__data-group">
                        <i class="ri-exchange-funds-line"></i> <?php echo htmlspecialchars($row['Speed']); ?> Km/h
                    </div>
                    <div class="popular__data-group">
                        <i class="ri-charging-pile-2-line"></i> Electric
                    </div>
                </div>
                <h3 class="popular__price">
                    $<?php echo number_format($row['Price']); ?>
                </h3>
                <button class="button popular__button">
                    <i class="ri-shopping-bag-2-line"></i>
                </button>
            </article>
            <?php
                }
            } else {
                echo "<p>No Porsche cars found.</p>";
            }
            ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>
        <!--==================== FEATURES ====================-->
        <section class="features section">
            <h2 class="section__title">More Features</h2>
            <div class="features__container container grid">
                <div class="features__group">
                    <img src="img/features.png" alt="" class="features__img">
                    <div class="features__card features__card-1">
                        <h3 class="features__card-title">800v</h3>
                        <p class="features__card-description">Turbo <br> Chargin</p>
                    </div>
                    <div class="features__card features__card-2">
                        <h3 class="features__card-title">350</h3>
                        <p class="features__card-description">Km <br> Range</p>
                    </div>
                    <div class="features__card features__card-3">
                        <h3 class="features__card-title">480</h3>
                        <p class="features__card-description">Km <br> Travel</p>
                    </div>
                </div>
            </div>
            <img src="img/map.svg" alt="" class="features__map">
        </section>
                    
     <!--==================== FEATURED ====================-->
    <section class="featured section" id="featured">
        <h2 class="section__title">Featured Luxury Cars</h2>
        <div class="featured__container container">
            <ul class="featured__filters">
                <li>
                    <button class="featured__item active-featured" data-filter="all">
                        <span>All</span>
                    </button>
                </li>
                <li>
                    <button class="featured__item" data-filter=".tesla">
                        <img src="img/logo3.png" alt="">
                    </button>
                </li>
                <li>
                    <button class="featured__item" data-filter=".audi">
                        <img src="img/logo2.png" alt="">
                    </button>
                </li>
                <li>
                    <button class="featured__item" data-filter=".porsche">
                        <img src="img/logo1.png" alt="">
                    </button>
                </li>
            </ul>

            <div class="featured__content grid">
                <?php
                // Fetch all cars and check 
                $sqlAllCars = "
                    SELECT
                        c.Car_Id,
                        c.Brand,
                        c.Model,
                        c.Speed,
                        c.Price,
                        c.Condition,
                        MIN(cp.Photo_URL) AS Photo_URL
                    FROM car c
                    JOIN car_photo cp ON c.Car_Id = cp.Car_Id
                    GROUP BY
                        c.Car_Id,
                        c.Brand,
                        c.Model,
                        c.Speed,
                        c.Price,
                        c.Condition
                ";
                $resultAll = $conn->query($sqlAllCars);
                if ($resultAll && $resultAll->num_rows > 0) {
                    while ($car = $resultAll->fetch_assoc()) {
                        // Convert brand to lowercase for the MixItUp filter class
                        $brandClass = strtolower($car['Brand']);
                        // Determine URL based on Condition
                        $redirectUrl = ($car['Condition'] === "customizable") 
                            ? "newcustom.php?id=" . $car['Car_Id'] 
                            : "ProductPage.php?id=" . $car['Car_Id'];
                ?>
                <article class="featured__card mix <?php echo $brandClass; ?>" 
                        data-id="<?php echo $car['Car_Id']; ?>"
                        data-url="<?php echo $redirectUrl; ?>">
                    <div class="shape shape__smaller"></div>

                    <h1 class="featured__title"><?php echo htmlspecialchars($car['Brand']); ?></h1>
                    <h3 class="featured__subtitle"><?php echo htmlspecialchars($car['Model']); ?></h3>

                   
                    <a href="<?php echo $redirectUrl; ?>">
                        <img src="<?php echo htmlspecialchars($car['Photo_URL']); ?>"
                            alt="<?php echo htmlspecialchars($car['Model']); ?>"
                            class="featured__img">
                    </a>

                    <h3 class="featured__price">
                        $<?php echo number_format($car['Price']); ?>
                    </h3>
                    <button class="button featured__button">
                        <i class="ri-shopping-bag-2-line"></i>
                    </button>
                </article>
                <?php
                    }
                } else {
                    echo "<p>No cars found in the database.</p>";
                }
                ?>
            </div>
        </div>
    </section>

        <!--==================== OFFER ====================-->
        <section class="offer section">
            <div class="offer__container container grid">
                <img src="img/offer-bg.png" alt="" class="offer__bg">
                <div class="offer__data">
                    <h2 class="section__title offer__title">
                        Do You Want To  <br> Sell Your Car?
                    </h2>
                    <p class="offer__description">
                        Looking to sell your car hassle-free? Get the best value for your vehicle with a 
                        smooth and seamless selling process. Contact us today, and let us take care of everything for you!
                    </p>
                    <a href="mailto:staff@gmail.com" class="button">Contact Us</a>
                </div>
                <img src="img/offer.png" alt="" class="offer__img">
            </div>
        </section>

        <!--==================== LOGOS ====================-->
        <section class="logos section">
            <div class="logos__container container grid">
                <div class="logos__content">
                    <img src="img/logo1.png" alt="" class="logos__img">
                </div>
                <div class="logos__content">
                    <img src="img/logo6.png" alt="" class="logos__img">
                </div>
                <div class="logos__content">
                    <img src="img/logo5.png" alt="" class="logos__img">
                </div>
                <div class="logos__content">
                    <img src="img/logo4.png" alt="" class="logos__img">
                </div>
                <div class="logos__content">
                    <img src="img/logo3.png" alt="" class="logos__img">
                </div>
                <div class="logos__content">
                    <img src="img/logo2.png" alt="" class="logos__img">
                </div>
            </div>
        </section>
    </main>

        <!--==================== REVIEWS SECTION ====================-->
        <section class="reviews section">
            <h2 class="section__title">Customer Reviews</h2>
            <div class="reviews__container container">
                <?php
                // Query to fetch reviews with customer names
                $sql = "SELECT r.Comment, c.Name 
                        FROM Review r
                        JOIN `Order` o ON r.Order_Id = o.Order_Id
                        JOIN Customer c ON o.Customer_Id = c.Customer_Id
                        ORDER BY r.Review_Id DESC 
                        LIMIT 3"; // Fetch the 3 most recent reviews

                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $comment = htmlspecialchars($row['Comment']);
                        $author = htmlspecialchars($row['Name']);
                ?>
                <div class="review__card">
                    <p class="review__text">"<?php echo $comment; ?>"</p>
                    <h3 class="review__author">— <?php echo $author; ?></h3>
                </div>
                <?php
                    }
                    $result->free(); // Free the result set
                } else {
                    // Fallback if no reviews exist
                ?>
                <div class="review__card">
                    <p class="review__text">"No reviews yet. Be the first to share your experience!"</p>
                    <h3 class="review__author">— Drift Team</h3>
                </div>
                <?php
                }
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
                    <i class="ri-steering-line"></i>
                    Drift
                </a>
                <p class="footer__description">
                    We offer the best cars of <br>
                    the most recognized brands in <br>
                    the world.
                </p>
            </div>
            <div class="footer__content">
                <h3 class="footer__title">Company </h3>
                <ul class="footer__links">
                    <li>
                        <a href="#" class="footer__links">About</a>
                    </li>
                    <li>
                        <a href="#" class="footer__links">Cars</a>
                    </li>
                    <li>
                        <a href="#" class="footer__links"></a>
                    </li>
                </ul>
            </div>
            <div class="footer__content">
                <h3 class="footer__title">Information </h3>
                <ul class="footer__links">
                    <li>
                        <a href="#" class="footer__links">Contact us</a>
                    </li>
                </ul>
            </div>
            <div class="footer__content">
                <h3 class="footer__title"> Follow us </h3>
                <ul class="footer__social">
                    <a href="" target="_blank" class="footer__social-link">
                        <i class="ri-facebook-fill"></i>
                    </a>
                    <a href="" target="_blank" class="footer__social-link">
                        <i class="ri-instagram-line"></i>
                    </a>
                    <a href="" target="_blank" class="footer__social-link">
                        <i class="ri-twitter-line"></i>
                    </a>
                </ul>
            </div>
        </div>
        <span class="footer__copy">
            &#169; <a target="_blank" href="Layan.aln" class="footer__dev-link">Drift</a>
            All rights reserved
            <p id="date"></p>
        </span>
    </footer>

    <!--========== SCROLL UP ==========-->
    <a href="" class="scrollup" id="scroll-up">
        <i class="ri-arrow-up-s-line"></i>
    </a>

    <!--=============== SCROLL REVEAL - PAGE ANIMATION ===============-->
    <script src="home_js/scrollrevealAnimation.min.js"></script>
    <!--=============== SWIPER JS - PRODUCT SLIDER ===============-->
    <script src="home_js/swiper-bundle.min.js"></script>
    <!--=============== MIXITUP JS - FILTER PRODUCT ===============-->
    <script src="home_js/mixitup.min.js"></script>
    <!--=============== MAIN JS ===============-->
    <script src="home_js/main.js"></script>
</body>
</html>