-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Jul 2025 pada 19.44
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cbtcat`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `jawaban_ujian`
--

CREATE TABLE `jawaban_ujian` (
  `id` int(10) UNSIGNED NOT NULL,
  `ujian_id` int(10) UNSIGNED NOT NULL,
  `peserta_id` int(10) UNSIGNED NOT NULL,
  `soal_id` int(10) UNSIGNED NOT NULL,
  `jawaban` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jawaban_ujian`
--

INSERT INTO `jawaban_ujian` (`id`, `ujian_id`, `peserta_id`, `soal_id`, `jawaban`, `created_at`, `updated_at`) VALUES
(1, 6, 14, 1, 'D', NULL, '2025-06-18 00:59:37'),
(2, 6, 14, 2, 'B', NULL, '2025-06-18 00:59:37'),
(3, 6, 14, 5, 'A', NULL, '2025-06-18 00:59:37'),
(4, 6, 14, 8, 'B', NULL, '2025-06-18 00:59:37'),
(5, 6, 14, 9, 'C', NULL, '2025-06-18 00:59:37'),
(6, 7, 9, 1, 'E', NULL, '2025-06-18 01:14:24'),
(7, 7, 9, 2, 'E', NULL, '2025-06-18 01:14:24'),
(8, 7, 9, 5, 'E', NULL, '2025-06-18 01:14:24'),
(9, 7, 9, 8, 'C', NULL, '2025-06-18 01:14:24'),
(10, 7, 9, 9, 'B', NULL, '2025-06-18 01:14:24'),
(11, 22, 22, 12, 'C', NULL, '2025-07-21 04:23:01'),
(12, 22, 22, 44, 'B', NULL, '2025-07-21 04:23:01'),
(13, 22, 22, 45, 'B', NULL, '2025-07-21 04:23:01'),
(14, 22, 22, 46, 'B', NULL, '2025-07-21 04:23:01'),
(15, 22, 22, 47, 'A', NULL, '2025-07-21 04:23:01'),
(16, 22, 22, 48, 'B', NULL, '2025-07-21 04:23:01'),
(17, 22, 22, 49, 'B', NULL, '2025-07-21 04:23:01'),
(18, 22, 22, 50, 'B', NULL, '2025-07-21 04:23:01'),
(19, 22, 22, 51, 'A', NULL, '2025-07-21 04:23:01'),
(20, 22, 22, 52, 'B', NULL, '2025-07-21 04:23:01'),
(21, 23, 22, 12, 'B', NULL, '2025-07-21 05:41:28'),
(22, 23, 22, 44, 'A', NULL, '2025-07-21 05:41:28'),
(23, 23, 22, 45, 'A', NULL, '2025-07-21 05:41:28'),
(24, 23, 22, 46, 'B', NULL, '2025-07-21 05:41:28'),
(25, 23, 22, 47, 'B', NULL, '2025-07-21 05:41:28'),
(26, 23, 22, 48, 'A', NULL, '2025-07-21 05:41:28'),
(27, 23, 22, 50, 'B', NULL, '2025-07-21 05:41:28'),
(28, 23, 22, 51, 'B', NULL, '2025-07-21 05:41:28'),
(29, 23, 22, 52, 'B', NULL, '2025-07-21 05:41:28'),
(30, 24, 22, 12, 'B', NULL, '2025-07-21 11:18:50'),
(31, 24, 22, 44, 'B', NULL, '2025-07-21 11:18:50'),
(32, 24, 22, 45, 'A', NULL, '2025-07-21 11:18:50'),
(33, 24, 22, 46, 'B', NULL, '2025-07-21 11:18:50'),
(34, 24, 22, 47, 'B', NULL, '2025-07-21 11:18:50'),
(35, 24, 22, 48, 'A', NULL, '2025-07-21 11:18:50'),
(36, 24, 22, 49, 'A', NULL, '2025-07-21 11:18:50'),
(37, 24, 22, 51, 'A', NULL, '2025-07-21 11:18:50'),
(38, 24, 22, 50, 'A', NULL, '2025-07-21 11:18:50'),
(39, 24, 22, 52, 'B', NULL, '2025-07-21 11:18:50'),
(40, 25, 22, 45, 'C', NULL, '2025-07-26 17:42:39'),
(41, 25, 22, 48, 'C', NULL, '2025-07-26 17:42:39'),
(42, 25, 22, 52, 'C', NULL, '2025-07-26 17:42:39'),
(43, 25, 22, 49, 'C', NULL, '2025-07-26 17:42:39'),
(44, 25, 22, 12, 'C', NULL, '2025-07-26 17:42:39'),
(45, 25, 22, 44, 'C', NULL, '2025-07-26 17:42:39'),
(46, 25, 22, 46, 'C', NULL, '2025-07-26 17:42:39'),
(47, 25, 22, 47, 'C', NULL, '2025-07-26 17:42:39'),
(48, 25, 22, 50, 'C', NULL, '2025-07-26 17:42:39'),
(49, 25, 22, 51, 'C', NULL, '2025-07-26 17:42:39'),
(50, 28, 22, 12, 'B', NULL, '2025-07-27 15:04:02'),
(51, 28, 22, 44, 'B', NULL, '2025-07-27 15:04:02'),
(52, 28, 22, 46, 'A', NULL, '2025-07-27 15:04:02'),
(53, 28, 22, 47, 'B', NULL, '2025-07-27 15:04:02'),
(54, 28, 22, 48, 'B', NULL, '2025-07-27 15:04:02'),
(55, 28, 22, 49, 'B', NULL, '2025-07-27 15:04:02'),
(56, 28, 22, 53, 'A', NULL, '2025-07-27 15:04:02'),
(57, 28, 22, 57, 'A', NULL, '2025-07-27 15:04:02'),
(58, 28, 22, 45, 'B', NULL, '2025-07-27 15:04:02'),
(59, 28, 22, 50, 'A', NULL, '2025-07-27 15:04:02'),
(60, 28, 22, 51, 'A', NULL, '2025-07-27 15:04:02'),
(61, 28, 22, 52, 'B', NULL, '2025-07-27 15:04:02'),
(62, 28, 22, 54, 'A', NULL, '2025-07-27 15:04:02'),
(63, 28, 22, 56, 'B', NULL, '2025-07-27 15:04:02'),
(64, 29, 22, 12, 'B', NULL, '2025-07-27 15:12:05'),
(65, 29, 22, 44, 'B', NULL, '2025-07-27 15:12:05'),
(66, 29, 22, 45, 'A', NULL, '2025-07-27 15:12:05'),
(67, 29, 22, 47, 'A', NULL, '2025-07-27 15:12:06'),
(68, 29, 22, 63, 'B', NULL, '2025-07-27 15:12:06'),
(69, 29, 22, 64, 'C', NULL, '2025-07-27 15:12:06'),
(70, 29, 22, 65, 'D', NULL, '2025-07-27 15:12:06'),
(71, 29, 22, 46, 'B', NULL, '2025-07-27 15:12:05'),
(72, 29, 22, 62, 'A', NULL, '2025-07-27 15:12:06'),
(73, 29, 22, 66, 'E', NULL, '2025-07-27 15:12:06'),
(74, 30, 22, 45, 'B', NULL, '2025-07-27 15:20:57'),
(75, 30, 22, 47, 'B', NULL, '2025-07-27 15:20:57'),
(76, 30, 22, 63, 'B', NULL, '2025-07-27 15:20:57'),
(77, 30, 22, 12, 'B', NULL, '2025-07-27 15:20:57'),
(78, 30, 22, 44, 'B', NULL, '2025-07-27 15:20:57'),
(79, 30, 22, 62, 'B', NULL, '2025-07-27 15:20:57'),
(80, 30, 22, 64, 'C', NULL, '2025-07-27 15:20:57'),
(81, 30, 22, 66, 'E', NULL, '2025-07-27 15:20:57'),
(82, 30, 22, 46, 'B', NULL, '2025-07-27 15:20:57'),
(83, 30, 22, 65, 'A', NULL, '2025-07-27 15:20:57'),
(84, 31, 22, 12, 'A', NULL, '2025-07-27 15:59:15'),
(85, 31, 22, 44, 'B', NULL, '2025-07-27 15:59:15'),
(86, 31, 22, 45, 'A', NULL, '2025-07-27 15:59:15'),
(87, 31, 22, 46, 'B', NULL, '2025-07-27 15:59:15'),
(88, 31, 22, 47, 'A', NULL, '2025-07-27 15:59:15'),
(89, 31, 22, 62, 'A', NULL, '2025-07-27 15:59:15'),
(90, 31, 22, 63, 'B', NULL, '2025-07-27 15:59:15'),
(91, 31, 22, 64, 'C', NULL, '2025-07-27 15:59:15'),
(92, 31, 22, 65, 'D', NULL, '2025-07-27 15:59:15'),
(93, 31, 22, 66, 'E', NULL, '2025-07-27 15:59:15'),
(94, 32, 22, 12, 'B', NULL, '2025-07-27 16:12:35'),
(95, 32, 22, 44, 'A', NULL, '2025-07-27 16:12:35'),
(96, 32, 22, 45, 'B', NULL, '2025-07-27 16:12:35'),
(97, 32, 22, 46, 'A', NULL, '2025-07-27 16:12:35'),
(98, 32, 22, 47, 'B', NULL, '2025-07-27 16:12:35'),
(99, 32, 22, 62, 'A', NULL, '2025-07-27 16:12:35'),
(100, 32, 22, 63, 'B', NULL, '2025-07-27 16:12:35'),
(101, 32, 22, 66, 'E', NULL, '2025-07-27 16:12:35'),
(102, 32, 22, 64, 'C', NULL, '2025-07-27 16:12:35'),
(103, 32, 22, 65, 'D', NULL, '2025-07-27 16:12:35'),
(104, 33, 22, 12, 'A', NULL, '2025-07-27 16:28:01'),
(105, 33, 22, 44, 'B', NULL, '2025-07-27 16:28:01'),
(106, 33, 22, 45, 'B', NULL, '2025-07-27 16:28:01'),
(107, 33, 22, 46, 'B', NULL, '2025-07-27 16:28:01'),
(108, 33, 22, 47, 'A', NULL, '2025-07-27 16:28:01'),
(109, 33, 22, 62, 'A', NULL, '2025-07-27 16:28:01'),
(110, 33, 22, 64, 'C', NULL, '2025-07-27 16:28:01'),
(111, 33, 22, 66, 'E', NULL, '2025-07-27 16:28:01'),
(112, 33, 22, 63, 'D', NULL, '2025-07-27 16:28:01'),
(113, 33, 22, 65, 'E', NULL, '2025-07-27 16:28:01'),
(114, 34, 22, 62, 'D', NULL, '2025-07-27 17:49:48'),
(115, 34, 22, 63, 'D', NULL, '2025-07-27 17:49:48'),
(116, 34, 22, 64, 'C', NULL, '2025-07-27 17:49:48'),
(117, 34, 22, 65, 'C', NULL, '2025-07-27 17:49:48'),
(118, 34, 22, 66, 'B', NULL, '2025-07-27 17:49:48'),
(119, 45, 22, 12, 'B', NULL, '2025-07-27 21:01:53'),
(120, 45, 22, 44, 'B', NULL, '2025-07-27 21:01:53'),
(121, 45, 22, 45, 'B', NULL, '2025-07-27 21:01:53'),
(122, 45, 22, 46, 'A', NULL, '2025-07-27 21:01:53'),
(123, 45, 22, 47, 'A', NULL, '2025-07-27 21:01:53'),
(124, 46, 22, 62, 'B', NULL, '2025-07-27 21:20:04'),
(125, 46, 22, 63, 'B', NULL, '2025-07-27 21:20:04'),
(126, 46, 22, 64, 'C', NULL, '2025-07-27 21:20:04'),
(127, 46, 22, 65, 'D', NULL, '2025-07-27 21:20:04'),
(128, 46, 22, 66, 'E', NULL, '2025-07-27 21:20:04'),
(129, 46, 22, 12, 'B', NULL, '2025-07-27 21:20:04'),
(130, 46, 22, 44, 'B', NULL, '2025-07-27 21:20:04'),
(131, 46, 22, 45, 'B', NULL, '2025-07-27 21:20:04'),
(132, 46, 22, 46, 'A', NULL, '2025-07-27 21:20:04'),
(133, 46, 22, 47, 'A', NULL, '2025-07-27 21:20:04'),
(134, 47, 22, 12, 'B', NULL, '2025-07-27 22:38:00'),
(135, 47, 22, 44, 'A', NULL, '2025-07-27 22:38:00'),
(136, 47, 22, 45, 'B', NULL, '2025-07-27 22:38:00'),
(137, 47, 22, 46, 'A', NULL, '2025-07-27 22:38:00'),
(138, 47, 22, 47, 'B', NULL, '2025-07-27 22:38:00'),
(139, 47, 22, 48, 'B', NULL, '2025-07-27 22:38:00'),
(140, 47, 22, 49, 'A', NULL, '2025-07-27 22:38:00'),
(141, 47, 22, 50, 'B', NULL, '2025-07-27 22:38:00'),
(142, 47, 22, 51, 'B', NULL, '2025-07-27 22:38:00'),
(143, 47, 22, 52, 'B', NULL, '2025-07-27 22:38:00'),
(144, 48, 22, 12, 'A', NULL, '2025-07-27 23:25:58'),
(145, 48, 22, 44, 'B', NULL, '2025-07-27 23:25:58'),
(146, 48, 22, 45, 'B', NULL, '2025-07-27 23:25:58'),
(147, 48, 22, 46, 'A', NULL, '2025-07-27 23:25:59'),
(148, 48, 22, 47, 'B', NULL, '2025-07-27 23:25:59'),
(149, 48, 22, 62, 'A', NULL, '2025-07-27 23:25:59'),
(150, 48, 22, 63, 'B', NULL, '2025-07-27 23:25:59'),
(151, 48, 22, 64, 'C', NULL, '2025-07-27 23:25:59'),
(152, 48, 22, 65, 'D', NULL, '2025-07-27 23:25:59'),
(153, 48, 22, 66, 'E', NULL, '2025-07-27 23:25:59'),
(154, 49, 22, 62, 'A', NULL, '2025-07-27 23:36:43'),
(155, 49, 22, 63, 'B', NULL, '2025-07-27 23:36:43'),
(156, 49, 22, 64, 'C', NULL, '2025-07-27 23:36:43'),
(157, 49, 22, 65, 'D', NULL, '2025-07-27 23:36:43'),
(158, 49, 22, 66, 'E', NULL, '2025-07-27 23:36:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_soal`
--

CREATE TABLE `kategori_soal` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori_soal`
--

INSERT INTO `kategori_soal` (`id`, `nama_kategori`) VALUES
(2, 'PASS HAND A2'),
(3, 'WAWASAN KEBANGSAAN 2025'),
(4, 'TWK 1 2025'),
(5, 'SOAL PENGETAHUAN UMUM 2025'),
(6, 'SOAL BAHASA INGGRIS 1 2025'),
(7, 'PENGETAHUAN UMUM AKPOL 2025'),
(8, 'PENALARAN NUMERIK 2025'),
(9, 'BAHASA INDONESIA AKPOL 2025'),
(10, 'KEPRIBADIAN 2025'),
(11, 'PSIKOLOGI'),
(12, 'TES UJI KEPRIBADIAN'),
(13, 'PSIKOLOGI'),
(14, 'SOAL SCAN NEW PENGETAHUAN UMUM'),
(15, 'KEPRIBADIAN BINTARA'),
(16, 'WAWASAN KEBANGSAAN BINTARA'),
(17, 'KECERDASAN BINTARA'),
(18, 'PENGETAHUAN UMUM BINTARA'),
(19, 'B.INGGRIS BINTARA'),
(20, 'UJI SOAL B.INGGRIS'),
(21, 'GABUNGAN KECERDASAN'),
(22, 'GABUNGAN WAWASAN KEBANGSAAN'),
(23, 'GABUNGAN MATEMATIKA'),
(24, 'TES CERMAT III REV'),
(25, 'TES CERMAT II REV'),
(26, 'TES CERMAT I REV'),
(27, 'GABUNGAN PENGETAHUAN UMUM'),
(28, 'SIP-KECERDASAN-SPASIAL GAMBAR'),
(29, 'SIP-KECERDASAN-HIPONIM'),
(30, 'SIP-KECERDASAN-SINONIM-'),
(31, 'SIP-KECERDASAN-MATEMATIKA DASAR'),
(32, 'SIP-KECERDASAN-DERET'),
(33, 'SIP-KECERDASAN-PENALARAN ANALITIS'),
(34, 'SIP-KECERDASAN-ANALOGI'),
(35, 'TES KEPRIBADIAN II'),
(36, 'TES KEPRIBADIAN I'),
(37, 'TES CERDAS 4'),
(38, 'TES CERDAS 3'),
(39, 'TES CERDAS 2'),
(40, 'TES CERDAS 1'),
(41, 'TES CERMAT 3'),
(42, 'TES CERMAT 2'),
(43, 'TES CERMAT 1'),
(44, 'SIP-KECERDASAN-ANTONIM'),
(45, 'TES CERMAT D'),
(46, 'SOAL MATEMATIKA BINTARA UPDATE 2'),
(47, 'SOAL MATEMATIKA BINTARA UPDATE 1'),
(48, 'SOAL BAHASA INGGRIS UPDATE 2'),
(49, 'SOAL BAHASA INGGRIS UPDATE 1'),
(50, 'SOAL PENGETAHUAN UMUM UPDATE 2'),
(51, 'SOAL PENGETAHUAN UMUM UPDATE 1'),
(52, 'SOAL WAWASAN KEBANGSAAN UPDATE 2'),
(53, 'SOAL WAWASAN KEBANGSAAN UPDATE 1'),
(54, 'TES BAHASA INGGRIS'),
(55, 'TES TRY OUT BAHASA INGGRIS'),
(56, 'CERDAS GABUNGAN'),
(57, 'CERDAS ANGGAPAN'),
(58, 'TES PRIBADI G'),
(59, 'TES PRIBADI F'),
(60, 'TES PRIBADI E'),
(61, 'TES PRIBADI D'),
(62, 'TES PRIBADI C'),
(63, 'TES PRIBADI B'),
(64, 'TES PRIBADI A'),
(65, 'TES CERMAT E'),
(66, 'TES CERDAS B'),
(67, 'TES CERDAS A'),
(68, 'TES CERMAT ANGKA HILANG'),
(69, 'TES CERMAT C'),
(70, 'TES CERMAT B'),
(71, 'TES CERMAT A'),
(72, 'TES KECERDASAN SESPIMMEN'),
(73, 'TES MATEMATIKA'),
(74, 'TES BAHASA INGGRIS 2022(jangan dipakai)'),
(75, 'TES PENGETAHUAN UMUM'),
(76, 'TES WAWASAN KEBANGSAAN'),
(77, 'TES CERMAT ANGKA HILANG DAN SIMBOL'),
(78, 'TES CERMAT ANGKA HILANG'),
(79, 'TES CERDAS SIP'),
(80, 'TES KOMPETENSI MANAGERIAL SIP'),
(81, 'TES KEPRIBADIAN SIP'),
(82, 'TES KOMPETENSI MANAGERIAL SESPIMA'),
(83, 'TES CERDAS SESPIMA'),
(84, 'TES KEPRIBADIAN SESPIMA'),
(85, 'TES KEPRIBADIAN II'),
(86, 'TES KEPRIBADIAN I'),
(87, 'TES CERDAS II'),
(88, 'TES CERMAT II'),
(89, 'TES CERDAS I'),
(90, 'TES CERMAT I'),
(91, 'TES KOMPETENSI MANGERIAL'),
(92, 'SOAL TRY OUT MATEMATIKA BINTARA'),
(93, 'TRY OUT WAWASAN KEBANGSAAN'),
(94, 'TRY OUT PENGETAHUAN UMUM BINTARA'),
(95, 'Soal Tryout B.Ingg ( jangan dipakai )'),
(96, 'MTK-MATRIKS'),
(97, 'MTK-SUKU BILANG'),
(98, 'MTK-PERSAMAAN 2 VARIABEL'),
(99, 'MTK - PELUANG'),
(100, 'PU-1'),
(101, 'TWK-1'),
(102, 'ENG-1'),
(103, 'MTK-1'),
(104, 'BAHASA INGGRIS (jangan dipakai )'),
(105, 'WAWASAN KEBANGSAAN'),
(106, 'MATEMATIKA'),
(108, 'PEMAHAMAN UMUM');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2025-06-15-000001', 'App\\Database\\Migrations\\Ujian', 'default', 'App', 1749974321, 1),
(2, '2025-06-15-102431', 'App\\Database\\Migrations\\UjianPeserta', 'default', 'App', 1749983181, 2),
(3, '2025-06-15-153200', 'App\\Database\\Migrations\\UjianPeserta', 'default', 'App', 1749983268, 3),
(4, '2025-06-16-002622', 'App\\Database\\Migrations\\JawabanUjian', 'default', 'App', 1750033626, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_app`
--

CREATE TABLE `setting_app` (
  `id` int(11) NOT NULL,
  `timezone` varchar(50) DEFAULT 'Asia/Jakarta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `setting_app`
--

INSERT INTO `setting_app` (`id`, `timezone`) VALUES
(1, 'Asia/Makassar');

-- --------------------------------------------------------

--
-- Struktur dari tabel `soal`
--

CREATE TABLE `soal` (
  `id` int(11) NOT NULL,
  `pertanyaan` text NOT NULL,
  `pilihan_a` text NOT NULL,
  `pilihan_b` text NOT NULL,
  `pilihan_c` text NOT NULL,
  `pilihan_d` text NOT NULL,
  `pilihan_e` text DEFAULT NULL,
  `kunci_jawaban` char(1) NOT NULL,
  `bobot` int(11) DEFAULT 1,
  `kategori_id` int(11) NOT NULL,
  `pembimbing_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `soal`
--

INSERT INTO `soal` (`id`, `pertanyaan`, `pilihan_a`, `pilihan_b`, `pilihan_c`, `pilihan_d`, `pilihan_e`, `kunci_jawaban`, `bobot`, `kategori_id`, `pembimbing_id`, `created_at`) VALUES
(12, '<p>Nilai jelek yang saya dapat dari guru karena pengaruh kawan-kawan saya.</p>', '<p>Menggambarkan</p>', '<p>Tidak Menggambarkan</p>', '<p>none</p>', '<p>none</p>', '<p>none</p>', 'B', 1, 2, 13, '2025-07-21 08:58:16'),
(44, 'Saya senang berkenalan dengan orang baru.', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'A', 1, 2, 13, '2025-07-21 11:18:17'),
(45, 'Saya merasa bahwa tidak ada yang berpihak kepada saya.', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'A', 1, 2, 13, '2025-07-21 11:18:17'),
(46, 'Saya menyesall perbuatan buruk yang saya lakukan.', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'A', 1, 2, 13, '2025-07-21 11:18:17'),
(47, 'Ada kalanya aku memaklumi saat disakiti oleh pasangan yang perna kukecewakan.', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'A', 1, 2, 13, '2025-07-21 11:18:17'),
(48, 'Saya adalah orang yang suka berpetualang', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'A', 1, 2, 13, '2025-07-21 11:18:17'),
(49, 'Tidak ada orang yan benar-benar baik di dunia ini', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'B', 1, 2, 13, '2025-07-21 11:18:17'),
(50, 'Waspada pada teman dekat itu perlu.', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'A', 1, 2, 13, '2025-07-21 11:18:17'),
(51, 'Kesalahan yang saya lakukan bukan semata-mata karena saya saja.', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'B', 1, 2, 13, '2025-07-21 11:18:17'),
(52, 'Seseorang telah memojokkan aku untuk membuatku dinilal bodoh.', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'B', 1, 2, 13, '2025-07-21 11:18:17'),
(53, 'Menurut saya kegagalan adalah hal yang sangat memalukan.', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'B', 1, 2, 13, '2025-07-21 11:18:17'),
(54, 'Sampal kapanpun, aku tidak bisa memaafkan orang yang menyakitiku.', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'B', 1, 2, 13, '2025-07-21 11:18:17'),
(55, 'Orang yang saya sukai hanya menganggap saya sebagai teman', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'B', 1, 2, 13, '2025-07-21 11:18:17'),
(56, 'Saya tidak memiliki kesulitan untuk mempercayai orang lain', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'A', 1, 2, 13, '2025-07-21 11:18:17'),
(57, 'Saya sering menggunakan uang SPP untuk keperluan lain yang saya anggap lebih penting.', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'B', 1, 2, 13, '2025-07-21 11:18:17'),
(58, 'Aku yakin bahwa ada yang bersekongkol untuk menjatuhkan aku.', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'B', 1, 2, 13, '2025-07-21 11:18:17'),
(59, 'Usaha yang tidak pernah cukup untuk membuat orang lain tertarik.', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'B', 1, 2, 13, '2025-07-21 11:18:17'),
(60, 'Saya merasa tidak ada lagi yang dapat diharapkan di dunia ini.', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'B', 1, 2, 13, '2025-07-21 11:18:17'),
(61, 'Jika sedang mengerjakan tugas, Saya berani menolak ajakan teman untuk nonton bioskop.', 'Menggambarkan', 'Tidak Menggambarkan', 'none', 'none', 'none', 'A', 1, 2, 13, '2025-07-21 11:18:17'),
(62, '<p>test soal 1</p>', '<p>fer</p>', '<p>dfgrdt</p>', '<p>gfdhy</p>', '<p>gftdh</p>', '<p>fdh</p>', 'A', 1, 3, 13, '2025-07-27 14:44:16'),
(63, '<p>tesl soal 2</p>', '<p>dsvdf</p>', '<p>cb</p>', '<p>gfng</p>', '<p>ncvmn</p>', '<p>cvnmgh</p>', 'B', 1, 3, 13, '2025-07-27 14:44:41');
INSERT INTO `soal` (`id`, `pertanyaan`, `pilihan_a`, `pilihan_b`, `pilihan_c`, `pilihan_d`, `pilihan_e`, `kunci_jawaban`, `bobot`, `kategori_id`, `pembimbing_id`, `created_at`) VALUES
(64, '<p>test soal 3 <img src=\"data:image/png;base64,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38lJb++mG0nLr/5vXrbileMyspbyl+3b55pbIvPFOEx8nFeDuR6MJJV5kjEQGTf3pawg0tErFCkgZbUn093Vl2WSkzCNiBzlKlM+A5XHFKpcmcoVp0z/dfjWLlKO+eZ5qc71aElLkZVE+vGDZUWSMkZ5hin3Rfoyn+VYniKUW/YnYyTSv4xNxs22eEzpaue58zHAadMZB7/mThkqApXiz5aRhHm2+BQ5KCtLWY953KdA5UpSClAKVGmXEpH1eExhS/jr+SzH8vav68vq08ry1PTBOoT1eSwxU/bafDtgc3zD19zg1vjnMGes1g9FyVf33bGJNC175iu30m5Ffxy/M9/O+6mIXlktyzTuywpV1iEyR4LfDbSiKIrSMvGToqlg34fLvXl11FaM1nZbE6nEl4kCNRQKIfTll4ZgEKEvvkDo888RWrfO8NlnCBUVIfTJJwgFAgh9/LHho48QKixE6MMPESooQOiDDxB6/32E8vMReu89hN55B6G8PMNbbyGUm4vQG28gtHYtQjk5CL3+OkKvvYZQdjZCr7yCUFYWQi+/jNBLLyH04osIPf88Qs89h9CzzyK0Zg1CzzyD0OrVCD39NEIrVyL01FMIPfkkQk88gdDjjyP02GMIPfooQsuXI/TIIwg99BBCDz6I0LJlCD3wAEL334/QffchdM89CN19N0J33WVYsgShxYsRWrQIoTvuQMiZT5fbbkPollsQuvlmhG66CaEFCwzz5iF0440I3XADQnPnGq69FqFrrkHo6qsRuvJKhK64wjB7NkKzZiE0cyZCM2YgdNllCF16KUKXXILQ9OkIXXwxQhddZLjgAoSmTkVoyhSEJk9GaNIkhCZORGjCBITGj0fo3HMRGjcOobFjETrnHITOPhuhs85C6MwzERo9GqEzzkDo9NMR4jk99VSERo1CaORIhE45BaGTT0bopJMQGj4coWHDDEOHIjRkCELOdR4aNAihAQMM/fsj1LcvQn36INSrl6FnT4S6d0fIucZD3bohdOyxhqOPRuiooxA68kiEDj8cocMOMxxyCEIHH4zQgQci5LwHuOy/P0LO9Rrabz+E9tkHob33Nuy5J0J77IHQbrshtOuuCO2yC0I774zQTjshtOOOCG2/PULbbYfQttsitM02CG29NUJduiDUuTNCnToh1LEjQh06INSuHUJt2yK01VYIbbklQm3a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', '<p>fdhbfg</p>', '<p>fbh</p>', '<p>fdbht</p>', '<p>tfrhb</p>', '<p>gfj</p>', 'C', 1, 3, 13, '2025-07-27 14:45:47');
INSERT INTO `soal` (`id`, `pertanyaan`, `pilihan_a`, `pilihan_b`, `pilihan_c`, `pilihan_d`, `pilihan_e`, `kunci_jawaban`, `bobot`, `kategori_id`, `pembimbing_id`, `created_at`) VALUES
(65, '<p>test soal 4</p>', '<p>q3v2fgx</p>', '<p>xvfcbc</p>', '<p>cvnfg</p>', '<p>vcbn</p>', '<p>vcbmjgh</p>', 'D', 1, 3, 13, '2025-07-27 14:46:15'),
(66, '<p>test soal 5</p>', '<p>fbjht</p>', '<p>ynky</p>', '<p>nyuk</p>', '<p>mhykju</p>', '<p>mjhkl</p>', 'E', 1, 3, 13, '2025-07-27 14:46:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ujian`
--

CREATE TABLE `ujian` (
  `id` int(10) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `kategori_id` int(10) UNSIGNED NOT NULL,
  `kategori_data` text DEFAULT NULL,
  `pembimbing_id` int(10) UNSIGNED NOT NULL,
  `jumlah_soal` int(10) UNSIGNED NOT NULL,
  `waktu_menit` int(10) UNSIGNED NOT NULL,
  `toleransi_menit` int(10) UNSIGNED NOT NULL,
  `acak_soal` tinyint(1) NOT NULL DEFAULT 1,
  `passing_grade` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `mulai` datetime NOT NULL,
  `selesai` datetime DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ujian`
--

INSERT INTO `ujian` (`id`, `judul`, `kategori_id`, `kategori_data`, `pembimbing_id`, `jumlah_soal`, `waktu_menit`, `toleransi_menit`, `acak_soal`, `passing_grade`, `mulai`, `selesai`, `status`, `created_at`, `updated_at`) VALUES
(45, 'UJIAN 1', 2, '[{\"kategori_id\":\"2\",\"jumlah_soal\":\"5\",\"passing_grade\":\"70\",\"waktu_menit\":\"5\"}]', 13, 5, 5, 5, 1, 70, '2025-07-28 00:06:22', NULL, 0, '2025-07-27 20:40:13', '2025-07-28 00:01:23'),
(46, 'UJIAN 2', 3, '[{\"kategori_id\":\"3\",\"jumlah_soal\":\"5\",\"passing_grade\":\"70\",\"waktu_menit\":\"5\"},{\"kategori_id\":\"2\",\"jumlah_soal\":\"5\",\"passing_grade\":\"80\",\"waktu_menit\":\"5\"}]', 13, 10, 10, 5, 1, 70, '2025-07-28 00:06:22', NULL, 0, '2025-07-27 20:40:53', '2025-07-28 00:01:23'),
(47, 'UJIAN 3', 2, '[{\"kategori_id\":\"2\",\"jumlah_soal\":\"10\",\"passing_grade\":\"80\",\"waktu_menit\":\"5\"}]', 13, 10, 5, 5, 1, 70, '2025-07-28 00:06:22', NULL, 0, '2025-07-27 22:18:48', '2025-07-28 00:01:23'),
(48, 'UJIAN 4', 3, '[{\"kategori_id\":\"3\",\"jumlah_soal\":\"5\",\"passing_grade\":\"80\",\"waktu_menit\":\"5\"},{\"kategori_id\":\"2\",\"jumlah_soal\":\"5\",\"passing_grade\":\"50\",\"waktu_menit\":\"5\"}]', 13, 10, 10, 5, 1, 80, '2025-07-28 00:06:22', NULL, 0, '2025-07-27 22:19:42', '2025-07-28 00:01:23'),
(49, 'UJIAN 5', 3, '[{\"kategori_id\":\"3\",\"jumlah_soal\":\"5\",\"passing_grade\":\"80\",\"waktu_menit\":\"10\"}]', 13, 5, 10, 5, 1, 70, '2025-07-28 00:06:22', NULL, 0, '2025-07-27 23:14:10', '2025-07-28 00:01:23'),
(50, 'UJIAN COBA 6', 3, '[{\"kategori_id\":\"3\",\"jumlah_soal\":\"5\",\"passing_grade\":\"50\",\"waktu_menit\":\"10\"}]', 13, 5, 10, 5, 1, 70, '2025-07-28 00:11:00', NULL, 0, '2025-07-27 23:22:59', '2025-07-28 00:09:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ujian_peserta`
--

CREATE TABLE `ujian_peserta` (
  `id` int(11) NOT NULL,
  `ujian_id` int(11) NOT NULL,
  `peserta_id` int(11) NOT NULL,
  `status` enum('belum_mulai','sedang_ujian','selesai') NOT NULL DEFAULT 'belum_mulai',
  `nilai` int(11) DEFAULT NULL,
  `mulai` datetime DEFAULT NULL,
  `selesai` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ujian_peserta`
--

INSERT INTO `ujian_peserta` (`id`, `ujian_id`, `peserta_id`, `status`, `nilai`, `mulai`, `selesai`) VALUES
(75, 45, 22, 'selesai', NULL, '2025-07-27 21:01:35', '2025-07-27 21:01:54'),
(76, 46, 22, 'selesai', NULL, '2025-07-27 21:19:16', '2025-07-27 21:20:04'),
(78, 48, 22, 'selesai', NULL, '2025-07-27 23:25:21', '2025-07-27 23:25:59'),
(79, 47, 22, 'selesai', NULL, '2025-07-27 22:37:39', '2025-07-27 22:38:00'),
(80, 49, 22, 'selesai', NULL, '2025-07-27 23:36:13', '2025-07-27 23:36:43'),
(81, 50, 22, 'belum_mulai', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `angkatan` varchar(10) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pembimbing','peserta') NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `aktif` tinyint(1) DEFAULT 1,
  `nip` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `angkatan`, `email`, `password`, `role`, `nama_lengkap`, `created_at`, `updated_at`, `aktif`, `nip`) VALUES
(1, 'admin', NULL, 'admin@cbt.local', '$2y$10$wirCIOsax8euXOaYjXKL9.hVEi80pf1w9.nCfzkqfe61dnSpR2muS', 'admin', 'Admin CAT', '2025-06-13 10:13:19', '2025-07-26 17:46:41', 1, NULL),
(13, 'PMB-0001', NULL, NULL, '$2y$10$i0.9OA70iKS/3eoQhtYID.i.4/T9s6L/d7KM263R01xSsxpn.5g7y', 'pembimbing', 'PEMBIMBING 1', '2025-06-14 08:01:49', '2025-07-21 01:49:22', 1, 'PMB-0001'),
(16, 'inidaus', NULL, NULL, '$2y$10$uVOyWqgsABUYTa0Vy8UFGuXVMCjrVk3gPOaMP68jvYjF8A1qrxo62', 'admin', 'NURIL FIRDAUS', '2025-06-14 10:24:37', '2025-06-14 10:24:37', 1, NULL),
(22, '1010ALFIN2005', '53', NULL, '$2y$10$wN5KUS27PsFTab7G9kuoYO39C7pCX1nVoawIqaiRfX0inToQX/Mqi', 'peserta', 'ALFIN', '2025-07-21 03:19:11', '2025-07-21 04:00:01', 0, NULL),
(23, '0309GRIFID2004', '53', NULL, '$2y$10$ONph9IMfQAx8mWwijR/sneiUqjAT8UQgV35lUX2oynUetBOxzdUZ.', 'peserta', 'GRIFID', '2025-07-21 03:19:57', '2025-07-21 04:01:12', 0, NULL),
(24, '0302ROSYID2007', '53', NULL, '$2y$10$D4FO/Tbooba/AjGdL17zB.tlVFpOfNJOc1EDD2/UOiQXSZGGgDMAa', 'peserta', 'ROSYID', '2025-07-21 03:21:36', '2025-07-21 04:01:11', 0, NULL),
(25, '1101MONA2007', '53', NULL, '$2y$10$i/hCLi6nimU/FBN2Z1UYVe9G5GTTQpRrSA6rWWLDawpaBp7fGV.oW', 'peserta', 'MONA', '2025-07-21 03:21:58', '2025-07-21 04:01:09', 0, NULL),
(26, '1012ALVIAN2006', '53', NULL, '$2y$10$GdRTgU0oNuDQgHO00Y62feboBoy.ouAwNhmjdAhqoEpZsozcxuyLC', 'peserta', 'ALVIAN', '2025-07-21 03:22:14', '2025-07-21 04:01:08', 0, NULL),
(27, '1012LUFHIAN2006', '53', NULL, '$2y$10$pNowby56My0Zv0JyR3.bh.nqElvvBf8.YdWNyFRROlg/OU1.vVMui', 'peserta', 'LUFHIAN', '2025-07-21 03:22:47', '2025-07-21 04:01:07', 0, NULL),
(28, '0607CITRA2007', '53', NULL, '$2y$10$PY.8x2rJLadGYp0PHjizHOjPzx/cQTrLdQXFOHltiYKCYY4sjL9gS', 'peserta', 'CITRA', '2025-07-21 03:23:06', '2025-07-21 04:01:05', 0, NULL),
(29, '1902SONI2025', '53', NULL, '$2y$10$2Bb/u/VnRAmIe6vgEAXEueDUW/xQTT1sf8sgEdxCOoyAwr8qX9TyK', 'peserta', 'FAHRI', '2025-07-21 03:23:25', '2025-07-21 04:01:03', 0, NULL),
(30, '1111RENDY2004', '53', NULL, '$2y$10$hIHLyMHf/WMMQbJzZ89BIeS7KOSSDj.nEkZTban1iZjwNb.37COZC', 'peserta', 'RENDY', '2025-07-21 03:23:40', '2025-07-21 04:01:01', 0, NULL),
(31, '1510MALIK2005', '53', NULL, '$2y$10$.m9WWeHSe3J2BlcBi7XCN.fSghHmpPADkYGBE35q/ieID7RSfhKM.', 'peserta', 'MALIK', '2025-07-21 03:23:57', '2025-07-21 04:00:58', 0, NULL),
(32, '2607ICA2001', '53', NULL, '$2y$10$pMuWRKS4nGLytgmsgXuZ9ea2f9hkEKNA8CdY2XGwQthWbSxe8IfEC', 'peserta', 'ICA', '2025-07-21 03:24:27', '2025-07-21 04:00:54', 0, NULL),
(33, '1208INTAN2005', '53', NULL, '$2y$10$4HERwKo938HrriTXMYgWOumLYBwJV1spQpTnt.1B7pR7PhpXh2Cn6', 'peserta', 'INTAN', '2025-07-21 03:25:04', '2025-07-21 04:00:50', 0, NULL),
(34, '1612EXEL2005', '53', NULL, '$2y$10$XOqPKdWrLV226GBIqQvc1eUgc.VEfQWHK9rbjAG5iCSNAFvRKG7.6', 'peserta', 'EXEL', '2025-07-21 03:25:23', '2025-07-21 04:00:43', 0, NULL),
(35, '1112FAUZAN2004', '53', NULL, '$2y$10$7B5h/hBHxPIgYVRx.nbK0.P2or3KD/LW1081Fh.fOG5C4ciF1fjbO', 'peserta', 'FAUZAN', '2025-07-21 03:25:38', '2025-07-21 04:00:39', 0, NULL),
(36, '1709FINO2004', '53', NULL, '$2y$10$z1fLEfKE8DgZuK3R/03muurZJxP7ZcmkI5VnhkcSqGZqeRbWlk2Se', 'peserta', 'REFINO', '2025-07-21 03:25:52', '2025-07-21 04:00:35', 0, NULL),
(37, '2505MADE2005', '53', NULL, '$2y$10$I/x2IKxk9T5Tcg8HDn9mxOUaRidAswH3HS3WF3WIVb8S9LVso35Y2', 'peserta', 'MADE', '2025-07-21 03:26:07', '2025-07-21 04:00:30', 0, NULL),
(38, '1506AIDIT2005', '53', NULL, '$2y$10$u/uhLyHdRoLb/rvT1weWHue6F/MXb6VJQbz7VPFelLAssWC3Za9wW', 'peserta', 'AIDIT', '2025-07-21 03:26:24', '2025-07-21 04:00:21', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `jawaban_ujian`
--
ALTER TABLE `jawaban_ujian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori_soal`
--
ALTER TABLE `kategori_soal`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `setting_app`
--
ALTER TABLE `setting_app`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `soal`
--
ALTER TABLE `soal`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ujian`
--
ALTER TABLE `ujian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ujian_peserta`
--
ALTER TABLE `ujian_peserta`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `jawaban_ujian`
--
ALTER TABLE `jawaban_ujian`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT untuk tabel `kategori_soal`
--
ALTER TABLE `kategori_soal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `soal`
--
ALTER TABLE `soal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT untuk tabel `ujian`
--
ALTER TABLE `ujian`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `ujian_peserta`
--
ALTER TABLE `ujian_peserta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
