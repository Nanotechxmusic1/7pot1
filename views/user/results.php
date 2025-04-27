<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Results - 7Pot Games</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet" />
    <style>
        body {
            background-color: #000000;
            color: #ffd700;
            font-family: 'Arial', sans-serif;
        }
        .ticker {
            background-color: #1a1a1a;
            border: 1px solid #ffd700;
            padding: 10px;
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
        }
        .ticker-item {
            display: inline-block;
            padding: 0 2rem;
            animation: ticker 20s linear infinite;
        }
        @keyframes ticker {
            0% { transform: translate3d(100%, 0, 0); }
            100% { transform: translate3d(-100%, 0, 0); }
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
        <h1 class="text-4xl font-bold mb-6">Recent Results</h1>
        <div class="ticker mb-6">
            <?php foreach ($recentResults as $result): ?>
                <span class="ticker-item">
                    <?php echo htmlspecialchars($result['game_name']); ?>: <?php echo $result['result_number']; ?> at <?php echo $result['result_time']; ?>
                </span>
            <?php endforeach; ?>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Game</th>
                    <th>Result Number</th>
                    <th>Result Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentResults as $result): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($result['game_name']); ?></td>
                        <td><?php echo $result['result_number']; ?></td>
                        <td><?php echo $result['result_time']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
