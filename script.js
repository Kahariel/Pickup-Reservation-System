document.addEventListener("DOMContentLoaded", () => {
    window.selectedItems = [];

    initializeReservationSystem();
    initializeSimpleReservationForm();
    setupImageHoverEffects();
    setupSlider();
    setupFeaturedProductsSlider();
    setupProductPopup();
    addTrackOrderButton();
    setupReservePageCarousel();
    setupItemPopups();
    setupTrackOrderForm();
});

// Global reservations store
const reservations = {};

function initializeReservationSystem() {
    const reserveForm = document.getElementById("reserveForm");
    const receiptList = document.getElementById("receiptList");
    const trackNumberSection = document.getElementById("trackNumber");
    const trackValue = document.getElementById("trackValue");

    let selectedItem = null;
    const carouselItems = document.querySelectorAll(".carousel-item");

    // Handle item selection from carousel
    carouselItems.forEach(item => {
        item.addEventListener("click", () => {
            carouselItems.forEach(i => i.classList.remove("selected"));
            item.classList.add("selected");
            selectedItem = {
                name: item.getAttribute("data-item-name"),
                img: item.getAttribute("data-item-img"),
                id: item.getAttribute("data-item-id")
            };
        });
    });

    // Handle reservation form submission
    reserveForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const name = document.getElementById("name").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const pickupDate = document.getElementById("pickup-date")?.value;
        const pickupTime = document.getElementById("pickup-time")?.value;
        const trackingID = "AA-" + Math.floor(100000 + Math.random() * 900000);

        if (!name || !phone || (!selectedItem && window.selectedItems.length === 0)) {
            alert("Please complete all fields and select at least one item.");
            return;
        }

        receiptList.innerHTML = `
            <li><strong>Name:</strong> ${name}</li>
            <li><strong>Phone:</strong> ${phone}</li>
            ${selectedItem ? `<li><strong>Reserved Item:</strong> ${selectedItem.name}</li>` : ""}
            ${pickupDate ? `<li><strong>Pickup Date:</strong> ${pickupDate}</li>` : ""}
            ${pickupTime ? `<li><strong>Pickup Time:</strong> ${pickupTime}</li>` : ""}
            ${window.selectedItems.length > 0 ? `<li><strong>Items:</strong> ${window.selectedItems.map(i => `${i.name} (Qty: ${i.quantity})`).join(", ")}</li>` : ""}
        `;

        // Save to reservations DB
        reservations[trackingID] = {
            name: name,
            status: "Reserved",
            estimatedDelivery: pickupDate || "To Be Decided"
        };

        // Display tracking number
        trackValue.textContent = trackingID;
        trackNumberSection.style.display = "block";

        formData = new FormData();
        formData.append("name", name);
        formData.append("phone", phone);
        formData.append("pickupDate", pickupDate);
        formData.append("pickupTime", pickupTime);
        formData.append("trackingID", trackingID);
        formData.append("items", JSON.stringify(window.selectedItems));
    
        fetch("api/reserve.php", {
            method: "POST",
            body: formData,
        }).then(response => response.json())
        .then(data => {
            console.log(data);
        });

        alert(`Reservation Successful! Tracking ID: ${trackingID}`);
        reserveForm.reset();
        window.selectedItems = [];
    });
}

function initializeSimpleReservationForm() {
    const simpleForm = document.getElementById("reservationForm");
    if (!simpleForm) return;

    simpleForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const name = document.getElementById("name").value;
        const contact = document.getElementById("contact").value;
        const item = document.getElementById("item").value;
        const quantity = document.getElementById("quantity").value;

        alert(`Reservation Confirmed!\n\nName: ${name}\nItem: ${item}\nQuantity: ${quantity}\nContact: ${contact}`);
        this.reset();
    });
}

function setupImageHoverEffects() {
    document.querySelectorAll(".product img").forEach(img => {
        const originalSrc = img.src;
        const hoverSrc = img.getAttribute("data-hover");

        img.addEventListener("mouseenter", () => hoverSrc && (img.src = hoverSrc));
        img.addEventListener("mouseleave", () => img.src = originalSrc);
    });
}

function setupSlider() {
    const slides = document.querySelectorAll(".slider img");
    const nextBtn = document.querySelector(".next");
    const prevBtn = document.querySelector(".prev");
    let currentSlide = 0;

    if (!slides.length) return;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.style.opacity = i === index ? "1" : "0";
            slide.style.transform = i === index ? "translateX(0)" : "translateX(100%)";
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    }

    nextBtn?.addEventListener("click", nextSlide);
    prevBtn?.addEventListener("click", prevSlide);

    setInterval(nextSlide, 3000);
    showSlide(currentSlide);
}

