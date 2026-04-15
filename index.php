<?php
session_start();
if (!isset($_SESSION['user']))
  header("Location: login.php");
include 'db.php';
$data = $conn->query("SELECT * FROM products LIMIT 3");
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SiTamDeals – Beranda</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            'pulse-ring': 'pulseRing 6s ease-in-out infinite',
            'bounce-down': 'bounceDown 2s ease infinite',
            'fade-up': {
              '0%': { opacity: '0', transform: 'translateY(30px)' },
              '100%': { opacity: '1', transform: 'translateY(0)' }
            },
          },
          keyframes: {
            pulseRing: { '0%,100%': { transform: 'scale(1)', opacity: '1' }, '50%': { transform: 'scale(1.05)', opacity: '0.6' } },
            bounceDown: { '0%,100%': { transform: 'translateX(-50%) translateY(0)' }, '50%': { transform: 'translateX(-50%) translateY(8px)' } },
          }
        }
      }
    }
  </script>
  <style>
    body { font-family: 'DM Sans', sans-serif; }
    .nav-link::after { content: ''; position: absolute; bottom: -4px; left: 0; width: 0; height: 2px; background: #d4af37; transition: width .3s; }
    .nav-link:hover::after, .nav-link.active::after { width: 100%; }
    /* Mobile Menu Toggle */
    #menu-toggle:checked ~ #mobile-menu { display: flex; }
  </style>
</head>

<body class="bg-cream text-taupe overflow-x-hidden">

  <nav class="fixed top-0 left-0 right-0 z-50 flex justify-between items-center px-[6%] h-[72px] bg-taupe-dark/95 backdrop-blur-md border-b border-beige/20">
    
    <a href="index.php" class="font-playfair text-xl font-black text-cream-light tracking-wide">
        SiTam<span class="text-gold">Deals</span>
    </a>

    <ul class="flex items-center gap-8 list-none">
      <li>
          <a href="index.php" class="nav-link text-gold text-sm font-medium tracking-widest uppercase border-b-2 border-gold">Home</a>
      </li>
      <li>
          <a href="products.php" class="nav-link text-cream-light/75 hover:text-gold text-sm font-medium tracking-widest uppercase transition relative">Produk</a>
      </li>
      
      <li class="relative inline-block">
        <button id="profileBtn" class="text-cream-light text-xl hover:text-gold transition focus:outline-none flex items-center">
            <i class="fas fa-user-circle"></i>
        </button>

        <div id="profileDropdown" class="hidden absolute right-0 mt-3 w-48 bg-cream-light rounded-lg shadow-xl py-2 border border-beige/50 animate-in fade-in zoom-in duration-200">
            <a href="profil.php" class="block px-4 py-2 text-taupe hover:bg-gold/10 hover:text-gold transition text-sm font-medium">
                <i class="fas fa-user-edit mr-2"></i> Profil
            </a>
            <div class="border-t border-beige/30 my-1"></div>
            <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-red-50 transition text-sm font-medium">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
        </div>
      </li>
    </ul>

    <div id="mobile-menu" class="hidden absolute top-[72px] left-0 w-full bg-taupe-dark flex-col p-6 gap-4 border-b border-beige/20 shadow-xl lg:hidden">
        <a href="index.php" class="text-gold font-bold uppercase tracking-widest text-sm">Home</a>
        <a href="products.php" class="text-cream-light font-bold uppercase tracking-widest text-sm">Produk</a>
        <a href="profil.php" class="text-cream-light font-bold uppercase tracking-widest text-sm">Profil</a>
        <a href="logout.php" class="text-red-400 font-bold uppercase tracking-widest text-sm">Logout</a>
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

  <section class="relative min-h-screen flex items-center px-[6%] pt-20 overflow-hidden"
    style="background: linear-gradient(135deg,rgba(107,90,66,.92) 0%,rgba(139,123,95,.75) 60%,rgba(168,156,126,.4) 100%), url('https://images.unsplash.com/photo-1578916171728-46686eac8d58?w=1600&q=80') center/cover no-repeat;">
    <div class="relative z-10 max-w-2xl" style="animation:fadeUp .8s ease both">
      <div class="inline-flex items-center gap-2 bg-gold/20 border border-gold/40 text-gold-light text-[10px] md:text-xs font-semibold tracking-[2px] uppercase px-4 py-1.5 rounded-full mb-5 md:mb-7">
        ✦ Swalayan Tambah Jaya
      </div>
      <h1 class="font-playfair text-4xl md:text-5xl lg:text-6xl font-black text-cream-light leading-tight mb-6">
        Belanja Lebih <em class="not-italic text-gold">Mudah,</em><br>
        Hidup Lebih <em class="not-italic text-gold">Hemat</em>
      </h1>
      <p class="text-cream-light text-base md:text-lg leading-relaxed max-w-lg mb-8 md:mb-10 font-light">
        Temukan ribuan produk berkualitas dengan harga yang transparan dan terjangkau.
      </p>
    </div>
  </section>

  <div class="bg-taupe-dark py-7 px-[6%] overflow-x-auto">
    <div class="flex min-w-[500px] lg:min-w-0 lg:max-w-4xl lg:mx-auto">
      <div class="flex-1 text-center border-r border-cream-light/20">
        <div class="font-playfair text-2xl md:text-3xl font-black text-gold mb-1">1000+</div>
        <div class="text-[10px] text-cream-light/60 tracking-widest uppercase">Produk</div>
      </div>
      <div class="flex-1 text-center border-r border-cream-light/20">
        <div class="font-playfair text-2xl md:text-3xl font-black text-gold mb-1">10K+</div>
        <div class="text-[10px] text-cream-light/60 tracking-widest uppercase">Pelanggan</div>
      </div>
      <div class="flex-1 text-center border-r border-cream-light/20">
        <div class="font-playfair text-2xl md:text-3xl font-black text-gold mb-1">5+</div>
        <div class="text-[10px] text-cream-light/60 tracking-widest uppercase">Tahun</div>
      </div>
      <div class="flex-1 text-center">
        <div class="font-playfair text-2xl md:text-3xl font-black text-gold mb-1">4.9★</div>
        <div class="text-[10px] text-cream-light/60 tracking-widest uppercase">Rating</div>
      </div>
    </div>
  </div>

  <section class="py-16 md:py-24 px-[6%] bg-cream" id="kategori">
    <div class="text-center mb-10 md:mb-14">
      <div class="text-[10px] md:text-xs font-semibold tracking-[3px] uppercase text-taupe-light mb-3">Jelajahi Kategori</div>
      <h2 class="font-playfair text-3xl md:text-4xl font-black text-taupe-dark">Semua Ada di Sini</h2>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 max-w-6xl mx-auto">
      <div class="bg-cream-light rounded-xl p-6 text-center shadow-sm hover:shadow-md transition-all border border-beige/30 hover:border-beige">
        <span class="text-3xl mb-2 block">🧀</span>
        <div class="text-xs font-bold text-taupe-dark uppercase">Susu</div>
      </div>
      <div class="bg-cream-light rounded-xl p-6 text-center shadow-sm hover:shadow-md transition-all border border-beige/30 hover:border-beige">
        <span class="text-3xl mb-2 block">🍜</span>
        <div class="text-xs font-bold text-taupe-dark uppercase">Bumbu</div>
      </div>
      <div class="bg-cream-light rounded-xl p-6 text-center shadow-sm hover:shadow-md transition-all border border-beige/30 hover:border-beige">
        <span class="text-3xl mb-2 block">🧴</span>
        <div class="text-xs font-bold text-taupe-dark uppercase">Perawatan</div>
      </div>
      <div class="bg-cream-light rounded-xl p-6 text-center shadow-sm hover:shadow-md transition-all border border-beige/30 hover:border-beige">
        <span class="text-3xl mb-2 block">🧹</span>
        <div class="text-xs font-bold text-taupe-dark uppercase">Kebersihan</div>
      </div>
      <div class="bg-cream-light rounded-xl p-6 text-center shadow-sm hover:shadow-md transition-all border border-beige/30 hover:border-beige">
        <span class="text-3xl mb-2 block">🍪</span>
        <div class="text-xs font-bold text-taupe-dark uppercase">Camilan</div>
      </div>
      <div class="bg-cream-light rounded-xl p-6 text-center shadow-sm hover:shadow-md transition-all border border-beige/30 hover:border-beige">
        <span class="text-3xl mb-2 block">👶</span>
        <div class="text-xs font-bold text-taupe-dark uppercase">Bayi</div>
      </div>
    </div>
  </section>

  <section class="py-16 md:py-24 px-[6%] bg-cream-dark" id="produk">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-10 max-w-5xl mx-auto">
      <div>
        <div class="text-[10px] tracking-[3px] uppercase text-taupe-light mb-1">Pilihan Unggulan</div>
        <h2 class="font-playfair text-3xl md:text-4xl font-black text-taupe-dark">Produk Terlaris</h2>
      </div>
      <a href="products.php" class="text-taupe-light text-sm font-semibold border-b border-taupe-light pb-0.5 hover:text-taupe-dark">Lihat Semua →</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
      <?php if ($data && $data->num_rows > 0): ?>
       <?php while ($row = $data->fetch_assoc()) { ?>
  <a href="detail.php?product_id=<?php echo $row['product_id']; ?>" class="group bg-cream-light rounded-2xl overflow-hidden hover:-translate-y-2 hover:shadow-2xl transition-all border border-beige/30 hover:border-beige">
    
    <div class="h-70 flex items-center justify-center relative overflow-hidden bg-beige/20">
      <?php 
        $imagePath = 'img/' . $row['image']; 
        // Cek jika kolom image tidak kosong dan filenya benar-benar ada di folder img
        if (!empty($row['image']) && file_exists($imagePath)): 
      ?>
        <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
      <?php else: ?>
        <div class="flex flex-col items-center justify-center text-taupe/50">
          <span class="text-5xl">🛒</span>
          <span class="text-[10px] mt-2 italic">No Image</span>
        </div>
      <?php endif; ?>
      
      <span class="absolute top-3 left-3 bg-gold text-cream-light text-[10px] font-black px-2 py-1 rounded-full uppercase shadow-sm">Produk</span>
    </div>

    <div class="p-5 flex justify-between items-center">
      <div>
        <div class="font-bold text-taupe-dark text-sm mb-1"><?= htmlspecialchars($row['name']) ?></div>
        <div class="font-playfair text-lg font-bold text-taupe-dark">Rp <?= number_format($row['price'], 0, ',', '.') ?></div>
      </div>
      <button class="w-10 h-10 bg-taupe-dark text-cream-light rounded-full text-xl hover:bg-gold hover:text-taupe-dark transition-colors flex items-center justify-center shadow-md">+</button>
    </div>
  </a>
<?php } ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="py-16 md:py-24 px-[6%] bg-taupe-dark relative overflow-hidden">
    <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center relative z-10">
      <div>
        <div class="text-[10px] tracking-[3px] uppercase text-taupe-light mb-3">Keunggulan Kami</div>
        <h2 class="font-playfair text-3xl md:text-4xl font-black text-cream-light mb-5 leading-tight">Mengapa Memilih Tambah Jaya?</h2>
        <div class="space-y-6">
          <div class="flex gap-4 items-start">
            <div class="w-10 h-10 min-w-[40px] bg-gold/20 border border-gold/30 rounded-lg flex items-center justify-center text-lg">🏷️</div>
            <div>
              <h4 class="text-cream-light font-bold text-sm">Harga Transparan</h4>
              <p class="text-cream-light/70 text-xs">Harga jujur tanpa biaya tersembunyi.</p>
            </div>
          </div>
          <div class="flex gap-4 items-start">
            <div class="w-10 h-10 min-w-[40px] bg-gold/20 border border-gold/30 rounded-lg flex items-center justify-center text-lg">✅</div>
            <div>
              <h4 class="text-cream-light font-bold text-sm">Kualitas Terjamin</h4>
              <p class="text-cream-light/70 text-xs">Produk diseleksi ketat setiap hari.</p>
            </div>
          </div>
        </div>
      </div>
      
      <div class="grid grid-cols-2 gap-3 md:gap-4">
        <div class="col-span-2 bg-gold/10 border border-gold/20 rounded-xl p-6 text-center">
          <div class="text-3xl mb-2">🏪</div>
          <h3 class="font-playfair text-lg text-cream-light">Toko Terpercaya</h3>
          <p class="text-cream-light/60 text-[10px]">Melayani Surabaya sejak 2021</p>
        </div>
        <div class="bg-cream-light/10 border border-cream