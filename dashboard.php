<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "library");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
// Tambah data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $nama_buku = $conn->real_escape_string($_POST['nama_buku']);
    $nama_peminjam = $conn->real_escape_string($_POST['nama_peminjam']);
    $email = $conn->real_escape_string($_POST['email']);
    $no_telp = $conn->real_escape_string($_POST['no_telp']);
    $tgl_pinjam = $conn->real_escape_string($_POST['tgl_pinjam']);
    $tgl_kembali = $conn->real_escape_string($_POST['tgl_kembali']);

    // Menyimpan data ke database
    $conn->query("INSERT INTO peminjaman (nama_buku, nama_peminjam, email, no_telp, tgl_pinjam, tgl_kembali) 
                  VALUES ('$nama_buku', '$nama_peminjam', '$email', '$no_telp', '$tgl_pinjam', '$tgl_kembali')");
    
    // Kirim notifikasi ke Telegram
    $bot_token = "7222526543:AAFkkTGwsrWn0d22kvp9Ud5E0dQINOwoXkA";
    $chat_id = "1936942692";
    $message = "Buku Dipinjam: $nama_buku\nPeminjam: $nama_peminjam\nEmail: $email\nNo. Telp: $no_telp\nTanggal Pinjam: $tgl_pinjam\nTanggal Kembali: $tgl_kembali";
    file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=" . urlencode($message));
}

// Ambil semua data
$result = $conn->query("SELECT * FROM peminjaman");

// Hapus data
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM peminjaman WHERE id=$id");

    // Kirim notifikasi ke Telegram
    $bot_token = "7222526543:AAFkkTGwsrWn0d22kvp9Ud5E0dQINOwoXkA";
    $chat_id = "1936942692";
    $message = "Data Peminjaman dengan ID: $id telah dihapus.";
    file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=" . urlencode($message));

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Usman Bin Affan</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    
</head>
<body>
    <h2>Perpustakaan Usman Bin Affan</h2>
    
    <!-- Form Tambah Data -->
    <form method="POST">
        <input type="text" name="nama_buku" placeholder="Nama Buku" required>
        <input type="text" name="nama_peminjam" placeholder="Nama Peminjam" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="no_telp" placeholder="No. Telp" required>
        <input type="date" name="tgl_pinjam" required>
        <input type="date" name="tgl_kembali" required>
        <button type="submit" name="add">Tambah</button>
    </form>

    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Buku</th>
                <th>Nama Peminjam</th>
                <th>Email</th>
                <th>No. Telp</th>
                <th>Tanggal Peminjaman</th>
                <th>Tanggal Dikembalikan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$i}</td>
                    <td>{$row['nama_buku']}</td>
                    <td>{$row['nama_peminjam']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['no_telp']}</td>
                    <td>{$row['tgl_pinjam']}</td>
                    <td>{$row['tgl_kembali']}</td>
                    <td>
                        <a href='update.php?id={$row['id']}'>Edit</a> |
                        <a href='dashboard.php?delete={$row['id']}' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>
                    </td>
                </tr>";
                $i++;
            }
            ?>
        </tbody>
    </table>
</body>
</html>
