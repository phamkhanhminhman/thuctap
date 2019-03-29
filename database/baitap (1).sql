-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 29, 2019 lúc 11:33 AM
-- Phiên bản máy phục vụ: 10.1.38-MariaDB
-- Phiên bản PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `baitap`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tb_group`
--

CREATE TABLE `tb_group` (
  `groupID` int(11) NOT NULL,
  `groupName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Đang đổ dữ liệu cho bảng `tb_group`
--

INSERT INTO `tb_group` (`groupID`, `groupName`) VALUES
(1, 'CNTT'),
(2, 'SPT'),
(3, 'AAA');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tb_users`
--

CREATE TABLE `tb_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `groupID` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_token` char(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tb_users`
--

INSERT INTO `tb_users` (`id`, `groupID`, `name`, `gender`, `email`, `password`, `api_token`, `image`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 1, '123123654', 1, 'jalon88@yahoo.com', '15815e794187cff7a19a3f0852e6a8c2', NULL, 'https://lorempixel.com/640/480/?93703', 'xxxxxxx', NULL, '2019-03-29 10:30:01', NULL),
(3, 3, 'aaaa', 1, 'kunde.ellen@yahoo.com', '33421da058b740a997cf427102e59e5c', NULL, 'https://lorempixel.com/640/480/?34752', 'quis', NULL, '2019-03-29 08:35:00', NULL),
(4, 2, 'q1q12', 1, 'a@gmail.com', '4297f44b13955235245b2497399d7a93', 'KAH1YIvouZeAhanBmh9qkAT4M3ToRL7acwxkdu6HmUryrQhOFYdzzmsZofWE', NULL, NULL, NULL, '2019-03-27 05:00:08', NULL),
(199, 2, '99999999', 1, 'b@gmail.com', '63ee451939ed580ef3c4b6f0109d1fd0', 'KNYs6jwNGR9obaDZBIpDmtghIGP4gbDYId00dXtC5j93zu3Sa7m0BhErhGJ5', NULL, 'asdasd', '2019-03-15 03:11:46', '2019-03-27 06:16:23', NULL),
(216, 2, '122312312', 0, 'a@a', '202cb962ac59075b964b07152d234b70', 'YGoU495hIkHlP7XeuNy4Oz2R5xqTutoMroAcavX8B4G6YlGaYORuJAic8JZA', NULL, '123213', '2019-03-18 10:24:34', '2019-03-29 09:46:30', NULL),
(272, 2, 'atom', 1, 'sssewee@g', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'aaa', '2019-03-26 21:35:59', '2019-03-27 06:16:29', NULL),
(273, 1, 'atom', 1, 'fffawe@g', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'aaa', '2019-03-26 21:35:59', '2019-03-27 06:16:34', NULL),
(274, 2, 'atom', 1, 'qw1rr@a', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'aaa', '2019-03-26 21:35:59', '2019-03-27 06:16:36', NULL),
(275, 3, 'atom', 1, 'hhee@g', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'aaa', '2019-03-26 21:35:59', '2019-03-27 09:37:57', NULL),
(372, 1, 'atoma', 1, 'qw1231ea@g', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'aaa', '2019-03-27 21:29:57', '2019-03-28 04:36:21', NULL),
(373, 2, 'atomb', 1, 'qwaewssee@g', '28c8edde3d61a0411511d3b1866f0636', NULL, NULL, 'aaa', '2019-03-27 21:29:57', '2019-03-28 10:52:45', NULL),
(374, 3, 'atomc', 1, 'qasdawsse@g', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'aaa', '2019-03-27 21:29:57', '2019-03-28 04:36:24', NULL),
(375, 1, 'atomd', 1, 'qwxx1zze@g', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'aaa', '2019-03-27 21:29:57', '2019-03-28 04:36:26', NULL),
(376, 2, 'atome', 1, 'qwaaaxxzee@g', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'aaa', '2019-03-27 21:29:57', '2019-03-28 04:36:28', NULL),
(378, 1, 'atomg', 1, 'qwqwr31e@g', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'aaa', '2019-03-27 21:29:57', '2019-03-28 04:36:32', NULL),
(379, 3, 'atom', 1, 'sssewee@g', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'aaa', '2019-03-27 21:29:57', '2019-03-29 01:00:48', '2019-03-29 01:00:48'),
(381, NULL, '123123', 1, 'q@123123', '4297f44b13955235245b2497399d7a93', NULL, 'http://127.0.0.1:8000/upload/ora.png', NULL, '2019-03-29 08:24:30', NULL, NULL),
(382, NULL, 'qweqwr', 1, 'a@qweqwe', '4297f44b13955235245b2497399d7a93', NULL, 'http://127.0.0.1:8000/upload/Untitled.png', NULL, '2019-03-29 08:29:07', NULL, NULL),
(383, NULL, '123123123', 1, 'a@qweadzx', '8d4646eb2d7067126eb08adb0672f7bb', NULL, 'http://127.0.0.1:8000/upload/ora.png', NULL, '2019-03-29 08:56:06', NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tb_group`
--
ALTER TABLE `tb_group`
  ADD PRIMARY KEY (`groupID`);

--
-- Chỉ mục cho bảng `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=384;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
