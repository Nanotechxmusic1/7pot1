<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Recharge - 7Pot Games</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet" />
    <style>
        body {
            background-color: #000000;
            color: #ffd700;
            font-family: 'Arial', sans-serif;
        }
        .btn-gold {
            background-color: #ffd700;
            color: #000000;
        }
        .btn-gold:hover {
            background-color: #c7af6b;
        }
        .input-gold {
            background-color: #1a1a1a;
            border: 1px solid #ffd700;
            color: #ffd700;
        }
        .input-gold::placeholder {
            color: #c7af6b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid #ffd700;
            padding: 0.5rem;
            text-align: center;
        }
        th {
            background-color: #ffd700;
            color: #000000;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <?php include 'partials/nav_user.php'; ?>
    <main class="flex-grow container mx-auto p-6">
        <h1 class="text-4xl font-bold mb-6">Recharge Wallet</h1>
        <?php
        require_once 'config/functions.php';
        display_flash_messages();
        ?>
        <form action="recharge.php" method="POST" class="space-y-6 max-w-lg">
            <?php
            require_once 'config/csrf.php';
            $csrf_token = generate_csrf_token();
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />
            <div>
                <label for="amount" class="block mb-2">Recharge Amount (Minimum ₹200)</label>
                <input type="number" id="amount" name="amount" min="200" step="0.01" required placeholder="Enter amount" class="input-gold w-full px-3 py-2 rounded" />
            </div>
            <div>
                <label for="utr" class="block mb-2">UTR Number</label>
                <input type="text" id="utr" name="utr" required placeholder="Enter UTR number" class="input-gold w-full px-3 py-2 rounded" />
            </div>
            <div>
                <label for="user_upi" class="block mb-2">Your UPI ID (optional)</label>
                <input type="text" id="user_upi" name="user_upi" placeholder="Your UPI ID" class="input-gold w-full px-3 py-2 rounded" />
            </div>
            <button type="submit" class="btn-gold px-6 py-2 rounded font-semibold">Submit Recharge</button>
        </form>
        <section class="mt-10">
            <h2 class="text-2xl font-semibold mb-4">Recharge History</h2>
            <table>
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>UTR</th>
                        <th>Your UPI</th>
                        <th>Status</th>
                        <th>Requested At</th>
                        <th>Processed At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recharges as $rc): ?>
                        <tr>
                            <td>₹<?php echo number_format($rc['amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($rc['utr']); ?></td>
                            <td><?php echo htmlspecialchars($rc['user_upi']); ?></td>
                            <td><?php echo ucfirst($rc['status']); ?></td>
                            <td><?php echo $rc['created_at']; ?></td>
                            <td><?php echo $rc['processed_at'] ?? '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
