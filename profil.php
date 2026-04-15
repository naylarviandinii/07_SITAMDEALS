<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];
$message = "";
$messageType = "";

// Proses Update Profile (nama & email)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'], $_POST['email'])) {
    $new_name = mysqli_real_escape_string($conn, $_POST['name']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Cek email duplikat
    $email_check = $conn->query("SELECT id FROM users WHERE email='$new_email' AND id != '$user_id'");
    if ($email_check->num_rows > 0) {
        $message = "Email sudah digunakan user lain!";
        $messageType = "error";
    } else {
        $query = "UPDATE users SET name='$new_name', email='$new_email' WHERE id='$user_id'";
        if (mysqli_query($conn, $query)) {
            $_SESSION['user']['name'] = $new_name;
            $_SESSION['user']['email'] = $new_email;
            $user = $_SESSION['user'];
            $message = "Profile berhasil diupdate!";
            $messageType = "success";
        } else {
            $message = "Gagal update profile!";
            $messageType = "error";
        }
    }
}

// Proses Update Password
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['current_password'])) {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    
    if ($current_pass === $user['password']) {
        if ($new_pass === $confirm_pass) {
            if (strlen($new_pass) >= 6) {
                $query = "UPDATE users SET password='$new_pass' WHERE id='$user_id'";
                if (mysqli_query($conn, $query)) {
                    $message = "Password berhasil diupdate!";
                    $messageType = "success";
                } else {
                    $message = "Gagal update password!";
                    $messageType = "error";
                }
            } else {
                $message = "Password minimal 6 karakter!";
                $messageType = "error";
            }
        } else {
            $message = "Konfirmasi password tidak cocok!";
            $messageType = "error";
        }
    } else {
        $message = "Password lama salah!";
        $messageType = "error";
    }
}

