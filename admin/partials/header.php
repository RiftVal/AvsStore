<?php
// Ensure we have an active session
// session_start();
// if (!isset($_SESSION['admin_id'])) {
//     header('Location: login.php');
//     exit();
// }
?>  
<!-- Main content area with header -->
<header class="bg-gray-100 shadow-md">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Search Bar -->
            <div class="flex-1 max-w-lg">
                <div class="relative">
                    <input type="text" 
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500" 
                        placeholder="Search...">
                    <div class="absolute left-3 top-2.5">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Profile Section -->
            <!-- Profile Section -->
            <div class="ml-4 flex items-center space-x-4">
                <!-- Profile Button with Navigation -->
                <a href="profile-settings.php" class="flex items-center space-x-3 hover:bg-gray-200 rounded-lg px-3 py-2 transition-colors">
                    <img class="h-8 w-8 rounded-full object-cover"
                        src="<?php echo isset($_SESSION['admin_avatar']) ? $_SESSION['admin_avatar'] : 'assets/default-avatar.png'; ?>" 
                        alt="Profile">
                    <span class="text-gray-700"><?php echo $_SESSION['admin_name'] ?? 'Admin'; ?></span>
                </a>
                <!-- Logout Button with Icon -->
                <a href="../backend/logout.php" class="p-2 text-gray-600 hover:text-red-600 hover:bg-gray-200 rounded-lg transition-colors" title="Logout">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </a>
            </div>

        </div>
    </div>
</header>
