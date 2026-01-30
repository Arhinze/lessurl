<?php
ini_set("display_errors", '1'); //for testing purposes..

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/php/account-manager.php");

// 1. HANDLE REDIRECTION
if (isset($_GET['url_path'])) {
    $path = htmlentities(str_replace("/","",$_GET['url_path']));
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Less URL | Shorten, Brand, & Redirect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #FF8C00; /* Orange */
            --dark: #0A192F;    /* Navy Blue */
            --light: #F4F7F6;
            --white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        body { background-color: var(--light); color: var(--dark); line-height: 1.6; }

        /* Navigation */
        nav { background: var(--dark); padding: 1rem 10%; display: flex; justify-content: space-between; align-items: center; color: white; }
        nav .logo { font-size: 1.5rem; font-weight: bold; color: var(--primary); }
        nav a { color: white; text-decoration: none; margin-left: 20px; font-size: 0.9rem; }

        /* Hero Section */
        header { background: var(--dark); color: white; padding: 100px 10% 150px; text-align: center; }
        header h1 { font-size: 3rem; margin-bottom: 1rem; }
        header p { font-size: 1.2rem; opacity: 0.8; max-width: 600px; margin: 0 auto 2rem; }

        /* Generator Card - Beautified */
        .generator-container { margin-top: -100px; padding: 0 10%; }
        .glass-card { 
            background: var(--white); 
            padding: 40px; 
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .input-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        @media (max-width: 600px) { .input-grid { grid-template-columns: 1fr; } }

        label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.85rem; color: #555; }
        
        input, select { 
            width: 100%; padding: 12px 15px; border: 2px solid #eee; 
            border-radius: 10px; font-size: 1rem; transition: 0.3s;
        }

        input:focus { border-color: var(--primary); outline: none; }

        .btn-main { 
            background: var(--primary); color: white; border: none; 
            padding: 15px 30px; border-radius: 10px; font-size: 1.1rem; 
            font-weight: bold; cursor: pointer; width: 100%; transition: 0.3s;
        }

        .btn-main:hover { background: #e67e00; transform: translateY(-2px); }

        /* Features Section */
        .features { padding: 80px 10%; display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
        .feat-box { text-align: center; padding: 30px; }
        .feat-box i { font-size: 2.5rem; color: var(--primary); margin-bottom: 15px; }

        .hidden { display: none; }
        #result { margin-top: 20px; padding: 15px; border-radius: 10px; text-align: center; font-weight: bold; }
        .success-box { background: #e7f9ed; color: #28a745; border: 1px solid #d4edda; }
    </style>
</head>
<body>

    <nav>
        <div class="logo">LessURL<span style="color:white">.xyz</span></div>
        <div>
            <a href="#features">Features</a>
            <a href="#how-it-works">How it works</a>
        </div>
    </nav>

    <header>
        <h1>Links made simple.</h1>
        <p>The ultimate link management tool for custom redirects, WhatsApp shortcuts, and SPA landing pages.</p>
    </header>

    <section class="generator-container">
        <div class="glass-card">
            <div style="margin-bottom: 25px;">
                <label>Link Purpose</label>
                <select id="typeSelector">
                    <option value="standard">Standard URL Redirection</option>
                    <option value="whatsapp">WhatsApp Direct Link</option>
                    <option value="spa">SPA Landing Page</option>
                </select>
            </div>

            <div class="input-grid">
                <div id="urlInputGroup">
                    <label>Destination URL</label>
                    <input type="text" id="longUrl" placeholder="https://example.com/very-long-link">
                </div>

                <div id="whatsappGroup" class="hidden">
                    <label>Phone Number</label>
                    <input type="text" id="phone" placeholder="e.g. 2348012345678">
                    <label style="margin-top:10px">Default Message</label>
                    <input type="text" id="message" placeholder="Hi, I'm interested in...">
                </div>

                <div>
                    <label>Custom Alias (lessurl.xyz/___)</label>
                    <input type="text" id="customName" placeholder="my-custom-name">
                </div>
            </div>

            <button class="btn-main" onclick="createLink()">Generate Short Link</button>
            <div id="result"></div>
        </div>
    </section>

    <section id="features" class="features">
        <div class="feat-box">
            <i class="fas fa-bolt"></i>
            <h3>Fast Redirection</h3>
            <p>Our servers ensure your users reach their destination in milliseconds.</p>
        </div>
        <div class="feat-box">
            <i class="fas fa-paint-brush"></i>
            <h3>Custom Branding</h3>
            <p>Ditch the random strings. Use names that represent your brand.</p>
        </div>
        <div class="feat-box">
            <i class="fab fa-whatsapp"></i>
            <h3>WhatsApp Ready</h3>
            <p>Generate pre-filled message links for your business effortlessly.</p>
        </div>
    </section>

    <script>
        const typeSelector = document.getElementById('typeSelector');
        
        typeSelector.addEventListener('change', (e) => {
            const isWA = e.target.value === 'whatsapp';
            document.getElementById('urlInputGroup').classList.toggle('hidden', isWA);
            document.getElementById('whatsappGroup').classList.toggle('hidden', !isWA);
        });

        async function createLink() {
            const btn = document.querySelector('.btn-main');
            btn.innerText = "Processing...";
            
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

            try {
                const response = await fetch('index.php', { method: 'POST', body: formData });
                const data = await response.json();
                
                const resultDiv = document.getElementById('result');
                if (data.status === 'success') {
                    resultDiv.className = "success-box";
                    resultDiv.innerHTML = `Success! Your link: <a href="/${customName}" target="_blank">lessurl.xyz/${customName}</a>`;
                } else {
                    resultDiv.className = "";
                    resultDiv.style.color = "red";
                    resultDiv.innerText = data.message;
                }
            } catch (e) {
                console.error(e);
            }
            btn.innerText = "Generate Short Link";
        }
    </script>
</body>
</html>