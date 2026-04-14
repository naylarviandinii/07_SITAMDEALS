<?php
session_start();
include 'db.php';

$error = "";

// redirect kalau sudah login
if (isset($_SESSION['user'])) {
  $role = $_SESSION['user']['role'];

  if ($role == "admin") {
    header("Location: admin_dashboard.php");
  } elseif ($role == "kasir") {
    header("Location: admin_orders.php");
  } else {
    header("Location: index.php");
  }
  exit;
}

// proses login
if ($_SERVER['REQUEST_METHOD'] == "POST") {

  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $pass  = $_POST['password'];

  $res = $conn->query("SELECT * FROM users WHERE email='$email'");

  if ($res && $res->num_rows > 0) {

    $user = $res->fetch_assoc();

    if ($pass === $user['password']) {

      $_SESSION['user'] = $user;

      switch ($user['role']) {
        case 'admin':
          header("Location: admin_dashboard.php");
          break;
        case 'kasir':
          header("Location: admin_orders.php");
          break;
        default:
          header("Location: index.php");
      }

      exit;

    } else {
      $error = "Password salah!";
    }

  } else {
    $error = "Email tidak ditemukan!";
  }
}
?>

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login – SiTamDeals</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            playfair:['"Playfair Display"','serif'],
            dm:['"DM Sans"','sans-serif']
          },
          colors: {
            forest:'#1e3a2f', moss:'#2e5c42', sage:'#4a8c64',
            leaf:'#72b88a', mint:'#b8d9c5', cream:'#f7f4ee',
            gold:'#c9a84c', 'gold-light':'#e8c96a', dark:'#111a15'
          },
          keyframes: {
            fadeUp:{ from:{opacity:'0',transform:'translateY(20px)'}, to:{opacity:'1',transform:'translateY(0)'} }
          },
          animation: {
            'fade-up':'fadeUp 0.6s ease both',
            'fade-up-1':'fadeUp 0.6s ease 0.1s both',
            'fade-up-2':'fadeUp 0.6s ease 0.2s both',
            'fade-up-3':'fadeUp 0.6s ease 0.3s both',
            'fade-up-4':'fadeUp 0.6s ease 0.4s both'
          }
        }
      }
    }
  </script>

  <style>
    body { font-family:'DM Sans',sans-serif; }
    .input-field {
      width:100%;
      padding:14px 18px;
      border:1.5px solid #e2e8e4;
      border-radius:14px;
      background:#f8faf9;
      outline:none;
      transition:all 0.25s;
      font-size:0.9rem;
    }
    .input-field:focus {
      border-color:#4a8c64;
      box-shadow:0 0 0 4px rgba(74,140,100,0.1);
      background:#fff;
    }
  </style>
</head>

<body class="h-full bg-[#1a3529] overflow-hidden">

<div class="min-h-screen flex">

  <!-- LEFT PANEL -->
  <div class="hidden lg:flex flex-col justify-between w-[42%] p-14 text-white">
    <div class="animate-fade-up">
      <div class="text-2xl font-black font-playfair">
        SiTam<span class="text-gold">Deals</span>
      </div>
    </div>

    <div class="animate-fade-up-1">
      <h1 class="text-5xl font-black font-playfair leading-tight">
        Belanja Lebih <span class="text-gold">Mudah</span>
      </h1>
      <p class="text-white/50 mt-4 text-sm max-w-sm">
        Platform untuk mengelola transaksi dan penjualan dengan cepat dan efisien.
      </p>
    </div>

    <div class="text-white/30 text-xs">
      © 2026 SiTamDeals
    </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="flex-1 flex items-center justify-center p-6">

    <div class="w-full max-w-md">

      <div class="bg-white rounded-3xl shadow-2xl p-10 animate-fade-up">

        <!-- HEADER -->
        <h1 class="font-playfair text-3xl font-black text-[#1e3a2f]">
          Welcome Back 👋
        </h1>
        <p class="text-gray-400 text-sm mt-1 mb-6">
          Login untuk melanjutkan
        </p>

        <!-- ERROR -->
        <?php if ($error): ?>
          <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-3 rounded-xl mb-4 text-center">
            <?= $error ?>
          </div>
        <?php endif; ?>

        <!-- FORM -->
        <form method="POST" class="space-y-4">

          <div>
            <label class="text-xs font-bold text-[#1e3a2f]/70 uppercase">Email</label>
            <input type="email" name="email" class="input-field mt-2" placeholder="contoh@email.com" required>
          </div>

          <div>
            <label class="text-xs font-bold text-[#1e3a2f]/70 uppercase">Password</label>
            <input type="password" name="password" class="input-field mt-2" placeholder="••••••••" required>
          </div>

          <button type="submit"
            class="w-full py-4 rounded-xl text-white font-bold bg-gradient-to-r from-[#1e3a2f] to-[#2e5c42] hover:scale-[1.02] transition">
            Login →
          </button>

        </form>

        <!-- FOOTER -->
        <p class="text-center text-sm text-gray-400 mt-6">
          Belum punya akun?
          <a href="register.php" class="text-[#1e3a2f] font-bold">Daftar</a>
        </p>

      </div>

    </div>
  </div>

</div>

</body>
</html>