const generatorTab = document.querySelector(".nav-gene");
const scannerTab = document.querySelector(".nav-scan");

generatorTab.addEventListener('click', () => {
	generatorTab.classList.add("active");
	scannerTab.classList.remove("active");

	document.querySelector(".scanner_rfid").style.display = "none";
	document.querySelector(".scanner_qr").style.display = "block";
})

scannerTab.addEventListener("click", () => {
	scannerTab.classList.add("active");
	generatorTab.classList.remove("active");

	document.querySelector(".scanner_rfid").style.display = "block";
	document.querySelector(".scanner_qr").style.display = "none";
}) 