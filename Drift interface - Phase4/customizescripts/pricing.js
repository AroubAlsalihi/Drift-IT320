document.addEventListener("DOMContentLoaded", function () {
    const colorBoxes = document.querySelectorAll(".color-box[data-image]");
    const wheels = document.querySelectorAll(".customization__wheels img");
    const interiorColorOptions = document.querySelectorAll(".color-box[data-interior]");
    const priceElement = document.querySelector(".bottom-bar strong");
    const buyBtn = document.querySelector(".buy"); // ✅ Corrected
    const saveButton = document.getElementById("saveButton");

    let selectedColor = "white";
    let selectedWheel = "rim1";
    let selectedInterior = "black";

    const pricing = {
        exterior: { white: 17590000, black: 17590000, blue: 17590000, green: 17590000 },
        interior: { black: 5000, brown: 5500, gray: 4800 },
        wheels: { rim1: 3000, rim2: 3500, rim3: 3800, rim4: 4000 }
    };

    function updatePrice() {
        const base = typeof basePriceFromDB !== 'undefined' ? basePriceFromDB : 0;

        const exterior = pricing.exterior[selectedColor] || 0;
        const interior = pricing.interior[selectedInterior] || 0;
        const wheel = pricing.wheels[selectedWheel] || 0;

        const total = base + exterior + interior + wheel;

        priceElement.textContent = `$${total.toLocaleString()}`;
        return total;
    }

    colorBoxes.forEach(box => {
        box.addEventListener("click", function () {
            selectedColor = this.getAttribute("style").split(":")[1].replace(";", "").trim().toLowerCase(); // get actual color
            updatePrice();
        });
    });

    wheels.forEach(wheel => {
        wheel.addEventListener("click", function () {
            selectedWheel = wheel.getAttribute("data-wheel") || "rim1";
            wheels.forEach(w => w.classList.remove("selected"));
            wheel.classList.add("selected");
            updatePrice();
        });
    });

    interiorColorOptions.forEach(colorBox => {
        colorBox.addEventListener("click", function () {
            selectedInterior = colorBox.getAttribute("data-interior") || "black";
            interiorColorOptions.forEach(box => box.classList.remove("selected"));
            colorBox.classList.add("selected");
            updatePrice();
        });
    });

    if (buyBtn) {
        buyBtn.addEventListener("click", function () {
            const price = updatePrice();
            const selectedImage = document.getElementById("mainCarImage").src;

            const url = `Status.php?car_id=2&image=${encodeURIComponent(selectedImage)}&color=${selectedColor}&interior=${selectedInterior}&wheel=${selectedWheel}&price=${price}`;
            window.location.href = url;
        });
    }

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
