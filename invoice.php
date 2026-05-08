<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['user_id'];

// PROSES SIMPAN DATA (Hanya jika ada kiriman POST dari detail.php)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $grade      = mysqli_real_escape_string($conn, $_POST['grade']);
    $qty        = intval($_POST['qty']);
    $unit_price = intval($_POST['unit_price']);

    // 1. Buat Header Order
    $conn->query("INSERT INTO orders (user_id, status) VALUES ('$user_id', 'pending')");
    $order_id = $conn->insert_id;

    // 2. Masukkan Item
    $conn->query("INSERT INTO order_items (order_id, product_id, grade, price, qty) 
                  VALUES ('$order_id', '$product_id', '$grade', '$unit_price', '$qty')");

    // 3. Kurangi Stok sesuai Grade
    $stock_col = "stock_" . $grade; // stock_A / stock_B / stock_C
    $conn->query("UPDATE products 
                  SET $stock_col = $stock_col - $qty 
                  WHERE product_id = $product_id 
                  AND $stock_col >= $qty");

    if ($conn->affected_rows == 0) {
        die("Gagal: Stok tidak mencukupi atau produk tidak ditemukan.");
    }
    
    // Redirect ke diri sendiri (GET) biar tidak duplikat datanya kalau di-refresh
    header("Location: invoice.php?id=" . $order_id);
    exit();
}

// TAMPILAN INVOICE
if (!isset($_GET['id'])) die("Akses ditolak: ID Order tidak ditemukan.");

$id = (int)$_GET['id'];

// Ambil data order & Nama Customer
$order_query = $conn->query("
    SELECT o.*, u.name as customer 
    FROM orders o 
    JOIN users u ON o.user_id = u.user_id 
    WHERE o.id = $id
");
$order = $order_query->fetch_assoc();

if (!$order) die("Order tidak ditemukan.");

// Ambil item produk
$items = $conn->query("
    SELECT oi.*, p.name 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = $id
");

$total = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $id ?> - SiTamDeals</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@900&family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { playfair: ['Playfair Display', 'serif'], dm: ['DM Sans', 'sans-serif'] },
                    colors: {
                        // Tema CREAM dengan text kontras tinggi
                        cream: '#fefbf3',           // Background utama
                        'cream-dark': '#f5f0e6',    
                        'cream-light': '#fffdf9',   
                        taupe: '#8b7b5f',           // TEXT UTAMA ⭐ (mudah dibaca)
                        'taupe-dark': '#6b5a42',    // Heading
                        'taupe-light': '#a89c7e',   
                        beige: '#d9c9a2',           
                        'beige-dark': '#b8a884',    
                        gold: '#d4af37',            
                        'gold-light': '#e8c968'     
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-playfair { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-cream min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full bg-cream-light rounded-3xl shadow-2xl overflow-hidden border border-beige/50">
        <div class="bg-taupe-dark p-8 text-center text-cream">
            <h1 class="font-playfair text-2xl text-gold mb-1">SiTam<span class="text-gold-light">Deals</span></h1>
            <p class="text-[10px] uppercase tracking-[3px] opacity-80 font-bold">Bukti Pembelian Sah</p>
        </div>

        <div class="p-8">
            <div class="flex justify-between border-b border-dashed border-beige/30 pb-4 mb-6">
                <div>
                    <p class="text-[10px] font-bold text-taupe-light uppercase tracking-wider">Nama Pelanggan</p>
                    <p class="font-bold text-taupe-dark text-base"><?= htmlspecialchars($order['customer']) ?></p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-taupe-light uppercase tracking-wider">Nomor Order</p>
                    <p class="font-mono font-bold text-taupe-dark">#STD-<?= str_pad($id, 4, '0', STR_PAD_LEFT) ?></p>
                </div>
            </div>

            <div class="space-y-5 mb-8">
                <?php while ($row = $items->fetch_assoc()): 
                    $subtotal = $row['price'] * $row['qty'];
                    $total += $subtotal;
                ?>
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <p class="font-bold text-taupe-dark leading-tight"><?= htmlspecialchars($row['name']) ?></p>
                        <p class="text-[11px] text-taupe/70 mt-0.5">Grade <?= $row['grade'] ?> &nbsp;•&nbsp; <?= $row['qty'] ?> Unit</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-taupe-dark text-sm">Rp <?= number_format($subtotal, 0, ',', '.') ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

            <div class="bg-cream-dark rounded-2xl p-5 mb-8 flex justify-between items-center border border-beige/30">
                <span class="text-xs font-bold text-taupe uppercase tracking-widest">Total Bayar</span>
                <span class="text-2xl font-black text-taupe-dark">Rp <?= number_format($total, 0, ',', '.') ?></span>
            </div>

            <div class="text-center">
                <div class="inline-flex items-center gap-2 bg-gold/10 text-gold text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest mb-6 border border-gold/20">
                    <i class="fas fa-check-circle"></i> Pesanan Berhasil
                </div>
                <p class="text-taupe/60 text-[10px] leading-relaxed">
                    Pesanan Anda telah masuk ke dalam sistem kami.<br>
                    Silakan ambil barang Anda di area <strong class="text-taupe-dark font-bold">Tambah Jaya</strong>.
                </p>
            </div>
        </div>

        <div class="p-8 pt-0">
            <a href="index.php" class="block w-full bg-gradient-to-r from-gold to-gold-light text-taupe-dark font-bold py-4 rounded-2xl hover:from-gold hover:to-beige transition-all text-center text-sm shadow-lg hover:shadow-xl border border-gold/20 hover:border-gold/40">
                Kembali ke Beranda
            </a>
        </div>
    </div>

</body>
</html>