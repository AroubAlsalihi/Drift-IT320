<?php
include 'connection.php';

$brand = "Unknown";
$model = "Unknown";
$price = 0;

if (isset($_GET['car_id'])) {
    $carId = intval($_GET['car_id']);

    $stmt = $conn->prepare("SELECT Brand, Model, Price FROM Car WHERE Car_Id = ?");
    $stmt->bind_param("i", $carId);
    $stmt->execute();
    $stmt->bind_result($brand, $model, $price);
    $stmt->fetch();
    $stmt->close();
}
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
    <link rel="stylesheet" href="home css/swiper-bundle.css" />

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="home_css/styles.css">

    <script>
    const basePriceFromDB = <?php echo (int)$price; ?>;
    </script>

    <script src="customizescripts/script.js"></script>
    <script src="customizescripts/script-internal.js"></script>
   <!-- <script src="customizescripts/pricing.js"></script> -->


    <script>
  const basePriceFromDB = <?php echo json_encode((int)$price); ?>;
</script>


    <title>Drift | Car Customizer</title>


    <style>
        .customization {
            margin-top: 6rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .customization__container {
            display: flex;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            width: 90%;
            max-width: 1200px;
            gap: 20px;
        }

        .customization__main {
            flex: 2;
            text-align: center;
        }

        .customization__image img {
            width: 100%;
            border-radius: 10px;
        }

        .customization__thumbnails {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 5px;
        }

        .customization__thumbnails img {
    width: 100px;
    cursor: pointer;
    border-radius: 5px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
    transition: transform 0.3s ease-in-out, opacity 0.2s;
}

.customization__thumbnails img:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 10px rgba(255, 255, 255, 0.6);
}

.customization__thumbnails img:active {
    transform: scale(0.95);
    opacity: 0.8;
}


.customization__thumbnails img.selected {
    border: 3px solid white;
}

