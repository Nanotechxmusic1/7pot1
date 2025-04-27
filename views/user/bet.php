<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Place Bet - 7Pot Games</title>
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
        .number-cell {
            border: 1px solid #ffd700;
            color: #ffd700;
            cursor: pointer;
            user-select: none;
            transition: background-color 0.3s;
        }
        .number-cell:hover {
            background-color: #c7af6b;
            color: #000000;
        }
        .number-cell.selected {
            background-color: #ffd700;
            color: #000000;
        }
        .input-gold {
            background-color: #1a1a1a;
            border: 1px solid #ffd700;
            color: #ffd700;
        }
        .input-gold::placeholder {
            color: #c7af6b;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cells = document.querySelectorAll('.number-cell');
            const betNumberInput = document.getElementById('bet_number');

            cells.forEach(cell => {
                cell.addEventListener('click', () => {
                    cells.forEach(c => c.classList.remove('selected'));
                    cell.classList.add('selected');
                    betNumberInput.value = cell.dataset.number;
                });
            });
        });
    </script>
</head>
<body class="min-h-screen flex flex-col">
    <?php include 'partials/nav_user.php'; ?>
    <main class="flex-grow container mx-auto p-6">
        <h1 class="text-4xl font-bold mb-6">Place Bet - <?php echo htmlspecialchars($game['name']); ?></h1>
        <?php
        require_once 'config/functions.php';
        display_flash_messages();
        ?>
        <form action="bet.php" method="POST" class="space-y-6 max-w-lg">
            <?php
            require_once 'config/csrf.php';
            $csrf_token = generate_csrf_token();
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />
            <input type="hidden" id="bet_number" name="bet_number" required />
            <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>" />
            <div>
                <label class="block mb-2">Select Number</label>
                <div class="grid grid-cols-10 gap-2 max-h-64 overflow-y-auto border border-yellow-400 p-2 rounded">
                    <?php
                    for ($i = 0; $i < $game['numbers']; $i++) {
                        echo "<div class='number-cell p-2 text-center rounded' data-number='{$i}'>{$i}</div>";
                    }
                    ?>
                </div>
            </div>
            <div>
                <label for="amount" class="block mb-2">Bet Amount (Minimum ₹<?php echo number_format($game['min_bet'], 2); ?>)</label>
                <input type="number" id="amount" name="amount" min="<?php echo $game['min_bet']; ?>" step="0.01" required placeholder="Enter bet amount" class="input-gold w-full px-3 py-2 rounded" />
            </div>
            <button type="submit" class="btn-gold px-6 py-2 rounded font-semibold">Place Bet</button>
        </form>
    </main>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
