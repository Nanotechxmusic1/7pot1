<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Withdrawals - 7Pot Games</title>
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
        <h1 class="text-4xl font-bold mb-6">Withdrawals</h1>
        <?php
        require_once 'config/functions.php';
        display_flash_messages();
        ?>
        <form action="withdrawal.php" method="POST" class="space-y-6 max-w-lg">
            <?php
            require_once 'config/csrf.php';
            $csrf_token = generate_csrf_token();
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />
            <div>
                <label for="amount" class="block mb-2">Withdrawal Amount (Minimum ₹800)</label>
                <input type="number" id="amount" name="amount" min="800" step="0.01" required placeholder="Enter amount" class="input-gold w-full px-3 py-2 rounded" />
            </div>
            <div>
                <label for="upi_id" class="block mb-2">UPI ID (optional)</label>
                <input type="text" id="upi_id" name="upi_id" placeholder="Your UPI ID" class="input-gold w-full px-3 py-2 rounded" />
            </div>
            <div>
                <label for="bank_details" class="block mb-2">Bank Details (optional)</label>
                <textarea id="bank_details" name="bank_details" rows="3" placeholder="Bank account details" class="input-gold w-full px-3 py-2 rounded"></textarea>
            </div>
            <button type="submit" class="btn-gold px-6 py-2 rounded font-semibold">Request Withdrawal</button>
        </form>
        <section class="mt-10">
            <h2 class="text-2xl font-semibold mb-4">Withdrawal History</h2>
            <table>
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>UPI ID</th>
                        <th>Bank Details</th>
                        <th>Status</th>
                        <th>Requested At</th>
                        <th>Processed At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($withdrawals as $wd): ?>
                        <tr>
                            <td>₹<?php echo number_format($wd['amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($wd['upi_id']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($wd['bank_details'])); ?></td>
                            <td><?php echo ucfirst($wd['status']); ?></td>
                            <td><?php echo $wd['created_at']; ?></td>
                            <td><?php echo $wd['processed_at'] ?? '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
