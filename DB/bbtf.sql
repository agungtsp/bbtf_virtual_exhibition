-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Waktu pembuatan: 26 Apr 2021 pada 23.04
-- Versi server: 5.7.26
-- Versi PHP: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bbtf_virtual_exhibition`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `access_log`
--

CREATE TABLE `access_log` (
  `id` int(11) NOT NULL,
  `id_auth_user` int(11) DEFAULT NULL,
  `log_date` datetime DEFAULT NULL,
  `activity` longtext NOT NULL,
  `ip` varchar(64) NOT NULL DEFAULT '',
  `detail` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `access_log`
--

INSERT INTO `access_log` (`id`, `id_auth_user`, `log_date`, `activity`, `ip`, `detail`) VALUES
(1, 1, '2020-07-07 21:31:44', 'Insert Category of Forum', '::1', 'INSERT INTO `forum_category` (`name`, `uri_path`, `create_date`, `user_id_create`) VALUES (\'Service Motor\', \'service-motor\', \'2020-07-07 21:31:44\', \'1\');\n'),
(2, 1, '2020-07-07 21:32:07', 'Update Category of Forum', '::1', 'UPDATE `forum_category` SET `name` = \'Service Motor2\', `uri_path` = \'service-motor2\', `description` = \'\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-07 21:32:07\'\nWHERE `id` = \'16\';\n'),
(3, 1, '2020-07-07 21:32:12', 'Update Category of Forum', '::1', 'UPDATE `forum_category` SET `name` = \'Service Motor\', `uri_path` = \'service-motor\', `description` = \'\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-07 21:32:12\'\nWHERE `id` = \'16\';\n'),
(4, 1, '2020-07-07 21:32:18', 'Insert Category of Forum', '::1', 'INSERT INTO `forum_category` (`name`, `uri_path`, `create_date`, `user_id_create`) VALUES (\'test\', \'test\', \'2020-07-07 21:32:18\', \'1\');\n'),
(5, 1, '2020-07-07 21:32:21', 'Delete Category of Forum', '::1', 'UPDATE `forum_category` SET `is_delete` = 1, `user_id_modify` = \'1\', `modify_date` = \'2020-07-07 21:32:21\'\nWHERE `id` = \'17\';\n'),
(6, 1, '2020-07-07 21:41:22', 'Insert Forum', '::1', 'INSERT INTO `forum` (`id_auth_user`, `id_forum_category`, `title`, `description`, `create_date`, `user_id_create`) VALUES (\'1\', \'16\', \'testing ini bener gk sih ?\', \'<p>coba cek deh ini bener gk motor panas kalau dipanasin ?</p>\\n\', \'2020-07-07 21:41:22\', \'1\');\n'),
(7, 1, '2020-07-07 21:46:16', 'Update Forum', '::1', 'UPDATE `forum` SET `id_auth_user` = \'1\', `id_forum_category` = \'16\', `title` = \'testing ini bener gk sih ?\', `description` = \'<p>coba cek deh ini bener gk motor panas kalau dipanasin ?</p>\\n\', `id_status_publish` = \'2\', `files` = \'\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-07 21:46:16\'\nWHERE `id` = \'1\';\n'),
(8, 1, '2020-07-07 21:46:21', 'Update Forum', '::1', 'UPDATE `forum` SET `id_auth_user` = \'1\', `id_forum_category` = \'16\', `title` = \'testing ini bener gk sih ?\', `description` = \'<p>coba cek deh ini bener gk motor panas kalau dipanasin ?</p>\\n\', `id_status_publish` = \'1\', `files` = \'\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-07 21:46:21\'\nWHERE `id` = \'1\';\n'),
(9, 1, '2020-07-07 21:46:34', 'Insert Forum', '::1', 'INSERT INTO `forum` (`id_auth_user`, `id_forum_category`, `title`, `description`, `id_status_publish`, `create_date`, `user_id_create`) VALUES (\'1\', \'16\', \'hore langsung tayang loh\', \'<p>hore langsung tayang loh</p>\\n\', \'2\', \'2020-07-07 21:46:34\', \'1\');\n'),
(10, 1, '2020-07-07 21:46:39', 'Delete Category of Forum', '::1', 'UPDATE `forum` SET `is_delete` = 1, `user_id_modify` = \'1\', `modify_date` = \'2020-07-07 21:46:39\'\nWHERE `id` = \'2\';\n'),
(11, 1, '2020-07-07 21:47:34', 'Update Forum', '::1', 'UPDATE `forum` SET `id_auth_user` = \'1\', `id_forum_category` = \'16\', `title` = \'testing ini bener gk sih ?\', `description` = \'<p>coba cek deh ini bener gk motor panas kalau dipanasin ?</p>\\n\', `id_status_publish` = \'2\', `files` = \'\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-07 21:47:34\'\nWHERE `id` = \'1\';\n'),
(12, 1, '2020-07-07 21:51:41', 'Delete Comment of Forum', '::1', 'UPDATE `forum_comment` SET `is_delete` = 1, `user_id_modify` = \'1\', `modify_date` = \'2020-07-07 21:51:41\'\nWHERE `id` = \'9\';\n'),
(13, NULL, '2020-07-09 13:06:50', 'User not found : superadmin', '::1', NULL),
(14, 1, '2020-07-09 13:06:53', 'Login', '::1', NULL),
(15, 1, '2020-07-09 13:40:45', 'Insert Help Category', '::1', 'INSERT INTO `motor_merek` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Yamaha\', \'2\', \'2020-07-09 13:40:45\', \'1\');\n'),
(16, 1, '2020-07-09 13:40:52', 'Insert Help Category', '::1', 'INSERT INTO `motor_merek` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Honda\', \'2\', \'2020-07-09 13:40:52\', \'1\');\n'),
(17, 1, '2020-07-09 13:41:11', 'Insert Help Category', '::1', 'INSERT INTO `motor_jenis` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Matik\', \'2\', \'2020-07-09 13:41:11\', \'1\');\n'),
(18, 1, '2020-07-09 13:41:18', 'Insert Help Category', '::1', 'INSERT INTO `motor_jenis` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Bebek\', \'2\', \'2020-07-09 13:41:18\', \'1\');\n'),
(19, 1, '2020-07-09 13:41:50', 'Insert Help Category', '::1', 'INSERT INTO `motor_jenis` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Sport\', \'2\', \'2020-07-09 13:41:50\', \'1\');\n'),
(20, 1, '2020-07-09 13:42:23', 'Insert Help Category', '::1', 'INSERT INTO `motor_model` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'ABS\', \'2\', \'2020-07-09 13:42:23\', \'1\');\n'),
(21, 1, '2020-07-09 13:42:31', 'Insert Help Category', '::1', 'INSERT INTO `motor_model` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Non-ABS\', \'2\', \'2020-07-09 13:42:31\', \'1\');\n'),
(22, 1, '2020-07-09 13:42:39', 'Insert Help Category', '::1', 'INSERT INTO `motor_model` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Standard\', \'2\', \'2020-07-09 13:42:39\', \'1\');\n'),
(23, 1, '2020-07-09 13:43:20', 'Insert Help Category', '::1', 'INSERT INTO `motor_model` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Premium\', \'2\', \'2020-07-09 13:43:20\', \'1\');\n'),
(24, 1, '2020-07-09 13:43:40', 'Update Help Category', '::1', 'UPDATE `motor_jenis` SET `name` = \'CUB (Bebek)\', `teaser` = \'\', `id_status_publish` = \'2\', `user_modify_id` = \'1\', `date_modify` = \'2020-07-09 13:43:40\'\nWHERE `id` = \'2\';\n'),
(25, 1, '2020-07-09 13:46:28', 'Insert Motor Tipe', '::1', 'INSERT INTO `motor_tipe` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Premium\', \'2\', \'2020-07-09 13:46:28\', \'1\');\n'),
(26, 1, '2020-07-09 13:46:36', 'Insert Motor Tipe', '::1', 'INSERT INTO `motor_tipe` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Standard\', \'2\', \'2020-07-09 13:46:36\', \'1\');\n'),
(27, 1, '2020-07-09 13:46:47', 'Insert Motor Tipe', '::1', 'INSERT INTO `motor_tipe` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Non-ABS\', \'2\', \'2020-07-09 13:46:47\', \'1\');\n'),
(28, 1, '2020-07-09 13:46:54', 'Insert Motor Tipe', '::1', 'INSERT INTO `motor_tipe` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'ABS\', \'2\', \'2020-07-09 13:46:54\', \'1\');\n'),
(29, 1, '2020-07-09 13:48:33', 'Insert Motor Tipe', '::1', 'INSERT INTO `motor_tipe` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Cast Wheel (CW )\', \'2\', \'2020-07-09 13:48:33\', \'1\');\n'),
(30, 1, '2020-07-09 13:48:59', 'Insert Motor Tipe', '::1', 'INSERT INTO `motor_tipe` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Combi Brake System (CBS)\', \'2\', \'2020-07-09 13:48:59\', \'1\');\n'),
(31, 1, '2020-07-09 13:49:11', 'Insert Motor Tipe', '::1', 'INSERT INTO `motor_tipe` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Idling Stop System (ISS)\', \'2\', \'2020-07-09 13:49:11\', \'1\');\n'),
(32, 1, '2020-07-09 13:52:07', 'Update Motor Tipe', '::1', 'UPDATE `motor_tipe` SET `name` = \'Cast Wheel (CW )\', `teaser` = \'CW yaitu Cast Wheel artinya Velg yang dibuat dengan cara logam yang dicetak\', `id_status_publish` = \'2\', `user_modify_id` = \'1\', `date_modify` = \'2020-07-09 13:52:07\'\nWHERE `id` = \'5\';\n'),
(33, 1, '2020-07-09 13:52:22', 'Update Motor Tipe', '::1', 'UPDATE `motor_tipe` SET `name` = \'Combi Brake System (CBS)\', `teaser` = \'Combi Brake System dimana fungsinya adalah mengkombinasikan antara rem depan dan rem belakang dalam satu rem saja sehingga dapat melakukan pengereman lebih tepat, dan lebih praktis.\', `id_status_publish` = \'2\', `user_modify_id` = \'1\', `date_modify` = \'2020-07-09 13:52:22\'\nWHERE `id` = \'6\';\n'),
(34, 1, '2020-07-09 13:52:44', 'Update Motor Tipe', '::1', 'UPDATE `motor_tipe` SET `name` = \'Idling Stop System (ISS)\', `teaser` = \'Teknologi ISS ini akan otomatis mematikan mesin ketika kita berhenti lebih dari 3 detik,kemudian akan menghidupkan lagi mesin apabila tuas gas di tarik lagi, tanpa menekan tombol stater, tanpa hentakan mesin\', `id_status_publish` = \'2\', `user_modify_id` = \'1\', `date_modify` = \'2020-07-09 13:52:44\'\nWHERE `id` = \'7\';\n'),
(35, 1, '2020-07-09 13:52:59', 'Update Motor Tipe', '::1', 'UPDATE `motor_tipe` SET `name` = \'ABS\', `teaser` = \'Auto Break System\', `id_status_publish` = \'2\', `user_modify_id` = \'1\', `date_modify` = \'2020-07-09 13:52:59\'\nWHERE `id` = \'4\';\n'),
(36, 1, '2020-07-09 19:00:56', 'Incorrect password', '::1', NULL),
(37, 1, '2020-07-09 19:01:02', 'Login', '::1', NULL),
(38, 1, '2020-07-09 19:13:25', 'Insert Motor', '::1', 'INSERT INTO `motor` (`id_motor_merek`, `id_motor_jenis`, `id_motor_tipe`, `id_motor_model`, `name`, `uri_path`, `img`, `teaser`, `id_status_publish`, `page_content`, `create_date`, `user_id_create`) VALUES (\'2\', \'1\', \'4\', \'4\', \'Yamaha All New NMAX 155 ABS\', \'yamaha-all-new-nmax-155-abs\', \'aa8c2f56f98b13329697b1a4aa8b0a87_nmax.jpg\', \'Yamaha N Max ABS merupakan skuter matik terlengkap yang sempurna untuk Anda\', \'2\', \'<p>All New Yamaha N Max  merupakan skuter matik terlengkap yang sempurna untuk Anda.</p>\\n\', \'2020-07-09 19:13:25\', \'1\');\n'),
(39, 1, '2020-07-09 19:28:24', 'Insert Motor', '::1', 'INSERT INTO `motor` (`id_motor_merek`, `id_motor_jenis`, `id_motor_tipe`, `id_motor_model`, `name`, `uri_path`, `img`, `teaser`, `id_status_publish`, `page_content`, `create_date`, `user_id_create`) VALUES (\'2\', \'1\', \'6\', \'1\', \'Honda Vario 125\', \'honda-vario-125\', \'97433f6adae3a0e6562faf755095a757_ff7abef5d63992eb626cf7eac5b18794.jpeg\', \'Didesain dengan bodi yang ramping &amp; stripping bergaya sporty, jadikan Honda Vario 125 lebih lincah untuk berbagai aksi\', \'2\', \'<p><span style=\\\"color:rgb(77,81,86);font-family:arial, sans-serif;font-size:14px;\\\">Didesain dengan bodi yang ramping &amp; stripping bergaya sporty, jadikan </span><span style=\\\"font-weight:bold;color:rgb(95,99,104);font-family:arial, sans-serif;font-size:14px;\\\">Honda Vario</span><span style=\\\"color:rgb(77,81,86);font-family:arial, sans-serif;font-size:14px;\\\"> 125 lebih lincah untuk berbagai aksi</span></p>\\n\', \'2020-07-09 19:28:24\', \'1\');\n'),
(40, 1, '2020-07-09 19:29:03', 'Update Motor', '::1', 'UPDATE `motor` SET `id_motor_merek` = \'2\', `id_motor_jenis` = \'1\', `id_motor_tipe` = \'6\', `id_motor_model` = \'1\', `name` = \'Honda Vario 125\', `uri_path` = \'honda-vario-125\', `teaser` = \'Didesain dengan bodi yang ramping &amp; stripping bergaya sporty, jadikan Honda Vario 125 lebih lincah untuk berbagai aksi\', `id_status_publish` = \'2\', `page_content` = \'<p><span style=\\\"color:rgb(77,81,86);font-family:arial, sans-serif;font-size:14px;\\\">Didesain dengan bodi yang ramping &amp; stripping bergaya sporty, jadikan </span><span style=\\\"font-weight:bold;color:rgb(95,99,104);font-family:arial, sans-serif;font-size:14px;\\\">Honda Vario</span><span style=\\\"color:rgb(77,81,86);font-family:arial, sans-serif;font-size:14px;\\\"> 125 lebih lincah untuk berbagai aksi</span></p>\\n\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-09 19:29:03\'\nWHERE `id` = \'2\';\n'),
(41, 1, '2020-07-09 19:50:12', 'Update Motor', '::1', 'UPDATE `motor` SET `id_motor_merek` = \'1\', `id_motor_jenis` = \'1\', `id_motor_tipe` = \'4\', `id_motor_model` = \'4\', `name` = \'Yamaha All New NMAX 155 ABS\', `uri_path` = \'yamaha-all-new-nmax-155-abs\', `teaser` = \'Yamaha N Max ABS merupakan skuter matik terlengkap yang sempurna untuk Anda\', `id_status_publish` = \'2\', `tahun_pembuatan` = \'2020\', `tipe_mesin` = \'Liquid cooled 4-stroke, SOHC\', `jumlah_silinder` = \'1\', `kapasitas_mesin` = \'155\', `torsi_maksimum` = \'14\', `sistem_starter` = \'1\', `bahan_bakar` = \'Bensin\', `kapasitas_oli_mesin` = \'1\', `kapasitas_tanki_bensin` = \'1\', `ban_depan` = \'110/70 - 13 Inch - Tubeless\', `ban_belakang` = \'130/70 - 13 Inch - Tubeless\', `rem_depan` = \'Single Disk Brake (ABS)\', `rem_belakang` = \'1\', `aki` = \'1\', `page_content` = \'<p>All New Yamaha N Max  merupakan skuter matik terlengkap yang sempurna untuk Anda.</p>\\n\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-09 19:50:12\'\nWHERE `id` = \'1\';\n'),
(42, 1, '2020-07-09 19:52:32', 'Update Motor', '::1', 'UPDATE `motor` SET `id_motor_merek` = \'1\', `id_motor_jenis` = \'1\', `id_motor_tipe` = \'4\', `id_motor_model` = \'4\', `name` = \'Yamaha All New NMAX 155 ABS\', `uri_path` = \'yamaha-all-new-nmax-155-abs\', `teaser` = \'Yamaha N Max ABS merupakan skuter matik terlengkap yang sempurna untuk Anda\', `id_status_publish` = \'2\', `tahun_pembuatan` = \'2020\', `tipe_mesin` = \'Liquid cooled 4-stroke, SOHC\', `jumlah_silinder` = \'1\', `kapasitas_mesin` = \'155\', `torsi_maksimum` = \'14\', `sistem_starter` = \'1\', `bahan_bakar` = \'Bensin\', `kapasitas_oli_mesin` = \'1\', `kapasitas_tanki_bensin` = \'1\', `ban_depan` = \'110/70 - 13 Inch - Tubeless\', `ban_belakang` = \'Bensin\', `rem_depan` = \'Single Disk Brake (ABS)\', `rem_belakang` = \'Single Disk Brake (ABS)\', `aki` = \'1\', `page_content` = \'<p>All New Yamaha N Max  merupakan skuter matik terlengkap yang sempurna untuk Anda.</p>\\n\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-09 19:52:32\'\nWHERE `id` = \'1\';\n'),
(43, 1, '2020-07-09 19:53:25', 'Update Motor', '::1', 'UPDATE `motor` SET `id_motor_merek` = \'1\', `id_motor_jenis` = \'1\', `id_motor_tipe` = \'4\', `id_motor_model` = \'4\', `name` = \'Yamaha All New NMAX 155 ABS\', `uri_path` = \'yamaha-all-new-nmax-155-abs\', `teaser` = \'Yamaha N Max ABS merupakan skuter matik terlengkap yang sempurna untuk Anda\', `id_status_publish` = \'2\', `tahun_pembuatan` = \'2020\', `tipe_mesin` = \'Liquid cooled 4-stroke, SOHC\', `jumlah_silinder` = \'1\', `kapasitas_mesin` = \'155\', `torsi_maksimum` = \'14\', `sistem_starter` = \'1\', `bahan_bakar` = \'Bensin\', `kapasitas_oli_mesin` = \'1\', `kapasitas_tanki_bensin` = \'1\', `ban_depan` = \'110/70 - 13 Inch - Tubeless\', `ban_belakang` = \'Bensin\', `rem_depan` = \'Single Disk Brake (ABS)\', `rem_belakang` = \'Single Disk Brake (ABS)\', `aki` = \'YTZ7V\', `page_content` = \'<p>All New Yamaha N Max  merupakan skuter matik terlengkap yang sempurna untuk Anda.</p>\\n\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-09 19:53:25\'\nWHERE `id` = \'1\';\n'),
(44, 1, '2020-07-09 19:54:26', 'Update Motor', '::1', 'UPDATE `motor` SET `id_motor_merek` = \'1\', `id_motor_jenis` = \'1\', `id_motor_tipe` = \'4\', `id_motor_model` = \'4\', `name` = \'Yamaha All New NMAX 155 ABS\', `uri_path` = \'yamaha-all-new-nmax-155-abs\', `teaser` = \'Yamaha N Max ABS merupakan skuter matik terlengkap yang sempurna untuk Anda\', `id_status_publish` = \'2\', `tahun_pembuatan` = \'2020\', `tipe_mesin` = \'Liquid cooled 4-stroke, SOHC\', `jumlah_silinder` = \'1\', `kapasitas_mesin` = \'155\', `torsi_maksimum` = \'14\', `sistem_starter` = \'Electric\', `aki` = \'YTZ7V\', `bahan_bakar` = \'Bensin\', `kapasitas_oli_mesin` = \'1\', `kapasitas_tanki_bensin` = \'1\', `ban_depan` = \'110/70 - 13 Inch - Tubeless\', `ban_belakang` = \'Bensin\', `rem_depan` = \'Single Disk Brake (ABS)\', `rem_belakang` = \'Single Disk Brake (ABS)\', `page_content` = \'<p>All New Yamaha N Max  merupakan skuter matik terlengkap yang sempurna untuk Anda.</p>\\n\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-09 19:54:26\'\nWHERE `id` = \'1\';\n'),
(45, 1, '2020-07-09 19:54:51', 'Update Motor', '::1', 'UPDATE `motor` SET `id_motor_merek` = \'1\', `id_motor_jenis` = \'1\', `id_motor_tipe` = \'4\', `id_motor_model` = \'4\', `name` = \'Yamaha All New NMAX 155 ABS\', `uri_path` = \'yamaha-all-new-nmax-155-abs\', `teaser` = \'Yamaha N Max ABS merupakan skuter matik terlengkap yang sempurna untuk Anda\', `id_status_publish` = \'2\', `tahun_pembuatan` = \'2020\', `tipe_mesin` = \'Liquid cooled 4-stroke, SOHC\', `jumlah_silinder` = \'1\', `kapasitas_mesin` = \'155\', `torsi_maksimum` = \'14\', `sistem_starter` = \'Electric\', `aki` = \'YTZ7V\', `bahan_bakar` = \'Bensin\', `kapasitas_oli_mesin` = \'1\', `kapasitas_tanki_bensin` = \'1\', `ban_depan` = \'110/70 - 13 Inch - Tubeless\', `ban_belakang` = \'Bensin\', `rem_depan` = \'Single Disk Brake (ABS)\', `rem_belakang` = \'Single Disk Brake (ABS)\', `page_content` = \'<p>All New Yamaha N Max  merupakan skuter matik terlengkap yang sempurna untuk Anda.</p>\\n\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-09 19:54:51\'\nWHERE `id` = \'1\';\n'),
(46, 1, '2020-07-09 19:56:33', 'Update Motor', '::1', 'UPDATE `motor` SET `id_motor_merek` = \'1\', `id_motor_jenis` = \'1\', `id_motor_tipe` = \'4\', `id_motor_model` = \'4\', `name` = \'Yamaha All New NMAX 155 ABS\', `uri_path` = \'yamaha-all-new-nmax-155-abs\', `teaser` = \'Yamaha N Max ABS merupakan skuter matik terlengkap yang sempurna untuk Anda\', `id_status_publish` = \'2\', `tahun_pembuatan` = \'2020\', `tipe_mesin` = \'Liquid cooled 4-stroke, SOHC\', `jumlah_silinder` = \'1\', `kapasitas_mesin` = \'155\', `torsi_maksimum` = \'14\', `sistem_starter` = \'Electric\', `aki` = \'YTZ7V\', `bahan_bakar` = \'Bensin\', `kapasitas_oli_mesin` = \'1\', `kapasitas_tanki_bensin` = \'6\', `ban_depan` = \'110/70 - 13 Inch - Tubeless\', `ban_belakang` = \'Bensin\', `rem_depan` = \'Single Disk Brake (ABS)\', `rem_belakang` = \'Single Disk Brake (ABS)\', `page_content` = \'<p>All New Yamaha N Max  merupakan skuter matik terlengkap yang sempurna untuk Anda.</p>\\n\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-09 19:56:33\'\nWHERE `id` = \'1\';\n'),
(47, 1, '2020-07-09 19:59:03', 'Update Motor', '::1', 'UPDATE `motor` SET `id_motor_merek` = \'1\', `id_motor_jenis` = \'1\', `id_motor_tipe` = \'4\', `id_motor_model` = \'4\', `name` = \'Yamaha All New NMAX 155 ABS\', `uri_path` = \'yamaha-all-new-nmax-155-abs\', `teaser` = \'Yamaha N Max ABS merupakan skuter matik terlengkap yang sempurna untuk Anda\', `id_status_publish` = \'2\', `tahun_pembuatan` = \'2020\', `tipe_mesin` = \'Liquid cooled 4-stroke, SOHC\', `jumlah_silinder` = \'1\', `kapasitas_mesin` = \'155\', `torsi_maksimum` = \'14\', `sistem_starter` = \'Electric\', `aki` = \'YTZ7V\', `bahan_bakar` = \'Bensin\', `kapasitas_oli_mesin` = \'1\', `kapasitas_tanki_bensin` = \'6.6\', `ban_depan` = \'110/70 - 13 Inch - Tubeless\', `ban_belakang` = \'Bensin\', `rem_depan` = \'Single Disk Brake (ABS)\', `rem_belakang` = \'Single Disk Brake (ABS)\', `page_content` = \'<p>All New Yamaha N Max  merupakan skuter matik terlengkap yang sempurna untuk Anda.</p>\\n\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-09 19:59:03\'\nWHERE `id` = \'1\';\n'),
(48, 1, '2020-07-09 19:59:25', 'Update Motor', '::1', 'UPDATE `motor` SET `id_motor_merek` = \'1\', `id_motor_jenis` = \'1\', `id_motor_tipe` = \'4\', `id_motor_model` = \'4\', `name` = \'Yamaha All New NMAX 155 ABS\', `uri_path` = \'yamaha-all-new-nmax-155-abs\', `teaser` = \'Yamaha N Max ABS merupakan skuter matik terlengkap yang sempurna untuk Anda\', `id_status_publish` = \'2\', `tahun_pembuatan` = \'2020\', `tipe_mesin` = \'Liquid cooled 4-stroke, SOHC\', `jumlah_silinder` = \'1\', `kapasitas_mesin` = \'155\', `torsi_maksimum` = \'14\', `sistem_starter` = \'Electric\', `aki` = \'YTZ7V\', `bahan_bakar` = \'Bensin\', `kapasitas_oli_mesin` = \'1\', `kapasitas_tanki_bensin` = \'6.6\', `ban_depan` = \'110/70 - 13 Inch - Tubeless\', `ban_belakang` = \'Bensin\', `rem_depan` = \'Single Disk Brake (ABS)\', `rem_belakang` = \'Single Disk Brake (ABS)\', `page_content` = \'<p>All New Yamaha N Max  merupakan skuter matik terlengkap yang sempurna untuk Anda.</p>\\n\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-09 19:59:25\'\nWHERE `id` = \'1\';\n'),
(49, 1, '2020-07-09 20:04:13', 'Update Motor', '::1', 'UPDATE `motor` SET `id_motor_merek` = \'2\', `id_motor_jenis` = \'1\', `id_motor_tipe` = \'6\', `id_motor_model` = \'1\', `name` = \'Honda Vario 125\', `uri_path` = \'honda-vario-125\', `teaser` = \'Didesain dengan bodi yang ramping &amp; stripping bergaya sporty, jadikan Honda Vario 125 lebih lincah untuk berbagai aksi\', `id_status_publish` = \'2\', `tahun_pembuatan` = \'2020\', `tipe_mesin` = \'4-Langkah, SOHC, eSP, Pendinginan Cairan\', `jumlah_silinder` = \'1\', `kapasitas_mesin` = \'125\', `torsi_maksimum` = \'10.8\', `sistem_starter` = \'Electric\', `aki` = \'MF 12V-5 Ah\', `bahan_bakar` = \'Bensin\', `kapasitas_oli_mesin` = \'0.8\', `kapasitas_tanki_bensin` = \'5.5\', `ban_depan` = \'80/90 - 14M/C\', `ban_belakang` = \'90/90 - 14M/C\', `rem_depan` = \'Single Disk Brake\', `rem_belakang` = \'Tromol\', `page_content` = \'<p><span style=\\\"color:rgb(77,81,86);font-family:arial, sans-serif;font-size:14px;\\\">Didesain dengan bodi yang ramping &amp; stripping bergaya sporty, jadikan </span><span style=\\\"font-weight:bold;color:rgb(95,99,104);font-family:arial, sans-serif;font-size:14px;\\\">Honda Vario</span><span style=\\\"color:rgb(77,81,86);font-family:arial, sans-serif;font-size:14px;\\\"> 125 lebih lincah untuk berbagai aksi</span></p>\\n\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-09 20:04:13\'\nWHERE `id` = \'2\';\n'),
(50, 1, '2020-07-09 20:05:06', 'Update Motor', '::1', 'UPDATE `motor` SET `id_motor_merek` = \'2\', `id_motor_jenis` = \'1\', `id_motor_tipe` = \'6\', `id_motor_model` = \'1\', `name` = \'Honda Vario 125\', `uri_path` = \'honda-vario-125\', `teaser` = \'Didesain dengan bodi yang ramping &amp; stripping bergaya sporty, jadikan Honda Vario 125 lebih lincah untuk berbagai aksi\', `id_status_publish` = \'2\', `tahun_pembuatan` = \'2020\', `tipe_mesin` = \'4-Langkah, SOHC, eSP, Pendinginan Cairan\', `jumlah_silinder` = \'1\', `kapasitas_mesin` = \'125\', `torsi_maksimum` = \'11\', `sistem_starter` = \'Electric\', `aki` = \'MF 12V-5 Ah\', `bahan_bakar` = \'Bensin\', `kapasitas_oli_mesin` = \'0.8\', `kapasitas_tanki_bensin` = \'5.5\', `ban_depan` = \'80/90 - 14M/C\', `ban_belakang` = \'90/90 - 14M/C\', `rem_depan` = \'Single Disk Brake\', `rem_belakang` = \'Tromol\', `page_content` = \'<p><span style=\\\"color:rgb(77,81,86);font-family:arial, sans-serif;font-size:14px;\\\">Didesain dengan bodi yang ramping &amp; stripping bergaya sporty, jadikan </span><span style=\\\"font-weight:bold;color:rgb(95,99,104);font-family:arial, sans-serif;font-size:14px;\\\">Honda Vario</span><span style=\\\"color:rgb(77,81,86);font-family:arial, sans-serif;font-size:14px;\\\"> 125 lebih lincah untuk berbagai aksi</span></p>\\n\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-09 20:05:06\'\nWHERE `id` = \'2\';\n'),
(51, 1, '2020-07-09 20:05:37', 'Update Motor', '::1', 'UPDATE `motor` SET `id_motor_merek` = \'2\', `id_motor_jenis` = \'1\', `id_motor_tipe` = \'6\', `id_motor_model` = \'1\', `name` = \'Honda Vario 125\', `uri_path` = \'honda-vario-125\', `teaser` = \'Didesain dengan bodi yang ramping &amp; stripping bergaya sporty, jadikan Honda Vario 125 lebih lincah untuk berbagai aksi\', `id_status_publish` = \'2\', `tahun_pembuatan` = \'2020\', `tipe_mesin` = \'4-Langkah, SOHC, eSP, Pendinginan Cairan\', `jumlah_silinder` = \'1\', `kapasitas_mesin` = \'125\', `torsi_maksimum` = \'11\', `sistem_starter` = \'Electric\', `aki` = \'MF 12V-5 Ah\', `bahan_bakar` = \'Bensin\', `kapasitas_oli_mesin` = \'0.8\', `kapasitas_tanki_bensin` = \'5.5\', `ban_depan` = \'80/90 - 14M/C\', `ban_belakang` = \'80/90 - 14M/C\', `rem_depan` = \'Single Disk Brake\', `rem_belakang` = \'Tromol\', `page_content` = \'<p><span style=\\\"color:rgb(77,81,86);font-family:arial, sans-serif;font-size:14px;\\\">Didesain dengan bodi yang ramping &amp; stripping bergaya sporty, jadikan </span><span style=\\\"font-weight:bold;color:rgb(95,99,104);font-family:arial, sans-serif;font-size:14px;\\\">Honda Vario</span><span style=\\\"color:rgb(77,81,86);font-family:arial, sans-serif;font-size:14px;\\\"> 125 lebih lincah untuk berbagai aksi</span></p>\\n\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-09 20:05:37\'\nWHERE `id` = \'2\';\n'),
(52, 1, '2020-07-09 20:05:59', 'Update Motor', '::1', 'UPDATE `motor` SET `id_motor_merek` = \'2\', `id_motor_jenis` = \'1\', `id_motor_tipe` = \'6\', `id_motor_model` = \'1\', `name` = \'Honda Vario 125\', `uri_path` = \'honda-vario-125\', `teaser` = \'Didesain dengan bodi yang ramping &amp; stripping bergaya sporty, jadikan Honda Vario 125 lebih lincah untuk berbagai aksi\', `id_status_publish` = \'2\', `tahun_pembuatan` = \'2020\', `tipe_mesin` = \'4-Langkah, SOHC, eSP, Pendinginan Cairan\', `jumlah_silinder` = \'1\', `kapasitas_mesin` = \'125\', `torsi_maksimum` = \'11\', `sistem_starter` = \'Electric\', `aki` = \'MF 12V-5 Ah\', `bahan_bakar` = \'Bensin\', `kapasitas_oli_mesin` = \'0.8\', `kapasitas_tanki_bensin` = \'5.5\', `ban_depan` = \'80/90 - 14M/C\', `ban_belakang` = \'90/90 - 14M/C\', `rem_depan` = \'Single Disk Brake\', `rem_belakang` = \'Tromol\', `page_content` = \'<p><span style=\\\"color:rgb(77,81,86);font-family:arial, sans-serif;font-size:14px;\\\">Didesain dengan bodi yang ramping &amp; stripping bergaya sporty, jadikan </span><span style=\\\"font-weight:bold;color:rgb(95,99,104);font-family:arial, sans-serif;font-size:14px;\\\">Honda Vario</span><span style=\\\"color:rgb(77,81,86);font-family:arial, sans-serif;font-size:14px;\\\"> 125 lebih lincah untuk berbagai aksi</span></p>\\n\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-09 20:05:59\'\nWHERE `id` = \'2\';\n'),
(53, 1, '2020-07-09 20:06:15', 'Update Motor', '::1', 'UPDATE `motor` SET `id_motor_merek` = \'2\', `id_motor_jenis` = \'1\', `id_motor_tipe` = \'6\', `id_motor_model` = \'1\', `name` = \'Honda Vario 125\', `uri_path` = \'honda-vario-125\', `teaser` = \'Didesain dengan bodi yang ramping &amp; stripping bergaya sporty, jadikan Honda Vario 125 lebih lincah untuk berbagai aksi\', `id_status_publish` = \'2\', `tahun_pembuatan` = \'2020\', `tipe_mesin` = \'4-Langkah, SOHC, eSP, Pendinginan Cairan\', `jumlah_silinder` = \'1\', `kapasitas_mesin` = \'125\', `torsi_maksimum` = \'11\', `sistem_starter` = \'Electric\', `aki` = \'MF 12V-5 Ah\', `bahan_bakar` = \'Bensin\', `kapasitas_oli_mesin` = \'0.8\', `kapasitas_tanki_bensin` = \'5.5\', `ban_depan` = \'80/90 - 14M/C - Tubeless\', `ban_belakang` = \'90/90 - 14M/C - Tubeless\', `rem_depan` = \'Single Disk Brake\', `rem_belakang` = \'Tromol\', `page_content` = \'<p><span style=\\\"color:rgb(77,81,86);font-family:arial, sans-serif;font-size:14px;\\\">Didesain dengan bodi yang ramping &amp; stripping bergaya sporty, jadikan </span><span style=\\\"font-weight:bold;color:rgb(95,99,104);font-family:arial, sans-serif;font-size:14px;\\\">Honda Vario</span><span style=\\\"color:rgb(77,81,86);font-family:arial, sans-serif;font-size:14px;\\\"> 125 lebih lincah untuk berbagai aksi</span></p>\\n\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-09 20:06:15\'\nWHERE `id` = \'2\';\n'),
(54, 1, '2020-07-09 20:14:27', 'Insert User Management', '::1', 'INSERT INTO `auth_user` (`id_auth_user_grup`, `full_name`, `userid`, `gender`, `birthdate`, `email`, `userpass`, `phone`, `postal_code`, `address`, `create_date`, `user_id_create`) VALUES (\'3\', \'Member Belum\', \'member-belum\', \'F\', \'1996-08-23\', \'member-belum@localhost.com\', \'e10adc3949ba59abbe56e057f20f883e\', \'089670013196\', \'16913\', \'Bogor\', \'2020-07-09 20:14:27\', \'1\');\n'),
(55, 1, '2020-07-09 20:15:12', 'Insert User Management', '::1', 'INSERT INTO `auth_user` (`id_auth_user_grup`, `full_name`, `userid`, `gender`, `birthdate`, `email`, `userpass`, `phone`, `postal_code`, `address`, `create_date`, `user_id_create`) VALUES (\'4\', \'member\', \'member\', \'M\', \'1996-08-23\', \'member@localhost.com\', \'e10adc3949ba59abbe56e057f20f883e\', \'0896700131123\', \'16913\', \'bogor\', \'2020-07-09 20:15:12\', \'1\');\n'),
(56, 1, '2020-07-09 20:18:20', 'Logout', '::1', NULL),
(57, 9, '2020-07-09 20:18:24', 'Incorrect password', '::1', NULL),
(58, 9, '2020-07-09 20:18:30', '', '::1', NULL),
(59, 7, '2020-07-09 20:19:33', 'Login', '::1', NULL),
(60, 1, '2020-07-09 20:21:14', 'Incorrect password', '::1', NULL),
(61, 1, '2020-07-09 20:21:16', 'Incorrect password', '::1', NULL),
(62, 1, '2020-07-09 20:21:20', 'Login', '::1', NULL),
(63, 1, '2020-07-09 21:27:16', 'Incorrect password', '::1', NULL),
(64, 1, '2020-07-09 21:27:20', 'Login', '::1', NULL),
(65, 1, '2020-07-09 21:27:29', 'Logout', '::1', NULL),
(66, 1, '2020-07-09 21:28:15', 'Login', '::1', NULL),
(67, 1, '2020-07-09 21:28:20', 'Logout', '::1', NULL),
(68, 7, '2020-07-09 21:28:25', 'Login', '::1', NULL),
(69, 7, '2020-07-09 21:47:33', 'Logout', '::1', NULL),
(70, 1, '2020-07-09 21:47:36', 'Login', '::1', NULL),
(71, 1, '2020-07-09 21:58:37', 'Delete Pages', '::1', 'UPDATE ref_kabupaten SET is_delete=N\'1\' WHERE id_kabupaten = 516;\n'),
(72, 1, '2020-07-09 21:59:03', 'Delete Pages', '::1', 'UPDATE ref_kecamatan SET is_delete=N\'1\' WHERE id_kecamatan = 7043;\n'),
(73, 1, '2020-07-09 21:59:15', 'Delete Pages', '::1', 'UPDATE ref_kelurahan SET is_delete=N\'1\' WHERE id_kelurahan = 81865;\n'),
(74, 1, '2020-07-09 22:13:34', 'Update User Management', '::1', 'UPDATE `auth_user` SET `id_auth_user_grup` = \'4\', `full_name` = \'member\', `userid` = \'member\', `gender` = \'M\', `birthdate` = \'1996-08-23\', `email` = \'member@localhost.com\', `phone` = \'0896700131123\', `kode_ref_provinsi` = \'3200000000\', `kode_ref_kabupaten` = \'3201000000\', `kode_ref_kecamatan` = \'3201210000\', `kode_ref_kelurahan` = \'3201210006\', `postal_code` = \'16913\', `address` = \'bogor\', `user_id_modify` = \'1\', `modify_date` = \'2020-07-09 22:13:34\'\nWHERE `id_auth_user` = \'9\';\n'),
(75, 1, '2020-07-09 23:06:28', 'Login', '::1', NULL),
(76, 1, '2020-07-10 20:46:28', 'Login', '::1', NULL),
(77, 1, '2020-07-10 20:46:41', 'Logout', '::1', NULL),
(78, 1, '2020-07-10 20:48:42', 'Login', '::1', NULL),
(79, 1, '2020-07-10 20:58:10', 'Logout', '::1', NULL),
(80, 7, '2020-07-10 20:58:18', 'Login', '::1', NULL),
(81, 7, '2020-07-10 20:58:42', 'Logout', '::1', NULL),
(82, 1, '2020-07-10 20:58:45', 'Login', '::1', NULL),
(83, 1, '2020-07-10 21:01:14', 'Logout', '::1', NULL),
(84, 7, '2020-07-10 21:01:21', 'Login', '::1', NULL),
(85, NULL, '2020-07-11 22:33:19', 'Logout', '::1', NULL),
(86, 1, '2020-07-11 22:33:22', 'Login', '::1', NULL),
(87, NULL, '2020-07-11 23:20:21', 'Logout', '::1', NULL),
(88, 1, '2020-07-11 23:20:24', 'Login', '::1', NULL),
(89, 1, '2020-07-12 00:21:14', 'Update Profil Pengguna', '::1', 'UPDATE `auth_user` SET `full_name` = \'Siti Hasuna\', `userid` = \'hananasss\', `phone` = \'085782186956\', `gender` = \'F\', `birthdate` = \'1996-07-20\', `sim_expired_date` = \'2025-07-04\', `address` = \'Jl. Kalibata Selatan 2 No. 42, RT 003/04,\', `kode_ref_provinsi` = \'3100000000\', `kode_ref_kabupaten` = \'3171000000\', `kode_ref_kecamatan` = \'3171080000\', `kode_ref_kelurahan` = \'3171080001\', `postal_code` = \'12740\'\nWHERE `id_auth_user` = \'10\';\n'),
(90, NULL, '2020-07-12 01:09:05', 'change password not found email : ', '', NULL),
(91, 1, '2020-07-12 01:16:43', 'Update Password', '::1', 'UPDATE `auth_user` SET `userpass` = \'e10adc3949ba59abbe56e057f20f883e\'\nWHERE `id_auth_user` = \'10\';\n'),
(92, 1, '2020-07-12 01:18:17', 'Logout', '::1', NULL),
(93, 7, '2020-07-12 01:18:24', 'Login', '::1', NULL),
(94, 1, '2020-07-12 17:31:21', 'Login', '::1', NULL),
(95, 1, '2020-07-13 01:19:27', 'Login', '::1', NULL),
(96, 1, '2020-07-13 01:48:39', 'Logout', '::1', NULL),
(97, 7, '2020-07-13 01:48:44', 'Login', '::1', NULL),
(98, 7, '2020-07-13 02:26:51', 'Logout', '::1', NULL),
(99, 1, '2020-07-13 02:26:57', 'Login', '::1', NULL),
(100, 1, '2021-04-26 21:55:38', 'Login', '::1', NULL),
(101, 1, '2021-04-26 22:19:57', 'Insert User Category', '::1', 'INSERT INTO `ref_user_category` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Seller\', \'2\', \'2021-04-26 22:19:57\', \'1\');\n'),
(102, 1, '2021-04-26 22:20:04', 'Insert User Category', '::1', 'INSERT INTO `ref_user_category` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Buyer\', \'2\', \'2021-04-26 22:20:04\', \'1\');\n'),
(103, 1, '2021-04-26 22:20:13', 'Insert User Category', '::1', 'INSERT INTO `ref_user_category` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'VIP\', \'2\', \'2021-04-26 22:20:13\', \'1\');\n'),
(104, 1, '2021-04-26 22:20:21', 'Insert User Category', '::1', 'INSERT INTO `ref_user_category` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Sponsor\', \'2\', \'2021-04-26 22:20:21\', \'1\');\n'),
(105, 1, '2021-04-26 22:20:43', 'Insert User Category', '::1', 'INSERT INTO `ref_user_category` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Observer\', \'2\', \'2021-04-26 22:20:43\', \'1\');\n'),
(106, 1, '2021-04-26 22:20:53', 'Insert User Category', '::1', 'INSERT INTO `ref_user_category` (`name`, `id_status_publish`, `create_date`, `user_create_id`) VALUES (\'Media\', \'2\', \'2021-04-26 22:20:53\', \'1\');\n'),
(107, 1, '2021-04-26 22:27:17', 'Update Country', '::1', 'UPDATE `ref_country` SET `name` = \'Zimbabwe\', `code` = \'ZW\', `teaser` = \'\', `id_status_publish` = \'2\', `user_modify_id` = \'1\', `date_modify` = \'2021-04-26 22:27:17\'\nWHERE `id` = \'239\';\n'),
(108, 1, '2021-04-26 22:51:43', 'Delete User Management', '::1', 'UPDATE `auth_user` SET `is_delete` = 1, `user_id_modify` = \'1\', `modify_date` = \'2021-04-26 22:51:43\'\nWHERE `id_auth_user` = \'8\';\n'),
(109, 1, '2021-04-26 22:56:03', 'Insert User Management', '::1', 'INSERT INTO `auth_user` (`id_auth_user_grup`, `title`, `full_name`, `email`, `phone`, `city`, `id_ref_country`, `company`, `job_title`, `id_ref_user_category`, `userpass`, `create_date`, `user_id_create`) VALUES (\'4\', \'mr\', \'Agung Trilaksono Suwarto Putra\', \'agungtrilaksonosp@gmail.com\', \'089670013196\', \'Kabupaten Bogor\', \'100\', \'PT Jemari\', \'Programmer\', \'3\', \'d55afc14846962a8a1700488fe28e185\', \'2021-04-26 22:56:03\', \'1\');\n'),
(110, 1, '2021-04-26 22:57:52', 'Update User Management', '::1', 'UPDATE `auth_user` SET `id_auth_user_grup` = \'4\', `title` = \'mr\', `full_name` = \'Agung Trilaksono Suwarto Putra\', `email` = \'agungtrilaksonosp@gmail.com\', `phone` = \'089670013196\', `city` = \'Kabupaten Bogor\', `id_ref_country` = \'100\', `company` = \'PT Jemari\', `job_title` = \'Programmer\', `id_ref_user_category` = \'3\', `userid` = \'agungtrilaksonosp@gmail.com\', `user_id_modify` = \'1\', `modify_date` = \'2021-04-26 22:57:52\'\nWHERE `id_auth_user` = \'11\';\n'),
(111, 1, '2021-04-26 23:03:22', 'Update User Management', '::1', 'UPDATE `auth_user` SET `id_auth_user_grup` = \'4\', `title` = \'mr\', `full_name` = \'Agung Trilaksono Suwarto Putra\', `email` = \'agungtrilaksonosp@gmail.com\', `phone` = \'089670013196\', `city` = \'Kabupaten Bogor\', `id_ref_country` = \'100\', `company` = \'PT Jemari\', `job_title` = \'Programmer\', `id_ref_user_category` = \'3\', `userid` = \'agungtrilaksonosp@gmail.com\', `user_id_modify` = \'1\', `modify_date` = \'2021-04-26 23:03:22\'\nWHERE `id_auth_user` = \'11\';\n');

-- --------------------------------------------------------

--
-- Struktur dari tabel `auth_pages`
--

CREATE TABLE `auth_pages` (
  `id_auth_pages` int(11) NOT NULL,
  `id_auth_user_grup` int(11) NOT NULL,
  `id_ref_menu_admin` int(11) NOT NULL,
  `c` smallint(6) NOT NULL,
  `r` smallint(6) NOT NULL,
  `u` smallint(6) NOT NULL,
  `d` smallint(6) NOT NULL,
  `create_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `auth_pages`
