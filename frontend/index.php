<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple E-Commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-50 text-gray-800 h-screen">

    <?php include 'partials/header.php'; ?>

    <main class="flex max-w-7xl mx-auto mt-6 px-4 gap-4 h-[calc(82vh)]">
        <?php include 'partials/sidebar.php'; ?>
        <?php include 'partials/products.php'; ?>
    </main>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
