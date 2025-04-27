document.addEventListener("DOMContentLoaded", () => {
    const adminForm = document.getElementById("adminLoginForm");
    const reservationList = document.getElementById("reservationList");
    const logoutBtn = document.getElementById("logoutBtn");


    // Hardcoded admin credentials (Replace with backend authentication)
    const adminUsername = "admin";
    const adminPassword = "admin123";

    // Admin Login
    if (adminForm) {
        adminForm.addEventListener("submit", (event) => {
            event.preventDefault();

            const username = document.getElementById("adminUsername").value;
            const password = document.getElementById("adminPassword").value;

            if (username === adminUsername && password === adminPassword) {
                sessionStorage.setItem("adminLoggedIn", "true");
                window.location.href = "admin_dashboard.php"; // Redirect to dashboard
            } else {
                document.getElementById("error-message").textContent = "Invalid username or password!";
            }
        });
    }

    // Check login status for dashboard
    if (reservationList) {
        if (!sessionStorage.getItem("adminLoggedIn")) {
            window.location.href = "admin_login.html"; // Redirect if not logged in
        }

        loadReservations();
        loadItems(); // Load existing items
    }

    // Logout Functionality
    if (logoutBtn) {
        logoutBtn.addEventListener("click", () => {
            sessionStorage.removeItem("adminLoggedIn");
            window.location.href = "admin_login.php"; // Redirect to login page
        });
    }

    // Function to Load Reservations
    function loadReservations() {
        const reservations = [
            
        ];

        reservationList.innerHTML = reservations.map(res => `
            <tr>
                <td>${res.orderId}</td>
                <td>${res.name}</td>
                <td>${res.date}</td>
                <td>${res.status}</td>
                <td>
                    <button class="confirm-btn" data-id="${res.orderId}">Confirm</button>
                    <button class="cancel-btn" data-id="${res.orderId}">Cancel</button>
                </td>
            </tr>
        `).join("");

    }

    // Function to Load Items from Local Storage
    function loadItems() {
        let items = JSON.parse(getItems) || [];
        let table = document.getElementById("itemsTable");

        if (!table) return; // Prevents errors if the table doesn't exist

        table.innerHTML = `
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        `;

        items.forEach((item, index) => {
            let row = table.insertRow();
            row.innerHTML = `
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>
                    <button class="delete-btn" onclick="deleteItem(${index})">Delete</button>
                </td>
            `;
        });
    }

    // Function to Delete an Item
    window.deleteItem = function(index) {
        let items = JSON.parse(localStorage.getItem("items")) || [];
        items.splice(index, 1);
        localStorage.setItem("items", JSON.stringify(items));
        loadItems();
    };
});
// Show and hide sections based on user selection from sidebar
function showSection(sectionId) {
    // Hide all sections
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => {
        section.style.display = 'none';
    });

    // Show the selected section
    document.getElementById(sectionId).style.display = 'block';
}

function addItem() {
    let itemName = document.getElementById("itemName").value;
    let itemQuantity = document.getElementById("itemQuantity").value;
    let itemPrice = document.getElementById("itemPrice").value;
    let itemImage = document.getElementById("itemImage").files[0];
    console.log(itemImage);

    const formData = new FormData();
    formData.append("itemName", itemName);
    formData.append("itemQuantity", itemQuantity);
    formData.append("itemPrice", itemPrice);
    formData.append("itemImage", itemImage);
    
    fetch("api/add_product.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Item added successfully!");
            location.reload(); // Reload the page to see the new item
        } else {
            alert("Failed to add item: " + data.error);
        }
    })

}

function deleteProduct(productId) {
    formData = new FormData();
    formData.append("productId", productId);

    fetch("api/delete_product.php", {
        method: "POST",
        body: formData,
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Product deleted successfully!");
            location.reload(); // Reload the page to see the changes
        } else {
            alert("Failed to delete product: " + data.error);
        }
    });
}

function confirmReservation(orderId) {
    formData = new FormData();
    formData.append("orderId", orderId);

    fetch("api/confirm_reservation.php", {
        method: "POST",
        body: formData,
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Reservation confirmed successfully!");
            location.reload(); // Reload the page to see the changes
        } else {
            alert("Failed to confirm reservation: " + data.error);
        }
    });
}

function cancelReservation(orderId) {
    formData = new FormData();
    formData.append("orderId", orderId);

    fetch("api/cancel_reservation.php", {
        method: "POST",
        body: formData,
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Reservation cancelled successfully!");
            location.reload(); // Reload the page to see the changes
        } else {
            alert("Failed to cancel reservation: " + data.error);
        }
    });
}