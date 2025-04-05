<?php
// Include database connection
include('connection.php');

include ('handleLogOut.php');


error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if car ID is provided in the URL
if (isset($_GET['id'])) {
    $car_id = intval($_GET['id']); 

    // Fetch car details
    $sql_car = "SELECT * FROM Car WHERE Car_Id = ?";
    $stmt_car = $conn->prepare($sql_car);
    $stmt_car->bind_param("i", $car_id);
    $stmt_car->execute();
    $result_car = $stmt_car->get_result();

    if ($result_car && $result_car->num_rows > 0) {
        $car = $result_car->fetch_assoc();

        // Fetch all photos for the car, ordered by Photo_Id ASC
        $sql_photos = "SELECT Photo_URL FROM Car_Photo WHERE Car_Id = ? ";
        $stmt_photos = $conn->prepare($sql_photos);
        $stmt_photos->bind_param("i", $car_id);
        $stmt_photos->execute();
        $result_photos = $stmt_photos->get_result();

        $photos = [];
        while ($row = $result_photos->fetch_assoc()) {
            $raw_path = $row['Photo_URL'];
            if(str_starts_with($row['Photo_URL'],'ProcutImages')){
              $photos[] =  $row['Photo_URL'];
            }
        }

        // Photos for carousel 
        $carousel_photos = array_slice($photos, 0, 3);

        // Photo for highlights 
        $highlights_photo = (count($photos) >= 4) ? $photos[3] : $photos[0];

        // Fetch report if car is used
        $report = null;
        if ($car['Condition'] === 'used') {
            $sql_report = "SELECT * FROM Report WHERE Car_Id = ?";
            $stmt_report = $conn->prepare($sql_report);
            $stmt_report->bind_param("i", $car_id);
            $stmt_report->execute();
            $result_report = $stmt_report->get_result();
            if ($result_report->num_rows > 0) {
                $report = $result_report->fetch_assoc();
            }
            $stmt_report->close();
        }

    } else {
        echo "<p>Car not found.</p>";
        exit;
    }

    // Close statements
    $stmt_car->close();
    $stmt_photos->close();
} else {
    echo "<p>No car ID provided.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DRIFT | <?php echo htmlspecialchars($car['Model']); ?></title>
    <link rel="stylesheet" href="ProductStyle.css" />
    <link rel="stylesheet" href="home_css/styles.css">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" class="favicon">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
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

    <!-- Main Content -->
    <div class="container">
        <!-- Overview Section -->
        <div class="info-wrapper">
            <div class="car-info">
                <div class="info-title">Overview</div>
                <p class="description"><?php echo htmlspecialchars($car['Description']); ?></p>
                <p class="price">Price: $<?php echo number_format($car['Price'], 2); ?></p>
            </div>
            <button class="place-order">Place Order</button>
        </div>

        <!-- Carousel Section -->
        <div class="car-display">
            <div class="carousel">
                <?php foreach ($carousel_photos as $index => $photo): ?>
                    <img class="carousel-image <?php echo $index === 0 ? 'active' : ''; ?>" 
                         src="<?php echo htmlspecialchars($photo); ?>" 
                         alt="<?php echo htmlspecialchars($car['Model']) . ' image ' . ($index + 1); ?>">
                <?php endforeach; ?>
            </div>
            <div class="carousel-nav">
                <?php foreach ($carousel_photos as $index => $photo): ?>
                    <button class="nav-btn <?php echo $index === 0 ? 'active' : ''; ?>" 
                            onclick="changeImage(<?php echo $index; ?>)">
                        <img class="thumbnail" 
                             src="<?php echo htmlspecialchars($photo); ?>" 
                             alt="Thumbnail <?php echo $index + 1; ?>">
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Vehicle Highlights Section -->
    <section class="vehicle-highlights-section">
        <div class="highlights-container">
            <div class="highlights-details">
                <h2>Vehicle Highlights</h2>
                <div class="price-banner">
                    Price: $<?php echo number_format($car['Price'], 2); ?>
                </div>
                <div class="highlights-grid">
                    <div class="highlight-item">
                        <span class="highlight-label">Year</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($car['Year']); ?></span>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-label">Condition</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($car['Condition']); ?></span>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-label">Gear</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($car['Gear']); ?></span>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-label">Interior</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($car['Interior_Color']); ?></span>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-label">Exterior</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($car['Exterior_Color']); ?></span>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-label">Engine</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($car['Engine_Type']); ?></span>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-label">Fuel</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($car['Fuel_Consumption']); ?></span>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-label">Brand</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($car['Brand']); ?></span>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-label">Speed</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($car['Speed']); ?> km/h</span>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-label">Model</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($car['Model']); ?></span>
                    </div>
                </div>
            </div>
            <div class="highlights-image">
                <img src="<?php echo htmlspecialchars($highlights_photo); ?>" 
                     alt="<?php echo htmlspecialchars($car['Model']); ?>">
            </div>
        </div>
    </section>

    <!-- Used Car Check Report Section -->
    <?php if ($car['Condition'] === 'used' && $report){ ?>
    <section class="used-car-report-section">
        <div class="report-container">
            <div class="report-details">
                <h2>Used Car Check Report</h2>
                <div class="highlights-grid">
                    <div class="highlight-item">
                        <span class="highlight-label">Report ID</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($report['Report_Id']); ?></span>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-label">Vehicle Interior</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($report['Vehical_Interior']); ?></span>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-label">Vehicle Exterior</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($report['Vehical_Exterior']); ?></span>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-label">Vehicle Chassis</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($report['Vehical_Chassis']); ?></span>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-label">Mechanical Condition</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($report['Mechanical_Condition']); ?></span>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-label">Additional Note</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($report['Additional_Note']); ?></span>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-label">Date</span>
                        <span class="highlight-value"><?php echo htmlspecialchars($report['Date']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>

    <!-- Footer -->
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
            © <a target="_blank" href="Layan.aln" class="footer__dev-link">Drift</a> All rights reserved
            <p id="date"></p>
        </span>
    </footer>

    <!-- JavaScript for Carousel -->
   
    <script>
        let currentImage = 0;
        const images = document.querySelectorAll('.carousel-image');
        const carouselNavButtons = document.querySelectorAll('.carousel-nav .nav-btn');

        function changeImage(index) {
            images[currentImage].classList.remove('active');
            carouselNavButtons[currentImage].classList.remove('active');
            currentImage = index;
            images[currentImage].classList.add('active');
            carouselNavButtons[currentImage].classList.add('active');
        }

        if (images.length > 1) {
            setInterval(() => {
                changeImage((currentImage + 1) % images.length);
            }, 9000);
        }

        document.querySelector('.place-order').addEventListener('click', () => {
            window.location.href = 'Status.php?car_id=<?php echo $car['Car_Id']; ?>';
        });
    </script>
    <script src="home_js/main.js"></script>
</body>
</html>