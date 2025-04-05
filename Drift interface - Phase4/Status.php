<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to access this page.'); window.location.href = 'signup1.php';</script>";
    exit();
}

include('handleLogOut.php');
include 'connection.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Handle order cancellation
if (isset($_POST['cancel_order']) && $_POST['cancel_order'] == '1') {
    if (isset($_SESSION['last_order_id'])) {
        $orderId = intval($_SESSION['last_order_id']);
        $checkSql = "SELECT Status FROM `Order` WHERE Order_Id = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("i", $orderId);
        $checkStmt->execute();
        $checkStmt->bind_result($status);
        $hasResult = $checkStmt->fetch();
        $checkStmt->close();

        error_log("Cancel attempt for Order ID: $orderId, Raw Status: " . ($hasResult ? "'$status'" : 'NULL') . ", Time: " . date('Y-m-d H:i:s'));

        if ($hasResult && strcasecmp($status, "Pending") === 0) {
            $deleteSql = "DELETE FROM `Order` WHERE Order_Id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $orderId);
            if ($deleteStmt->execute()) {
                unset($_SESSION['last_order_id']);
                error_log("Order ID: $orderId canceled successfully");
                echo json_encode(['success' => true]);
            } else {
                error_log("Failed to delete Order ID: $orderId - " . $conn->error);
                echo json_encode(['success' => false, 'error' => $conn->error]);
            }
            $deleteStmt->close();
        } else {
            $errorMsg = $hasResult ? "Order cannot be canceled because it is in '$status' status" : "Order ID $orderId not found";
            error_log("Cancellation rejected: $errorMsg");
            echo json_encode(['success' => false, 'error' => $errorMsg]);
        }
    } else {
        error_log("No order to cancel - last_order_id not set");
        echo json_encode(['success' => false, 'error' => 'No order to cancel']);
    }
    exit();
}

