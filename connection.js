
function checkConnection() {

    fetch("ping.php", {
        method: "GET",
        cache: "no-store"
    })

    .then(response => {

        if (!response.ok) {
            throw new Error("Connection failed");
        }

        // Internet is available
        let last = localStorage.getItem("last_page");

        // Return to previous page
        if (
            window.location.pathname.includes("offline.php") &&
            last
        ) {
            localStorage.removeItem("last_page");
            window.location.href = last;
        }

    })

    .catch(() => {

        // Internet is unavailable
        if (!window.location.pathname.includes("offline.php")) {

            localStorage.setItem(
                "last_page",
                window.location.href
            );

            window.location.href = "offline.php";
        }

    });

}

// Check immediately
checkConnection();

// Check every 3 seconds
setInterval(checkConnection, 3000);
