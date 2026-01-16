document.addEventListener("DOMContentLoaded", () => {
    const images = document.querySelectorAll(".gallery-grid img");
    if (!images.length) return;

    let currentIndex = 0;

    const modal = document.createElement("div");
    modal.className = "gallery-modal";
    modal.innerHTML = `
        <span class="gallery-close">&times;</span>
        <img class="gallery-modal-img">
        <span class="gallery-prev">&#10094;</span>
        <span class="gallery-next">&#10095;</span>
    `;
    document.body.appendChild(modal);

    const modalImg = modal.querySelector(".gallery-modal-img");
    const closeBtn = modal.querySelector(".gallery-close");
    const prevBtn = modal.querySelector(".gallery-prev");
    const nextBtn = modal.querySelector(".gallery-next");

    function openModal(index) {
        currentIndex = index;
        modalImg.src = images[currentIndex].src;
        modal.style.display = "flex";
        document.body.style.overflow = "hidden";
    }

    function closeModal() {
        modal.style.display = "none";
        document.body.style.overflow = "";
    }

    function showNext() {
        currentIndex = (currentIndex + 1) % images.length;
        modalImg.src = images[currentIndex].src;
    }

    function showPrev() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        modalImg.src = images[currentIndex].src;
    }

    images.forEach((img, i) => {
        img.addEventListener("click", () => openModal(i));
    });

    closeBtn.onclick = closeModal;
    nextBtn.onclick = showNext;
    prevBtn.onclick = showPrev;

    modal.onclick = e => {
        if (e.target === modal) closeModal();
    };

    document.addEventListener("keydown", e => {
        if (modal.style.display !== "flex") return;
        if (e.key === "Escape") closeModal();
        if (e.key === "ArrowRight") showNext();
        if (e.key === "ArrowLeft") showPrev();
    });
});