--

INSERT INTO `auth_pages` (`id_auth_pages`, `id_auth_user_grup`, `id_ref_menu_admin`, `c`, `r`, `u`, `d`, `create_date`) VALUES
(2, 1, 15, 1, 1, 1, 1, NULL),
(3, 1, 18, 0, 1, 0, 0, NULL),
(4, 1, 28, 1, 1, 1, 1, NULL),
(6, 1, 7, 1, 1, 1, 1, NULL),
(7, 2, 7, 1, 1, 1, 1, NULL),
(8, 3, 7, 1, 1, 1, 1, NULL),
(9, 4, 7, 1, 1, 1, 1, NULL),
(10, 1, 8, 1, 1, 1, 1, NULL),
(11, 2, 8, 1, 1, 1, 1, NULL),
(12, 3, 8, 1, 1, 1, 1, NULL),
(13, 4, 8, 1, 1, 1, 1, NULL),
(14, 1, 9, 1, 1, 1, 1, NULL),
(15, 2, 9, 1, 1, 1, 1, NULL),
(16, 3, 9, 1, 1, 1, 1, NULL),
(17, 4, 9, 1, 1, 1, 1, NULL),
(38, 1, 2, 1, 1, 1, 1, NULL),
(39, 2, 2, 0, 0, 0, 0, NULL),
(40, 3, 2, 0, 0, 0, 0, NULL),
(41, 4, 2, 0, 0, 0, 0, NULL),
(46, 1, 16, 1, 1, 1, 1, NULL),
(47, 2, 16, 0, 0, 0, 0, NULL),
(48, 3, 16, 0, 0, 0, 0, NULL),
(49, 4, 16, 0, 0, 0, 0, NULL),
(50, 1, 44, 1, 1, 1, 1, NULL),
(51, 2, 44, 0, 0, 0, 0, NULL),
(52, 3, 44, 1, 1, 1, 1, NULL),
(53, 4, 44, 0, 0, 0, 0, NULL),
(70, 1, 43, 1, 1, 1, 1, NULL),
(71, 2, 43, 1, 1, 1, 1, NULL),
(72, 3, 43, 0, 0, 0, 0, NULL),
(73, 4, 43, 0, 0, 0, 0, NULL),
(79, 2, 18, 0, 0, 0, 0, NULL),
(81, 2, 15, 0, 0, 0, 0, NULL),
(83, 3, 18, 0, 0, 0, 0, NULL),
(85, 3, 15, 0, 0, 0, 0, NULL),
(202, 1, 39, 1, 1, 1, 1, NULL),
(203, 2, 39, 0, 0, 0, 0, NULL),
(204, 3, 39, 0, 0, 0, 0, NULL),
(205, 4, 39, 0, 0, 0, 0, NULL),
(206, 1, 1, 1, 1, 1, 1, NULL),
(207, 2, 1, 1, 1, 1, 1, NULL),
(208, 3, 1, 1, 1, 1, 1, NULL),
(209, 4, 1, 0, 1, 0, 0, NULL),
(282, 1, 71, 0, 0, 0, 0, NULL),
(283, 2, 71, 1, 1, 1, 1, NULL),
(284, 3, 71, 0, 0, 0, 0, NULL),
(285, 4, 71, 0, 0, 0, 0, NULL),
(358, 1, 49, 1, 1, 1, 1, NULL),
(359, 2, 49, 1, 1, 1, 1, NULL),
(360, 3, 49, 0, 0, 0, 0, NULL),
(361, 4, 49, 0, 0, 0, 0, NULL),
(362, 1, 50, 1, 1, 1, 1, NULL),
(363, 2, 50, 1, 1, 1, 1, NULL),
(364, 3, 50, 0, 0, 0, 0, NULL),
(365, 4, 50, 0, 0, 0, 0, NULL),
(366, 1, 51, 1, 1, 1, 1, NULL),
(367, 2, 51, 1, 1, 1, 1, NULL),
(368, 3, 51, 0, 0, 0, 0, NULL),
(369, 4, 51, 0, 0, 0, 0, NULL),
(370, 1, 52, 1, 1, 1, 1, NULL),
(371, 2, 52, 1, 1, 1, 1, NULL),
(372, 3, 52, 0, 0, 0, 0, NULL),
(373, 4, 52, 0, 0, 0, 0, NULL),
(382, 1, 42, 1, 1, 1, 1, NULL),
(383, 2, 42, 1, 1, 1, 1, NULL),
(384, 3, 42, 0, 0, 0, 0, NULL),
(385, 4, 42, 0, 0, 0, 0, NULL),
(386, 1, 14, 1, 1, 1, 1, NULL),
(387, 2, 14, 1, 1, 1, 1, NULL),
(388, 3, 14, 0, 0, 0, 0, NULL),
(389, 4, 14, 0, 0, 0, 0, NULL),
(458, 1, 32, 1, 1, 1, 1, NULL),
(459, 2, 32, 0, 0, 0, 0, NULL),
(460, 3, 32, 1, 1, 1, 1, NULL),
(461, 4, 32, 0, 0, 0, 0, NULL),
(462, 1, 72, 0, 0, 0, 0, NULL),
(463, 2, 72, 1, 1, 1, 1, NULL),
(464, 3, 72, 0, 0, 0, 0, NULL),
(465, 4, 72, 0, 0, 0, 0, NULL),
(474, 1, 63, 1, 1, 1, 1, NULL),
(475, 2, 63, 1, 1, 1, 1, NULL),
(476, 3, 63, 0, 0, 0, 0, NULL),
(477, 4, 63, 0, 0, 0, 0, NULL),
(506, 1, 92, 1, 1, 1, 1, NULL),
(507, 2, 92, 1, 1, 1, 1, NULL),
(508, 3, 92, 0, 0, 0, 0, NULL),
(509, 4, 92, 0, 0, 0, 0, NULL),
(514, 1, 93, 1, 1, 1, 1, NULL),
(515, 2, 93, 1, 1, 1, 1, NULL),
(516, 3, 93, 0, 0, 0, 0, NULL),
(517, 4, 93, 0, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `auth_user`
--

CREATE TABLE `auth_user` (
  `id_auth_user` int(11) NOT NULL,
  `id_auth_user_grup` int(11) NOT NULL,
  `userid` varchar(64) NOT NULL DEFAULT '',
  `userpass` varchar(255) NOT NULL DEFAULT '',
  `full_name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(64) NOT NULL DEFAULT '',
  `phone` varchar(64) DEFAULT '',
  `create_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `user_id_create` int(11) DEFAULT NULL,
  `user_id_modify` int(11) DEFAULT NULL,
  `is_delete` smallint(6) NOT NULL DEFAULT '0',
  `title` enum('mr','mrs','ms') DEFAULT NULL,
  `id_ref_country` int(11) NOT NULL,
  `id_ref_user_category` int(11) DEFAULT NULL,
  `city` varchar(150) NOT NULL,
  `company` varchar(150) NOT NULL,
  `job_title` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `auth_user`
--

INSERT INTO `auth_user` (`id_auth_user`, `id_auth_user_grup`, `userid`, `userpass`, `full_name`, `email`, `phone`, `create_date`, `modify_date`, `user_id_create`, `user_id_modify`, `is_delete`, `title`, `id_ref_country`, `id_ref_user_category`, `city`, `company`, `job_title`) VALUES
(1, 1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin', 'admin@localhost.com', '089670013196', '2013-08-11 09:10:08', '2019-04-17 14:20:40', NULL, 1, 0, '', 0, NULL, '', '', ''),
(7, 2, 'staf', '7b8a17c3f48d4453fde0fd74b4fa9212', 'staf', 'staf@localhost.com', '089670013196', '2019-01-21 14:45:49', '2019-04-17 14:20:13', 1, 1, 0, '', 0, NULL, '', '', ''),
(8, 3, 'member-belum', 'e10adc3949ba59abbe56e057f20f883e', 'Member Belum', 'member-belum@localhost.com', '089670013196', '2020-07-09 20:14:27', '2021-04-26 22:51:43', 1, 1, 1, '', 0, NULL, '', '', ''),
(9, 4, 'member', 'e10adc3949ba59abbe56e057f20f883e', 'member', 'member@localhost.com', '0896700131123', '2020-07-09 20:15:12', '2020-07-09 22:13:34', 1, 1, 0, '', 2147483647, NULL, '3201000000', '3201210000', '3201210006'),
(10, 3, 'hananasss', 'e10adc3949ba59abbe56e057f20f883e', 'Siti Hasuna', 'sh.hanaaa@gmail.com', '085782186956', '2020-07-11 22:47:34', NULL, NULL, NULL, 0, '', 2147483647, NULL, '3171000000', '3171080000', '3171080001'),
(11, 4, 'agungtrilaksonosp@gmail.com', 'd55afc14846962a8a1700488fe28e185', 'Agung Trilaksono Suwarto Putra', 'agungtrilaksonosp@gmail.com', '089670013196', '2021-04-26 22:56:03', '2021-04-26 23:03:22', 1, 1, 0, 'mr', 100, 3, 'Kabupaten Bogor', 'PT Jemari', 'Programmer');

-- --------------------------------------------------------

--
-- Struktur dari tabel `auth_user_grup`
--

CREATE TABLE `auth_user_grup` (
  `id_auth_user_grup` int(11) NOT NULL,
  `grup` varchar(255) DEFAULT '',
  `description` text,
  `create_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `user_id_create` int(11) NOT NULL,
  `user_id_modify` int(11) DEFAULT NULL,
  `is_delete` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `auth_user_grup`
--

INSERT INTO `auth_user_grup` (`id_auth_user_grup`, `grup`, `description`, `create_date`, `modify_date`, `user_id_create`, `user_id_modify`, `is_delete`) VALUES
(1, 'Admin', 'Admin untuk mengelola semua konten website', '2018-11-26 18:07:19', NULL, 1, NULL, 0),
(2, 'Staff', 'Staff untuk mengelola sebagian konten website', NULL, NULL, 1, NULL, 0),
(3, 'Member (Not Confirmed)', 'Member yang belum melakukan verifikasi data diri', NULL, NULL, 1, NULL, 0),
(4, 'Member (Confirmed)', 'Member yang sudah melakukan verifikasi data diri', NULL, NULL, 1, NULL, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('89716ac808313c29589075ed2bc318e90cc13e0f', '::1', 1619449103, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631393434393130333b41444d5f534553537c613a383a7b733a31303a2261646d696e5f6e616d65223b733a353a2241646d696e223b733a32343a2261646d696e5f69645f617574685f757365725f67726f7570223b733a313a2231223b733a323a226964223b733a313a2231223b733a31383a2261646d696e5f69645f617574685f75736572223b733a313a2231223b733a31323a2261646d696e5f69645f726566223b4e3b733a31303a2261646d696e5f74797065223b4e3b733a31353a2270726f66696c5f6d697472615f6964223b4e3b733a32363a2261646d696e5f69645f7265665f757365725f63617465676f7279223b4e3b7d),
('f0c600c039baf08a00800d0ff10d595fcef30188', '::1', 1619449550, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631393434393535303b41444d5f534553537c613a383a7b733a31303a2261646d696e5f6e616d65223b733a353a2241646d696e223b733a32343a2261646d696e5f69645f617574685f757365725f67726f7570223b733a313a2231223b733a323a226964223b733a313a2231223b733a31383a2261646d696e5f69645f617574685f75736572223b733a313a2231223b733a31323a2261646d696e5f69645f726566223b4e3b733a31303a2261646d696e5f74797065223b4e3b733a31353a2270726f66696c5f6d697472615f6964223b4e3b733a32363a2261646d696e5f69645f7265665f757365725f63617465676f7279223b4e3b7d),
('95f6d31e3778637a5b15b9a36e02ddd1923a362c', '::1', 1619449894, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631393434393839343b41444d5f534553537c613a383a7b733a31303a2261646d696e5f6e616d65223b733a353a2241646d696e223b733a32343a2261646d696e5f69645f617574685f757365725f67726f7570223b733a313a2231223b733a323a226964223b733a313a2231223b733a31383a2261646d696e5f69645f617574685f75736572223b733a313a2231223b733a31323a2261646d696e5f69645f726566223b4e3b733a31303a2261646d696e5f74797065223b4e3b733a31353a2270726f66696c5f6d697472615f6964223b4e3b733a32363a2261646d696e5f69645f7265665f757365725f63617465676f7279223b4e3b7d),
('ec458943b9071577d9833d51a3afedc58e34fa3f', '::1', 1619450210, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631393435303231303b41444d5f534553537c613a383a7b733a31303a2261646d696e5f6e616d65223b733a353a2241646d696e223b733a32343a2261646d696e5f69645f617574685f757365725f67726f7570223b733a313a2231223b733a323a226964223b733a313a2231223b733a31383a2261646d696e5f69645f617574685f75736572223b733a313a2231223b733a31323a2261646d696e5f69645f726566223b4e3b733a31303a2261646d696e5f74797065223b4e3b733a31353a2270726f66696c5f6d697472615f6964223b4e3b733a32363a2261646d696e5f69645f7265665f757365725f63617465676f7279223b4e3b7d),
('8d1b2a7a0cde5ae75c0968804615112d7b14ad5c', '::1', 1619450697, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631393435303639373b41444d5f534553537c613a383a7b733a31303a2261646d696e5f6e616d65223b733a353a2241646d696e223b733a32343a2261646d696e5f69645f617574685f757365725f67726f7570223b733a313a2231223b733a323a226964223b733a313a2231223b733a31383a2261646d696e5f69645f617574685f75736572223b733a313a2231223b733a31323a2261646d696e5f69645f726566223b4e3b733a31303a2261646d696e5f74797065223b4e3b733a31353a2270726f66696c5f6d697472615f6964223b4e3b733a32363a2261646d696e5f69645f7265665f757365725f63617465676f7279223b4e3b7d),
('aee9bb66734fc9aab0e814aaea5817908b3794a6', '::1', 1619451010, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631393435313031303b41444d5f534553537c613a383a7b733a31303a2261646d696e5f6e616d65223b733a353a2241646d696e223b733a32343a2261646d696e5f69645f617574685f757365725f67726f7570223b733a313a2231223b733a323a226964223b733a313a2231223b733a31383a2261646d696e5f69645f617574685f75736572223b733a313a2231223b733a31323a2261646d696e5f69645f726566223b4e3b733a31303a2261646d696e5f74797065223b4e3b733a31353a2270726f66696c5f6d697472615f6964223b4e3b733a32363a2261646d696e5f69645f7265665f757365725f63617465676f7279223b4e3b7d),
('758b91e7293ce4188883b5bd98441f77ed8c98ed', '::1', 1619451407, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631393435313430373b41444d5f534553537c613a383a7b733a31303a2261646d696e5f6e616d65223b733a353a2241646d696e223b733a32343a2261646d696e5f69645f617574685f757365725f67726f7570223b733a313a2231223b733a323a226964223b733a313a2231223b733a31383a2261646d696e5f69645f617574685f75736572223b733a313a2231223b733a31323a2261646d696e5f69645f726566223b4e3b733a31303a2261646d696e5f74797065223b4e3b733a31353a2270726f66696c5f6d697472615f6964223b4e3b733a32363a2261646d696e5f69645f7265665f757365725f63617465676f7279223b4e3b7d),
('3e9086edbf7c6d1608204af43b80e015e02470af', '::1', 1619451716, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631393435313731363b41444d5f534553537c613a383a7b733a31303a2261646d696e5f6e616d65223b733a353a2241646d696e223b733a32343a2261646d696e5f69645f617574685f757365725f67726f7570223b733a313a2231223b733a323a226964223b733a313a2231223b733a31383a2261646d696e5f69645f617574685f75736572223b733a313a2231223b733a31323a2261646d696e5f69645f726566223b4e3b733a31303a2261646d696e5f74797065223b4e3b733a31353a2270726f66696c5f6d697472615f6964223b4e3b733a32363a2261646d696e5f69645f7265665f757365725f63617465676f7279223b4e3b7d),
('3d4e05a759710a58a04f1e0407704ef987b87431', '::1', 1619452024, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631393435323032343b41444d5f534553537c613a383a7b733a31303a2261646d696e5f6e616d65223b733a353a2241646d696e223b733a32343a2261646d696e5f69645f617574685f757365725f67726f7570223b733a313a2231223b733a323a226964223b733a313a2231223b733a31383a2261646d696e5f69645f617574685f75736572223b733a313a2231223b733a31323a2261646d696e5f69645f726566223b4e3b733a31303a2261646d696e5f74797065223b4e3b733a31353a2270726f66696c5f6d697472615f6964223b4e3b733a32363a2261646d696e5f69645f7265665f757365725f63617465676f7279223b4e3b7d),
('805ddab453d1b593c4948d1317d92476826d3e66', '::1', 1619452376, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631393435323337363b41444d5f534553537c613a383a7b733a31303a2261646d696e5f6e616d65223b733a353a2241646d696e223b733a32343a2261646d696e5f69645f617574685f757365725f67726f7570223b733a313a2231223b733a323a226964223b733a313a2231223b733a31383a2261646d696e5f69645f617574685f75736572223b733a313a2231223b733a31323a2261646d696e5f69645f726566223b4e3b733a31303a2261646d696e5f74797065223b4e3b733a31353a2270726f66696c5f6d697472615f6964223b4e3b733a32363a2261646d696e5f69645f7265665f757365725f63617465676f7279223b4e3b7d),
('b8c0f0047de8196dd4b513f3b550aaa892e2440c', '::1', 1619452836, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631393435323833363b41444d5f534553537c613a383a7b733a31303a2261646d696e5f6e616d65223b733a353a2241646d696e223b733a32343a2261646d696e5f69645f617574685f757365725f67726f7570223b733a313a2231223b733a323a226964223b733a313a2231223b733a31383a2261646d696e5f69645f617574685f75736572223b733a313a2231223b733a31323a2261646d696e5f69645f726566223b4e3b733a31303a2261646d696e5f74797065223b4e3b733a31353a2270726f66696c5f6d697472615f6964223b4e3b733a32363a2261646d696e5f69645f7265665f757365725f63617465676f7279223b4e3b7d6d6573736167657c733a31343a225570646174652053756363657373223b),
('c0faef2352c5437d9888905318d9e42f3d4f9a00', '::1', 1619453026, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631393435323833363b41444d5f534553537c613a383a7b733a31303a2261646d696e5f6e616d65223b733a353a2241646d696e223b733a32343a2261646d696e5f69645f617574685f757365725f67726f7570223b733a313a2231223b733a323a226964223b733a313a2231223b733a31383a2261646d696e5f69645f617574685f75736572223b733a313a2231223b733a31323a2261646d696e5f69645f726566223b4e3b733a31303a2261646d696e5f74797065223b4e3b733a31353a2270726f66696c5f6d697472615f6964223b4e3b733a32363a2261646d696e5f69645f7265665f757365725f63617465676f7279223b4e3b7d6d6573736167657c733a31343a225570646174652053756363657373223b);

-- --------------------------------------------------------

--
-- Struktur dari tabel `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `handphone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `modify_date` datetime DEFAULT NULL,
  `is_delete` smallint(2) NOT NULL DEFAULT '0',
  `user_id_create` int(11) DEFAULT NULL,
  `user_id_modify` int(11) DEFAULT NULL,
  `subject` varchar(225) DEFAULT NULL,
  `message` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `contact_us`
--

INSERT INTO `contact_us` (`id`, `fullname`, `handphone`, `email`, `create_date`, `modify_date`, `is_delete`, `user_id_create`, `user_id_modify`, `subject`, `message`) VALUES
(2, 'Agung Trilaksono Suwarto Putra', '+6289670013196', 'agungtrilaksonosp@gmail.com', '2020-07-07 20:23:46', NULL, 0, NULL, NULL, 'cari hana', 'cari hana'),
(3, 'Siti Hasuna', '085782186956', 'sh.hanaaa@gmail.com', '2020-07-13 01:19:05', NULL, 0, NULL, NULL, 'Test', 'test');

-- --------------------------------------------------------

--
-- Struktur dari tabel `contact_us_reply`
--

CREATE TABLE `contact_us_reply` (
  `id` int(11) NOT NULL,
  `id_contact_us` int(11) NOT NULL DEFAULT '0',
  `message` longtext NOT NULL,
  `title` varchar(255) NOT NULL,
  `user_id_create` int(11) NOT NULL DEFAULT '0',
  `user_id_modify` int(11) DEFAULT '0',
  `create_date` datetime NOT NULL DEFAULT '1753-01-01 00:00:00',
  `modify_date` datetime DEFAULT '1753-01-01 00:00:00',
  `is_delete` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `contact_us_reply`
--

INSERT INTO `contact_us_reply` (`id`, `id_contact_us`, `message`, `title`, `user_id_create`, `user_id_modify`, `create_date`, `modify_date`, `is_delete`) VALUES
(1, 2, 'halo disini nih', '', 1, 0, '2020-07-07 20:31:10', '1753-01-01 00:00:00', 0),
(2, 3, 'test', '', 1, 0, '2020-07-13 01:19:53', '1753-01-01 00:00:00', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `email_config`
--

CREATE TABLE `email_config` (
  `smtp_host` varchar(255) NOT NULL,
  `port` varchar(10) NOT NULL,
  `is_ssl` varchar(1) NOT NULL,
  `smtp_user_alias` varchar(64) DEFAULT NULL,
  `smtp_user` varchar(128) NOT NULL,
  `smtp_pass` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL,
  `sendmail_path` varchar(255) DEFAULT NULL,
  `smtp_user_from` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Topik untuk mengolah konfigurasi email';

--
-- Dumping data untuk tabel `email_config`
--

INSERT INTO `email_config` (`smtp_host`, `port`, `is_ssl`, `smtp_user_alias`, `smtp_user`, `smtp_pass`, `type`, `sendmail_path`, `smtp_user_from`) VALUES
('ssl://smtp.googlemail.com', '465', 'n', '', '', '', 'smtp', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `email_tmp`
--

CREATE TABLE `email_tmp` (
  `id` int(11) NOT NULL COMMENT 'identitas email tmp',
  `template_name` varchar(200) NOT NULL DEFAULT 'NULL' COMMENT 'nama template',
  `subject` varchar(200) NOT NULL DEFAULT 'NULL' COMMENT 'judul ',
  `page_content` longtext COMMENT 'isi konten',
  `modify_date` date DEFAULT NULL COMMENT 'Tanggal terakhir dimodifikasi',
  `user_id_create` int(11) NOT NULL DEFAULT '0' COMMENT 'ID user yang membuat data',
  `id_lang` int(11) DEFAULT NULL COMMENT 'identitas bahasa yang digunakan',
  `id_parent_lang` int(11) DEFAULT NULL COMMENT 'identitas bahasa ibu yang digunakan',
  `user_id_modify` int(11) DEFAULT '0' COMMENT 'ID user yang memodifikasi data',
  `id_status_publish` int(11) NOT NULL DEFAULT '1' COMMENT 'status publikasi',
  `is_delete` smallint(6) NOT NULL DEFAULT '0' COMMENT 'apakah data sudah didelete',
  `id_ref_email_category` int(11) NOT NULL DEFAULT '1' COMMENT 'identitas referensi kategori email',
  `create_date` datetime DEFAULT '1753-01-01 00:00:00' COMMENT 'tanggal dibuatnya data'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table untuk mengolah template email';

-- --------------------------------------------------------

--
-- Struktur dari tabel `file_manager`
--

CREATE TABLE `file_manager` (
  `id` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `is_public` smallint(6) NOT NULL DEFAULT '0',
  `is_delete` smallint(6) NOT NULL DEFAULT '0',
  `user_id_create` int(11) NOT NULL,
  `user_id_modify` int(11) DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `create_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `file_manager`
--

INSERT INTO `file_manager` (`id`, `name`, `is_public`, `is_delete`, `user_id_create`, `user_id_modify`, `modify_date`, `create_date`) VALUES
(1, 'aa8c2f56f98b13329697b1a4aa8b0a87_nmax.jpg', 0, 0, 1, NULL, NULL, '2020-07-09 19:06:35'),
(2, '97433f6adae3a0e6562faf755095a757_ff7abef5d63992eb626cf7eac5b18794.jpeg', 0, 0, 1, NULL, NULL, '2020-07-09 19:27:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `help`
--

CREATE TABLE `help` (
  `id` int(11) NOT NULL,
  `id_help_category` int(11) NOT NULL,
  `ref_id_tags` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `meta_keywords` varchar(200) NOT NULL,
  `meta_description` varchar(160) NOT NULL,
  `seo_title` varchar(60) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `user_modify_id` int(11) NOT NULL,
  `user_create_id` int(11) NOT NULL,
  `publish_date` date NOT NULL,
  `id_status_publish` int(11) NOT NULL,
  `date_create` datetime NOT NULL,
  `date_modify` datetime NOT NULL,
  `is_delete` int(11) NOT NULL,
  `description` text,
  `uri_path` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `help_category`
--

CREATE TABLE `help_category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `teaser` varchar(255) NOT NULL,
  `user_modify_id` int(11) NOT NULL,
  `user_create_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `date_modify` datetime NOT NULL,
  `id_status_publish` int(11) NOT NULL,
  `is_delete` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `help_comment`
--

CREATE TABLE `help_comment` (
  `id` int(11) NOT NULL,
  `id_help` int(11) NOT NULL,
  `opt` smallint(2) NOT NULL COMMENT '1 = yes & 2 = no',
  `message` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `login_transaction`
--

CREATE TABLE `login_transaction` (
  `id` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `ip_address` longtext,
  `user_agent` longtext,
  `create_date` datetime DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `lock_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `login_transaction`
--

INSERT INTO `login_transaction` (`id`, `userid`, `ip_address`, `user_agent`, `create_date`, `last_activity`, `is_active`, `lock_date`) VALUES
(1, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36', '2019-06-04 13:38:17', '2019-06-06 08:59:32', 2, '2019-06-06 08:59:32'),
(2, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36', '2019-06-06 08:59:32', '2019-06-06 09:48:06', 2, '2019-06-06 09:48:06'),
(3, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36', '2019-06-06 09:48:11', '2019-06-08 23:02:10', 2, '2019-06-08 23:02:10'),
(4, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.80 Safari/537.36', '2019-06-08 23:01:01', '2019-06-08 23:01:22', 2, '2019-06-08 23:01:21'),
(5, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.80 Safari/537.36', '2019-06-08 23:01:28', '2019-06-08 23:02:10', 2, '2019-06-08 23:02:10'),
(6, 7, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.80 Safari/537.36', '2019-06-08 23:02:14', '2019-06-08 23:26:25', 2, '2019-06-08 23:26:25'),
(7, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.80 Safari/537.36', '2019-06-08 23:26:30', '2019-06-09 11:03:22', 2, '2019-06-09 11:03:22'),
(8, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.80 Safari/537.36', '2019-06-09 11:03:22', '2019-06-29 16:32:35', 2, '2019-06-29 16:32:35'),
(9, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.90 Safari/537.36', '2019-06-16 10:22:12', '2019-06-16 16:52:55', 2, '2019-06-16 16:52:55'),
(10, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.90 Safari/537.36', '2019-06-16 10:22:24', '2019-06-29 16:32:35', 2, '2019-06-29 16:32:35'),
(11, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.90 Safari/537.36', '2019-06-16 16:52:55', '2019-06-22 08:03:52', 2, '2019-06-22 08:03:52'),
(12, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36', '2019-06-19 21:41:29', '2019-06-22 08:03:52', 2, '2019-06-22 08:03:52'),
(13, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36', '2019-06-22 08:03:52', '2019-06-22 23:12:11', 2, '2019-06-22 23:12:11'),
(14, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36', '2019-06-22 23:12:11', '2019-06-29 10:36:14', 2, '2019-06-29 10:36:14'),
(15, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36', '2019-06-29 10:36:14', '2019-06-29 11:36:17', 2, '2019-06-29 11:36:17'),
(16, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36', '2019-06-29 11:36:17', '2019-06-29 12:49:39', 2, '2019-06-29 12:49:39'),
(17, 7, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36', '2019-06-29 12:49:56', '2020-07-09 21:28:25', 2, '2020-07-09 21:28:25'),
(18, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36', '2019-06-29 12:50:11', '2019-06-29 12:51:21', 2, '2019-06-29 12:51:21'),
(19, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36', '2019-06-29 12:51:28', '2019-06-29 16:32:35', 2, '2019-06-29 16:32:35'),
(20, 1, '192.168.1.10', 'Mozilla/5.0 (Linux; Android 9; Redmi Note 7 Build/PKQ1.180904.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/75.0.3770.101 Mobile Safari/537.36', '2019-06-29 12:55:53', '2019-06-29 12:55:53', 1, NULL),
(21, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36', '2019-06-29 16:30:02', '2019-06-29 16:30:28', 2, '2019-06-29 16:30:28'),
(22, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36', '2019-06-29 16:32:35', '2019-06-29 16:32:35', 1, NULL),
(23, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36', '2019-06-30 17:19:07', '2020-07-07 20:00:41', 2, '2020-07-07 20:00:41'),
(24, 1, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36', '2020-07-07 19:04:14', '2020-07-07 20:00:41', 2, '2020-07-07 20:00:41'),
(25, 1, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36', '2020-07-07 20:01:30', '2020-07-07 20:01:36', 2, '2020-07-07 20:01:36'),
(26, 1, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36', '2020-07-07 20:04:10', '2020-07-07 20:04:26', 2, '2020-07-07 20:04:26'),
(27, 1, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36', '2020-07-07 20:04:29', '2020-07-07 20:05:36', 2, '2020-07-07 20:05:36'),
(28, 1, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36', '2020-07-07 20:05:51', '2020-07-09 19:01:02', 2, '2020-07-09 19:01:02'),
(29, 1, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-09 13:06:53', '2020-07-09 19:01:02', 2, '2020-07-09 19:01:02'),
(30, 1, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-09 19:01:02', '2020-07-09 20:18:20', 2, '2020-07-09 20:18:20'),
(31, 7, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-09 20:19:33', '2020-07-09 21:28:25', 2, '2020-07-09 21:28:25'),
(32, 1, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-09 20:21:20', '2020-07-09 21:27:20', 2, '2020-07-09 21:27:20'),
(33, 1, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-09 21:27:20', '2020-07-09 21:27:29', 2, '2020-07-09 21:27:29'),
(34, 1, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-09 21:28:15', '2020-07-09 21:28:20', 2, '2020-07-09 21:28:20'),
(35, 7, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-09 21:28:25', '2020-07-09 21:47:33', 2, '2020-07-09 21:47:33'),
(36, 1, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-09 21:47:36', '2020-07-10 20:46:28', 2, '2020-07-10 20:46:28'),
(37, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-09 23:06:28', '2020-07-10 20:46:28', 2, '2020-07-10 20:46:28'),
(38, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-10 20:46:28', '2020-07-10 20:46:41', 2, '2020-07-10 20:46:41'),
(39, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-10 20:48:42', '2020-07-10 20:58:10', 2, '2020-07-10 20:58:10'),
(40, 7, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-10 20:58:18', '2020-07-10 20:58:42', 2, '2020-07-10 20:58:42'),
(41, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-10 20:58:45', '2020-07-10 21:01:14', 2, '2020-07-10 21:01:14'),
(42, 7, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-10 21:01:21', '2020-07-12 01:18:24', 2, '2020-07-12 01:18:24'),
(43, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-11 22:33:22', '2020-07-11 23:20:24', 2, '2020-07-11 23:20:24'),
(44, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-11 23:20:24', '2020-07-12 01:18:17', 2, '2020-07-12 01:18:17'),
(45, 7, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-12 01:18:24', '2020-07-13 01:48:44', 2, '2020-07-13 01:48:44'),
(46, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-12 17:31:21', '2020-07-13 01:19:27', 2, '2020-07-13 01:19:27'),
(47, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-13 01:19:27', '2020-07-13 01:48:39', 2, '2020-07-13 01:48:39'),
(48, 7, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-13 01:48:44', '2020-07-13 02:26:51', 2, '2020-07-13 02:26:51'),
(49, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36', '2020-07-13 02:26:57', '2020-07-13 02:26:57', 1, NULL),
(50, 1, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.72 Safari/537.36', '2021-04-26 21:55:38', '2021-04-26 21:55:38', 1, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_email`
--

CREATE TABLE `log_email` (
  `id` int(11) NOT NULL,
  `from_email` varchar(50) NOT NULL,
  `to_email` varchar(50) DEFAULT NULL,
  `category` varchar(200) DEFAULT NULL,
  `process_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `log_email`
--

INSERT INTO `log_email` (`id`, `from_email`, `to_email`, `category`, `process_date`) VALUES
(1, 'maretta.shop1@gmail.com', 'agungtrilaksonosp@gmail.com', 'Notifikasi Contact Us', '2019-06-06 09:39:10'),
(2, 'maretta.shop1@gmail.com', 'agungtrilaksonosp@gmail.com', '[CS] Notifikasi Contact Us', '2019-06-06 09:39:15'),
(3, 'maretta.shop1@gmail.com', 'agungtrilaksonosp@gmail.com', 'Balasan Hubungi Kami', '2019-06-06 09:44:05'),
(4, 'maretta.shop1@gmail.com', 'agungtrilaksonosp@gmail.com', 'Notifikasi Contact Us', '2020-07-07 20:23:51'),
(5, 'maretta.shop1@gmail.com', 'agungtrilaksonosp@gmail.com', '[CS] Notifikasi Contact Us', '2020-07-07 20:23:56'),
(6, 'maretta.shop1@gmail.com', 'agungtrilaksonosp@gmail.com', 'Balasan Hubungi Kami', '2020-07-07 20:31:10'),
(7, 'maretta.shop1@gmail.com', 'sh.hanaaa@gmail.com', 'Notifikasi Contact Us', '2020-07-13 01:19:09'),
(8, 'maretta.shop1@gmail.com', 'EMAIL_CUSTOMER_SERVICE', '[CS] Notifikasi Contact Us', '2020-07-13 01:19:09'),
(9, 'maretta.shop1@gmail.com', 'sh.hanaaa@gmail.com', 'Balasan Hubungi Kami', '2020-07-13 01:19:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pages`
--

CREATE TABLE `pages` (
  `id` int(1) NOT NULL,
  `page_name` varchar(200) NOT NULL DEFAULT 'NULL',
  `teaser` longtext,
  `page_content` longtext,
  `uri_path` varchar(200) NOT NULL DEFAULT 'NULL',
  `img` varchar(200) DEFAULT 'NULL',
  `is_delete` smallint(6) NOT NULL DEFAULT '0',
  `user_id_create` int(11) NOT NULL DEFAULT '0',
  `user_id_modify` int(11) DEFAULT '0',
  `modify_date` datetime DEFAULT '1753-01-01 00:00:00',
  `create_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `pages`
--

INSERT INTO `pages` (`id`, `page_name`, `teaser`, `page_content`, `uri_path`, `img`, `is_delete`, `user_id_create`, `user_id_modify`, `modify_date`, `create_date`) VALUES
(1, 'Tentang Kami', 'Tentang Kami', '<p>Folio adalah program kasir, manajemen stok dan pelanggan berbasis cloud. Sangat mudah digunakan dan dapat berjalan secara online maupun offline.</p>\r\n\r\n<p>Mengelola Bisnis Lebih Mudah, Cepat dan Efisien.</p>\r\n\r\n<p>Pantau bisnis Anda dari mana saja dan kapan saja. Program kasir Folio menggunakan teknologi cloud computing yang dapat berjalan secara online maupun offline di berbagai perangkat seperti komputer, laptop, tablet dan juga smartphone.</p>\r\n', 'tentang-kami', '', 0, 1, 1, '2019-04-20 06:39:10', '2018-12-16 23:15:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_country`
--

CREATE TABLE `ref_country` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `teaser` varchar(255) NOT NULL,
  `user_modify_id` int(11) NOT NULL,
  `user_create_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `date_modify` datetime NOT NULL,
  `id_status_publish` int(11) NOT NULL,
  `is_delete` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ref_country`
--

INSERT INTO `ref_country` (`id`, `name`, `code`, `teaser`, `user_modify_id`, `user_create_id`, `create_date`, `date_modify`, `id_status_publish`, `is_delete`) VALUES
(1, 'Afghanistan', 'AF', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(2, 'Albania', 'AL', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(3, 'Algeria', 'DZ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(4, 'American Samoa', 'AS', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(5, 'Andorra', 'AD', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(6, 'Angola', 'AO', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(7, 'Anguilla', 'AI', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(8, 'Antarctica', 'AQ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(9, 'Antigua and Barbuda', 'AG', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(10, 'Argentina', 'AR', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(11, 'Armenia', 'AM', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(12, 'Aruba', 'AW', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(13, 'Australia', 'AU', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(14, 'Austria', 'AT', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(15, 'Azerbaijan', 'AZ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(16, 'Bahamas', 'BS', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(17, 'Bahrain', 'BH', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(18, 'Bangladesh', 'BD', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(19, 'Barbados', 'BB', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(20, 'Belarus', 'BY', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(21, 'Belgium', 'BE', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(22, 'Belize', 'BZ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(23, 'Benin', 'BJ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(24, 'Bermuda', 'BM', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(25, 'Bhutan', 'BT', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(26, 'Bolivia', 'BO', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(27, 'Bosnia and Herzegovina', 'BA', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(28, 'Botswana', 'BW', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(29, 'Bouvet Island', 'BV', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(30, 'Brazil', 'BR', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(31, 'British Indian Ocean Territory', 'IO', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(32, 'Brunei Darussalam', 'BN', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(33, 'Bulgaria', 'BG', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(34, 'Burkina Faso', 'BF', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(35, 'Burundi', 'BI', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(36, 'Cambodia', 'KH', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(37, 'Cameroon', 'CM', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(38, 'Canada', 'CA', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(39, 'Cape Verde', 'CV', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(40, 'Cayman Islands', 'KY', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(41, 'Central African Republic', 'CF', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(42, 'Chad', 'TD', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(43, 'Chile', 'CL', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(44, 'China', 'CN', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(45, 'Christmas Island', 'CX', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(46, 'Cocos (Keeling) Islands', 'CC', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(47, 'Colombia', 'CO', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(48, 'Comoros', 'KM', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(49, 'Congo', 'CG', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(50, 'Congo, the Democratic Republic of the', 'CD', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(51, 'Cook Islands', 'CK', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(52, 'Costa Rica', 'CR', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(53, 'Cote D\'Ivoire', 'CI', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(54, 'Croatia', 'HR', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(55, 'Cuba', 'CU', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(56, 'Cyprus', 'CY', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(57, 'Czech Republic', 'CZ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(58, 'Denmark', 'DK', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(59, 'Djibouti', 'DJ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(60, 'Dominica', 'DM', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(61, 'Dominican Republic', 'DO', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(62, 'Ecuador', 'EC', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(63, 'Egypt', 'EG', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(64, 'El Salvador', 'SV', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(65, 'Equatorial Guinea', 'GQ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(66, 'Eritrea', 'ER', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(67, 'Estonia', 'EE', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(68, 'Ethiopia', 'ET', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(69, 'Falkland Islands (Malvinas)', 'FK', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(70, 'Faroe Islands', 'FO', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(71, 'Fiji', 'FJ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(72, 'Finland', 'FI', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(73, 'France', 'FR', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(74, 'French Guiana', 'GF', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(75, 'French Polynesia', 'PF', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(76, 'French Southern Territories', 'TF', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(77, 'Gabon', 'GA', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(78, 'Gambia', 'GM', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(79, 'Georgia', 'GE', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(80, 'Germany', 'DE', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(81, 'Ghana', 'GH', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(82, 'Gibraltar', 'GI', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(83, 'Greece', 'GR', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(84, 'Greenland', 'GL', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(85, 'Grenada', 'GD', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(86, 'Guadeloupe', 'GP', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(87, 'Guam', 'GU', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(88, 'Guatemala', 'GT', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(89, 'Guinea', 'GN', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(90, 'Guinea-Bissau', 'GW', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(91, 'Guyana', 'GY', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(92, 'Haiti', 'HT', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(93, 'Heard Island and Mcdonald Islands', 'HM', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(94, 'Holy See (Vatican City State)', 'VA', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(95, 'Honduras', 'HN', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(96, 'Hong Kong', 'HK', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(97, 'Hungary', 'HU', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(98, 'Iceland', 'IS', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(99, 'India', 'IN', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(100, 'Indonesia', 'ID', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(101, 'Iran, Islamic Republic of', 'IR', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(102, 'Iraq', 'IQ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(103, 'Ireland', 'IE', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(104, 'Israel', 'IL', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(105, 'Italy', 'IT', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(106, 'Jamaica', 'JM', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(107, 'Japan', 'JP', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(108, 'Jordan', 'JO', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(109, 'Kazakhstan', 'KZ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(110, 'Kenya', 'KE', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(111, 'Kiribati', 'KI', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(112, 'Korea, Democratic People\'s Republic of', 'KP', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(113, 'Korea, Republic of', 'KR', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(114, 'Kuwait', 'KW', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(115, 'Kyrgyzstan', 'KG', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(116, 'Lao People\'s Democratic Republic', 'LA', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(117, 'Latvia', 'LV', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(118, 'Lebanon', 'LB', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(119, 'Lesotho', 'LS', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(120, 'Liberia', 'LR', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(121, 'Libyan Arab Jamahiriya', 'LY', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(122, 'Liechtenstein', 'LI', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(123, 'Lithuania', 'LT', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(124, 'Luxembourg', 'LU', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(125, 'Macao', 'MO', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(126, 'Macedonia, the Former Yugoslav Republic of', 'MK', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(127, 'Madagascar', 'MG', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(128, 'Malawi', 'MW', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(129, 'Malaysia', 'MY', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(130, 'Maldives', 'MV', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(131, 'Mali', 'ML', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(132, 'Malta', 'MT', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(133, 'Marshall Islands', 'MH', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(134, 'Martinique', 'MQ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(135, 'Mauritania', 'MR', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(136, 'Mauritius', 'MU', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(137, 'Mayotte', 'YT', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(138, 'Mexico', 'MX', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(139, 'Micronesia, Federated States of', 'FM', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(140, 'Moldova, Republic of', 'MD', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(141, 'Monaco', 'MC', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(142, 'Mongolia', 'MN', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(143, 'Montserrat', 'MS', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(144, 'Morocco', 'MA', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(145, 'Mozambique', 'MZ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(146, 'Myanmar', 'MM', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(147, 'Namibia', 'NA', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(148, 'Nauru', 'NR', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(149, 'Nepal', 'NP', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(150, 'Netherlands', 'NL', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(151, 'Netherlands Antilles', 'AN', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(152, 'New Caledonia', 'NC', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(153, 'New Zealand', 'NZ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(154, 'Nicaragua', 'NI', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(155, 'Niger', 'NE', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(156, 'Nigeria', 'NG', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(157, 'Niue', 'NU', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(158, 'Norfolk Island', 'NF', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(159, 'Northern Mariana Islands', 'MP', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(160, 'Norway', 'NO', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(161, 'Oman', 'OM', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(162, 'Pakistan', 'PK', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(163, 'Palau', 'PW', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(164, 'Palestinian Territory, Occupied', 'PS', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(165, 'Panama', 'PA', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(166, 'Papua New Guinea', 'PG', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(167, 'Paraguay', 'PY', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(168, 'Peru', 'PE', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(169, 'Philippines', 'PH', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(170, 'Pitcairn', 'PN', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(171, 'Poland', 'PL', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(172, 'Portugal', 'PT', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(173, 'Puerto Rico', 'PR', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(174, 'Qatar', 'QA', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(175, 'Reunion', 'RE', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(176, 'Romania', 'RO', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(177, 'Russian Federation', 'RU', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(178, 'Rwanda', 'RW', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(179, 'Saint Helena', 'SH', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(180, 'Saint Kitts and Nevis', 'KN', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(181, 'Saint Lucia', 'LC', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(182, 'Saint Pierre and Miquelon', 'PM', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(183, 'Saint Vincent and the Grenadines', 'VC', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(184, 'Samoa', 'WS', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(185, 'San Marino', 'SM', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(186, 'Sao Tome and Principe', 'ST', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(187, 'Saudi Arabia', 'SA', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(188, 'Senegal', 'SN', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(189, 'Serbia and Montenegro', 'CS', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(190, 'Seychelles', 'SC', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(191, 'Sierra Leone', 'SL', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(192, 'Singapore', 'SG', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(193, 'Slovakia', 'SK', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(194, 'Slovenia', 'SI', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(195, 'Solomon Islands', 'SB', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(196, 'Somalia', 'SO', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(197, 'South Africa', 'ZA', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(198, 'South Georgia and the South Sandwich Islands', 'GS', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(199, 'Spain', 'ES', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(200, 'Sri Lanka', 'LK', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(201, 'Sudan', 'SD', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(202, 'Suriname', 'SR', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(203, 'Svalbard and Jan Mayen', 'SJ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(204, 'Swaziland', 'SZ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(205, 'Sweden', 'SE', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(206, 'Switzerland', 'CH', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(207, 'Syrian Arab Republic', 'SY', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(208, 'Taiwan, Province of China', 'TW', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(209, 'Tajikistan', 'TJ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(210, 'Tanzania, United Republic of', 'TZ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(211, 'Thailand', 'TH', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(212, 'Timor-Leste', 'TL', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(213, 'Togo', 'TG', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(214, 'Tokelau', 'TK', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(215, 'Tonga', 'TO', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(216, 'Trinidad and Tobago', 'TT', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(217, 'Tunisia', 'TN', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(218, 'Turkey', 'TR', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(219, 'Turkmenistan', 'TM', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(220, 'Turks and Caicos Islands', 'TC', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(221, 'Tuvalu', 'TV', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(222, 'Uganda', 'UG', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(223, 'Ukraine', 'UA', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(224, 'United Arab Emirates', 'AE', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(225, 'United Kingdom', 'GB', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(226, 'United States', 'US', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(227, 'United States Minor Outlying Islands', 'UM', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(228, 'Uruguay', 'UY', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(229, 'Uzbekistan', 'UZ', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(230, 'Vanuatu', 'VU', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(231, 'Venezuela', 'VE', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(232, 'Viet Nam', 'VN', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(233, 'Virgin Islands, British', 'VG', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(234, 'Virgin Islands, U.s.', 'VI', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(235, 'Wallis and Futuna', 'WF', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(236, 'Western Sahara', 'EH', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(237, 'Yemen', 'YE', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(238, 'Zambia', 'ZM', '', 0, 1, '2021-04-26 22:26:14', '2021-04-26 22:26:14', 2, 0),
(239, 'Zimbabwe', 'ZW', '', 1, 1, '2021-04-26 22:26:14', '2021-04-26 22:27:17', 2, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_menu_admin`
--

CREATE TABLE `ref_menu_admin` (
  `id_ref_menu_admin` int(11) NOT NULL,
  `id_parents_menu_admin` int(11) DEFAULT NULL,
  `menu` varchar(255) NOT NULL DEFAULT '',
  `controller` varchar(255) NOT NULL DEFAULT '',
  `urut` decimal(3,0) DEFAULT NULL,
  `img_icon` varchar(32) DEFAULT '',
  `create_date` datetime DEFAULT NULL,
  `breadcrumb` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `ref_menu_admin`
--

INSERT INTO `ref_menu_admin` (`id_ref_menu_admin`, `id_parents_menu_admin`, `menu`, `controller`, `urut`, `img_icon`, `create_date`, `breadcrumb`) VALUES
(1, 0, 'Home', 'home', '2', 'fa fa-laptop', NULL, 'Home'),
(2, 14, 'Menu Management', 'auth_menu', '11', 'fa fa-align-left', NULL, 'Menu Management'),
(7, 0, 'My Account', '#', '42', 'fa fa-child', NULL, 'My Account'),
(8, 7, 'Change Password', 'change_pwd', '1', '', NULL, 'Change Password'),
(9, 7, 'User\'s Profile', 'profile', '4', '', NULL, 'User\'s Profile'),
(14, 0, 'Admin', '#', '41', 'fa fa-bullhorn', NULL, 'Admin'),
(15, 39, 'Users', 'user', '1', 'fa fa-user', NULL, 'Users'),
(16, 39, 'User\'s Groups', 'auth_pages', '10', '', NULL, 'User\'s Groups'),
(18, 14, 'User Log', 'log', '28', 'fa fa-archive', NULL, 'User Log'),
(32, 0, 'Master Data', 'home#', '38', 'fa fa-cog', NULL, 'Master Data'),
(39, 0, 'User Management', '#', '39', 'fa fa-user', NULL, 'User Management'),
(42, 14, 'Pages', 'pages', '1', '', NULL, 'Pages'),
(43, 0, 'Contact Us', 'contact_us', '30', 'fa fa-phone', NULL, 'Contact Us'),
(44, 0, 'Settings', '#', '43', 'fa fa-cogs', NULL, 'Settings'),
(49, 0, 'Panduan Penggunaan', '#', '34', 'fa fa-flag', NULL, 'Panduan Penggunaan'),
(50, 49, 'Panduan Penggunaan', 'help', '1', '', NULL, 'Panduan Penggunaan'),
(51, 49, 'Kategori Panduan', 'help_category', '2', '', NULL, 'Kategori Panduan'),
(52, 49, 'Komentar Panduan', 'help_comment', '33', '', NULL, 'Komentar Panduan'),
(63, 0, 'Member Management', 'member', '25', 'fa fa-users', NULL, 'Member Management'),
(71, 0, 'Report', '#', '36', 'fa fa-bar-chart', NULL, 'Report'),
(72, 71, 'Member\'s Report', 'member_report', '1', '', NULL, 'Member\'s Report'),
(92, 32, 'Country', 'ref_country', '1', '', NULL, 'Country'),
(93, 32, 'Member Categories', 'ref_user_category', '44', '', NULL, 'Member Categories');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_user_category`
--

CREATE TABLE `ref_user_category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `teaser` varchar(255) NOT NULL,
  `user_modify_id` int(11) NOT NULL,
  `user_create_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `date_modify` datetime NOT NULL,
  `id_status_publish` int(11) NOT NULL,
  `is_delete` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ref_user_category`
--

INSERT INTO `ref_user_category` (`id`, `name`, `teaser`, `user_modify_id`, `user_create_id`, `create_date`, `date_modify`, `id_status_publish`, `is_delete`) VALUES
(1, 'Seller', '', 0, 1, '2021-04-26 22:19:57', '0000-00-00 00:00:00', 2, 0),
(2, 'Buyer', '', 0, 1, '2021-04-26 22:20:04', '0000-00-00 00:00:00', 2, 0),
(3, 'VIP', '', 0, 1, '2021-04-26 22:20:13', '0000-00-00 00:00:00', 2, 0),
(4, 'Sponsor', '', 0, 1, '2021-04-26 22:20:21', '0000-00-00 00:00:00', 2, 0),
(5, 'Observer', '', 0, 1, '2021-04-26 22:20:43', '0000-00-00 00:00:00', 2, 0),
(6, 'Media', '', 0, 1, '2021-04-26 22:20:53', '0000-00-00 00:00:00', 2, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `services`
--

CREATE TABLE `services` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_auth_user` int(11) NOT NULL,
  `id_motor_user` int(11) NOT NULL,
  `service_date` date DEFAULT NULL,
  `service_fee` double(8,2) NOT NULL DEFAULT '0.00',
  `technician` varchar(80) DEFAULT NULL,
  `place` varchar(255) DEFAULT NULL,
  `gps_point` longtext,
  `mileage_km` double(12,2) DEFAULT NULL,
  `oli_price` double(8,2) NOT NULL DEFAULT '0.00',
  `aki_price` double(8,2) NOT NULL DEFAULT '0.00',
  `rem_depan_price` double(8,2) NOT NULL DEFAULT '0.00',
  `rem_belakang_price` double(8,2) NOT NULL DEFAULT '0.00',
  `ban_depan_price` double(8,2) NOT NULL DEFAULT '0.00',
  `ban_belakang_price` double(8,2) NOT NULL DEFAULT '0.00',
  `note` varchar(255) DEFAULT NULL,
  `user_id_create` int(11) NOT NULL,
  `user_id_modify` int(11) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `modify_date` datetime DEFAULT NULL,
  `is_delete` smallint(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `services`
--

INSERT INTO `services` (`id`, `id_auth_user`, `id_motor_user`, `service_date`, `service_fee`, `technician`, `place`, `gps_point`, `mileage_km`, `oli_price`, `aki_price`, `rem_depan_price`, `rem_belakang_price`, `ban_depan_price`, `ban_belakang_price`, `note`, `user_id_create`, `user_id_modify`, `create_date`, `modify_date`, `is_delete`) VALUES
(1, 10, 5, '2020-07-11', 20000.00, '', '', '', 15000.00, 1000.00, 23000.00, 4000.00, 0.00, 250000.00, 0.00, '', 0, 0, '2020-07-12 00:00:00', '2020-07-12 00:00:00', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `status_publish`
--

CREATE TABLE `status_publish` (
  `id` int(10) NOT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT 'Keterangan pempublikasian'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table Status Pempublikasian';

--
-- Dumping data untuk tabel `status_publish`
--

INSERT INTO `status_publish` (`id`, `name`) VALUES
(1, 'Unpublished'),
(2, 'Publish');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tags`
--

CREATE TABLE `tags` (
  `id` int(10) NOT NULL,
  `name` varchar(225) DEFAULT NULL,
  `tags_count` int(10) DEFAULT '0',
  `is_delete` smallint(5) DEFAULT '0',
  `img` varchar(225) NOT NULL,
  `page_content` text NOT NULL,
  `teaser` varchar(225) NOT NULL,
  `maps` text NOT NULL,
  `seo_title` varchar(225) NOT NULL,
  `meta_description` varchar(225) NOT NULL,
  `meta_keywords` varchar(225) NOT NULL,
  `id_status_publish` int(10) NOT NULL,
  `user_id_create` int(10) NOT NULL,
  `user_id_modify` int(10) DEFAULT NULL,
  `create_date` datetime(6) NOT NULL,
  `modify_date` datetime(6) DEFAULT NULL,
  `uri_path` varchar(225) DEFAULT NULL,
  `id_lang` int(10) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tags`
--

INSERT INTO `tags` (`id`, `name`, `tags_count`, `is_delete`, `img`, `page_content`, `teaser`, `maps`, `seo_title`, `meta_description`, `meta_keywords`, `id_status_publish`, `user_id_create`, `user_id_modify`, `create_date`, `modify_date`, `uri_path`, `id_lang`) VALUES
(1, 'marketing', 0, 0, '', '', '', '', '', '', '', 2, 1, 1, '2017-03-20 10:07:09.000000', '2017-04-17 18:56:42.000000', 'marketing', 1),
(2, 'diskon', 0, 0, '', '', '', '', '', '', '', 2, 1, 1, '2017-03-20 13:19:47.000000', '2017-04-17 18:56:53.000000', 'diskon', 1),
(3, 'return', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-03-30 18:44:19.000000', NULL, 'return', 1),
(4, 'produk', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-03-30 18:44:19.000000', NULL, 'produk', 1),
(5, 'pemesanan-produk', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-04-07 15:08:41.000000', NULL, 'pemesanan-produk', 1),
(6, 'stok', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-04-07 15:08:41.000000', NULL, 'stok', 1),
(7, 'fitur', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-04-17 11:41:06.000000', NULL, 'fitur', 1),
(8, 'retail', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-04-17 19:04:53.000000', NULL, 'retail', 1),
(9, 'point of sales', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-05-02 15:32:07.000000', NULL, 'point-of-sales', 1),
(10, 'software', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-05-04 16:10:31.000000', NULL, 'software', 1),
(11, 'kasir', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-05-04 16:10:31.000000', NULL, 'kasir', 1),
(12, 'online', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-05-04 16:10:31.000000', NULL, 'online', 1),
(13, 'bisnis', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-05-26 18:23:10.000000', NULL, 'bisnis', 1),
(14, 'enterpreneur', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-05-26 18:23:10.000000', NULL, 'enterpreneur', 1),
(15, 'ramadhan', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-06-02 22:23:03.000000', NULL, 'ramadhan', 1),
(16, 'omzet', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-06-02 22:23:03.000000', NULL, 'omzet', 1),
(17, 'faq', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-07-20 15:23:35.000000', NULL, 'faq', 1),
(18, 'pengaturan', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-07-26 19:53:06.000000', NULL, 'pengaturan', 1),
(19, 'stock opname', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-07-26 19:53:06.000000', NULL, 'stock-opname', 1),
(20, 'salon', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-08-10 15:15:56.000000', NULL, 'salon', 1),
(21, 'cafe', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-08-14 14:57:57.000000', NULL, 'cafe', 1),
(22, 'fnb', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-08-14 14:57:57.000000', NULL, 'fnb', 1),
(23, 'pasar', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2017-08-16 14:56:30.000000', NULL, 'pasar', 1),
(24, '', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2019-04-20 06:41:58.000000', NULL, '', 1),
(25, 'test', 0, 0, '', '', '', '', '', '', '', 0, 1, NULL, '2019-04-20 06:42:43.000000', NULL, 'test', 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `access_log`
--
ALTER TABLE `access_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_auth_user` (`id_auth_user`);

--
-- Indeks untuk tabel `auth_pages`
--
ALTER TABLE `auth_pages`
  ADD PRIMARY KEY (`id_auth_pages`),
  ADD KEY `id_auth_pages` (`id_auth_pages`),
  ADD KEY `id_auth_user_grup` (`id_auth_user_grup`),
  ADD KEY `id_ref_menu_admin` (`id_ref_menu_admin`);

--
-- Indeks untuk tabel `auth_user`
--
ALTER TABLE `auth_user`
  ADD PRIMARY KEY (`id_auth_user`),
  ADD KEY `id_auth_user_grup` (`id_auth_user_grup`);

--
-- Indeks untuk tabel `auth_user_grup`
--
ALTER TABLE `auth_user_grup`
  ADD PRIMARY KEY (`id_auth_user_grup`);

--
-- Indeks untuk tabel `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `contact_us_reply`
--
ALTER TABLE `contact_us_reply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_contact_us` (`id_contact_us`);

--
-- Indeks untuk tabel `email_config`
--
ALTER TABLE `email_config`
  ADD PRIMARY KEY (`smtp_host`);

--
-- Indeks untuk tabel `email_tmp`
--
ALTER TABLE `email_tmp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_status_publish` (`id_status_publish`);

--
-- Indeks untuk tabel `file_manager`
--
ALTER TABLE `file_manager`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `help`
--
ALTER TABLE `help`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_help_category` (`id_help_category`),
  ADD KEY `id_status_publish` (`id_status_publish`);

--
-- Indeks untuk tabel `help_category`
--
ALTER TABLE `help_category`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `help_comment`
--
ALTER TABLE `help_comment`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `login_transaction`
--
ALTER TABLE `login_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log_email`
--
ALTER TABLE `log_email`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indeks untuk tabel `ref_country`
--
ALTER TABLE `ref_country`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_menu_admin`
--
ALTER TABLE `ref_menu_admin`
  ADD PRIMARY KEY (`id_ref_menu_admin`);

--
-- Indeks untuk tabel `ref_user_category`
--
ALTER TABLE `ref_user_category`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `status_publish`
--
ALTER TABLE `status_publish`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `access_log`
--
ALTER TABLE `access_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT untuk tabel `auth_pages`
--
ALTER TABLE `auth_pages`
  MODIFY `id_auth_pages` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=518;

--
-- AUTO_INCREMENT untuk tabel `auth_user`
--
ALTER TABLE `auth_user`
  MODIFY `id_auth_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `auth_user_grup`
--
ALTER TABLE `auth_user_grup`
  MODIFY `id_auth_user_grup` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `contact_us_reply`
--
ALTER TABLE `contact_us_reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `email_tmp`
--
ALTER TABLE `email_tmp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identitas email tmp', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `file_manager`
--
ALTER TABLE `file_manager`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `help`
--
ALTER TABLE `help`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `help_category`
--
ALTER TABLE `help_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `help_comment`
--
ALTER TABLE `help_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `login_transaction`
--
ALTER TABLE `login_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `log_email`
--
ALTER TABLE `log_email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `ref_country`
--
ALTER TABLE `ref_country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;

--
-- AUTO_INCREMENT untuk tabel `ref_menu_admin`
--
ALTER TABLE `ref_menu_admin`
  MODIFY `id_ref_menu_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT untuk tabel `ref_user_category`
--
ALTER TABLE `ref_user_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `status_publish`
--
ALTER TABLE `status_publish`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
