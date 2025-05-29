<section class="flex-1">
    <h2 class="text-xl font-semibold mb-4">Katalog Produk</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <!-- Contoh produk -->
        <?php for ($i = 1; $i <= 8; $i++): ?>
        <div class="bg-white rounded shadow p-4">
            <a href="product-detail.php?id=<?= $i ?>" class="block">
            <div class="aspect-square bg-gray-200 mb-2">
                <img 
                    src="<?= isset($product_image) ? $product_image : 'https://placehold.co/400x400?text=No+Image' ?>" 
                    alt="Produk <?= $i ?>"
                    class="w-full h-full object-cover"
                >
            </div>
            <h3 class="text-sm font-medium">Produk <?= $i ?></h3>
            <p class="text-blue-600 font-semibold">Rp <?= number_format(10000 * $i) ?></p>
            </a>
            <button class="mt-2 w-full bg-blue-600 text-white text-sm py-1.5 rounded hover:bg-blue-700">Tambah ke Keranjang</button>
        </div>
        <?php endfor; ?>
    </div>
</section>
