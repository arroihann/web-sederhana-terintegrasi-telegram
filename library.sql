create database library;



CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_buku VARCHAR(255),
    nama_peminjam VARCHAR(255),
    email VARCHAR(255),
    no_telp VARCHAR(20),
    tgl_pinjam DATE,
    tgl_kembali DATE
);
