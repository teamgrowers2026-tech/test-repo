<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory.sc</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

   

</head>
<body>
<header class="topbar">
    <div class="topbar-left">
        <form class="search-bar" onsubmit="return false;">
            <input type="text" id="searchBar" placeholder="Search item..." 
       onkeyup="searchItem(this.value)">

        </form>
    </div>

    <div class="topbar-right">
        <button id="notifyBtn" class="notification-btn">
            🔔 <span id="notifCount" class="notif-count">0</span>
        </button>

        <div id="notifDropdown" class="notif-dropdown" style="display:none;">
            <ul id="notifList"></ul>
        </div>

        <a href="profile.php" class="profile-link">
            <button class="icon-btn profile-btn" title="Profile">👤</button>
        </a>
    </div>
</header>

<!-- Floating Search Result Box -->
<!-- SEARCH POPUP OVERLAY -->
<div id="searchPopup" class="search-popup-overlay">
    <div class="search-popup-box">
        <h3>Search Result</h3>
        <div id="searchResultData"></div>

        <button class="close-search-popup" onclick="closeSearchPopup()">Close</button>
    </div>
</div>


<script>
// SEARCH FUNCTION
function openSearchPopup(data) {
    document.getElementById("searchResultData").innerHTML = data;
    document.getElementById("searchPopup").style.display = "flex";
}

function closeSearchPopup() {
    document.getElementById("searchPopup").style.display = "none";
}

// SEARCH FUNCTION
function searchItem(query) {
    if (query.trim() === "") return;

    fetch("search_backend.php?q=" + encodeURIComponent(query))
        .then(response => response.text())
        .then(data => {
            openSearchPopup(data);
        });
}



// NOTIFICATIONS (unchanged)
function loadNotifications() {
    fetch("notifications.php")
        .then(response => response.json())
        .then(data => {
            const notifCount = document.getElementById("notifCount");
            const notifList = document.getElementById("notifList");

            notifList.innerHTML = "";
            notifCount.innerText = data.length;

            if (data.length === 0) {
                notifList.innerHTML = "<li>No notifications</li>";
                return;
            }

            data.forEach(item => {
                let icon = item.type === "expired" ? "⚠️" : "❗";
                notifList.innerHTML += `
                    <li>${icon} ${item.message}</li>
                `;
            });
        });
}

document.getElementById("notifyBtn").addEventListener("click", () => {
    const dropdown = document.getElementById("notifDropdown");
    dropdown.style.display = dropdown.style.display === "none" ? "block" : "none";
});

setInterval(loadNotifications, 10000);
loadNotifications();
</script>

</body>
</html>
