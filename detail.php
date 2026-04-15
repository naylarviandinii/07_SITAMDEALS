<?php
session_start();
include 'db.php';

// Proteksi login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Ambil product_id dari URL
if (!isset($_GET['product_id'])) {
    die("Akses ditolak: ID Produk tidak ditemukan.");
}

$product_id = intval($_GET['product_id']);

// Query ambil data produk lengkap
$query = "SELECT * FROM products WHERE product_id = $product_id";
$res = $conn->query($query);

if (!$res || $res->num_rows == 0) {
    die("Produk tidak ditemukan di database.");
}

$p = $res->fetch_assoc();

// Pisahkan deskripsi (asumsi kalimat pertama adalah deskripsi umum)
$deskripsi_split = explode('.', $p['description'], 2);
$deskripsi_umum = $deskripsi_split[0] . '.';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail – <?= htmlspecialchars($p['name']) ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

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

    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .qty-btn { transition: all 0.2s; }
        .qty-btn:active { transform: scale(0.9); }
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="bg-cream text-taupe">

<nav class="fixed top-0 left-0 right-0 z-50 flex justify-between items-center px-[6%] h-[72px] bg-taupe-dark/95 backdrop-blur-md border-b border-beige/30">
    <a href="index.php" class="font-playfair text-xl font-black text-cream-light">
        SiTam<span class="text-gold">Deals</span>
    </a>
    <ul class="flex items-center gap-8 list-none text-sm font-medium uppercase tracking-widest">
        <li><a href="index.php" class="text-beige hover:text-gold transition">Home</a></li>
        <li><a href="products.php" class="text-beige hover:text-gold transition">Produk</a></li>
        <li class="relative">
            <button id="profileBtn" class="text-cream-light text-xl hover:text-gold transition">
                <i class="fas fa-user-circle"></i>
            </button>
            <div id="profileDropdown" class="hidden absolute right-0 mt-3 w-48 bg-cream-light rounded-lg shadow-xl py-2 border border-beige">
                <a href="profil.php" class="block px-4 py-2 text-taupe hover:bg-beige/20">Profil</a>
                <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-red-50">Logout</a>
            </div>
        </li>
    </ul>
</nav>

<div class="max-w-6xl mx-auto pt-28 pb-20 px-6">
    <a href="products.php" class="text-taupe-light text-sm hover:underline mb-4 inline-block font-semibold">← Kembali ke Katalog</a>

    <div class="grid md:grid-cols-2 gap-10 items-start">
        
        <div class="rounded-3xl overflow-hidden shadow-lg bg-cream-light p-4 sticky top-24 border border-beige/50">
            <?php $img = 'img/' . $p['image']; ?>
            <img src="<?= file_exists($img) ? $img : 'https://via.placeholder.com/500' ?>" 
                 class="w-full h-[380px] object-contain">
        </div>

        <div>
            <span class="text-[10px] font-bold text-taupe-light/70 uppercase tracking-[2px]">Tambah Jaya</span>
            <h1 class="font-playfair text-4xl font-black text-taupe-dark mt-1 mb-2 leading-tight">
                <?= htmlspecialchars($p['name']) ?>
            </h1>

            <div id="price" class="text-3xl font-bold text-taupe-dark mb-1">
                Rp <?= number_format($p['price'], 0, ',', '.') ?>
            </div>
            <div class="text-sm text-taupe/60 mb-4 italic">
                Harga Normal: Rp <?= number_format($p['price'], 0, ',', '.') ?>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold mb-2 text-taupe/80 uppercase tracking-wide">Pilih Grade Kondisi Barang</label>
                <select id="grade" onchange="refresh()"
                  class="w-full border border-beige p-3 rounded-xl focus:ring-2 focus:ring-taupe-light bg-cream-light text-sm shadow-sm outline-none">
                  <option value="">-- Pilih Grade --</option>
                  <option value="A">Grade A (Lecet Halus - Diskon 15%)</option>
                  <option value="B">Grade B (Penyok/Kardus Rusak - Diskon 30%)</option>
                  <option value="C">Grade C (Repack/Dekat Expired - Diskon 50%)</option>
                </select>
                <p id="stock-display" class="text-[11px] font-bold text-orange-600 mt-2 hidden">
                    <i class="fas fa-boxes mr-1"></i> Stok Tersedia: <span id="stock-count">0</span>
                </p>
            </div>

            <div class="mb-6">
                <p class="text-taupe/70 text-sm leading-relaxed mb-4">
                    <?= htmlspecialchars($deskripsi_umum) ?>
                </p>
                
                <div class="space-y-2">
                    <p class="text-[10px] font-bold text-taupe-light uppercase tracking-widest">Detail Kondisi:</p>
                    <div class="grid grid-cols-1 gap-2">
                        <div class="bg-cream-light/80 border border-beige/50 rounded-lg p-2 text-[11px] text-taupe/70 hover:bg-beige/10">
                            <b class="text-taupe-dark">Grade A:</b> Kemasan mulus, sisa event.
                        </div>
                        <div class="bg-cream-light/80 border border-beige/50 rounded-lg p-2 text-[11px] text-taupe/70 hover:bg-beige/10">
                            <b class="text-taupe-dark">Grade B:</b> Kardus penyok, expired > 4 bln.
                        </div>
                        <div class="bg-cream-light/80 border border-beige/50 rounded-lg p-2 text-[11px] text-taupe/70 hover:bg-beige/10">
                            <b class="text-taupe-dark">Grade C:</b> Kemasan repack, expired dekat.
                        </div>
                    </div>
                </div>
            </div>

            <div id="checkout-section">
                <div class="bg-cream-light rounded-2xl shadow-xl p-4 mb-4 flex items-center justify-between border border-beige">
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="changeQty(-1)" class="qty-btn w-10 h-10 bg-beige/30 rounded-full flex items-center justify-center font-bold text-taupe shadow-sm hover:bg-beige/50">
                            <i class="fas fa-minus text-xs"></i>
                        </button>
                        <div class="text-center w-12">
                            <div class="text-[9px] text-taupe/60 uppercase font-bold">Qty</div>
                            <input type="number" id="qty-input" value="0" class="font-black text-xl text-center w-full bg-transparent border-none focus:ring-0 text-taupe-dark">
                        </div>
                        <button type="button" onclick="changeQty(1)" class="qty-btn w-10 h-10 bg-taupe rounded-full flex items-center justify-center font-bold text-cream-light shadow-md hover:bg-taupe-dark">
                            <i class="fas fa-plus text-xs"></i>
                        </button>
                    </div>

                    <div class="text-right">
                        <div class="text-[9px] text-taupe/60 uppercase font-bold">Subtotal</div>
                        <div id="subtotal-box" class="text-2xl font-black text-taupe-dark tracking-tight">Rp 0</div>
                    </div>
                </div>

                <form action="invoice.php" method="POST" onsubmit="return validateOrder()">
                    <input type="hidden" name="product_id" value="<?= $p['product_id'] ?>">
                    <input type="hidden" name="grade"      id="h-grade">
                    <input type="hidden" name="qty"        id="h-qty" value="0">
                    <input type="hidden" name="unit_price" id="h-unit-price" value="<?= $p['price'] ?>">

                    <button type="submit" class="w-full bg-gold text-taupe-dark font-black py-4 rounded-2xl shadow-lg hover:bg-gold-light hover:shadow-xl transition-all flex items-center justify-center gap-3 border-2 border-gold/20 hover:border-gold">
                        <i class="fas fa-shopping-cart"></i>
                        Checkout (<span id="checkout-qty">0</span> item) →
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<footer class="bg-taupe-dark text-beige/70 text-center py-8 text-xs border-t border-beige/30">
    &copy; 2026 <span class="text-gold font-bold">SiTamDeals</span> — UPN "Veteran" Jawa Timur
