<?php
 require_once '../admin/backend/process_login.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Simple E-Commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-50 text-gray-800 h-screen">

    <header class="bg-white shadow-md px-4 py-3 h-[calc(7vh)]">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="index.php" class="text-2xl font-bold text-blue-600">ShopEx</a>
            <p class="text-lg">Admin Dashboard</p>
        </div>
    </header>

    <main class="flex items-center justify-center h-[calc(82vh)]">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                        Username
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="username" 
                           type="username" 
                           name="username" 
                           placeholder="Enter your username"
                           required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="password" 
                           type="password" 
                           name="password" 
                           placeholder="Enter your password"
                           required>
                </div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
                        type="submit">
                    Sign In
                </button>
            </form>
        </div>
    </main>

    <?php include 'partials/footer.php'; ?>

</body>
</html>