// Hapus akun
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $query = "DELETE FROM users WHERE id = '$user_id'";
    if (mysqli_query($conn, $query)) {
        session_destroy();
        header("Location: login.php?msg=account_deleted");
        exit();
    } else {
        $message = "Gagal menghapus akun!";
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil – SiTamDeals</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
        .input-field {
            @apply w-full px-4 py-4 border border-beige/50 rounded-xl bg-cream-light 
                   hover:border-beige focus:border-taupe-light focus:ring-2 focus:ring-taupe-light/20 
                   focus:outline-none transition-all text-sm placeholder-taupe/60 text-taupe;
        }
        @media (max-width: 1024px) { nav ul { display: none; } }
    </style>
</head>

<body class="bg-cream min-h-screen">
    <!-- NAVBAR -->
    <nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-4 sm:px-6 lg:px-8 h-14 lg:h-16 bg-taupe-dark/95 backdrop-blur-md border-b border-beige/30">
        <a href="index.php" class="font-playfair text-lg font-black text-cream">SiTam<span class="text-gold">Deals</span></a>
        <div class="hidden lg:flex items-center gap-6">
            <a href="profil.php" class="text-gold font-bold text-sm uppercase tracking-wide border-b-2 border-gold pb-1">Profil</a>
        </div>
        <button id="mobileMenuBtn" class="lg:hidden text-cream text-xl">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <!-- MOBILE MENU -->
    <div id="mobileMenu" class="hidden lg:hidden fixed top-14 left-0 right-0 bg-taupe-dark p-4 z-40 border-b border-beige/30">
        <a href="profil.php" class="block py-3 text-gold font-bold text-lg">Profil</a>
    </div>

    <!-- MAIN CONTENT -->
    <main class="pt-16 lg:pt-20 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto space-y-8">
            
            <!-- ALERT MESSAGE -->
            <?php if ($message): ?>
                <div class="p-4 rounded-2xl text-center font-medium text-sm 
                    <?= $messageType == 'error' ? 'bg-red-50/80 border-2 border-red-200/50 text-red-700' : 'bg-emerald-50/80 border-2 border-emerald-200/50 text-emerald-700' ?>">
                    <i class="fas <?= $messageType == 'error' ? 'fa-exclamation-circle' : 'fa-check-circle' ?> mr-2"></i>
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <!-- AVATAR CARD -->
            <div class="bg-cream-light rounded-3xl shadow-xl p-6 sm:p-8 text-center border border-beige/50">
                <div class="relative mx-auto w-20 h-20 sm:w-24 sm:h-24 mb-6">
                    <div class="w-full h-full bg-gradient-to-br from-gold to-gold-light rounded-full flex items-center justify-center text-2xl sm:text-3xl font-black text-taupe-dark shadow-lg border-4 border-cream-light">
                        <?= strtoupper(substr($user['name'], 0, 2)) ?>
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-gold border-4 border-cream-light rounded-full flex items-center justify-center text-xs font-bold text-taupe-dark shadow-md">✓</div>
                </div>
                <h1 class="font-playfair text-xl sm:text-2xl font-black text-taupe-dark mb-1"><?= htmlspecialchars($user['name']) ?></h1>
                <p class="text-taupe-light uppercase tracking-wide font-medium text-xs"><?= ucfirst($user['role']) ?></p>
            </div>

            <!-- PROFILE FORM -->
            <form method="POST" class="bg-cream-light rounded-3xl shadow-xl p-6 sm:p-8 border border-beige/50 space-y-6">
                <h2 class="font-playfair text-xl font-black text-taupe-dark text-center mb-1">Edit Profile</h2>
                <p class="text-center text-taupe/70 text-sm mb-8">Ubah nama dan email Anda</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-taupe uppercase tracking-wide mb-2">Nama</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-taupe/60"></i>
                            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required maxlength="100" 
                                   class="input-field pl-11">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-taupe uppercase tracking-wide mb-2">Email</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-taupe/60"></i>
                            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required 
                                   class="input-field pl-11">
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-taupe to-taupe-dark text-cream py-4 rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all border border-taupe/20 hover:border-taupe/40">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </form>

            <!-- PASSWORD FORM -->
            <form method="POST" class="bg-cream-light rounded-3xl shadow-xl p-6 sm:p-8 border border-beige/50 space-y-6">
                <h2 class="font-playfair text-xl font-black text-taupe-dark text-center mb-1">Ubah Password</h2>
                <p class="text-center text-taupe/70 text-sm mb-8">Masukkan password lama untuk verifikasi</p>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-taupe uppercase tracking-wide mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" required class="input-field" placeholder="Password lama">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-taupe uppercase tracking-wide mb-2">Password Baru</label>
                            <input type="password" name="new_password" required class="input-field" placeholder="Min 6 karakter">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-taupe uppercase tracking-wide mb-2">Konfirmasi</label>
                            <input type="password" name="confirm_password" required class="input-field" placeholder="Ulangi">
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-gold to-gold-light text-taupe-dark py-4 rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all border border-gold/20 hover:border-gold/40">
                    <i class="fas fa-lock mr-2"></i>Ubah Password
                </button>
            </form>

            <!-- DELETE ACCOUNT -->
            <div class="text-center p-6">
                <button onclick="confirmDelete()" 
                        class="px-8 py-4 border-2 border-red-300/50 text-red-600 bg-red-50/80 rounded-xl font-bold hover:bg-red-100/80 hover:scale-[1.02] transition-all shadow-md">
                    <i class="fas fa-user-slash mr-2"></i>Hapus Akun
                </button>
            </div>

            <!-- BACK BUTTON -->
            <div class="text-center">
                <a href="index.php" class="inline-flex items-center gap-2 text-sm text-taupe hover:text-taupe-dark font-medium transition-colors hover:underline">
                    <i class="fas fa-chevron-left"></i>Kembali
                </a>
            </div>
        </div>
    </main>

    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });

        // Delete confirmation
        function confirmDelete() {
            if (confirm('⚠️ Akun akan terhapus permanen! Lanjutkan?')) {
                window.location.href = 'profil.php?action=delete';
            }
        }
    </script>
</body>
</html>