#mainCarImage {
    transition: opacity 0.5s ease-in-out;
}


        .customization__sidebar {
            flex: 1;
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
            color: white;
        }

        .customization__sidebar h4 {
            margin-bottom: 10px;
            font-size: 1.2rem;
        }

        .customization__colors {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .color-box {
            width: 40px;
            height: 40px;
            border-radius: 5px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border 0.2s;
        }

        .color-box:hover, .color-box.selected {
            border: 2px solid white;
        }

        .customization__wheels {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .customization__wheels img {
    width: 60px;
    cursor: pointer;
    border-radius: 5px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.customization__wheels img:hover {
    transform: scale(1.1);
    box-shadow: 0 3px 8px rgba(255, 255, 255, 0.8);
}

.customization__wheels img.selected {
    border: 3px solid red;
    transform: scale(1.15);
}

        .selected-wheel {
            border-bottom: 4px solid red;
        }

        .bottom-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            background: #444;
            padding: 15px;
            border-radius: 5px;
            width: 90%;
            max-width: 1200px;
            color: white;
            font-size: 1.2rem;
        }

.buttons button {
    padding: 12px 20px;
    font-size: 16px;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    font-weight: bold;
    transition: all 0.3s ease-in-out;
    outline: none;
}

.save {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.4);
}

.save:hover {
    background: linear-gradient(135deg, #0056b3, #004092);
    transform: scale(1.05);
}

.save:active {
    transform: scale(0.95);
    box-shadow: 0 2px 4px rgba(0, 123, 255, 0.5);
}

.buy {
    background: linear-gradient(135deg, #448663, #495057);
    color: white;
    box-shadow: 0 4px 8px rgba(108, 117, 125, 0.4);
}

.buy:hover {
    background: linear-gradient(135deg, #495057, #343a40);
    transform: scale(1.05);
}

.buy:active {
    transform: scale(0.95);
    box-shadow: 0 2px 4px rgba(108, 117, 125, 0.5);
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

.buttons button.pulsate {
    animation: pulse 0.3s ease-in-out;
}


       /*=============== INTERIOR IMAGE CONTAINER ===============*/
.customization__interior {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1); 
    border-radius: 10px; 
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5); 
    width: 100%; 
    max-width: 1200px; 
    margin: 20px auto; 
}

.customization__interior h4 {
    color: white;
    font-size: 1.5rem;
    margin-bottom: 10px;
}

.customization__interior img {
    width: 70%;
    height: auto;
    border-radius: 10px; 
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5); 
    transition: transform 0.3s ease-in-out; 
}

.customization__interior img:hover {
    transform: scale(1.05); 
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
    <!--==================== MAIN CONTENT (Car Customizer) ====================-->
    

    <section class="customization">
    <div style="text-align:left; padding: 3rem 2rem 1rem;">
    <h1 style="color: white; font-size: 2.5rem;">
        <?php echo htmlspecialchars("$brand $model"); ?>
    </h1>
</div>
        <div class="customization__container">
            <div class="customization__main">
                <div class="customization__image">
                    <img id="mainCarImage" src="img/2025-audi-q5.jpg" alt="Car Image">
                </div>
                <div class="customization__thumbnails">
                    <img src="img/Q5black-rim1.png" onclick="changeImage(this.src)" alt="Side View">
                    <img src="img/Q5black-rim1.png" onclick="changeImage(this.src)" alt="Side View">
                    <img src="img/Q5black1-rim1.png" onclick="changeImage(this.src)" alt="Front View">
                    <img src="img/Q5black2-rim1.png" onclick="changeImage(this.src)" alt="Rear View">
                </div>
            </div>
            <div class="customization__sidebar">
                <h4>Exterior Color</h4>
                <div class="customization__colors">
                    <div class="color-box" style="background: white;" data-image="img/Q5white.png"></div>
                    <div class="color-box" style="background: black;" data-image="img/Q5white1.png"></div>
                    <div class="color-box" style="background: blue;" data-image="img/Q5white2.png"></div>
                    <div class="color-box" style="background: green;" data-image="img/Q5white3.png"></div>
                </div>

                <h4>Interior Color</h4>
                <div class="customization__colors">
                    <div class="color-box selected" data-interior="black" style="background-color: black;"></div>
                    <div class="color-box" data-interior="brown" style="background-color: brown;"></div>
                    <div class="color-box" data-interior="gray" style="background-color: gray;"></div>
                </div>
                
                

                <h4>Wheels</h4>
                <div class="customization__wheels">
                    <img src="img/rim1.png" data-wheel="rim1" alt="Wheel 1">
                    <img src="img/rim2.png" data-wheel="rim2" alt="Wheel 2">
                    <img src="img/rim3.png" data-wheel="rim3" alt="Wheel 3">
                    <img src="img/rim4.png" data-wheel="rim4" alt="Wheel 4">

                </div>
            </div>
        </div>

        <div class="customization__interior">
            <h4>Interior View</h4>
            <img id="interiorImage" src="img/interior-black.jpeg" alt="Interior View">
        </div>

        <div class="bottom-bar">
        <h3>Build Price <strong>$<?php echo number_format($price); ?></strong></h3>

            <div class="buttons">
                <button class="save" id="saveButton" onclick="confirmSave()">Save</button>
                <button class="buy">Place Order</button>

                
            </div>
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
            &#169; <a target="_blank" href="Layan.aln" class="footer__dev-link">Drift</a> All rigths
            reserved
            <p id="date"></p>
        </span>
    </footer>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    const colorBoxes = document.querySelectorAll(".color-box[data-image]");
    const wheels = document.querySelectorAll(".customization__wheels img");
    const interiorColorOptions = document.querySelectorAll(".color-box[data-interior]");
    const thumbnails = document.querySelectorAll(".customization__thumbnails img"); // Add thumbnails
    const priceElement = document.querySelector(".bottom-bar strong");
    const buyBtn = document.querySelector(".buy");
    const saveButton = document.getElementById("saveButton");

    let selectedColor = "white";
    let selectedWheel = "rim1";
    let selectedInterior = "black";

    const pricing = {
        exterior: { white: 0, black: 1000, blue: 600, green: 1000 },
        interior: { black: 0, brown: 500, gray: 300 },
        wheels: { rim1: 0, rim2: 500, rim3: 800, rim4: 1000 }
    };

    function updatePrice() {
        const base = typeof basePriceFromDB !== 'undefined' ? basePriceFromDB : 0;
        const exterior = pricing.exterior[selectedColor] || 0;
        const interior = pricing.interior[selectedInterior] || 0;
        const wheel = pricing.wheels[selectedWheel] || 0;
        const total = base + exterior + interior + wheel;

        if (priceElement) {
            priceElement.textContent = `$${total.toLocaleString()}`;
        }

        return total;
    }

    function changeImage(thumbnail) {
        const mainImage = document.getElementById("mainCarImage");
        mainImage.style.opacity = 0; // Fade out
        setTimeout(() => {
            mainImage.src = thumbnail.src; // Update the source
            mainImage.style.opacity = 1; // Fade in
        }, 500); // Match the CSS transition duration
        thumbnails.forEach(t => t.classList.remove("selected")); // Remove 'selected' from all
        thumbnail.classList.add("selected"); // Add 'selected' to clicked thumbnail
    }

    // Thumbnail selection
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener("click", function () {
            changeImage(this);
        });
    });

    // Color selection
    colorBoxes.forEach(box => {
        box.addEventListener("click", function () {
            selectedColor = box.style.backgroundColor.toLowerCase().trim();
            colorBoxes.forEach(b => b.classList.remove("selected"));
            box.classList.add("selected");
            updatePrice();
        });
    });

    // Interior selection
    interiorColorOptions.forEach(colorBox => {
        colorBox.addEventListener("click", function () {
            selectedInterior = colorBox.dataset.interior;
            interiorColorOptions.forEach(b => b.classList.remove("selected"));
            colorBox.classList.add("selected");
            document.getElementById("interiorImage").src = `img/interior-${selectedInterior}.jpeg`;
            updatePrice();
        });
    });

    // Wheel selection
    wheels.forEach(wheel => {
        wheel.addEventListener("click", function () {
            selectedWheel = wheel.dataset.wheel || "rim1";
            wheels.forEach(w => w.classList.remove("selected"));
            wheel.classList.add("selected");
            updatePrice();
        });
    });

    // Buy button
    if (buyBtn) {
        buyBtn.addEventListener("click", function () {
            const price = updatePrice();
            const selectedImage = document.getElementById("mainCarImage").src;
            const url = `Status.php?car_id=2&image=${encodeURIComponent(selectedImage)}&color=${selectedColor}&interior=${selectedInterior}&wheel=${selectedWheel}&price=${price}`;
            window.location.href = url;
        });
    }

    updatePrice(); // Initial call after DOM is ready
});

function confirmSave() {
    const confirmation = confirm("Are you sure you want to make these changes?");
    if (confirmation) {
        alert("Your customization has been saved! ✅");
        console.log("Customization saved successfully.");
    } else {
        console.log("Customization changes were canceled.");
    }
}
</script>

</body>
</html>
