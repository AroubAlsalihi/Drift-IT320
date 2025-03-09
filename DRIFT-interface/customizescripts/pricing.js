document.addEventListener("DOMContentLoaded", function () {
    const colorBoxes = document.querySelectorAll(".color-box[data-image]");
    const wheels = document.querySelectorAll(".customization__wheels img");
    const interiorColorOptions = document.querySelectorAll(".color-box[data-interior]");
    const priceElement = document.querySelector(".bottom-bar strong");
    const buyNowButton = document.getElementById("buyNowButton");
    const saveButton = document.getElementById("saveButton");

    let selectedColor = "white"; // Default exterior color
    let selectedWheel = "rim1"; // Default wheel
    let selectedInterior = "black"; // Default interior


    const pricing = {
        exterior: { white: 50000, black: 52000, blue: 51500, green: 53000 },
        interior: { black: 5000, brown: 5500, gray: 4800 },
        wheels: { rim1: 3000, rim2: 3500, rim3: 3800, rim4: 4000 }
    };

    function updatePrice() {
        console.log(`Debug: Color=${selectedColor}, Interior=${selectedInterior}, Wheels=${selectedWheel}`); // Debugging log

        // Ensure selected values exist in pricing data
        if (!pricing.exterior[selectedColor]) {
            console.warn(`Warning: Invalid exterior color '${selectedColor}', using default.`);
            selectedColor = "white";
        }
        if (!pricing.interior[selectedInterior]) {
            console.warn(`Warning: Invalid interior color '${selectedInterior}', using default.`);
            selectedInterior = "black";
        }
        if (!pricing.wheels[selectedWheel]) {
            console.warn(`Warning: Invalid wheel '${selectedWheel}', using default.`);
            selectedWheel = "rim1";
        }

        // Calculate total price
        const totalPrice = pricing.exterior[selectedColor] + pricing.interior[selectedInterior] + pricing.wheels[selectedWheel];
        priceElement.textContent = `$${totalPrice.toLocaleString()}`;
        console.log(`Debug: Total Price=${totalPrice}`); // Debugging log
        return totalPrice;
    }

   
    colorBoxes.forEach(box => {
        box.addEventListener("click", function () {
            selectedColor = this.getAttribute("data-image") || "white";
            updatePrice();
        });
    });

   
    wheels.forEach(wheel => {
        wheel.addEventListener("click", function () {
            selectedWheel = wheel.getAttribute("data-wheel") || "rim1";
            updatePrice();
        });
    });

    
    interiorColorOptions.forEach(colorBox => {
        colorBox.addEventListener("click", function () {
            selectedInterior = colorBox.getAttribute("data-interior") || "black";
            updatePrice();
        });
    });

    //  Redirect to payment page when clicking place order
    buyNowButton.addEventListener("click", function () {
        const totalPrice = updatePrice();
        const paymentURL = `Status.html?color=${selectedColor}&interior=${selectedInterior}&wheels=${selectedWheel}&price=${totalPrice}`;
        window.location.href = paymentURL;
    });



    updatePrice();
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
