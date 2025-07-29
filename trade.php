<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $type = $_POST['type'];
    $action = $_POST['action'];
    $currency = $_POST['currency'];
    $amount = $_POST['amount'];
    $price = $_POST['price'] ?? null;
    $stmt = $conn->prepare("INSERT INTO orders (user_id, type, action, currency, amount, price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $type, $action, $currency, $amount, $price]);
    echo "<script>alert('Order placed successfully!');</script>";
}

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

// Fetch historical chart data for BTC
function getChartData() {
    $apiKey = 'CG-okNuw6BnPDRHVKmdyGB2BQP4';
    $url = 'https://api.coingecko.com/api/v3/coins/bitcoin/market_chart?vs_currency=usd&days=30';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-Cg-Pro-Api-Key: $apiKey"]);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}
$chartData = getChartData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade - Coinbase Clone</title>
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
        .trade {
            padding: 50px 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .trade h2 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #00d4ff;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 1rem;
        }
        select, input {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 1rem;
        }
        select:focus, input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.3);
        }
        button {
            width: 100%;
            padding: 12px;
            background: #00d4ff;
            border: none;
            border-radius: 8px;
            color: #0a0c2c;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #00b0d4;
        }
        .prices {
            margin-bottom: 40px;
            text-align: center;
        }
        .prices h3 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #00d4ff;
        }
        .price-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        .price-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 8px;
        }
        .price-card p {
            font-size: 1.1rem;
        }
        canvas {
            margin-top: 40px;
            max-width: 100%;
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
    <section class="trade">
        <h2>Place a Trade</h2>
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
        <form method="POST">
            <div class="form-group">
                <label for="type">Order Type</label>
                <select id="type" name="type" required>
                    <option value="market">Market</option>
                    <option value="limit">Limit</option>
                </select>
            </div>
            <div class="form-group">
                <label for="action">Action</label>
                <select id="action" name="action" required>
                    <option value="buy">Buy</option>
                    <option value="sell">Sell</option>
                </select>
            </div>
            <div class="form-group">
                <label for="currency">Currency</label>
                <select id="currency" name="currency" required>
                    <option value="BTC">Bitcoin (BTC)</option>
                    <option value="ETH">Ethereum (ETH)</option>
                    <option value="BNB">Binance Coin (BNB)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount" step="0.00000001" required>
            </div>
            <div class="form-group">
                <label for="price">Price (Limit Orders Only)</label>
                <input type="number" id="price" name="price" step="0.01">
            </div>
            <button type="submit">Place Order</button>
        </form>
        <canvas id="priceChart"></canvas>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('priceChart').getContext('2d');
        const chartData = <?php
            $labels = [];
            $data = [];
            foreach ($chartData['prices'] ?? [] as $point) {
                $labels[] = new Date($point[0]).toLocaleDateString();
                $data[] = $point[1];
            }
            echo json_encode(['labels' => $labels, 'data' => $data]);
        ?>;
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'BTC Price (USD)',
                    data: chartData.data,
                    borderColor: '#00d4ff',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });
    </script>
</body>
</html>
