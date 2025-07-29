<?php
// Fetch real-time prices from CoinGecko API
function getCryptoPrices() {
    $apiKey = 'CG-okNuw6BnPDRHVKmdyGB2BQP4';
    $url = 'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin,ethereum,binancecoin&vs_currencies=usd';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-Cg-Pro-Api-Key: $apiKey"]);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}
$prices = getCryptoPrices();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coinbase Clone - Home</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #0a0c2c, #1e3a8a);
            color: #fff;
        }
        header {
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            font-size: 2rem;
            color: #00d4ff;
        }
        nav a {
            color: #fff;
            margin: 0 15px;
            text-decoration: none;
            font-size: 1.1rem;
        }
        nav a:hover {
            color: #00d4ff;
        }
        .hero {
            text-align: center;
            padding: 100px 20px;
        }
        .hero h2 {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #00d4ff;
        }
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        .hero button {
            padding: 15px 30px;
            background: #00d4ff;
            border: none;
            border-radius: 8px;
            color: #0a0c2c;
            font-size: 1.2rem;
            cursor: pointer;
        }
        .hero button:hover {
            background: #00b0d4;
        }
        .prices {
            padding: 50px 20px;
            text-align: center;
        }
        .prices h2 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #00d4ff;
        }
        .price-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .price-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        }
        .price-card h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .price-card p {
            font-size: 1.2rem;
            color: #00d4ff;
        }
    </style>
</head>
<body>
    <header>
        <h1>Coinbase Clone</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="signup.php">Sign Up</a>
            <a href="login.php">Login</a>
        </nav>
    </header>
    <section class="hero">
        <h2>Trade Cryptocurrencies with Ease</h2>
        <p>Join the future of finance with our secure and user-friendly platform.</p>
        <button onclick="window.location.href='signup.php'">Get Started</button>
    </section>
    <section class="prices">
        <h2>Live Crypto Prices</h2>
        <div class="price-grid">
            <div class="price-card">
                <h3>Bitcoin (BTC)</h3>
                <p>$<?php echo number_format($prices['bitcoin']['usd'] ?? 60000, 2); ?></p>
            </div>
            <div class="price-card">
                <h3>Ethereum (ETH)</h3>
                <p>$<?php echo number_format($prices['ethereum']['usd'] ?? 2500, 2); ?></p>
            </div>
            <div class="price-card">
                <h3>Binance Coin (BNB)</h3>
                <p>$<?php echo number_format($prices['binancecoin']['usd'] ?? 600, 2); ?></p>
            </div>
        </div>
    </section>
</body>
</html>
