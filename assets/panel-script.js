document.addEventListener("DOMContentLoaded", function () {
    const panel = document.getElementById("sliding-panel");
    const openBtn = document.getElementById("open-panel");
    const closeBtn = document.getElementById("close-panel");

    openBtn.addEventListener("click", function () {
        panel.style.right = "0px"; // Slide in
    });

    closeBtn.addEventListener("click", function () {
        panel.style.right = "-300px"; // Slide out
    });
});
