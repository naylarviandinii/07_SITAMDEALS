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
                fontFamily: { playfair: ['Playfair Display', 'serif'], dm: ['DM Sans', 'sans-serif'] },
                colors: {
                    // Tema cream yang lebih soft dengan kontras tinggi untuk text
                    cream: '#fefbf3',           // Cream sangat terang (background utama)
                    'cream-dark': '#f5f0e6',    // Cream sedikit lebih gelap
                    'cream-light': '#fffdf9',   // Cream paling terang
                    taupe: '#8b7b5f',           // Taupe gelap untuk text utama (kontras tinggi)
                    'taupe-dark': '#6b5a42',    // Taupe lebih gelap untuk heading
                    'taupe-light': '#a89c7e',   // Taupe terang untuk accent
                    beige: '#d9c9a2',           // Beige untuk border/hover
                    'beige-dark': '#b8a884',    // Beige lebih gelap
                    gold: '#d4af37',            // Gold untuk highlight
                    'gold-light': '#e8c968'     // Gold terang
                },
                animation: {
                    'fade-up': {
                        '0%': { opacity: '0', transform: 'translateY(30px)' },
                        '100%': { opacity: '1', transform: 'translateY(0)' }
                    }
                }
            }
        }
    }
  </script>
</head>

<body class="bg-cream text-taupe">

<!-- NAVBAR MULAI DARI SINI (BARIS ~35 - ~85) -->
<nav class="fixed top-0 left-0 right-0 z-50 flex justify-between items-center px-[6%] h-[72px] bg-taupe-dark/95 backdrop-blur-md border-b border-beige/20">
    
    <!-- LOGO -->
    <a href="index.php" class="font-playfair text-xl font-black text-cream-light tracking-wide">
        SiTam<span class="text-gold">Deals</span>
    </a>

    <!-- MENU DESKTOP -->
    <ul class="flex items-center gap-8 list-none">
      <li><a href="index.php" class="text-gold text-sm font-medium tracking-widest uppercase border-b-2 border-gold">Home</a></li>
      <li><a href="products.php" class="text-cream-light/75 hover:text-gold text-sm font-medium tracking-widest uppercase transition">Produk</a></li>
      
      <!-- DROPDOWN PROFILE -->
      <li class="relative inline-block">
        <button id="profileBtn" class="text-cream-light text-xl hover:text-gold transition focus:outline-none flex items-center">
            <i class="fas fa-user-circle"></i>
        </button>
        <!-- DROPDOWN MENU -->
        <div id="profileDropdown" class="hidden absolute right-0 mt-3 w-48 bg-cream-light rounded-lg shadow-xl py-2 border border-beige animate-in fade-in zoom-in duration-200">
            <a href="profil.php" class="block px-4 py-2 text-taupe hover:bg-gold/10 hover:text-gold transition text-sm font-medium">
                <i class="fas fa-user-edit mr-2"></i> Profil
            </a>
            <div class="border-t border-beige/50 my-1"></div>
            <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-red-50 transition text-sm font-medium">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
        </div>
      </li>
    </ul>

    <div id="mobile-menu" class="hidden absolute top-[72px] left-0 w-full bg-taupe-dark flex-col p-6 gap-4 border-b border-beige/30 shadow-xl lg:hidden">
    </div>
</nav>

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
  
  <header class="pt-32 pb-12 px-[6%] bg-taupe-dark text-center">
      <h1 class="font-playfair text-3xl md:text-4xl font-black text-cream-light">Katalog Lengkap</h1>
      <p class="text-beige text-sm mt-2 tracking-widest uppercase font-semibold">Cari kebutuhanmu di sini</p>
  </header>

  <section class="py-10 px-[6%]">
    <div class="max-w-6xl mx-auto flex justify-center">
      <form action="products.php" method="GET" class="w-full md:w-1/2 flex shadow-xl rounded-2xl overflow-hidden">
        <input type="text" name="search" placeholder="Cari minyak, beras, susu..." 
               value="<?= htmlspecialchars($search) ?>"
               class="w-full px-6 py-4 bg-cream-light text-sm outline-none text-taupe-dark">
        <button type="submit" class="bg-gold text-taupe-dark px-8 font-bold hover:bg-gold-light transition-all">🔍</button>
      </form>
    </div>
  </section>

  <section class="pb-24 px-[6%]">
    <div class="max-w-6xl mx-auto">
      <?php if ($search != ''): ?>
        <p class="mb-8 text-sm text-taupe-light/70 italic text-center">Menampilkan hasil untuk: <span class="font-bold text-taupe-dark">"<?= htmlspecialchars($search) ?>"</span></p>
      <?php endif; ?>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php if ($data && $data->num_rows > 0): ?>
          <?php while ($row = $data->fetch_assoc()): ?>
            <div class="group bg-cream-light rounded-3xl overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-beige/50 flex flex-col">
              
              <div class="h-52 overflow-hidden bg-beige/20 relative">
                <?php 
                  $imagePath = 'img/' . $row['image']; 
                  if (!empty($row['image']) && file_exists($imagePath)): 
                ?>
                  <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <?php else: ?>
                  <div class="w-full h-full flex flex-col items-center justify-center text-taupe-light/50">
                    <span class="text-5xl">🛒</span>
                    <span class="text-[10px] mt-2 italic">Gambar Belum Tersedia</span>
                  </div>
                <?php endif; ?>
                <div class="absolute top-3 left-3 bg-gold/90 text-taupe-dark text-[10px] font-black px-2 py-1 rounded-lg uppercase shadow-sm">Produk</div>
              </div>

              <div class="p-6 flex flex-col flex-grow">
                <span class="text-[10px] font-bold text-taupe-light/70 uppercase tracking-[2px] mb-2">Tambah Jaya</span>
                <h3 class="font-bold text-taupe-dark text-sm mb-2 group-hover:text-gold transition-colors line-clamp-2">
                  <?= htmlspecialchars($row['name']) ?>
                </h3>
                <div class="font-playfair text-xl font-black text-taupe-dark mt-auto">
                  Rp <?= number_format($row['price'], 0, ',', '.') ?>
                </div>

                <div class="mt-5 pt-5 border-t border-beige/30 flex items-center justify-between">
                   <a href="detail.php?product_id=<?= $row['product_id'] ?>" class="text-[11px] font-bold text-taupe/60 hover:text-gold transition uppercase tracking-widest">Lihat Detail</a>
                   <a href="detail.php?product_id=<?= $row['product_id'] ?>" class="bg-taupe-dark text-cream-light w-10 h-10 rounded-xl hover:bg-gold hover:text-taupe-dark transition-all flex items-center justify-center shadow-lg font-bold">
                     +
                   </a>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="col-span-full text-center py-32">
            <div class="text-6xl mb-6">🥡</div>
            <h3 class="font-playfair text-2xl font-bold text-taupe-dark mb-2">Produk Tidak Ditemukan</h3>
            <p class="text-taupe-light/60 text-sm">Coba kata kunci lain atau cek stok swalayan nanti.</p>
            <a href="products.php" class="mt-6 inline-block bg-taupe-dark text-cream-light px-8 py-3 rounded-2xl font-bold shadow-lg hover:bg-gold hover:text-taupe-dark transition-all">Reset Katalog</a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <footer class="bg-taupe-dark text-cream-light/60 text-center py-10 text-sm tracking-wide">
    &copy; 2026 <span class="text-gold">SiTamDeals</span> — Proyek Website UPN "Veteran" Jawa Timur
  </footer>

</body>
</html>