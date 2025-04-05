<?php 

include('connection.php');
include('handleLogOut.php');
// ... rest of your code
// Fetch all car models and years from database
$carQuery = "SELECT c.Car_Id, c.Model, c.Year, 
            (SELECT Photo_URL FROM Car_Photo WHERE Car_Id = c.Car_Id LIMIT 1) as Photo_URL
            FROM Car c
            ORDER BY c.Model, c.Year DESC";
$carResult = mysqli_query($conn, $carQuery);

if (!$carResult) {
    die("Database error: " . mysqli_error($conn));
}

$cars = [];
$carDetails = [];
while ($row = mysqli_fetch_assoc($carResult)) {
    $cars[$row['Model']][] = $row['Year'];
    $carDetails[$row['Model'].$row['Year']] = [
        'id' => $row['Car_Id'],
        'image' => $row['Photo_URL'] ?? 'img/addcar.png'
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home_css/styles.css">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" class="favicon">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <title>Car Comparison</title>
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
            align-items: center;
            justify-content: center;
            position: relative;
            padding-top: 10rem;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            position: fixed;
            top: 0;
            width: 100%;
            background: transparent;
            z-index: 999;
        }

        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(60, 60, 60, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
            animation: pulse 8s infinite;
            z-index: 0;
            opacity: 0.4;
        }

        h1 {
            font-size: 4.5rem;
            font-weight: 900;
            text-transform: uppercase;
            background: linear-gradient(45deg, #3a3a3a, #a0a0a0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 12px rgba(160, 160, 160, 0.4);
            margin-bottom: 120px;
            animation: glow 2.5s ease-in-out infinite alternate;
            z-index: 1;
            letter-spacing: 2px;
        }

        .comparison-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 85%;
            position: relative;
            z-index: 1;
        }

        .car {
            position: relative;
            width: 45%;
            padding: 25px;
            background: linear-gradient(135deg, rgba(15, 15, 15, 0.95), rgba(25, 25, 25, 0.95));
            border-radius: 20px;
            border: 1px solid rgba(40, 40, 40, 0.6);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.7), inset 0 0 10px rgba(60, 60, 60, 0.2);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .car:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.9), 0 0 15px rgba(60, 60, 60, 0.3);
        }

        .car img {
            width: 65%;
            height: auto;
            display: block;
            margin: 0 auto;
            cursor: pointer;
            filter: drop-shadow(0 12px 25px rgba(0, 0, 0, 0.8));
            transition: opacity 0.3s ease, transform 0.5s ease;
            border-radius: 10px;
            border: 1px solid rgba(50, 50, 50, 0.5);
        }

        .car img:hover {
            opacity: 0.9;
            transform: scale(1.08);
        }

        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(145deg, rgba(20, 20, 20, 0.95), rgba(30, 30, 30, 0.95));
            padding: 25px;
            border-radius: 20px;
            border: 1px solid rgba(40, 40, 40, 0.6);
            z-index: 1000;
            color: #c0c0c0;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.8), inset 0 0 10px rgba(60, 60, 60, 0.2);
            width: 300px;
        }

        .modal h2 {
            font-size: 1.8rem;
            color: #c0c0c0;
            text-shadow: 0 0 10px rgba(160, 160, 160, 0.4);
            margin-bottom: 20px;
        }

        .modal label {
            font-size: 1.2rem;
            color: #707070;
            text-transform: uppercase;
            margin-bottom: 10px;
            text-shadow: 0 0 6px rgba(112, 112, 112, 0.3);
        }

        .modal select {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border-radius: 30px;
            border: 1px solid rgba(40, 40, 40, 0.7);
            background: #151515;
            color: #c0c0c0;
            font-size: 1rem;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="%23707070" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
        }

        .modal select:hover, .modal select:focus {
            background-color: #252525;
            border-color: #707070;
            box-shadow: 0 0 12px rgba(112, 112, 112, 0.4);
            outline: none;
        }

        .modal button {
            padding: 12px 25px;
            background: linear-gradient(90deg, rgba(30, 30, 30, 0.9), rgba(50, 50, 50, 0.9));
            color: #c0c0c0;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1rem;
            text-transform: uppercase;
            font-weight: 600;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .modal button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(112, 112, 112, 0.5);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.4s ease, height 0.4s ease;
        }

        .modal button:hover::before {
            width: 200px;
            height: 200px;
        }
        
        .footer section {
            align-items: stretch;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .modal button:hover {
            color: #ffffff;
            box-shadow: 0 0 15px rgba(112, 112, 112, 0.5);
        }

        .home__button {
            z-index: 888;
            position: relative;
            width: 120px;
            height: 50px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1rem;
            color: #c0c0c0;
            font-weight: 600;
            text-transform: uppercase;
            text-decoration: none;
            background: linear-gradient(90deg, rgba(20, 20, 20, 0.8), rgba(40, 40, 40, 0.8));
            border: 1px solid rgba(40, 40, 40, 0.7);
            transition: all 0.4s ease;
            pointer-events: none;
            opacity: 0.4;
            overflow: hidden;
            position: relative;
            margin-top: 40px;
            margin-bottom: 60px;
        }

        .home__button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(112, 112, 112, 0.5);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.4s ease, height 0.4s ease;
        }

        .home__button.enabled {
            pointer-events: auto;
            opacity: 1;
            background: linear-gradient(90deg, rgba(30, 30, 30, 0.9), rgba(50, 50, 50, 0.9));
        }

        .home__button.enabled:hover::before {
            width: 200px;
            height: 200px;
        }

        .home__button.enabled:hover {
            color: #ffffff;
            box-shadow: 0 0 15px rgba(112, 112, 112, 0.5);
        }

        @keyframes glow {
            from { text-shadow: 0 0 10px rgba(160, 160, 160, 0.4); }
            to { text-shadow: 0 0 18px rgba(160, 160, 160, 0.6); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.03); }
        }
        
        .footer {
            width: 100%;
            padding: 20px 0;
            text-align: center;
            position: relative;
            margin-top: auto;
        }
    </style>
