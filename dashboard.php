<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM wallets WHERE user_id = ?");
$stmt->execute([$user_id]);
$wallets = $stmt->fetchAll();

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
    <title>Dashboard - Coinbase Clone</title>
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
        .dashboard {
            padding: 50px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .dashboard h2 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #00d4ff;
        }
        .wallet-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .wallet-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        }
        .wallet-card h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .wallet-card p {
            font-size: 1.2rem;
            color: #00d4ff;
        }
        .prices {
            margin-top: 40px;
        }
        .prices h3 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #00d4ff;
        }
        .price-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .price-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .price-card p {
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>Coinbase Clone</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="trade.php">Trade</a>
            <a href="wallet.php">Wallet</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <section class="dashboard">
        <h2>Your Portfolio</h2>
        <div class="wallet-grid">
            <?php foreach ($wallets as $wallet): ?>
                <div class="wallet-card">
                    <h3><?php echo htmlspecialchars($wallet['currency']); ?></h3>
                    <p>Balance: <?php echo number_format($wallet['balance'], 8); ?></p>
                    <p>Address: <?php echo htmlspecialchars($wallet['address']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="prices">
            <h3>Live Market Prices</h3>
            <div class="price-grid">
                <div class="price-card">
                    <h4>Bitcoin (BTC)</h4>
                    <p>$<?php echo number_format($prices['bitcoin']['usd'] ?? 60000, 2); ?></p>
                </div>
                <div class="price-card">
                    <h4>Ethereum (ETH)</h4>
                    <p>$<?php echo number_format($prices['ethereum']['usd'] ?? 2500, 2); ?></p>
                </div>
                <div class="price-card">
                    <h4>Binance Coin (BNB)</h4>
                    <p>$<?php echo number_format($prices['binancecoin']['usd'] ?? 600, 2); ?></p>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
