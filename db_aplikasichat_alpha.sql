-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Okt 2022 pada 13.24
-- Versi server: 10.4.11-MariaDB
-- Versi PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_aplikasichat_alpha`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `akun`
--

CREATE TABLE `akun` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `akun`
--

INSERT INTO `akun` (`id`, `username`, `password`, `photo`) VALUES
(2, 'Afrian', '$2y$10$/eZLSAsDHq8.3n4DOpyPKufreOmzKkSi7pFe5dd0Rd90VP20r3IBC', 'kacamata bg asa_796.jpg'),
(3, 'Satrio', '$2y$10$R6sxcVYVlhoZBiU5s9lKUOC.pQm585EOr0/YIYt0m4XhAaCkcoKVi', 'IMG-20210215-WA0056.jpg'),
(4, 'Siyami', '$2y$10$Z9hyr08ebdvMqkkGvDp3s.rzQwR.XrXlyzaewJ7ZOBheWRofcGVdi', '3x4.jpg'),
(5, 'Supri', '$2y$10$ktv4uF5WJsEc2f8YsKwKt.Xmh943NpYAbGmF7fW0b6fXldrCpnTuC', '20210630_080912.jpg'),
(6, 'Jordy', '$2y$10$xot.R0ueSs.0MD2MRJeB0OL1XKFDSRcOYPSoDRJt76juCvGLDlEyC', 'WhatsApp Image 2021-09-30 at 08.08.01.jpeg'),
(7, 'Joni', '$2y$10$ySSoc.hqQ/jKtVDPs8x.tezPXZa9.RwzKAzfnD7lOEKSFHfYadtXu', 'sukarno.png'),
(8, 'Dimas Abib', '$2y$10$LmOKHgvNNyT/CGZj5eSLk.fdS.t3jFHE3LcSxCR/WjCagIDU48jpe', '11 Dimas Abib.JPG'),
(9, 'Arnold', '$2y$10$0FGYBrRlwMyQDaUR.AYITu8miAALhF6FXhn6QNyY5ilCgiQD0AKwm', 'Screenshot_1.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` int(11) NOT NULL,
  `tgl_waktu` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `tgl_waktu`) VALUES
(1, '2022-03-30 21:10:15'),
(2, '2022-09-18 22:14:59'),
(3, '2022-03-30 23:14:48'),
(4, '2022-03-30 21:21:03'),
(5, '2022-04-01 00:11:53'),
(6, '2022-04-01 00:21:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_kontak`
--

CREATE TABLE `log_kontak` (
  `id` int(11) NOT NULL,
  `id_log_aktivitas` int(11) NOT NULL,
  `id_akun` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `log_kontak`
--

INSERT INTO `log_kontak` (`id`, `id_log_aktivitas`, `id_akun`) VALUES
(1, 1, 2),
(2, 1, 3),
(3, 2, 2),
(4, 2, 6),
(5, 3, 3),
(6, 3, 6),
(7, 4, 2),
(8, 4, 7),
(9, 5, 8),
(10, 5, 2),
(11, 6, 2),
(12, 6, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesan`
--

CREATE TABLE `pesan` (
  `id` int(11) NOT NULL,
  `id_akun` int(11) NOT NULL,
  `id_log_aktivitas` int(11) NOT NULL,
  `isi_pesan` varchar(255) NOT NULL,
  `tgl_waktu` datetime NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pesan`
--

INSERT INTO `pesan` (`id`, `id_akun`, `id_log_aktivitas`, `isi_pesan`, `tgl_waktu`, `status`) VALUES
(1, 2, 1, 'hallo, satrio! apa kabar?', '2022-03-29 23:03:54', 'terbaca'),
(2, 2, 2, 'siap ndan, laksanakan!', '2022-03-29 23:08:30', 'terbaca'),
(3, 2, 2, 'gimana, ndan??', '2022-03-29 23:24:02', 'terbaca'),
(4, 3, 1, 'oiya, kabarku baik. trims..', '2022-03-29 23:54:57', 'terbaca'),
(5, 3, 3, 'hallo, komandan jordy, siap bertugas!', '2022-03-29 23:55:49', 'terbaca'),
(6, 2, 1, 'ak juga baik, kapan ktm?', '2022-03-30 11:33:28', 'terbaca'),
(7, 2, 1, 'oiya, hari ini hariku lumayan baik, bagaimana dengan harimu, oke kan?', '2022-03-30 14:41:42', 'terbaca'),
(8, 2, 4, 'Ayo main jon!', '2022-03-30 21:01:35', 'terkirim'),
(9, 2, 1, 'kok ra bales meneh??', '2022-03-30 21:10:15', 'terbaca'),
(10, 2, 4, 'sorry jon, rasido yo, lagi enek acara iki, sorry tenan loo', '2022-03-30 21:21:03', 'terkirim'),
(11, 6, 2, 'tidak apa2, ndan, lanjutkan bertugas!', '2022-03-30 21:40:19', 'terbaca'),
(12, 3, 3, 'segera merapat komandan!', '2022-03-30 23:14:48', 'terbaca'),
(13, 8, 5, 'Nengdi, broo?', '2022-04-01 00:10:26', 'terbaca'),
(14, 2, 5, 'lagi neng kene ae', '2022-04-01 00:11:53', 'terbaca'),
(15, 2, 6, 'mbok siyami, saget tindak mriki mboten?', '2022-04-01 00:15:35', 'terbaca'),
(16, 4, 6, 'saget masszeeh', '2022-04-01 00:19:26', 'terbaca'),
(17, 4, 6, 'wonten perlu nopo gih?', '2022-04-01 00:19:44', 'terbaca'),
(18, 2, 6, 'jenengan dipadosi ibuk kulo', '2022-04-01 00:20:19', 'terbaca'),
(19, 4, 6, 'oiyo, langsung otw masszeeh', '2022-04-01 00:20:56', 'terbaca'),
(20, 2, 6, 'gih kulo entosi, ngatos2 gih', '2022-04-01 00:21:16', 'terbaca'),
(21, 6, 2, 'hei, cuiy', '2022-09-18 22:14:59', 'terbaca');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log_kontak`
--
ALTER TABLE `log_kontak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_log_aktivitas` (`id_log_aktivitas`),
  ADD KEY `id_akun` (`id_akun`);

--
-- Indeks untuk tabel `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_akun` (`id_akun`),
  ADD KEY `id_log_aktivitas` (`id_log_aktivitas`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `akun`
--
ALTER TABLE `akun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `log_kontak`
--
ALTER TABLE `log_kontak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `log_kontak`
--
ALTER TABLE `log_kontak`
  ADD CONSTRAINT `log_kontak_ibfk_1` FOREIGN KEY (`id_log_aktivitas`) REFERENCES `log_aktivitas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `log_kontak_ibfk_2` FOREIGN KEY (`id_akun`) REFERENCES `akun` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pesan`
--
ALTER TABLE `pesan`
  ADD CONSTRAINT `pesan_ibfk_1` FOREIGN KEY (`id_akun`) REFERENCES `akun` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pesan_ibfk_2` FOREIGN KEY (`id_log_aktivitas`) REFERENCES `log_aktivitas` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