<a href="Compare.php"></a>
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

    <div class="particles"></div>

    <div class="main-content">
        <h1>Car Comparison</h1>
        <div class="comparison-container">
            <div class="car left">
                <img id="leftCarImg" src="img/addcar.png" alt="Left Car" onclick="openModal('left')">
            </div>
            <div class="car right">
                <img id="rightCarImg" src="img/addcar.png" alt="Right Car" onclick="openModal('right')">
            </div>
        </div>
    </div>

    <div id="carModal" class="modal">
        <h2 id="modalTitle"></h2>
        <label for="carType">Model:</label>
        <select id="carType" onchange="updateYearOptions()">
            <option value="">Select a model</option>
            <?php foreach ($cars as $model => $years): ?>
                <option value="<?php echo htmlspecialchars($model); ?>"><?php echo htmlspecialchars($model); ?></option>
            <?php endforeach; ?>
        </select>
        <label for="carYear">Year:</label>
        <select id="carYear"></select>
        <button onclick="submitCarInfo()">Submit</button>
    </div>
    
    <form id="compareForm" action="Compare2.php" method="post">
        <input type="hidden" id="car1Id" name="car1Id">
        <input type="hidden" id="car2Id" name="car2Id">
        <button type="submit" class="home__button" id="compareButton">Compare</button>
    </form>

    <script>
        let selectedCarSide = '';
        const carsData = <?php echo json_encode($cars); ?>;
        const carDetails = <?php echo json_encode($carDetails); ?>;

        function openModal(side) {
            selectedCarSide = side;
            document.getElementById('modalTitle').innerText = `Select ${side === 'left' ? 'Left' : 'Right'} Car`;
            document.getElementById('carModal').style.display = 'block';
            updateYearOptions();
        }

        function updateYearOptions() {
            const model = document.getElementById('carType').value;
            const yearSelect = document.getElementById('carYear');
            yearSelect.innerHTML = '';

            if (model && carsData[model]) {
                carsData[model].forEach(year => {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;
                    yearSelect.appendChild(option);
                });
            }
        }

        function submitCarInfo() {
            const model = document.getElementById('carType').value;
            const year = document.getElementById('carYear').value;

            if (!model || !year) {
                alert('Please select both model and year');
                return;
            }

            const carKey = model + year;
            const carInfo = carDetails[carKey];
            
            if (!carInfo) {
                alert('Car details not found');
                return;
            }

            const imgId = selectedCarSide === 'left' ? 'leftCarImg' : 'rightCarImg';
            document.getElementById(imgId).src = carInfo.image;

            if (selectedCarSide === 'left') {
                document.getElementById('car1Id').value = carInfo.id;
            } else {
                document.getElementById('car2Id').value = carInfo.id;
            }

            if (document.getElementById('car1Id').value && document.getElementById('car2Id').value) {
                document.getElementById('compareButton').classList.add('enabled');
            }

            document.getElementById('carModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target === document.getElementById('carModal')) {
                document.getElementById('carModal').style.display = 'none';
            }
        }
    </script>

    <script src="home_js/main.js"></script>
    
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