<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('connection.php');

// Get car IDs from form submission
$car1Id = $_POST['car1Id'] ?? null;
$car2Id = $_POST['car2Id'] ?? null;

if (!$car1Id || !$car2Id) {
    header("Location: Compare.php");
    exit;
}

// Function to get car details
function getCarDetails($conn, $carId) {
    $query = "SELECT c.*, 
             (SELECT Photo_URL FROM Car_Photo WHERE Car_Id = c.Car_Id LIMIT 1) as Photo_URL
             FROM Car c WHERE c.Car_Id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $carId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

$car1 = getCarDetails($conn, $car1Id);
$car2 = getCarDetails($conn, $car2Id);

if (!$car1 || !$car2) {
    header("Location: Compare.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home_css/styles.css">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <title>Comparison Results</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: #0a0a0a;
            color: #b0b0b0;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            position: relative;
            padding-top: 10rem;
        }
        
        .main-content {
            width: 100%;
            max-width: 1500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        h1 {
            font-size: 3.5rem;
            font-weight: 900;
            text-transform: uppercase;
            background: linear-gradient(45deg, #606060, #d0d0d0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 15px rgba(208, 208, 208, 0.5);
            margin-bottom: 40px;
            text-align: center;
        }
        
        .comparison-container {
            display: flex;
            justify-content: space-around;
            width: 100%;
            gap: 40px;
        }
        
        .car {
            position: relative;
            width: 48%;
            padding: 30px;
            background: linear-gradient(145deg, rgba(30, 30, 30, 0.95), rgba(20, 20, 20, 0.95));
            border-radius: 20px;
            border: 1px solid rgba(60, 60, 60, 0.6);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.7);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .car-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: #d0d0d0;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .car img {
            width: 80%;
            height: auto;
            filter: drop-shadow(0 15px 25px rgba(0, 0, 0, 0.8));
            margin-bottom: 30px;
            border-radius: 10px;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(20, 20, 20, 0.8);
            border-radius: 12px;
            overflow: hidden;
        }
        
        .details-table th, .details-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(60, 60, 60, 0.3);
        }
        
        .details-table th {
            color: #909090;
            font-weight: 600;
            background: rgba(30, 30, 30, 0.9);
        }
        
        .details-table td {
            color: #d0d0d0;
        }
        
        .details-table tr:last-child td {
            border-bottom: none;
        }
        
        .comparison-button {
            margin-top: 40px;
            text-align: center;
        }
        
        .comparison-button a {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(90deg, #303030, #404040);
            color: #d0d0d0;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .comparison-button a:hover {
            background: linear-gradient(90deg, #404040, #505050);
        }
    </style>
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
                        <a href="Compare.php" class="nav__link active-link">Compare</a>
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
    <div class="main-content">
        <h1>Comparison Results</h1>
        <div class="comparison-container">
            <!-- First Car -->
            <div class="car">
                <div class="car-title"><?php echo htmlspecialchars($car1['Model']); ?> (<?php echo htmlspecialchars($car1['Year']); ?>)</div>
                <img src="<?php echo htmlspecialchars($car1['Photo_URL']); ?>" alt="<?php echo htmlspecialchars($car1['Model']); ?>">
                <table class="details-table">
                    <tr>
                        <th>Brand</th>
                        <td><?php echo htmlspecialchars($car1['Brand']); ?></td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>$<?php echo number_format($car1['Price'], 2); ?></td>
                    </tr>
                    <tr>
                        <th>Condition</th>
                        <td><?php echo htmlspecialchars(ucfirst($car1['Condition'])); ?></td>
                    </tr>
                    <tr>
                        <th>Engine</th>
                        <td><?php echo htmlspecialchars($car1['Engine_Type']); ?></td>
                    </tr>
                    <tr>
                        <th>Top Speed</th>
                        <td><?php echo htmlspecialchars($car1['Speed']); ?> mph</td>
                    </tr>
                    <tr>
                        <th>Fuel Consumption</th>
                        <td><?php echo htmlspecialchars($car1['Fuel_Consumption']); ?></td>
                    </tr>
                </table>
            </div>
            
            <!-- Second Car -->
            <div class="car">
                <div class="car-title"><?php echo htmlspecialchars($car2['Model']); ?> (<?php echo htmlspecialchars($car2['Year']); ?>)</div>
                <img src="<?php echo htmlspecialchars($car2['Photo_URL']); ?>" alt="<?php echo htmlspecialchars($car2['Model']); ?>">
                <table class="details-table">
                    <tr>
                        <th>Brand</th>
                        <td><?php echo htmlspecialchars($car2['Brand']); ?></td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>$<?php echo number_format($car2['Price'], 2); ?></td>
                    </tr>
                    <tr>
                        <th>Condition</th>
                        <td><?php echo htmlspecialchars(ucfirst($car2['Condition'])); ?></td>
                    </tr>
                    <tr>
                        <th>Engine</th>
                        <td><?php echo htmlspecialchars($car2['Engine_Type']); ?></td>
                    </tr>
                    <tr>
                        <th>Top Speed</th>
                        <td><?php echo htmlspecialchars($car2['Speed']); ?> mph</td>
                    </tr>
                    <tr>
                        <th>Fuel Consumption</th>
                        <td><?php echo htmlspecialchars($car2['Fuel_Consumption']); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="comparison-button">
            <a href="Compare.php">Compare Other Cars</a>
        </div>
    </div>
    
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
                    <li><a href="#" class="footer__links"></a></li>
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
</body>
</html>