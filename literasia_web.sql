-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 11 Jan 2026 pada 16.29
-- Versi server: 12.1.2-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `literasia_web`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `fungsionaris`
--

CREATE TABLE `fungsionaris` (
  `id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `nip` varchar(255) NOT NULL,
  `nik` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `posisi` varchar(255) NOT NULL,
  `jabatan` enum('guru','pegawai') NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `no_hp` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `pendidikan_terakhir` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `fungsionaris`
--

INSERT INTO `fungsionaris` (`id`, `user_id`, `nip`, `nik`, `nama`, `posisi`, `jabatan`, `status`, `no_hp`, `alamat`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `pendidikan_terakhir`, `created_at`, `updated_at`) VALUES
('019bad70-4c7f-7294-91cd-650d5f50a414', '019bad70-4c7c-7020-a146-1be96f358f79', '1982898289289287', '1810081189827726', 'Wahyu Rahmat Hidayat, S.Kom., Gr.', 'Guru Bahasa Inggris', 'guru', 'aktif', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-11 07:22:56', '2026-01-11 07:23:06'),
('019bad70-d6cb-73ab-9e3f-ee6e2dd54e3f', '019bad70-d6c8-70b8-9d94-d2c3d3552e91', '1238712873827772', '182918297387333', 'Citra Karina', 'Staff TU', 'pegawai', 'aktif', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-11 07:23:32', '2026-01-11 07:23:32'),
('019bad76-35da-71b1-bf6b-ae316b083ffc', '019bad76-35d5-73d1-8fc3-9a4a5387614d', '198501012010011001', '3201010101010001', 'Budi Santoso', 'Guru Matematika', 'guru', 'aktif', '08123456789', 'Jl. Pendidikan No. 123', 'Jakarta', '1985-01-01', 'L', 'S1 Pendidikan Matematika', '2026-01-11 07:29:24', '2026-01-11 07:29:24'),
('019bad76-36b2-735f-b06b-6a3eb922a12a', '019bad76-36b1-7111-9c54-af48e339579f', '199005052015022002', '3201010505900002', 'Siti Aminah', 'Staf Administrasi', 'pegawai', 'aktif', '08987654321', 'Jl. Merdeka No. 45', 'Bandung', '1990-05-05', 'P', 'D3 Administrasi Perkantoran', '2026-01-11 07:29:24', '2026-01-11 07:29:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jam_pelajarans`
--

CREATE TABLE `jam_pelajarans` (
  `id` char(36) NOT NULL,
  `hari` varchar(255) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jam_pelajarans`
--

INSERT INTO `jam_pelajarans` (`id`, `hari`, `jam_mulai`, `jam_selesai`, `created_at`, `updated_at`) VALUES
('019bad9c-7b99-731f-b8b3-913b3706a801', 'Senin', '07:30:00', '08:30:00', '2026-01-11 08:11:12', '2026-01-11 08:11:12'),
('019bad9c-c4b2-73d3-a32b-8ccf0e4a3059', 'Selasa', '07:30:00', '08:30:00', '2026-01-11 08:11:30', '2026-01-11 08:11:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurusans`
--

CREATE TABLE `jurusans` (
  `id` char(36) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jurusans`
--

INSERT INTO `jurusans` (`id`, `nama`, `deskripsi`, `is_active`, `created_at`, `updated_at`) VALUES
('019bad86-0198-70db-9c00-71efd30f3d1f', 'IPA', NULL, 1, '2026-01-11 07:46:39', '2026-01-11 07:46:39'),
('019bad86-4925-7254-a7fd-78ba3c5ab62f', 'IPS', NULL, 1, '2026-01-11 07:46:57', '2026-01-11 07:46:57');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id` char(36) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tingkat` varchar(255) NOT NULL,
  `wali_kelas_id` char(36) DEFAULT NULL,
  `jurusan_id` char(36) DEFAULT NULL,
  `kapasitas` int(11) NOT NULL DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id`, `nama`, `tingkat`, `wali_kelas_id`, `jurusan_id`, `kapasitas`, `keterangan`, `created_at`, `updated_at`) VALUES
('019bad87-869b-7160-8264-939570c80f2f', 'X IPA 1', '10', '019bad76-35da-71b1-bf6b-ae316b083ffc', '019bad86-0198-70db-9c00-71efd30f3d1f', 36, NULL, '2026-01-11 07:48:18', '2026-01-11 07:48:18'),
('019bad87-c912-70bc-88c2-f3365b07ef6e', 'X IPS 1', '10', '019bad70-4c7f-7294-91cd-650d5f50a414', '019bad86-4925-7254-a7fd-78ba3c5ab62f', 36, NULL, '2026-01-11 07:48:35', '2026-01-11 07:48:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_11_132406_add_role_to_users_table', 1),
(5, '2026_01_11_134048_create_fungsionaris_table', 1),
(6, '2026_01_11_134052_add_username_to_users_table', 1),
(7, '2026_01_11_143959_create_jurusans_table', 2),
(8, '2026_01_11_143959_create_kelas_table', 2),
(9, '2026_01_11_143959_create_school_settings_table', 2),
(10, '2026_01_11_150456_add_is_active_to_school_settings_table', 3),
(11, '2026_01_11_150456_create_jam_pelajarans_table', 3),
(12, '2026_01_11_150457_create_subjects_table', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `schedules`
--

CREATE TABLE `schedules` (
  `id` char(36) NOT NULL,
  `kelas_id` char(36) NOT NULL,
  `subject_id` char(36) NOT NULL,
  `jam_id` char(36) NOT NULL,
  `school_setting_id` char(36) NOT NULL,
  `hari` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `schedules`
--

INSERT INTO `schedules` (`id`, `kelas_id`, `subject_id`, `jam_id`, `school_setting_id`, `hari`, `created_at`, `updated_at`) VALUES
('019bada3-dfb4-7008-b8ef-8f937f7681ad', '019bad87-869b-7160-8264-939570c80f2f', '019bad9c-f902-73d9-a263-87c9a7aaaaf9', '019bad9c-7b99-731f-b8b3-913b3706a801', '019bad87-0ebb-7177-aefb-3154fdef2e8c', 'Senin', '2026-01-11 08:19:16', '2026-01-11 08:19:16'),
('019bada4-1eb8-72e5-bda7-b0dcb15a5f18', '019bad87-c912-70bc-88c2-f3365b07ef6e', '019bad9c-f902-73d9-a263-87c9a7aaaaf9', '019bad9c-7b99-731f-b8b3-913b3706a801', '019bad87-0ebb-7177-aefb-3154fdef2e8c', 'Senin', '2026-01-11 08:19:32', '2026-01-11 08:19:32'),
('019bada4-24dd-7376-ba84-1b1c3bffb79a', '019bad87-c912-70bc-88c2-f3365b07ef6e', '019bad9c-f902-73d9-a263-87c9a7aaaaf9', '019bad9c-c4b2-73d3-a32b-8ccf0e4a3059', '019bad87-0ebb-7177-aefb-3154fdef2e8c', 'Selasa', '2026-01-11 08:19:34', '2026-01-11 08:19:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `school_settings`
--

CREATE TABLE `school_settings` (
  `id` char(36) NOT NULL,
  `semester` enum('ganjil','genap') DEFAULT NULL,
  `tahun_pelajaran` varchar(255) DEFAULT NULL,
  `jenjang` enum('sd','smp','sma_smk') DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `school_settings`
--

INSERT INTO `school_settings` (`id`, `semester`, `tahun_pelajaran`, `jenjang`, `is_active`, `created_at`, `updated_at`) VALUES
('019bad87-0ebb-7177-aefb-3154fdef2e8c', 'genap', '2026/2027', 'sma_smk', 1, '2026-01-11 07:47:48', '2026-01-11 08:24:05'),
('019bada7-c09a-72bb-b921-142afe697294', 'ganjil', '2026/2027', 'sma_smk', 0, '2026-01-11 08:23:30', '2026-01-11 08:24:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('FzESvhSrNvFDCbJHBAe8IW7aLrlqnlNfEXh4qmGT', '019bad4c-672f-72cb-97f9-0ede19cbea18', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoieVFRQzN4RndoMHJOdzBhT2paQ3o0cXVzMVNLS0UwcjlxQ3BjQ0NNeCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7czozNjoiMDE5YmFkNGMtNjcyZi03MmNiLTk3ZjktMGVkZTE5Y2JlYTE4Ijt9', 1768141290),
('Mnue97nCLvoITEz8Jp619a4R1gz1hLHNh54gL4R5', '019bad4c-672f-72cb-97f9-0ede19cbea18', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTDVQWnREVHEybU9peld4M3dVa2duUmhhMzdnN3Qxd0JkbFJVRGMzNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9mdW5nc2lvbmFyaXMiO3M6NToicm91dGUiO3M6MTg6ImZ1bmdzaW9uYXJpcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjM2OiIwMTliYWQ0Yy02NzJmLTcyY2ItOTdmOS0wZWRlMTljYmVhMTgiO30=', 1768141038),
('Sc4lm0DUg1OVWKxEMW0hvFercXfRcntfIv35TSY2', '019bad4c-672f-72cb-97f9-0ede19cbea18', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMXRPb25GcHNhcVF3V2w5R3VMeWhKWUNlTjh1MDJHSmxkRE1wTFZKcyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYXRhLXBlbGFqYXJhbiI7czo1OiJyb3V0ZSI7czoyMDoibWF0YS1wZWxhamFyYW4uaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7czozNjoiMDE5YmFkNGMtNjcyZi03MmNiLTk3ZjktMGVkZTE5Y2JlYTE4Ijt9', 1768145253);

-- --------------------------------------------------------

--
-- Struktur dari tabel `subjects`
--

CREATE TABLE `subjects` (
  `id` char(36) NOT NULL,
  `kode_pelajaran` varchar(255) NOT NULL,
  `nama_pelajaran` varchar(255) NOT NULL,
  `guru_id` char(36) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `subjects`
--

INSERT INTO `subjects` (`id`, `kode_pelajaran`, `nama_pelajaran`, `guru_id`, `is_active`, `created_at`, `updated_at`) VALUES
('019bad9c-f902-73d9-a263-87c9a7aaaaf9', 'MP001', 'Bahasa Inggris', '019bad76-35da-71b1-bf6b-ae316b083ffc', 1, '2026-01-11 08:11:44', '2026-01-11 08:11:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'siswa',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
('019bad4c-672f-72cb-97f9-0ede19cbea18', 'Administrator Literasia', 'admin@literasia.com', 'admin', NULL, '$2y$12$qcBvN6WaQU/2yyscKP4zWe1kEjgXcnQpnl1QGbDorCwYe/HTD/OXm', 'admin', NULL, '2026-01-11 06:43:44', '2026-01-11 06:43:44'),
('019bad4c-680d-737c-8cca-9252ed51d4b3', 'Guru Literasia', 'guru@literasia.com', 'guru', NULL, '$2y$12$AmrEUnLKvs8Tsurh5iLWh.GUIpFTz/uBzEbgOViIupJbEQ05ZeOYy', 'guru', NULL, '2026-01-11 06:43:44', '2026-01-11 06:43:44'),
('019bad4c-68e8-7060-b139-9f33f50f4f59', 'Siswa Literasia', 'siswa@literasia.com', 'siswa', NULL, '$2y$12$pvnchAj4s5Pedf0dXcgXSeIyVzq/m4xAx4jZ6QyPQAuxrmA0GYc2W', 'siswa', NULL, '2026-01-11 06:43:44', '2026-01-11 06:43:44'),
('019bad70-4c7c-7020-a146-1be96f358f79', 'Wahyu Rahmat Hidayat, S.Kom., Gr.', 'wahyurah55@literasia.com', 'wahyurah55', NULL, '$2y$12$gdQqu2FFcpDX7L0Fl7T3wu1BXqUNDUXjDNmzSL1wve4yvkdyGkIZ6', 'guru', NULL, '2026-01-11 07:22:56', '2026-01-11 07:23:06'),
('019bad70-d6c8-70b8-9d94-d2c3d3552e91', 'Citra Karina', 'citra55@literasia.com', 'citra55', NULL, '$2y$12$r924fJl4E9.O9hHYq6rc1e6NY6lll8S0n2CcXm0Tq7dG83NtY.Foi', 'pegawai', NULL, '2026-01-11 07:23:32', '2026-01-11 07:23:32'),
('019bad76-35d5-73d1-8fc3-9a4a5387614d', 'Budi Santoso', 'budi_guru@literasia.com', 'budi_guru', NULL, '$2y$12$fFqvJlNH.4ocJ2eKZfzP/eTvbP3puyoDv5/3rCKfyrQUYMhFKQvlK', 'guru', NULL, '2026-01-11 07:29:24', '2026-01-11 07:29:24'),
('019bad76-36b1-7111-9c54-af48e339579f', 'Siti Aminah', 'siti_staf@literasia.com', 'siti_staf', NULL, '$2y$12$3Q0ZN.aHWTEwrv20DYHd5evI9jEq1/smpMs6BgCeM7qji.c3h5N4O', 'pegawai', NULL, '2026-01-11 07:29:24', '2026-01-11 07:29:24');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `fungsionaris`
--
ALTER TABLE `fungsionaris`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fungsionaris_nip_unique` (`nip`),
  ADD UNIQUE KEY `fungsionaris_nik_unique` (`nik`),
  ADD KEY `fungsionaris_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `jam_pelajarans`
--
ALTER TABLE `jam_pelajarans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jurusans`
--
ALTER TABLE `jurusans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_wali_kelas_id_foreign` (`wali_kelas_id`),
  ADD KEY `kelas_jurusan_id_foreign` (`jurusan_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedules_kelas_id_foreign` (`kelas_id`);

--
-- Indeks untuk tabel `school_settings`
--
ALTER TABLE `school_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subjects_kode_pelajaran_unique` (`kode_pelajaran`),
  ADD KEY `subjects_guru_id_foreign` (`guru_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `fungsionaris`
--
ALTER TABLE `fungsionaris`
  ADD CONSTRAINT `fungsionaris_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_jurusan_id_foreign` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kelas_wali_kelas_id_foreign` FOREIGN KEY (`wali_kelas_id`) REFERENCES `fungsionaris` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `fungsionaris` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