// Create order
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['car_id']) && !isset($_POST['submitReview']) && !isset($_POST['update_status']) && !isset($_POST['update_location'])) {
    $carId = intval($_GET['car_id']);
    $customerId = intval($_SESSION['user_id']);
    $orderStatus = "Pending";
    $location = null;
    $date = date('Y-m-d');
    $time = date('H:i:s');

    $checkSql = "SELECT Order_Id, Status FROM `Order` WHERE Car_Id = ? AND Customer_Id = ? AND Status = 'Pending'";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $carId, $customerId);
    $checkStmt->execute();
    $checkStmt->bind_result($existingOrderId, $existingStatus);
    $hasPendingOrder = $checkStmt->fetch();
    $checkStmt->close();

    if (!$hasPendingOrder) {
        $orderSql = "INSERT INTO `Order` (Car_Id, Customer_Id, Status, Location, Date, Time) VALUES (?, ?, ?, ?, ?, ?)";
        $orderStmt = $conn->prepare($orderSql);
        $orderStmt->bind_param("iissss", $carId, $customerId, $orderStatus, $location, $date, $time);
        
        if ($orderStmt->execute()) {
            $_SESSION['last_order_id'] = $conn->insert_id;
            error_log("Order created with ID: " . $_SESSION['last_order_id']);
            echo "<script>console.log('Order created with ID: " . $_SESSION['last_order_id'] . "');</script>";
        } else {
            error_log("Failed to create order: " . $conn->error);
            echo "<script>alert('Failed to create order: " . addslashes($conn->error) . "'); console.log('Order creation error: " . addslashes($conn->error) . "');</script>";
            unset($_SESSION['last_order_id']);
        }
        $orderStmt->close();
    } else {
        $_SESSION['last_order_id'] = $existingOrderId;
        error_log("Using existing pending order with ID: " . $_SESSION['last_order_id']);
        echo "<script>console.log('Using existing pending order with ID: " . $_SESSION['last_order_id'] . "');</script>";
    }
} else if (isset($_GET['car_id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("Skipping order creation for Car_Id: " . $_GET['car_id'] . " on POST request");
}

// Handle review submission
if (isset($_POST['submitReview'])) {
    if (!isset($_SESSION['last_order_id']) || empty($_SESSION['last_order_id'])) {
        echo "<script>alert('Error: No valid order found. Please ensure an order is placed first.');</script>";
    } else {
        $orderId = intval($_SESSION['last_order_id']);
        $comment = $_POST['comment'] ?? '';

        $checkSql = "SELECT COUNT(*) FROM `Order` WHERE Order_Id = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("i", $orderId);
        $checkStmt->execute();
        $checkStmt->bind_result($orderExists);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($orderExists > 0) {
            $sql = "INSERT INTO Review (Order_Id, Comment) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $orderId, $comment);

            if ($stmt->execute()) {
                echo "<script>alert('Thank you! Your review has been added for Order ID: $orderId.'); window.location.href = 'home.php'; </script>";
            } else {
                error_log("Failed to insert review for Order ID: $orderId - " . $conn->error);
                echo "<script>alert('Failed to submit review: " . addslashes($conn->error) . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Error: Order ID $orderId does not exist in the Order table. Please place a valid order first.'); console.log('Invalid Order ID: $orderId');</script>";
            unset($_SESSION['last_order_id']);
        }
    }
}

// Fetch current order status
$orderStatus = "Pending"; // Default status
if (isset($_SESSION['last_order_id'])) {
    $orderId = intval($_SESSION['last_order_id']);
    $statusSql = "SELECT Status FROM `Order` WHERE Order_Id = ?";
    $statusStmt = $conn->prepare($statusSql);
    $statusStmt->bind_param("i", $orderId);
    $statusStmt->execute();
    $statusStmt->bind_result($orderStatus);
    $statusStmt->fetch();
    $statusStmt->close();
} else if (isset($_GET['car_id']) && !isset($_SESSION['last_order_id'])) {
    $orderStatus = "Pending";
}

$engine = "Not available";
$speed = "Not available";
$brand = "Unknown";
$model = "Unknown";
$price = 0;
$image = "img/default-car.png";

$initialPickupDate = date('Y-m-d', strtotime('+7 days'));

if (isset($_GET['car_id'])) {
    $carId = intval($_GET['car_id']);
    $stmt = $conn->prepare("SELECT c.Engine_Type, c.Speed, c.Brand, c.Model, c.Price, cp.Photo_URL FROM Car c LEFT JOIN Car_Photo cp ON c.Car_Id = cp.Car_Id WHERE c.Car_Id = ? LIMIT 1");
    $stmt->bind_param("i", $carId);
    $stmt->execute();
    $stmt->bind_result($engine, $speed, $brand, $model, $price, $dbImage);
    $stmt->fetch();
    $stmt->close();
    if (!isset($_GET['image'])) {
        $image = $dbImage ?: $image;
    } else {
        $image = htmlspecialchars($_GET['image']);
    }
}

// Handle status update after timer 
if (isset($_POST['update_status']) && isset($_SESSION['last_order_id'])) {
    $orderId = intval($_SESSION['last_order_id']);
    $status = "Confirmed";
    $sql = "UPDATE `Order` SET Status = ? WHERE Order_Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $orderId);
    if ($stmt->execute()) {
        error_log("Status updated to Confirmed for Order ID: $orderId, Time: " . date('Y-m-d H:i:s'));
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    $stmt->close();
    exit();
}

// Handle location and date update 
if (isset($_POST['update_location']) && isset($_SESSION['last_order_id'])) {
    $orderId = intval($_SESSION['last_order_id']);
    $location = $_POST['location'] ?? '';
    $date = $_POST['date'] ?? $initialPickupDate;
    $sql = "UPDATE `Order` SET Location = ?, Date = ? WHERE Order_Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $location, $date, $orderId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    $stmt->close();
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="home_css/styles.css">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" class="favicon">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <title>Drift | Premium Car Sales</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap");

        :root {
            --drift-black: #0a0a0a;
            --drift-white: #ffffff;
            --drift-gray-dark: #2e2e2e;
            --drift-gray-medium: #707070;
            --drift-gray-light: #c0c0c0;
            --drift-max-width: 1200px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .order-status-container {
            width: 100%;
            text-align: center;
            padding: 0.5rem 0;
            font-size: 1.2em;
            font-weight: bold;
            color: var(--drift-white);
        }
        #order-status {
            text-transform: uppercase;
        }

        .drift-section__container {
            max-width: var(--drift-max-width);
            margin: auto;
            padding: 5rem 1rem;
        }

        .drift-section__header {
            margin-bottom: 1rem;
            font-size: 2.5rem;
            font-weight: 600;
            color: var(--drift-white);
            line-height: 3.25rem;
            text-align: center;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        }

        .drift-section__description {
            color: var(--drift-gray-medium);
            text-align: center;
            line-height: 1.5rem;
        }

        .drift-btn {
            padding: 0.75rem 1.5rem;
            outline: none;
            border: 2px solid var(--drift-gray-light);
            font-size: 1rem;
            color: var(--drift-gray-light);
            background: transparent;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .drift-btn:hover {
            background: var(--drift-gray-light);
            color: var(--drift-black);
            box-shadow: 0 0 15px rgba(192, 192, 192, 0.5);
        }

        .drift-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.4s ease, height 0.4s ease;
        }

        .drift-btn:hover::before {
            width: 200px;
            height: 200px;
        }

        .drift-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .drift-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        img {
            display: flex;
            width: 100%;
        }

        a {
            text-decoration: none;
            transition: 0.3s;
        }

        ul {
            list-style: none;
        }

        html,
        body {
            scroll-behavior: smooth;
            padding-top: 5rem;
        }

        body {
            font-family: "Roboto", sans-serif;
            background: var(--drift-black);
        }

        .drift-header__container {
            padding-top: 5rem;
            display: grid;
        }

        .drift-header__image {
            position: relative;
            isolation: isolate;
            overflow: hidden;
        }

        .drift-header__image::before {
            position: absolute;
            content: "";
            height: 100%;
            width: 100%;
            top: 0;
            left: 5rem;
            background: var(--drift-gray-dark);
            opacity: 0.5;
            border-top-left-radius: 2rem;
            z-index: -1;
        }

        .drift-header__content {
            padding-block: 2rem 5rem;
            padding-inline: 1rem;
        }

        .drift-header__content h2 {
            width: fit-content;
            margin-inline: auto;
            margin-bottom: 1rem;
            padding: 0.5rem 1.5rem;
            font-size: 1rem;
            font-weight: 500;
            color: var(--drift-white);
            background: var(--drift-gray-dark);
            border-radius: 5rem;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }

        .drift-header__content h1 {
            margin-bottom: 2rem;
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--drift-white);
            text-align: center;
            text-shadow: 0 0 12px rgba(255, 255, 255, 0.4);
        }

        .drift-header__form form {
            max-width: 900px;
            margin-inline: auto;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            background: var(--drift-gray-dark);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);
            border-radius: 1rem;
        }

        .drift-header__form .drift-input__group {
            flex: 1 0 170px;
            display: grid;
            gap: 10px;
        }

        .drift-header__form label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--drift-gray-light);
        }

        .drift-header__form input {
            width: 100%;
            outline: none;
            border: none;
            font-size: 1rem;
            color: var(--drift-gray-light);
            background: transparent;
        }

        .drift-header__form input::placeholder {
            color: var(--drift-gray-medium);
        }

        .drift-header__form select {
            width: 100%;
            padding: 0.5rem;
            font-size: 1rem;
            color: var(--drift-gray-light);
            background: var(--drift-gray-dark);
            border: 1px solid var(--drift-gray-light);
            border-radius: 0.5rem;
            appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg fill='%23c0c0c0' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 10px center;
        }

        .drift-header__form select:focus {
            outline: none;
            border-color: var(--drift-white);
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.3);
        }

        .drift-header__form .drift-btn {
            padding: 15px 17px;
        }

        .drift-about__container .drift-section__description {
            max-width: 600px;
            margin-inline: auto;
            margin-bottom: 4rem;
        }

        .drift-about__grid {
            display: grid;
            gap: 2rem 1rem;
            justify-content: center;
        }

        .drift-about__card {
            max-width: 300px;
            margin-inline: auto;
            text-align: center;
            background: var(--drift-gray-dark);
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        .drift-about__card span {
            display: inline-block;
            margin-bottom: 2rem;
            font-size: 2.5rem;
            padding: 16px 20px;
            border-radius: 1.25rem;
            color: var(--drift-white);
            background: var(--drift-gray-medium);
        }

        .drift-about__card h4 {
            margin-bottom: 10px;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--drift-white);
        }

        .drift-about__card p {
            color: var(--drift-gray-medium);
            line-height: 1.5rem;
        }

        .drift-about__card label {
            display: block;
            margin-top: 1rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--drift-white);
        }

        .drift-about__card input,
        .drift-about__card select {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.5rem;
            background: var(--drift-gray-medium);
            border: none;
            border-radius: 0.5rem;
            color: var(--drift-white);
        }

        .drift-about__card input[type="text"],
        .drift-about__card select {
            font-size: 1rem;
        }

        .drift-about__card input[type="date"] {
            font-size: 1rem;
            color: var(--drift-gray-light);
        }

        .drift-about__card input::placeholder,
        .drift-about__card select option {
            color: var(--drift-white);
        }

        .drift-footer {
            background: var(--drift-gray-dark);
        }

        .drift-footer__container {
            display: grid;
            gap: 4rem 2rem;
        }

        .drift-footer__logo {
            margin-bottom: 2rem;
        }

        .drift-footer__logo img {
            max-width: 45px;
        }

        .drift-footer__logo span {
            font-size: 1.5rem;
            color: var(--drift-white);
        }

        .drift-footer__col p {
            margin-bottom: 2rem;
            color: var(--drift-gray-light);
            line-height: 1.5rem;
        }

        .drift-footer__socials {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .drift-footer__socials a {
            padding: 6px 8px;
            font-size: 1.125rem;
            color: var(--drift-gray-dark);
            background: var(--drift-white);
            border-radius: 100%;
        }

        .drift-footer__socials a:hover {
            color: var(--drift-white);
            background: var(--drift-gray-medium);
        }

        .drift-footer__col h4 {
            margin-bottom: 2rem;
            font-size: 1.2rem;
            font-weight: 500;
            color: var(--drift-white);
        }

        .drift-footer__links {
            display: grid;
            gap: 1rem;
        }

        .drift-footer__links a {
            color: var(--drift-gray-light);
        }

        .drift-footer__links a:hover {
            color: var(--drift-white);
        }

        .drift-footer__links span {
            display: inline-block;
            margin-right: 10px;
            padding: 4px 6px;
            border: 1px solid var(--drift-gray-light);
            border-radius: 100%;
        }

        .drift-footer__links a:hover span {
            border-color: var(--drift-white);
        }

        .drift-footer__bar {
            padding: 1rem;
            font-size: 0.9rem;
            color: var(--drift-gray-light);
            text-align: center;
        }

        @media (width > 540px) {
            .drift-about__grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (width > 768px) {
            .drift-header__container {
                padding-top: 0;
                grid-template-columns: minmax(0, 1fr) minmax(0, calc(var(--drift-max-width) / 2)) minmax(0, calc(var(--drift-max-width) / 2)) minmax(0, 1fr);
            }

            .drift-header__image {
                grid-area: 1/3/2/5;
                height: 100%;
            }

            .drift-header__image img {
                padding-bottom: 5rem;
                position: absolute;
                top: 50%;
                left: 0;
                transform: translateY(-50%);
                width: unset;
                height: 100%;
            }

            .drift-header__content {
                padding-block: 2rem 10rem;
                grid-area: 1/2/2/3;
            }

            .drift-header__content h2 {
                margin-inline-start: unset;
            }

            .drift-header__content :is(h1, .drift-section__description) {
                text-align: left;
            }

            .drift-header__form form {
                padding: 1.5rem;
                transform: translateY(-50%);
            }

            .drift-about__container {
                padding-top: 3rem;
            }

            .drift-about__grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .drift-line-container {
            position: relative;
            width: 100%;
            max-width: 900px;
            height: 12px;
            margin: 1rem auto;
            background: linear-gradient(90deg, var(--drift-gray-dark), var(--drift-gray-medium));
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5), inset 0 0 5px rgba(255, 255, 255, 0.1);
            border: 1px solid var(--drift-gray-light);
        }

        .drift-line {
            position: absolute;
            left: 0;
            width: 0%;
            height: 100%;
            background: linear-gradient(90deg, var(--drift-gray-light), #a0a0ff);
            border-radius: 6px;
            box-shadow: 0 0 15px rgba(192, 192, 192, 0.7);
            animation: drift-moveLine 10s linear forwards;
            transition: width 0.1s ease-out;
        }

        @keyframes drift-moveLine {
            from { width: 0%; }
            to { width: 100%; }
        }

        .drift-about__card .edit-btn,
        .drift-about__card .save-btn,
        .drift-about__card .cancel-btn {
            margin-top: 1rem;
            margin-right: 0.5rem;
        }

        .order-summary {
            margin-top: 2rem;
            padding: 1rem;
            background: var(--drift-gray-dark);
            border-radius: 0.5rem;
            color: var(--drift-white);
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
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="handleLogOut.php?logout=true" class="auth__link">Log Out</a>
                <?php else: ?>
                    <a href="signup1.php" class="auth__link">Sign Up / Log In</a>
                <?php endif; ?>
                <div class="nav__close" id="nav-close">
                    <i class="ri-close-line"></i>
                </div>
            </div>
            <div class="nav__toggle" id="nav-toggle">
                <i class="ri-menu-line"></i>
            </div>
        </nav>
    </header>

    <div class="drift-header__container" id="drift-home">
        <div class="drift-header__image">
            <img id="mainCarImg" src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($brand . ' ' . $model); ?>">
        </div>
        <div class="drift-header__content">
            <h1><?php echo htmlspecialchars($brand . " " . $model); ?></h1>
            <p class="drift-section__description">
                Thank you for your interest!<br /> The process is still in progress, and we kindly ask you to wait until the timeline is fully completed before proceeding. Once it’s done, you will be able to continue with the necessary steps.
            </p>
        </div>
    </div>

    <section class="drift-header__form">
        <form>
            <div class="order-status-container">
                Order Status: <span id="order-status"><?php echo htmlspecialchars($orderStatus); ?></span>
            </div>
            <div class="drift-input__group">
                <label for="drift-engine">Engine</label>
                <input type="text" name="drift-engine" id="drift-engine" value="<?php echo htmlspecialchars($engine); ?>" readonly/>
            </div>
            <div class="drift-input__group">
                <label for="drift-speed">Speed</label>
                <input type="text" name="drift-speed" id="drift-speed" value="<?php echo htmlspecialchars($speed); ?>" readonly/>
            </div>
            <div class="drift-input__group">
                <label for="drift-price">Price</label>
                <input type="text" name="drift-price" id="drift-price" value="<?php 
                    if (isset($_GET['price'])) {
                        echo '$' . number_format(intval($_GET['price']));   
                    } elseif (isset($_GET['car_id'])) {      
                        echo '$' . number_format($price, 2);    
                    } else {
                        echo '$0.00';
                    }
                ?>" readonly/>
            </div>
            <button type="button" class="drift-btn" id="drift-buyBtn" disabled>Pay Now</button>
        </form>
    </section>

    <div class="drift-line-container">
        <div class="drift-line" id="drift-timerLine"></div>
    </div>

    <section class="drift-section__container drift-about__container" id="drift-about">
        <h2 class="drift-section__header">Delivery Details</h2>
        <div class="drift-about__grid">
            <div class="drift-about__card">
                <span><i class="ri-map-pin-2-fill"></i></span>
                <h4>Delivery Address</h4>
                <label for="drift-city">City:</label>
                <select id="drift-city" name="drift-city" disabled>
                    <option value="Riyadh" selected>Riyadh</option>
                    <option value="Jeddah">Jeddah</option>
                    <option value="Dammam">Dammam</option>
                    <option value="Makkah">Makkah</option>
                    <option value="Medina">Medina</option>
                    <option value="Taif">Taif</option>
                    <option value="Khobar">Khobar</option>
                    <option value="Jubail">Jubail</option>
                    <option value="Abha">Abha</option>
                    <option value="Hail">Hail</option>
                </select>
                <label for="drift-street">Street:</label>
                <input type="text" id="drift-street" placeholder="Street" disabled />
                <label for="drift-neighborhood">Neighborhood:</label>
                <input type="text" id="drift-neighborhood" placeholder="Neighborhood" disabled />
                <button type="button" class="drift-btn edit-btn" disabled>Edit</button>
                <button type="button" class="drift-btn save-btn" style="display:none;">Save</button>
                <button type="button" class="drift-btn cancel-btn" style="display:none;">Cancel</button>
                <div class="order-summary" id="pickup-summary">
                    <p>Original Pickup: Unknown, Unknown, Unknown - <?php echo $initialPickupDate; ?></p>
                </div>
            </div>
            <div class="drift-about__card">
                <span><i class="ri-calendar-event-fill"></i></span>
                <h4>Delivery Date</h4>
                <p>Choose your preferred delivery date</p>
                <input type="date" id="drift-dateInput" name="drift-date" value="<?php echo $initialPickupDate; ?>" disabled />
                <button type="button" class="drift-btn edit-btn" disabled>Edit</button>
                <button type="button" class="drift-btn save-btn" style="display:none;">Save</button>
                <button type="button" class="drift-btn cancel-btn" style="display:none;">Cancel</button>
            </div>
            <div class="drift-about__card">
                <span><i class="ri-roadster-fill"></i></span>
                <h4>Cancel Order</h4>
                <p>Are you sure?</p>
                <p>Canceling will remove your order permanently.</p>
                <button type="button" class="drift-btn" id="drift-cancelOrderBtn" style="margin-top: 1rem;">Cancel</button>
            </div>
        </div>
    </section>

    <section id="reviewSection" style="display: none; max-width:600px; margin: 2rem auto;">
        <h2 style="color:#fff; text-align:center; margin-bottom:1rem;">Leave a Review</h2>
        <form method="POST" action="" style="display:flex; flex-direction:column; gap:1rem;">
            <input type="hidden" name="order_id" value="<?php echo $_SESSION['last_order_id'] ?? ''; ?>">
            <label for="comment" style="color:#fff; font-weight:600;">Your Comment:</label>
            <textarea name="comment" id="comment" rows="4" style="width:100%; padding:0.5rem; border-radius:0.5rem; outline:none;"></textarea>
            <button type="submit" name="submitReview" class="drift-btn" style="width:200px; margin-inline:auto;">Submit Review</button>
        </form>
    </section>

    <footer class="footer section">
        <div class="shapeX shape__big"></div>
        <div class="shapeX shape__small"></div>
        <div class="footer__container container grid">
            <div class="footer__content">
                <a href="#" class="footer__logo">
                    <i class="ri-steering-line"></i> Drift
                </a>
                <p class="footer__description">
                    We offer the best cars of <br>the most recognized brands in <br>the world.
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
        </span>
    </footer>

    <script>
        let isPaymentConfirmed = false;
        let isTimerExpired = false;
        let isOrderCanceled = false;

        const originalPickup = {
            city: "Unknown",
            street: "Unknown",
            neighborhood: "Unknown",
            date: "<?php echo $initialPickupDate; ?>"
        };
        let currentPickup = { ...originalPickup };

        window.onload = function () {
            const params = new URLSearchParams(window.location.search);
            const selectedImage = params.get("image");
            const selectedPrice = params.get("price");
            const selectedColor = params.get("color");
            const selectedInterior = params.get("interior");
            const selectedWheel = params.get("wheel");

            if (selectedImage) {
                document.getElementById("mainCarImg").src = selectedImage;
            }

            if (selectedPrice) {
                document.getElementById("drift-price").value = `$${parseInt(selectedPrice).toLocaleString()}`;
            }

            const carId = params.get("car_id");
            if (!carId && selectedColor && selectedInterior) {
                document.getElementById("drift-engine").value = `${capitalize(selectedColor)} exterior, ${capitalize(selectedInterior)} interior`;
            }
            if (!carId && selectedWheel) {
                document.getElementById("drift-speed").value = `With ${selectedWheel} wheels`;
            }

            const initialStatus = document.getElementById("order-status").textContent.toLowerCase();
            console.log("Initial status on page load: " + initialStatus);

            if (initialStatus === "pending") {
                console.log("Starting 10-second timer for Pending order");
                setTimeout(() => {
                    isTimerExpired = true;
                    document.getElementById("drift-buyBtn").disabled = false;
                    document.getElementById("drift-cancelOrderBtn").disabled = true;

                    console.log("Timer expired, updating status to Confirmed");
                    fetch('?update_status=1', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'update_status=1'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Order status updated to Confirmed');
                            document.getElementById("order-status").textContent = "Confirmed";
                        } else {
                            console.error('Error updating status: ' + data.error);
                        }
                    })
                    .catch(error => console.error('AJAX error:', error));

                    document.getElementById("drift-timerLine").style.animation = "drift-moveLine 10s linear forwards";
                }, 10000);
            } else {
                console.log("Status is not Pending, setting button states accordingly");
                document.getElementById("drift-buyBtn").disabled = false;
                document.getElementById("drift-cancelOrderBtn").disabled = true;
            }
        };

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        document.getElementById("drift-buyBtn").addEventListener("click", function(e) {
            e.preventDefault();
            const engine = document.getElementById("drift-engine").value;
            const speed = document.getElementById("drift-speed").value;
            const price = document.getElementById("drift-price").value;

            if (engine && speed && price) {
                isPaymentConfirmed = true;
                alert(`Purchase confirmed for ${engine}, ${speed}, ${price}. You can now edit delivery details.`);
                document.querySelectorAll(".edit-btn").forEach(btn => btn.disabled = false);
                this.disabled = true;
                document.getElementById("reviewSection").style.display = "block";
            } else {
                alert("Please ensure all fields are filled before confirming your purchase.");
            }
        });

        document.getElementById("drift-cancelOrderBtn").addEventListener("click", function() {
            if (isOrderCanceled) {
                alert("Order has already been canceled.");
                return;
            }

            const currentStatus = document.getElementById("order-status").textContent.toLowerCase();
            console.log("Attempting to cancel order, current frontend status: " + currentStatus);

            if (confirm("Are you sure you want to cancel your order? This action cannot be undone.")) {
                fetch('?cancel_order=1', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'cancel_order=1'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        isOrderCanceled = true;
                        this.disabled = true;
                        document.getElementById("order-status").textContent = "Canceled";
                        console.log("Order canceled successfully");
                        alert("Order canceled successfully. Redirecting to home page.");
                        window.location.href = "home.php";
                    } else {
                        console.error("Cancellation failed: " + data.error);
                        alert("Error canceling order: " + data.error);
                    }
                })
                .catch(error => {
                    console.error('AJAX error:', error);
                    alert("An error occurred while canceling the order. Please try again.");
                });
            }
        });

        document.querySelectorAll(".edit-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                this.style.display = "none";
                const card = this.closest(".drift-about__card");
                card.querySelector(".save-btn").style.display = "inline-block";
                card.querySelector(".cancel-btn").style.display = "inline-block";
                
                if (card.querySelector("#drift-city")) {
                    document.getElementById("drift-city").disabled = false;
                    document.getElementById("drift-street").disabled = false;
                    document.getElementById("drift-neighborhood").disabled = false;
                } else if (card.querySelector("#drift-dateInput")) {
                    document.getElementById("drift-dateInput").disabled = false;
                }
            });
        });

        document.querySelectorAll(".save-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                const card = this.closest(".drift-about__card");
                if (card.querySelector("#drift-city")) {
                    currentPickup.city = document.getElementById("drift-city").value;
                    currentPickup.street = document.getElementById("drift-street").value;
                    currentPickup.neighborhood = document.getElementById("drift-neighborhood").value;
                    document.getElementById("pickup-summary").innerHTML = 
                        `<p>Updated Pickup: ${currentPickup.city}, ${currentPickup.street}, ${currentPickup.neighborhood} - ${currentPickup.date}</p>`;
                    
                    const fullLocation = `${currentPickup.city}, ${currentPickup.street}, ${currentPickup.neighborhood}`;
                    fetch('?update_location=1', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `update_location=1&location=${encodeURIComponent(fullLocation)}&date=${encodeURIComponent(currentPickup.date)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Location and Date updated in database');
                        } else {
                            console.error('Error updating location: ' + data.error);
                        }
                    })
                    .catch(error => console.error('AJAX error:', error));
                } else if (card.querySelector("#drift-dateInput")) {
                    currentPickup.date = document.getElementById("drift-dateInput").value;
                    document.getElementById("pickup-summary").innerHTML = 
                        `<p>Updated Pickup: ${currentPickup.city}, ${currentPickup.street}, ${currentPickup.neighborhood} - ${currentPickup.date}</p>`;
                    
                    const fullLocation = `${currentPickup.city}, ${currentPickup.street}, ${currentPickup.neighborhood}`;
                    fetch('?update_location=1', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `update_location=1&location=${encodeURIComponent(fullLocation)}&date=${encodeURIComponent(currentPickup.date)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Location and Date updated in database');
                        } else {
                            console.error('Error updating date: ' + data.error);
                        }
                    })
                    .catch(error => console.error('AJAX error:', error));
                }
                toggleButtons(card, true);
            });
        });

        document.querySelectorAll(".cancel-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                const card = this.closest(".drift-about__card");
                if (card.querySelector("#drift-city")) {
                    document.getElementById("drift-city").value = originalPickup.city;
                    document.getElementById("drift-street").value = originalPickup.street;
                    document.getElementById("drift-neighborhood").value = originalPickup.neighborhood;
                    document.getElementById("drift-city").disabled = true;
                    document.getElementById("drift-street").disabled = true;
                    document.getElementById("drift-neighborhood").disabled = true;
                } else if (card.querySelector("#drift-dateInput")) {
                    document.getElementById("drift-dateInput").value = originalPickup.date;
                    document.getElementById("drift-dateInput").disabled = true;
                }
                toggleButtons(card, true);
            });
        });

        function toggleButtons(card, isEditMode) {
            card.querySelector(".edit-btn").style.display = isEditMode ? "inline-block" : "none";
            card.querySelector(".save-btn").style.display = isEditMode ? "none" : "inline-block";
            card.querySelector(".cancel-btn").style.display = isEditMode ? "none" : "inline-block";
        }
    </script>
</body>
</html>