<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register - 7Pot Games</title>
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
        <h2 class="text-3xl font-bold text-center mb-6">Register for 7Pot Games</h2>
        <?php
        require_once '../../../config/functions.php';
        display_flash_messages();
        ?>
        <form action="register.php" method="POST" class="space-y-4">
            <?php
            require_once '../../../config/csrf.php';
            $csrf_token = generate_csrf_token();
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />
            <div>
                <label for="name" class="block mb-1">Name</label>
                <input type="text" id="name" name="name" required placeholder="Your full name" class="input-gold w-full px-3 py-2 rounded" />
            </div>
            <div>
                <label for="mobile" class="block mb-1">Mobile Number</label>
                <input type="text" id="mobile" name="mobile" required placeholder="10-digit mobile number" class="input-gold w-full px-3 py-2 rounded" />
            </div>
            <div>
                <label for="email" class="block mb-1">Email</label>
                <input type="email" id="email" name="email" required placeholder="you@example.com" class="input-gold w-full px-3 py-2 rounded" />
            </div>
            <div>
                <label for="password" class="block mb-1">Password</label>
                <input type="password" id="password" name="password" required placeholder="********" class="input-gold w-full px-3 py-2 rounded" />
            </div>
            <div>
                <label for="confirm_password" class="block mb-1">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="********" class="input-gold w-full px-3 py-2 rounded" />
            </div>
            <div>
                <label for="referral_code" class="block mb-1">Referral Code (optional)</label>
                <input type="text" id="referral_code" name="referral_code" placeholder="Referral code" class="input-gold w-full px-3 py-2 rounded" />
            </div>
            <button type="submit" class="btn-gold w-full py-2 rounded font-semibold transition-colors">Register</button>
        </form>
        <p class="text-center mt-4 text-sm text-gray-400">
            Already have an account? <a href="login.php" class="text-yellow-400 hover:text-yellow-300">Login here</a>
        </p>
    </div>
</body>
</html>
