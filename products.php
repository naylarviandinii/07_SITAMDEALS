<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM products";
if ($search != '') {
    // Gunakan real_escape_string untuk keamanan dari SQL Injection
    $safe_search = $conn->real_escape_string($search);
    $query .= " WHERE name LIKE '%$safe_search%'";
}
$query .= " ORDER BY name ASC";
$data = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Katalog Produk – SiTamDeals</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { playfair: ['"Playfair Display"', 'serif'], dm: ['"DM Sans"', 'sans-serif'] },
          colors: { forest: '#1e3a2f', sage: '#4a8c64', mint: '#b8d9c5', cream: '#f7f4ee', gold: '#c9a84c' }
        }
      }
    }
  </script>
</head>

<body class="bg-cream text-forest">

<!-- NAVBAR MULAI DARI SINI (BARIS ~35 - ~85) -->
<nav class="fixed top-0 left-0 right-0 z-50 flex justify-between items-center px-[6%] h-[72px] bg-forest/95 backdrop-blur-md border-b border-leaf/10">
    
    <!-- LOGO -->
    <a href="index.php" class="font-playfair text-xl font-black text-cream tracking-wide">
        SiTam<span class="text-gold">Deals</span>
    </a>

    <!-- MENU DESKTOP -->
    <ul class="flex items-center gap-8 list-none">
      <li><a href="index.php" class="text-gold text-sm font-medium tracking-widest uppercase border-b-2 border-gold">Home</a></li>
      <li><a href="products.php" class="text-cream/75 hover:text-gold text-sm font-medium tracking-widest uppercase transition">Produk</a></li>
      
      <!-- DROPDOWN PROFILE -->
      <li class="relative inline-block">
        <button id="profileBtn" class="text-cream text-xl hover:text-gold transition focus:outline-none flex items-center">
            <i class="fas fa-user-circle"></i>
        </button>
        <!-- DROPDOWN MENU -->
        <div id="profileDropdown" class="hidden absolute right-0 mt-3 w-48 bg-white rounded-lg shadow-xl py-2 border border-gray-100 animate-in fade-in zoom-in duration-200">
            <a href="profil.php" class="block px-4 py-2 text-gray-700 hover:bg-gold/10 hover:text-gold transition text-sm font-medium">
                <i class="fas fa-user-edit mr-2"></i> Profil
            </a>
            <div class="border-t border-gray-100 my-1"></div>
            <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-red-50 transition text-sm font-medium">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
        </div>
      </li>
    </ul>

    <div id="mobile-menu" class="hidden absolute top-[72px] left-0 w-full bg-forest flex-col p-6 gap-4 border-b border-leaf/20 shadow-xl lg:hidden">
    </div>
</nav>

<script>
</script>
  <script>
    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');

    // Klik untuk buka/tutup
    profileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        profileDropdown.classList.toggle('hidden');
    });

    // Tutup jika klik di luar area menu
    window.addEventListener('click', (e) => {
        if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
            profileDropdown.classList.add('hidden');
        }
    });
  </script>
  
  <header class="pt-32 pb-12 px-[6%] bg-forest text-center">
      <h1 class="font-playfair text-3xl md:text-4xl font-black text-cream">Katalog Lengkap</h1>
      <p class="text-mint/60 text-sm mt-2 tracking-widest uppercase font-semibold">Cari kebutuhanmu di sini</p>
  </header>

  <section class="py-10 px-[6%]">
    <div class="max-w-6xl mx-auto flex justify-center">
      <form action="products.php" method="GET" class="w-full md:w-1/2 flex shadow-xl rounded-2xl overflow-hidden">
        <input type="text" name="search" placeholder="Cari minyak, beras, susu..." 
               value="<?= htmlspecialchars($search) ?>"
               class="w-full px-6 py-4 bg-white text-sm outline-none text-forest">
        <button type="submit" class="bg-gold text-forest px-8 font-bold hover:bg-gold-light transition-all">🔍</button>
      </form>
    </div>
  </section>

  <section class="pb-24 px-[6%]">
    <div class="max-w-6xl mx-auto">
      <?php if ($search != ''): ?>
        <p class="mb-8 text-sm text-gray-500 italic text-center">Menampilkan hasil untuk: <span class="font-bold text-forest">"<?= htmlspecialchars($search) ?>"</span></p>
      <?php endif; ?>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php if ($data && $data->num_rows > 0): ?>
          <?php while ($row = $data->fetch_assoc()): ?>
            <div class="group bg-white rounded-3xl overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 flex flex-col">
              
              <div class="h-52 overflow-hidden bg-gray-100 relative">
                <?php 
                  $imagePath = 'img/' . $row['image']; 
                  if (!empty($row['image']) && file_exists($imagePath)): 
                ?>
                  <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <?php else: ?>
                  <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                    <span class="text-5xl">🛒</span>
                    <span class="text-[10px] mt-2 italic">Gambar Belum Tersedia</span>
                  </div>
                <?php endif; ?>
                <div class="absolute top-3 left-3 bg-gold/90 text-forest text-[10px] font-black px-2 py-1 rounded-lg uppercase shadow-sm">Produk</div>
              </div>

              <div class="p-6 flex flex-col flex-grow">
                <span class="text-[10px] font-bold text-sage/60 uppercase tracking-[2px] mb-2">Tambah Jaya</span>
                <h3 class="font-bold text-forest text-sm mb-2 group-hover:text-gold transition-colors line-clamp-2">
                  <?= htmlspecialchars($row['name']) ?>
                </h3>
                <div class="font-playfair text-xl font-black text-sage mt-auto">
                  Rp <?= number_format($row['price'], 0, ',', '.') ?>
                </div>

                <div class="mt-5 pt-5 border-t border-gray-50 flex items-center justify-between">
                   <a href="detail.php?product_id=<?= $row['product_id'] ?>" class="text-[11px] font-bold text-forest/40 hover:text-gold transition uppercase tracking-widest">Lihat Detail</a>
                   <a href="detail.php?product_id=<?= $row['product_id'] ?>" class="bg-forest text-white w-10 h-10 rounded-xl hover:bg-gold hover:text-forest transition-all flex items-center justify-center shadow-lg font-bold">
                     +
                   </a>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="col-span-full text-center py-32">
            <div class="text-6xl mb-6">🥡</div>
            <h3 class="font-playfair text-2xl font-bold text-forest mb-2">Produk Tidak Ditemukan</h3>
            <p class="text-gray-400 text-sm">Coba kata kunci lain atau cek stok swalayan nanti.</p>
            <a href="products.php" class="mt-6 inline-block bg-forest text-white px-8 py-3 rounded-2xl font-bold shadow-lg">Reset Katalog</a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <footer class="bg-dark text-white/40 text-center py-10 text-sm tracking-wide">
    &copy; 2026 <span class="text-gold">SiTamDeals</span> — Proyek Website UPN "Veteran" Jawa Timur
  </footer>

</body>
</html>