<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) die("Produk tidak ditemukan");
$id = intval($_GET['id']);

$p = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
if (!$p) die("Produk tidak ditemukan");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Produk - <?= htmlspecialchars($p['name']) ?></title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>

<script>
tailwind.config = {
  theme: {
    extend: {
      fontFamily: {
        playfair: ['"Playfair Display"', 'serif'],
        dm: ['"DM Sans"', 'sans-serif']
      },
      colors: {
        forest: '#1e3a2f',
        sage:   '#4a8c64',
        mint:   '#b8d9c5',
        cream:  '#f7f4ee',
        gold:   '#c9a84c'
      }
    }
  }
}
</script>

<style>
body { font-family: 'DM Sans', sans-serif; }

/* Hide number input arrows */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
input[type=number] { -moz-appearance: textfield; }

/* Subtle fade-in */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}
.fade-up { animation: fadeUp .45s ease both; }
</style>

<script>
/* ── State ── */
let qty = 1;

/* ── Helpers ── */
function basePrice()  { return <?= intval($p['price']) ?>; }

function gradeMultiplier() {
  const g = document.getElementById("grade").value;
  if (g === "A") return 0.85;
  if (g === "B") return 0.70;
  if (g === "C") return 0.50;
  return 1;
}

function unitPrice() { return Math.floor(basePrice() * gradeMultiplier()); }

function fmt(n) { return "Rp " + n.toLocaleString('id-ID'); }

/* ── Update UI ── */
function refresh() {
  const unit = unitPrice();
  const sub  = unit * qty;

  document.getElementById("price").innerText      = fmt(unit);
  document.getElementById("fair-price").innerText = fmt(basePrice());
  document.getElementById("qty-display").innerText = qty;
  document.getElementById("subtotal-box").innerText = fmt(sub);

  // Update hidden fields
  document.getElementById("h-grade").value = document.getElementById("grade").value;
  document.getElementById("h-qty").value   = qty;

  // Show/hide checkout section
  const gradeSelected = document.getElementById("grade").value !== "";
  document.getElementById("checkout-section").style.display = gradeSelected ? "block" : "none";
}

function changeQty(delta) {
  qty = Math.max(1, qty + delta);
  refresh();
}

function onGradeChange() {
  refresh();
}

function submitOrder() {
  if (!document.getElementById("grade").value) {
    alert("Pilih grade dulu!");
    return false;
  }
  return true;
}
</script>

</head>

<body class="bg-cream">

<div class="max-w-5xl mx-auto py-16 px-6">

  <a href="index.php" class="text-sage mb-8 inline-block hover:underline text-sm">← Kembali</a>

  <div class="grid md:grid-cols-2 gap-14 items-start fade-up">

    <!-- ── IMAGE ── -->
    <div class="rounded-3xl overflow-hidden shadow-lg sticky top-8">
      <img src="<?= htmlspecialchars($p['image']) ?>"
           alt="<?= htmlspecialchars($p['name']) ?>"
           class="w-full h-[380px] object-cover">
    </div>

    <!-- ── DETAIL ── -->
    <div>

      <h1 class="font-playfair text-4xl font-black text-forest mb-3 leading-tight">
        <?= htmlspecialchars($p['name']) ?>
      </h1>

      <!-- Price -->
      <div id="price" class="text-3xl font-bold text-sage mb-1">
        Rp <?= number_format($p['price'], 0, ',', '.') ?>
      </div>
      <div class="text-sm text-gray-400 mb-6">
        Harga Layak: <span id="fair-price">Rp <?= number_format($p['price'], 0, ',', '.') ?></span>
      </div>

      <!-- Grade Selector -->
      <div class="mb-6">
        <label class="block text-sm font-semibold mb-2 text-forest">Pilih Grade</label>
        <select id="grade" onchange="onGradeChange()"
          class="w-full border border-mint p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-sage bg-white text-forest">
          <option value="">-- Pilih Grade --</option>
          <option value="A">Grade A (Diskon 15%)</option>
          <option value="B">Grade B (Diskon 30%)</option>
          <option value="C">Grade C (Diskon 50%)</option>
        </select>
      </div>

      <!-- Description -->
      <p class="text-gray-500 text-sm leading-relaxed mb-8">
        <?= htmlspecialchars($p['description'] ?? 'Produk berkualitas SiTamDeals.') ?>
      </p>

      <!-- ── CHECKOUT SECTION (hidden until grade selected) ── -->
      <div id="checkout-section" style="display:none;">

        <!-- Qty + Subtotal row -->
        <div class="bg-white rounded-2xl shadow-sm p-4 mb-5 flex items-center justify-between gap-4">

          <!-- Qty Controls -->
          <div class="flex items-center gap-3">
            <button type="button" onclick="changeQty(-1)"
              class="w-9 h-9 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center text-xl font-bold text-forest transition">
              −
            </button>

            <div class="text-center w-12">
              <div class="text-xs text-gray-400 leading-none mb-0.5">Qty</div>
              <div id="qty-display" class="font-bold text-xl text-forest">1</div>
            </div>

            <button type="button" onclick="changeQty(1)"
              class="w-9 h-9 bg-sage hover:bg-forest text-white rounded-full flex items-center justify-center text-xl font-bold transition">
              +
            </button>
          </div>

          <!-- Subtotal -->
          <div class="text-right">
            <div class="text-xs text-gray-400 mb-0.5">Subtotal</div>
            <div id="subtotal-box" class="text-xl font-bold text-sage">Rp 0</div>
          </div>

        </div>

        <!-- Direct to Invoice / Checkout form -->
        <form action="checkout_direct.php" method="POST" onsubmit="return submitOrder()">
          <input type="hidden" name="id"         value="<?= $p['id'] ?>">
          <input type="hidden" name="name"       value="<?= htmlspecialchars($p['name']) ?>">
          <input type="hidden" name="base_price" value="<?= $p['price'] ?>">
          <input type="hidden" name="grade"      id="h-grade">
          <input type="hidden" name="qty"        id="h-qty" value="1">

          <button type="submit"
            class="w-full bg-gold text-forest font-bold py-4 rounded-2xl hover:opacity-90 transition shadow text-base tracking-wide">
            Beli Sekarang →
          </button>
        </form>

      </div>
      <!-- /checkout-section -->

      <!-- Placeholder when no grade selected -->
      <div id="no-grade-msg" class="mt-2">
        <!-- shown implicitly when checkout-section is hidden -->
      </div>

    </div>
    <!-- /DETAIL -->

  </div>

</div>

</body>
</html>