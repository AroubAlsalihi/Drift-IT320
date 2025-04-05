document.addEventListener("DOMContentLoaded", function () {
    const interiorColorOptions = document.querySelectorAll(".color-box[data-interior]");
    const interiorImage = document.getElementById("interiorImage");

    let selectedInterior = "black"; 

    
    const interiorConfigurations = {
        black: "img/interior-black.jpeg",
        brown: "img/interior-brown.jpg",
        gray: "img/interior-gray.jpg"
    };

    function updateInteriorImage() {
        if (interiorConfigurations[selectedInterior]) {
            interiorImage.src = interiorConfigurations[selectedInterior];
        }
    }

  
    interiorColorOptions.forEach(colorBox => {
        colorBox.addEventListener("click", function () {

            interiorColorOptions.forEach(box => box.classList.remove("selected"));
            this.classList.add("selected");


            selectedInterior = this.getAttribute("data-interior");


            updateInteriorImage();
        });
    });


    updateInteriorImage();
});
