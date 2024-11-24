<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "library");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data berdasarkan ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM peminjaman WHERE id=$id");
    $data = $result->fetch_assoc();
}

// Update data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nama_buku = $conn->real_escape_string($_POST['nama_buku']);
    $nama_peminjam = $conn->real_escape_string($_POST['nama_peminjam']);
    $email = $conn->real_escape_string($_POST['email']);
    $no_telp = $conn->real_escape_string($_POST['no_telp']);
    $tgl_pinjam = $conn->real_escape_string($_POST['tgl_pinjam']);
    $tgl_kembali = $conn->real_escape_string($_POST['tgl_kembali']);

    $conn->query("UPDATE peminjaman SET 
                  nama_buku='$nama_buku', 
                  nama_peminjam='$nama_peminjam', 
                  email='$email', 
                  no_telp='$no_telp', 
                  tgl_pinjam='$tgl_pinjam', 
                  tgl_kembali='$tgl_kembali' 
                  WHERE id=$id");

    // Kirim notifikasi ke Telegram
    $bot_token = "7222526543:AAFkkTGwsrWn0d22kvp9Ud5E0dQINOwoXkA";
    $chat_id = "1936942692";
    $message = "Data Pinjaman Diperbarui:\nID: $id\nNama Buku: $nama_buku\nNama Peminjam: $nama_peminjam\nEmail: $email\nNo. Telp: $no_telp\nTanggal Pinjam: $tgl_pinjam\nTanggal Kembali: $tgl_kembali";
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
    <title>Update Data</title>
    <style>
        form {
            width: 50%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Edit Data Peminjaman</h2>

    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
        <input type="text" name="nama_buku" placeholder="Nama Buku" value="<?php echo $data['nama_buku']; ?>" required>
        <input type="text" name="nama_peminjam" placeholder="Nama Peminjam" value="<?php echo $data['nama_peminjam']; ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?php echo $data['email']; ?>" required>
        <input type="text" name="no_telp" placeholder="No. Telp" value="<?php echo $data['no_telp']; ?>" required>
        <input type="date" name="tgl_pinjam" value="<?php echo $data['tgl_pinjam']; ?>" required>
        <input type="date" name="tgl_kembali" value="<?php echo $data['tgl_kembali']; ?>" required>
        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>