</footer>

<script>
const stocks = {
    "A": <?= intval($p['stock_A']) ?>,
    "B": <?= intval($p['stock_B']) ?>,
    "C": <?= intval($p['stock_C']) ?>
};

const profileBtn = document.getElementById('profileBtn');
const profileDropdown = document.getElementById('profileDropdown');
profileBtn.onclick = (e) => { e.stopPropagation(); profileDropdown.classList.toggle('hidden'); };
window.onclick = () => profileDropdown.classList.add('hidden');

let qty = 0; 
const basePrice = <?= intval($p['price']) ?>;

function refresh() {
    const g = document.getElementById("grade").value;
    const qtyInput = document.getElementById("qty-input");
    const stockDisplay = document.getElementById("stock-display");
    const stockCount = document.getElementById("stock-count");
    
    if (g && stocks[g] !== undefined) {  // Fixed: was "!=="
        stockDisplay.classList.remove("hidden");
        stockCount.innerText = stocks[g];
        if (qty > stocks[g]) qty = stocks[g];
    } else {
        stockDisplay.classList.add("hidden");
    }

    qtyInput.value = qty;

    let multiplier = 1;
    if (g === "A") multiplier = 0.85;
    else if (g === "B") multiplier = 0.70;
    else if (g === "C") multiplier = 0.50;

    const unit = Math.floor(basePrice * multiplier);
    const sub = unit * qty;

    document.getElementById("price").innerText = "Rp " + unit.toLocaleString('id-ID');
    document.getElementById("subtotal-box").innerText = "Rp " + sub.toLocaleString('id-ID');
    document.getElementById("checkout-qty").innerText = qty;

    document.getElementById("h-grade").value = g;
    document.getElementById("h-qty").value = qty;
    document.getElementById("h-unit-price").value = unit;
}

function changeQty(delta) {
    const g = document.getElementById("grade").value;
    if (!g) { alert("Pilih Grade dulu!"); return; }

    let maxStock = stocks[g];
    let nextQty = qty + delta;

    if (nextQty < 0) nextQty = 0;
    if (nextQty > maxStock) {
        alert("Stok Grade " + g + " habis!");
        nextQty = maxStock;
    }
    
    qty = nextQty;
    refresh();
}

function validateOrder() {
    if (!document.getElementById("grade").value) { alert("Pilih Grade dulu!"); return false; }
    if (qty <= 0) { alert("Qty masih 0!"); return false; }
    return true;
}
</script>
</body>
</html>