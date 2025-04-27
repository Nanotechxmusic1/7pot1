<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - 7Pot Games</title>
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
        a:hover {
            color: #c7af6b;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <?php include 'partials/nav_user.php'; ?>
    <main class="flex-grow container mx-auto p-6">
        <h1 class="text-4xl font-bold mb-6">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <?php
        require_once 'config/functions.php';
        display_flash_messages();
        ?>
        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Wallet Balance</h2>
            <div class="text-3xl font-bold text-yellow-400">₹<?php echo number_format($user['balance'], 2); ?></div>
        </section>
        <section>
            <h2 class="text-2xl font-semibold mb-4">Available Games</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php foreach ($games as $game): ?>
                    <div class="border border-yellow-400 rounded p-4 hover:bg-yellow-900 transition cursor-pointer">
                        <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($game['name']); ?></h3>
                        <p>Minimum Bet: ₹<?php echo number_format($game['min_bet'], 2); ?></p>
                        <p>Numbers: <?php echo $game['numbers']; ?></p>
                        <a href="bet.php?game_id=<?php echo $game['id']; ?>" class="btn-gold inline-block mt-4 px-4 py-2 rounded font-semibold">Place Bet</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