function setupFeaturedProductsSlider() {
    const products = document.querySelectorAll(".featured-products .product");
    const container = document.querySelector(".featured-container");
    let currentIndex = 0;

    if (!products.length || !container) return;

    function showNextProduct() {
        products.forEach(p => p.classList.remove("active"));
        products[currentIndex].classList.add("active");
        container.style.transform = `translateX(-${currentIndex * 220}px)`;
        currentIndex = (currentIndex + 1) % products.length;
    }

    setInterval(showNextProduct, 3000);
}

function setupProductPopup() {
    const products = document.querySelectorAll(".featured-products .product");

    products.forEach(product => {
        product.addEventListener("click", () => {
            const popup = document.createElement("div");
            popup.className = "product-popup";
            popup.innerHTML = `
                <div class="popup-content">
                    <span class="close-btn">&times;</span>
                    <img src="${product.querySelector("img").src}" alt="Product">
                    <h3>${product.querySelector("h3")?.innerText}</h3>
                    <p>${product.querySelector(".description")?.innerText}</p>
                </div>
            `;
            document.body.appendChild(popup);
            setTimeout(() => popup.classList.add("visible"), 10);

            popup.querySelector(".close-btn").onclick = () => {
                popup.classList.remove("visible");
                setTimeout(() => popup.remove(), 300);
            };

            popup.addEventListener("click", e => {
                if (e.target === popup) {
                    popup.classList.remove("visible");
                    setTimeout(() => popup.remove(), 300);
                }
            });
        });
    });
}

function addTrackOrderButton() {
    const header = document.querySelector("header");
    if (!header || document.querySelector(".track-order-btn")) return;

    const btn = document.createElement("button");
    btn.className = "track-order-btn";
    btn.innerText = "Track Order";
    btn.onclick = () => window.location.href = "track_order.php";
    header.prepend(btn);
}

function setupReservePageCarousel() {
    const track = document.querySelector('.carousel-track');
    const items = Array.from(track?.children || []);
    const nextBtn = document.querySelector('.carousel-btn.right');
    const prevBtn = document.querySelector('.carousel-btn.left');
    let currentIndex = 0;
    const itemWidth = items[0]?.offsetWidth + 20 || 0;

    if (!track || !itemWidth) return;

    function updateCarousel() {
        track.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
    }

    nextBtn?.addEventListener("click", () => {
        if (currentIndex < items.length - 1) currentIndex++;
        updateCarousel();
    });

    prevBtn?.addEventListener("click", () => {
        if (currentIndex > 0) currentIndex--;
        updateCarousel();
    });

    window.addEventListener("resize", updateCarousel);
}

function setupItemPopups() {
    const items = document.querySelectorAll(".carousel-item");

    items.forEach(item => {
        item.addEventListener("click", () => {
            const itemName = item.getAttribute("data-item-name");
            const itemImg = item.getAttribute("data-item-img");
            const itemId = item.getAttribute("data-item-id");

            const popup = document.createElement("div");
            popup.className = "popup-overlay";
            popup.innerHTML = `
                <div class="popup-content">
                    <span class="popup-close">&times;</span>
                    <img src="${itemImg}" alt="${itemName}">
                    <h3>${itemName}</h3>
                    <label for="qty">Quantity:</label>
                    <input type="number" id="qty" min="1" placeholder="Enter quantity">
                    <button class="add-item-btn">Add to Reservation</button>
                </div>
            `;
            document.body.appendChild(popup);

            popup.querySelector(".popup-close").onclick = () => popup.remove();
            popup.addEventListener("click", e => {
                if (e.target === popup) popup.remove();
            });

            popup.querySelector(".add-item-btn").onclick = () => {
                const qty = parseInt(popup.querySelector("#qty").value);
                if (!qty || qty <= 0) return alert("Please enter a valid quantity.");
                window.selectedItems.push({ name: itemName, quantity: qty, id: itemId });
                popup.remove();
            };
        });
    });
}

function setupTrackOrderForm() {
    const trackInput = document.getElementById("trackingInput");
    const orderStatusDiv = document.getElementById("orderStatus");

    if (trackInput && orderStatusDiv) {
        window.trackOrder = function () {
            const trackingID = trackInput.value.trim();
            if (!trackingID) {
                orderStatusDiv.innerHTML = "<p class='error'>Please enter a Tracking ID!</p>";
                return;
            }

            const order = reservations[trackingID];
            if (order) {
                orderStatusDiv.innerHTML = `
                    <div class="order-details">
                        <p><strong>Name:</strong> ${order.name}</p>
                        <p><strong>Status:</strong> ${order.status}</p>
                        <p><strong>Estimated Delivery:</strong> ${order.estimatedDelivery}</p>
                    </div>
                `;
            } else {
                orderStatusDiv.innerHTML = "<p class='error'>Tracking ID not found.</p>";
            }
        };
    }
}
