<?php
session_start();
include 'db.php'; // Pastikan file ini berisi koneksi $conn

// 1. Proteksi Sesi: Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";
$messageType = "";

// 2. Logika Penghapusan Akun (Hanya berjalan jika ada request action=delete)
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $queryDelete = "DELETE FROM users WHERE id = '$user_id'";
    if (mysqli_query($conn, $queryDelete)) {
        session_destroy();
        // Redirect ke login dengan pesan sukses
        header("Location: login.php?msg=account_deleted");
        exit();
    } else {
        $message = "Gagal menghapus akun: " . mysqli_error($conn);
        $messageType = "error";
    }
}

// 3. Ambil Data User untuk Ditampilkan
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("Data pengguna tidak ditemukan di database.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | SiTamDeals</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@700;900&display=swap');
        body { font-family: 'DM Sans', sans-serif; }
        .font-playfair { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FDFBF7] text-[#1B3022]">

    <div class="max-w-md mx-auto mt-16 px-4">
        
        <?php if ($message): ?>
            <div class="mb-4 p-4 rounded-xl text-sm font-medium <?= $messageType == 'error' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-green-50 text-green-600 border border-green-100' ?>">
                <i class="fas <?= $messageType == 'error' ? 'fa-exclamation-circle' : 'fa-check-circle' ?> mr-2"></i>
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-[2rem] shadow-2xl shadow-forest/5 overflow-hidden border border-gray-50">
            
            <div class="bg-[#1B3022] p-10 text-center relative">
                <div class="absolute top-4 right-6 opacity-20 text-white text-6xl">
                    <i class="fas fa-leaf"></i>
                </div>
                
                <div class="relative inline-block">
                    <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center text-3xl text-gold border border-gold/30 backdrop-blur-sm">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 border-4 border-[#1B3022] rounded-full"></div>
                </div>

                <h1 class="text-white text-2xl font-black mt-4 font-playfair tracking-tight italic">
                    <?= htmlspecialchars($user['name']) ?>
                </h1>
                <p class="text-gold/80 text-[10px] uppercase tracking-[0.2em] font-bold mt-1">
                    Verified <?= htmlspecialchars($user['role']) ?>
                </p>
            </div>

            <div class="p-8 space-y-6">
                <div class="group">
                    <label class="text-[10px] uppercase text-gray-400 font-bold tracking-widest block mb-1">Email Address</label>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-envelope text-gray-300 group-hover:text-gold transition"></i>
                        <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                </div>

                <div class="group border-t border-gray-50 pt-4">
                    <label class="text-[10px] uppercase text-gray-400 font-bold tracking-widest block mb-1">Security</label>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-lock text-gray-300 group-hover:text-gold transition"></i>
                            <p class="text-sm font-medium text-gray-700 tracking-[0.3em]">••••••••</p>
                        </div>
                        <span class="text-[10px] bg-gray-50 px-2 py-1 rounded text-gray-400">Encrypted</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 pt-6">
                    <a href="edit_profil.php" class="w-full bg-[#C9A84C] text-white text-center py-4 rounded-2xl font-bold hover:bg-[#B3933C] transition-all shadow-lg shadow-gold/20 flex items-center justify-center gap-2">
                        <i class="fas fa-pen-nib text-xs"></i> Edit Personal Data
                    </a>
                    
                    <button onclick="confirmDeletion()" class="w-full bg-transparent border border-red-100 text-red-400 py-4 rounded-2xl text-xs font-bold hover:bg-red-50 hover:text-red-600 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-user-minus"></i> Deactivate Account
                    </button>
                </div>
            </div>
        </div>

        <div class="text-center mt-8 pb-10">
            <a href="index.php" class="text-xs text-gray-400 hover:text-gold transition uppercase tracking-widest font-bold">
                <i class="fas fa-chevron-left mr-2"></i> Back to Store
            </a>
        </div>
    </div>

    <script>
    function confirmDeletion() {
        if (confirm("⚠️ PERINGATAN: Menghapus akun akan menghilangkan semua akses belanja Anda. Lanjutkan?")) {
            window.location.href = "profil.php?action=delete";
        }
    }
    </script>

</body>
</html>