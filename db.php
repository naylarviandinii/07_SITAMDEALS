<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          forest: '#1e3a2f',
          moss: '#2e5c42',
          sage: '#4a8c64',
          leaf: '#72b88a',
          mint: '#b8d9c5',
          cream: '#f7f4ee',
          gold: '#c9a84c',
          dark: '#111a15'
        }
      }
    }
  }
</script>

<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "sitamdeals";

$conn = mysqli_connect($host, $user, $password, $database);

// Error handling
if (!$conn) {
  die("Koneksi database gagal: " . mysqli_connect_error());
}

// Optional: set charset biar aman
mysqli_set_charset($conn, "utf8");
?>