<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}
$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $currency = $_POST['currency'];
    $amount = $_POST['amount'];
    $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, currency, amount) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $type, $currency, $amount]);
    echo "<script>alert('Transaction initiated!');</script>";
}
$stmt = $conn->prepare("SELECT * FROM transactions WHERE user_id = ?");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet - Coinbase Clone</title>
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
        .wallet {
            padding: 50px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .wallet h2 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #00d4ff;
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
        .transactions {
            margin-top: 50px;
        }
        .transactions h3 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #00d4ff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            text-align: left;
            font-size: 1rem;
        }
        th {
            background: rgba(0, 0, 0, 0.3);
        }
        tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.05);
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
    <section class="wallet">
        <h2>Your Wallet</h2>
        <form method="POST">
            <div class="form-group">
                <label for="type">Transaction Type</label>
                <select id="type" name="type" required>
                    <option value="deposit">Deposit</option>
                    <option value="withdrawal">Withdrawal</option>
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
            <button type="submit">Submit</button>
        </form>
        <div class="transactions">
            <h3>Transaction History</h3>
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Currency</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transaction['type']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['currency']); ?></td>
                            <td><?php echo number_format($transaction['amount'], 8); ?></td>
                            <td><?php echo htmlspecialchars($transaction['status']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>
