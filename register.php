<?php
session_start();
include 'db.php';

$error = "";
$success = "";

// Redirect jika sudah login
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Proses Register
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    if (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else {
        $check = $conn->query("SELECT user_id FROM users WHERE email='$email'");
        if ($check->num_rows > 0) {
            $error = "Email sudah terdaftar!";
        } else {
            $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', 'pembeli')";
            if (mysqli_query($conn, $query)) {
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Gagal registrasi: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar – SiTamDeals</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        playfair: ['Playfair Display', 'serif'],
                        dm: ['DM Sans', 'sans-serif']
                    },
                    colors: {
                        forest: '#1e3a2f', moss: '#2e5c42', sage: '#4a8c64',
                        leaf: '#72b88a', mint: '#b8d9c5', cream: '#f7f4ee',
                        gold: '#c9a84c', 'gold-light': '#e8c96a'
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

<body class="bg-gradient-to-br from-cream to-mint/30 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    
    <div class="max-w-md mx-auto">
        
        <?php if ($success): ?>
            <!-- SUCCESS PAGE -->
            <div class="bg-white rounded-3xl shadow-2xl p-10 sm:p-12 text-center border border-mint/50">
                <div class="w-24 h-24 bg-gradient-to-r from-mint to-leaf rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-xl">
                    <i class="fas fa-check text-3xl text-white"></i>
                </div>
                <h1 class="font-playfair text-3xl font-black text-forest mb-4">Registrasi Berhasil!</h1>
                <p class="text-sage text-lg mb-8 leading-relaxed">Akun Anda telah dibuat. Silakan login untuk melanjutkan.</p>
                <a href="login.php" class="inline-block bg-gradient-to-r from-forest to-moss text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk Sekarang
                </a>
            </div>
        <?php else: ?>
          <!-- FORM FIELDS -->
               <!-- FORM -->
            <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl p-8 sm:p-10 border border-gray-100/50">
                
                <!-- HEADER -->
                <div class="text-center mb-8">
                    <h1 class="font-playfair text-2xl sm:text-3xl font-black text-forest mb-2">Buat Akun</h1>
                    <p class="text-gray-600">Mulai belanja sekarang</p>
                </div>

                <!-- ERROR -->
                <?php if ($error): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl mb-6 text-sm">
                        <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <!-- FORM FIELDS -->
                <form method="POST" class="space-y-5">
                    
                    <!-- NAMA -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Nama Lengkap</label>
                        <div class="relative">
                            <input type="text" name="name" required 
                                   class="w-full pl-11 pr-4 py-4 border border-gray-200 rounded-xl bg-white hover:border-gray-300 focus:border-sage focus:ring-2 focus:ring-sage/20 focus:outline-none transition-all text-sm placeholder-gray-400" 
                                   placeholder="Nama lengkap">
                            <i class="fas fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        </div>
                    </div>

                    <!-- EMAIL -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Email</label>
                        <div class="relative">
                            <input type="email" name="email" required 
                                   class="w-full pl-11 pr-4 py-4 border border-gray-200 rounded-xl bg-white hover:border-gray-300 focus:border-sage focus:ring-2 focus:ring-sage/20 focus:outline-none transition-all text-sm placeholder-gray-400" 
                                   placeholder="email@contoh.com">
                            <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        </div>
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Password</label>
                        <div class="relative">
                            <input type="password" name="password" required minlength="6"
                                   class="w-full pl-11 pr-4 py-4 border border-gray-200 rounded-xl bg-white hover:border-gray-300 focus:border-sage focus:ring-2 focus:ring-sage/20 focus:outline-none transition-all text-sm placeholder-gray-400" 
                                   placeholder="Min 6 karakter">
                            <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                    </div>

                    <!-- BUTTON -->
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-forest to-moss text-white py-5 rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300">
                        <i class="fas fa-user-plus mr-3"></i>Buat Akun Baru
                    </button>
                </form>

                <!-- LOGIN LINK -->
                <div class="text-center pt-8 mt-8 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-3">Sudah punya akun?</p>
                    <a href="login.php" class="inline-flex items-center gap-2 text-forest font-semibold hover:text-sage transition-colors text-base">
                        <i class="fas fa-sign-in-alt"></i>Masuk
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- BACK TO HOME -->
        <div class="text-center mt-8">
            <a href="index.php" class="text-sm text-gray-500 hover:text-gray-700 flex items-center justify-center gap-2">
                <i class="fas fa-home"></i>Kembali ke Beranda
            </a>
        </div>
    </div>

</body>
</html>