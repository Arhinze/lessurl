<?php
ini_set("display_errors", '1'); //for testing purposes..

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/php/account-manager.php");

// 1. HANDLE REDIRECTION
if (isset($_GET['url_path'])) {
    $path = htmlentities($_GET['url_path']);
    echo $path;
    //$path = parse_url($url, PHP_URL_PATH);
    $link_stmt = $pdo->prepare("SELECT * FROM links WHERE custom_name = ?");
    $link_stmt->execute([$path]);
    $link_data = $link_stmt->fetch(PDO::FETCH_OBJ);

    if ($link_data) {
        // If it's a landing page, we might want to display content instead of redirecting
        if ($link_data->is_landing_page == 1) {
            echo "<h1>Welcome to the Landing Page for: " . htmlspecialchars($path) . "</h1>";
            echo "<p>Your SPA content goes here.</p>";
            exit;
        }
        header("Location: ".$link_data->long_url);
        exit;
    } else {
        echo "404 - Link not found.";
        exit;
    }
}

// 2. HANDLE FORM SUBMISSION (API)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $long_url = $_POST['long_url'];
    $custom_name = $_POST['custom_name'];
    $is_spa = isset($_POST['is_spa']) ? 1 : 0;

    $stmt = $pdo->prepare("INSERT INTO links (custom_name, long_url, is_landing_page) VALUES (?, ?, ?)");
    if ($stmt->execute([$custom_name, $long_url, $is_spa])) {
        echo json_encode(["status" => "success", "short_link" => "lessurl.xyz/".$custom_name]);
    } else {
        echo json_encode(["status" => "error", "message" => "Name already taken!"]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding: 50px; background: #f4f7f6; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 400px; }
        input, select, button { width: 100%; margin: 10px 0; padding: 10px; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; cursor: pointer; }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Less URL</h2>
        
        <select id="typeSelector">
            <option value="standard">Standard URL</option>
            <option value="whatsapp">WhatsApp Link</option>
            <option value="spa">SPA Landing Page</option>
        </select>

        <div id="urlInputGroup">
            <input type="text" id="longUrl" placeholder="https://very-long-link.com/...">
        </div>

        <div id="whatsappGroup" class="hidden">
            <input type="text" id="phone" placeholder="Phone (e.g., 123456789)">
            <input type="text" id="message" placeholder="Custom Message">
        </div>

        <input type="text" id="customName" placeholder="Custom Name (e.g., abc)">
        <button onclick="createLink()">Shorten Link</button>
        
        <p id="result"></p>
    </div>

    <script>
        const typeSelector = document.getElementById('typeSelector');
        
        typeSelector.addEventListener('change', (e) => {
            document.getElementById('urlInputGroup').classList.toggle('hidden', e.target.value === 'whatsapp');
            document.getElementById('whatsappGroup').classList.toggle('hidden', e.target.value !== 'whatsapp');
        });

        async function createLink() {
            let finalUrl = document.getElementById('longUrl').value;
            const customName = document.getElementById('customName').value;
            const type = typeSelector.value;

            if (type === 'whatsapp') {
                const phone = document.getElementById('phone').value;
                const msg = encodeURIComponent(document.getElementById('message').value);
                finalUrl = `https://wa.me/${phone}?text=${msg}`;
            }

            const formData = new FormData();
            formData.append('long_url', finalUrl);
            formData.append('custom_name', customName);
            if (type === 'spa') formData.append('is_spa', '1');

            const response = await fetch('index.php', { method: 'POST', body: formData });
            const data = await response.json();
            
            document.getElementById('result').innerHTML = data.status === 'success' 
                ? `Done! <a href="${data.short_link}">${data.short_link}</a>` 
                : data.message;
        }
    </script>
</body>
</html>