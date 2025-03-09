document.addEventListener("DOMContentLoaded", function () {
    const colorBoxes = document.querySelectorAll(".color-box[data-image]");
    const wheels = document.querySelectorAll(".customization__wheels img");
    const interiorColorOptions = document.querySelectorAll(".color-box[data-interior]");
    const mainCarImage = document.getElementById("mainCarImage");
    const interiorImage = document.getElementById("interiorImage");
    const thumbnails = document.querySelectorAll(".customization__thumbnails img");

    let selectedColor = "black"; // Default exterior color
    let selectedWheel = "rim1"; // Default wheel
    let selectedInterior = "img/interior-black.jpeg"; // Default interior image


    const carConfigurations = {
        white: {
            rim1: {
                main: "img/Q5white-rim1.png",
                thumbnails: [
                    "img/Q5white-rim1.png",
                    "img/Q5white1-rim1.png",
                    "img/Q5white2-rim1.png",
                    "img/Q5white3-rim1.png",
                    
                ]
            },
            rim2: {
                main: "img/Q5white-rim2.png",
                thumbnails: [
                    "img/Q5white-rim2.png",
                    "img/Q5white1-rim2.png",
                    "img/Q5white2-rim2.png",
                    "img/Q5white3-rim2.png",
                    
                ]
            },
            rim3: {
                main: "img/Q5white-rim3.png",
                thumbnails: [
                    "img/Q5white-rim3.png",
                    "img/Q5white1-rim3.png",
                    "img/Q5white2-rim3.png",
                    "img/Q5white3-rim3.png",
                    
                ]
            },
            rim4: {
                main: "img/Q5white-rim4.png",
                thumbnails: [
                    "img/Q5white-rim4.png",
                    "img/Q5white1-rim4.png",
                    "img/Q5white2-rim4.png",
                    "img/Q5white3-rim4.png",
                    
                ]
            }
        },
        black: {
            rim1: {
                main: "img/Q5black-rim1.png",
                thumbnails: [
                    "img/Q5black-rim1.png",
                    "img/Q5black1-rim1.png",
                    "img/Q5black2-rim1.png",
                    "img/Q5black3-rim1.png",
                    
                ]
            },
            rim2: {
                main: "img/Q5black-rim2.png",
                thumbnails: [
                    "img/Q5black-rim2.png",
                    "img/Q5black1-rim2.png",
                    "img/Q5black2-rim2.png",
                    "img/Q5black3-rim2.png",
                    
                ]
            },
            rim3: {
                main: "img/Q5black-rim3.png",
                thumbnails: [
                    "img/Q5black-rim3.png",
                    "img/Q5black1-rim3.png",
                    "img/Q5black2-rim3.png",
                    "img/Q5black3-rim3.png",
                    
                ]
            },
            rim4: {
                main: "img/Q5black-rim4.png",
                thumbnails: [
                    "img/Q5black-rim4.png",
                    "img/Q5black1-rim4.png",
                    "img/Q5black2-rim4.png",
                    "img/Q5black3-rim4.png",
                    
                ]
            }
        },
        blue: {
            rim1: {
                main: "img/Q5blue-rim1.png",
                thumbnails: [
                    "img/Q5blue-rim1.png",
                    "img/Q5blue1-rim1.png",
                    "img/Q5blue2-rim1.png",
                    "img/Q5blue3-rim1.png",
                    
                ]
            },
            rim2: {
                main: "img/Q5blue-rim2.png",
                thumbnails: [
                   "img/Q5blue-rim2.png",
                   "img/Q5blue-rim2.png",
                   "img/Q5blue-rim2.png",
                   "img/Q5blue-rim2.png",
                   
                ]
            },
            rim3: {
                main: "img/Q5blue-rim3.png",
                thumbnails: [
                    "img/Q5blue-rim3.png",
                    "img/Q5blue1-rim3.png",
                    "img/Q5blue2-rim3.png",
                    "img/Q5blue3-rim3.png",
                    
                ]
            },
            rim4: {
                main: "img/Q5blue-rim4.png",
                thumbnails: [
                    "img/Q5blue-rim4.png",
                    "img/Q5blue1-rim4.png",
                    "img/Q5blue2-rim4.png",
                    "img/Q5blue3-rim4.png",
                    
                ]
            }
        },
        green: {
            rim1: {
                main: "img/Q5green-rim1.png",
                thumbnails: [
                    "img/Q5green-rim1.png",
                    "img/Q5green1-rim1.png",
                    "img/Q5green2-rim1.png",
                    "img/Q5green3-rim1.png",
                    
                ]
            },
            rim2: {
                main: "img/Q5green-rim2.png",
                thumbnails: [
                    "img/Q5green-rim2.png",
                    "img/Q5green1-rim2.png",
                    "img/Q5green2-rim2.png",
                    "img/Q5green3-rim2.png",
                    
                ]
            },
            rim3: {
                main: "img/Q5green-rim3.png",
                thumbnails: [
                   "img/Q5green-rim3.png",
                   "img/Q5green-rim3.png",
                   "img/Q5green-rim3.png",
                   "img/Q5green-rim3.png",
                   
                ]
            },
            rim4: {
                main: "img/Q5green-rim4.png",
                thumbnails: [
                    "img/Q5green-rim4.png",
                    "img/Q5green-rim4.png",
                    "img/Q5green-rim4.png",
                    "img/Q5green-rim4.png",
                    
                ]
            }
        }
    };

    

    function updateCarImages() {
        if (carConfigurations[selectedColor] && carConfigurations[selectedColor][selectedWheel]) {
            mainCarImage.src = carConfigurations[selectedColor][selectedWheel].main;
            thumbnails.forEach((thumb, index) => {
                thumb.src = carConfigurations[selectedColor][selectedWheel].thumbnails[index];
            });
        }
    }

    // Color Selection
    colorBoxes.forEach(box => {
        box.addEventListener("click", function () {
            colorBoxes.forEach(box => box.classList.remove("selected"));
            this.classList.add("selected");

            selectedColor = this.style.backgroundColor.toLowerCase();
            updateCarImages();
        });
    });

    

    // Wheel Selection
    wheels.forEach(wheel => {
        wheel.addEventListener("click", function () {
            wheels.forEach(w => w.classList.remove("selected-wheel"));
            this.classList.add("selected-wheel");

            selectedWheel = this.dataset.wheel.split('/').pop().split('.')[0]; // Extract rim name
            updateCarImages();
        });
    });

    updateCarImages();
});
