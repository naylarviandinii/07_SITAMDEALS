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
          keyframes: {
            fadeUp:{ from:{opacity:'0',transform:'translateY(30px)'}, to:{opacity:'1',transform:'translateY(0)'} }
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
      border:1.5px solid #d9c9a2;
      border-radius:14px;
      background:#fffdf9;
      outline:none;
      transition:all 0.25s;
      font-size:0.9rem;
      color:#6b5a42;
    }
    .input-field:focus {
      border-color:#d4af37;
      box-shadow:0 0 0 4px rgba(212,175,55,0.15);
      background:#fffdf9;
    }
    .input-field::placeholder {
      color:#a89c7e;
    }
  </style>
</head>

<body class="h-full bg-cream overflow-hidden">

<div class="min-h-screen flex">

  <!-- LEFT PANEL -->
  <div class="hidden lg:flex flex-col justify-between w-[42%] p-14 text-taupe-dark bg-cream-dark">
    <div class="animate-fade-up">
      <div class="text-2xl font-black font-playfair">
        SiTam<span class="text-gold">Deals</span>
      </div>
    </div>

    <div class="animate-fade-up-1">
      <h1 class="text-5xl font-black font-playfair leading-tight">
        Belanja Lebih <span class="text-gold">Mudah</span>
      </h1>
      <p class="text-taupe mt-4 text-sm max-w-sm">
        Platform untuk mengelola transaksi dan penjualan dengan cepat dan efisien.
      </p>
    </div>

    <div class="text-taupe-light text-xs">
      © 2026 SiTamDeals
    </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="flex-1 flex items-center justify-center p-6 bg-cream-light">

    <div class="w-full max-w-md">

      <div class="bg-cream rounded-3xl shadow-2xl p-10 border border-beige animate-fade-up">

        <!-- HEADER -->
        <h1 class="font-playfair text-3xl font-black text-taupe-dark">
          Welcome Back 👋
        </h1>
        <p class="text-taupe text-sm mt-1 mb-6">
          Login untuk melanjutkan
        </p>

        <!-- ERROR -->
        <?php if ($error): ?>
          <div class="bg-[#fef2f2] border border-[#fecaca] text-taupe-dark text-sm p-3 rounded-xl mb-4 text-center">
            <?= $error ?>
          </div>
        <?php endif; ?>

        <!-- FORM -->
        <form method="POST" class="space-y-4">

          <div>
            <label class="text-xs font-bold text-taupe uppercase">Email</label>
            <input type="email" name="email" class="input-field mt-2" placeholder="contoh@email.com" required>
          </div>

          <div>
            <label class="text-xs font-bold text-taupe uppercase">Password</label>
            <input type="password" name="password" class="input-field mt-2" placeholder="••••••••" required>
          </div>

          <button type="submit"
            class="w-full py-4 rounded-xl text-cream font-bold bg-gradient-to-r from-taupe-dark via-taupe to-beige-dark hover:scale-[1.02] transition-all duration-200 hover:shadow-lg hover:shadow-gold/20">
            Login →
          </button>

        </form>

        <!-- FOOTER -->
        <p class="text-center text-sm text-taupe mt-6">
          Belum punya akun?
          <a href="register.php" class="text-taupe-dark font-bold hover:text-gold transition-colors">Daftar</a>
        </p>

      </div>

    </div>
  </div>

</div>

</body>
</html>