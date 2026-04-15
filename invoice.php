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
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-playfair { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#f7f4ee] min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
        <div class="bg-[#1e3a2f] p-8 text-center text-white">
            <h1 class="font-playfair text-2xl text-[#c9a84c] mb-1">SiTamDeals</h1>
            <p class="text-[10px] uppercase tracking-[3px] opacity-60 font-bold">Bukti Pembelian Sah</p>
        </div>

        <div class="p-8">
            <div class="flex justify-between border-b border-dashed border-gray-200 pb-4 mb-6">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nama Pelanggan</p>
                    <p class="font-bold text-gray-800 text-base"><?= htmlspecialchars($order['customer']) ?></p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nomor Order</p>
                    <p class="font-mono font-bold text-gray-800">#STD-<?= str_pad($id, 4, '0', STR_PAD_LEFT) ?></p>
                </div>
            </div>

            <div class="space-y-5 mb-8">
                <?php while ($row = $items->fetch_assoc()): 
                    $subtotal = $row['price'] * $row['qty'];
                    $total += $subtotal;
                ?>
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <p class="font-bold text-gray-800 leading-tight"><?= htmlspecialchars($row['name']) ?></p>
                        <p class="text-[11px] text-gray-400 mt-0.5">Grade <?= $row['grade'] ?> &nbsp;•&nbsp; <?= $row['qty'] ?> Unit</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800 text-sm">Rp <?= number_format($subtotal, 0, ',', '.') ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

            <div class="bg-gray-50 rounded-2xl p-5 mb-8 flex justify-between items-center border border-gray-100">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Bayar</span>
                <span class="text-2xl font-black text-[#4a8c64]">Rp <?= number_format($total, 0, ',', '.') ?></span>
            </div>

            <div class="text-center">
                <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest mb-6">
                    <i class="fas fa-check-circle"></i> Pesanan Berhasil
                </div>
                <p class="text-gray-400 text-[10px] leading-relaxed">
                    Pesanan Anda telah masuk ke dalam sistem kami.<br>
                    Silakan ambil barang Anda di area <strong>Tambah Jaya</strong>.
                </p>
            </div>
        </div>

        <div class="p-8 pt-0">
            <a href="index.php" class="block w-full bg-[#c9a84c] text-[#1e3a2f] font-bold py-4 rounded-2xl hover:bg-[#b08f3a] transition-all text-center text-sm shadow-lg shadow-gold/20">
                Kembali ke Beranda
            </a>
        </div>
    </div>

</body>
</html>