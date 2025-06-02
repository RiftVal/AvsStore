<header class="bg-white shadow-md px-4 py-3 h-[calc(7vh)]">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <a href="index.php" class="text-2xl font-bold text-blue-600">AVS Store</a>
        
        <div class="flex items-center gap-4">
            <a href="cart.php" class="text-gray-700 hover:text-blue-600">
                <i class="fas fa-shopping-cart"></i> Keranjang
            </a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="relative" id="userDropdown">
                    <button onclick="toggleDropdown()" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                        <i class="fas fa-user"></i> <?= $_SESSION['name'] ?>
                    </button>
                    <div id="dropdownContent" class="absolute right-0 mt-2 w-40 bg-white shadow-lg rounded hidden z-10">
                        <a href="profile.php" class="block px-4 py-2 hover:bg-gray-100">
                            <i class="fas fa-user-circle"></i> Profil
                        </a>
                        <a href="cart.php" class="block px-4 py-2 hover:bg-gray-100">
                            <i class="fas fa-shopping-cart"></i> Keranjang
                        </a>
                        <a href="transactions.php" class="block px-4 py-2 hover:bg-gray-100">
                            <i class="fas fa-shopping-bag"></i> Pesanan
                        </a>
                        <a href="../backend/logout.php" class="block px-4 py-2 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </a>
                    </div>
                </div>
                <script>
                    function toggleDropdown() {
                        document.getElementById('dropdownContent').classList.toggle('hidden');
                    }

                    // Menutup dropdown ketika mengklik di luar
                    window.onclick = function(event) {
                        if (!event.target.matches('#userDropdown *')) {
                            var dropdown = document.getElementById('dropdownContent');
                            if (!dropdown.classList.contains('hidden')) {
                                dropdown.classList.add('hidden');
                            }
                        }
                    }
                </script>
            <?php else: ?>
                <a href="login.php" class="text-sm bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700">
                    <i class="fas fa-sign-in-alt"></i> Masuk / Daftar
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
