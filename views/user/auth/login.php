<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - 7Pot Games</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
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
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-8 space-y-6 bg-black bg-opacity-80 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-center mb-6">Login to 7Pot Games</h2>
        <?php
        require_once '../../../config/functions.php';
        display_flash_messages();
        ?>
        <form action="login.php" method="POST" class="space-y-4">
            <?php
            require_once '../../../config/csrf.php';
            $csrf_token = generate_csrf_token();
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />
            <div>
                <label for="email" class="block mb-1">Email</label>
                <input type="email" id="email" name="email" required placeholder="you@example.com" class="input-gold w-full px-3 py-2 rounded" />
            </div>
            <div>
                <label for="password" class="block mb-1">Password</label>
                <input type="password" id="password" name="password" required placeholder="********" class="input-gold w-full px-3 py-2 rounded" />
            </div>
            <button type="submit" class="btn-gold w-full py-2 rounded font-semibold transition-colors">Login</button>
        </form>
        <p class="text-center mt-4 text-sm text-gray-400">
            Don't have an account? <a href="register.php" class="text-yellow-400 hover:text-yellow-300">Register here</a>
        </p>
    </div>
</body>
</html>
