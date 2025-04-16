/*M!999999\- enable the sandbox mode */ 
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;
DROP TABLE IF EXISTS `agamas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `agamas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_agama` int(11) NOT NULL,
  `nama_agama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agamas_id_agama_unique` (`id_agama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `aktivitas_konversi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `aktivitas_konversi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_kurikulum` varchar(255) NOT NULL,
  `nama_kurikulum` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) NOT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `id_jenis_aktivitas` varchar(255) NOT NULL,
  `nama_jenis_aktivitas` varchar(255) NOT NULL,
  `id_matkul` varchar(255) NOT NULL,
  `kode_mata_kuliah` varchar(255) DEFAULT NULL,
  `nama_mata_kuliah` varchar(255) DEFAULT NULL,
  `sks_mata_kuliah` varchar(255) DEFAULT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `penilaian_langsung` int(11) NOT NULL DEFAULT 0 COMMENT '0: Sidang, 1: Penilaian Langsung Personal, 2: Penilaian Langsung Tim',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aktivitas_konversi_id_kurikulum_foreign` (`id_kurikulum`),
  KEY `aktivitas_konversi_id_prodi_foreign` (`id_prodi`),
  KEY `aktivitas_konversi_id_matkul_foreign` (`id_matkul`),
  CONSTRAINT `aktivitas_konversi_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `list_kurikulums` (`id_kurikulum`),
  CONSTRAINT `aktivitas_konversi_id_matkul_foreign` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliahs` (`id_matkul`),
  CONSTRAINT `aktivitas_konversi_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `aktivitas_kuliah_mahasiswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `aktivitas_kuliah_mahasiswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `id_registrasi_mahasiswa` varchar(255) DEFAULT NULL,
  `nim` varchar(255) DEFAULT NULL,
  `nama_mahasiswa` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `angkatan` varchar(255) DEFAULT NULL,
  `id_periode_masuk` varchar(255) DEFAULT NULL,
  `id_semester` varchar(255) DEFAULT NULL,
  `nama_semester` varchar(255) DEFAULT NULL,
  `id_status_mahasiswa` varchar(255) DEFAULT NULL,
  `nama_status_mahasiswa` varchar(255) DEFAULT NULL,
  `ips` varchar(255) DEFAULT NULL,
  `ipk` varchar(255) DEFAULT NULL,
  `sks_semester` varchar(255) DEFAULT NULL,
  `sks_total` varchar(255) DEFAULT NULL,
  `biaya_kuliah_smt` varchar(255) DEFAULT NULL,
  `id_pembiayaan` int(11) DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id_registrasi_mahasiswa_id_semester` (`id_registrasi_mahasiswa`,`id_semester`),
  KEY `aktivitas_kuliah_mahasiswas_id_periode_masuk_foreign` (`id_periode_masuk`),
  KEY `aktivitas_kuliah_mahasiswas_id_semester_foreign` (`id_semester`),
  KEY `aktivitas_kuliah_mahasiswas_id_status_mahasiswa_foreign` (`id_status_mahasiswa`),
  KEY `aktivitas_kuliah_mahasiswas_id_pembiayaan_foreign` (`id_pembiayaan`),
  KEY `akm_feeder_prodi_semester` (`feeder`,`id_prodi`,`id_semester`),
  KEY `akm_prodi_semester` (`id_prodi`,`id_semester`),
  CONSTRAINT `aktivitas_kuliah_mahasiswas_id_pembiayaan_foreign` FOREIGN KEY (`id_pembiayaan`) REFERENCES `pembiayaans` (`id_pembiayaan`) ON DELETE SET NULL,
  CONSTRAINT `aktivitas_kuliah_mahasiswas_id_periode_masuk_foreign` FOREIGN KEY (`id_periode_masuk`) REFERENCES `semesters` (`id_semester`) ON DELETE SET NULL,
  CONSTRAINT `aktivitas_kuliah_mahasiswas_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE SET NULL,
  CONSTRAINT `aktivitas_kuliah_mahasiswas_id_registrasi_mahasiswa_foreign` FOREIGN KEY (`id_registrasi_mahasiswa`) REFERENCES `riwayat_pendidikans` (`id_registrasi_mahasiswa`) ON DELETE SET NULL,
  CONSTRAINT `aktivitas_kuliah_mahasiswas_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE SET NULL,
  CONSTRAINT `aktivitas_kuliah_mahasiswas_id_status_mahasiswa_foreign` FOREIGN KEY (`id_status_mahasiswa`) REFERENCES `status_mahasiswas` (`id_status_mahasiswa`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `aktivitas_magangs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `aktivitas_magangs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_aktivitas` varchar(255) DEFAULT NULL,
  `id_registrasi_mahasiswa` varchar(255) NOT NULL,
  `nama_mahasiswa` varchar(255) DEFAULT NULL,
  `id_semester` varchar(255) DEFAULT NULL,
  `nama_semester` varchar(255) DEFAULT NULL,
  `nama_instansi` varchar(255) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `aktivitas_magangs_id_aktivitas_unique` (`id_aktivitas`),
  KEY `aktivitas_magangs_id_registrasi_mahasiswa_foreign` (`id_registrasi_mahasiswa`),
  KEY `aktivitas_magangs_id_semester_foreign` (`id_semester`),
  CONSTRAINT `aktivitas_magangs_id_registrasi_mahasiswa_foreign` FOREIGN KEY (`id_registrasi_mahasiswa`) REFERENCES `riwayat_pendidikans` (`id_registrasi_mahasiswa`),
  CONSTRAINT `aktivitas_magangs_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `aktivitas_mahasiswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `aktivitas_mahasiswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `approve_krs` tinyint(1) NOT NULL DEFAULT 1,
  `tanggal_approve` date DEFAULT NULL,
  `approve_sidang` tinyint(1) NOT NULL DEFAULT 1,
  `alasan_pembatalan_sidang` varchar(255) DEFAULT NULL,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `submitted` tinyint(1) NOT NULL DEFAULT 1,
  `id_aktivitas` varchar(255) DEFAULT NULL,
  `program_mbkm` int(11) DEFAULT NULL,
  `nama_program_mbkm` varchar(255) DEFAULT NULL,
  `jenis_anggota` int(11) DEFAULT NULL,
  `nama_jenis_anggota` varchar(255) DEFAULT NULL,
  `id_jenis_aktivitas` int(11) DEFAULT NULL,
  `nama_jenis_aktivitas` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_prodi` varchar(255) DEFAULT NULL,
  `id_semester` varchar(255) DEFAULT NULL,
  `nama_semester` varchar(255) DEFAULT NULL,
  `judul` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `sk_tugas` varchar(255) DEFAULT NULL,
  `sumber_data` varchar(255) DEFAULT NULL,
  `tanggal_sk_tugas` date DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `untuk_kampus_merdeka` tinyint(1) DEFAULT NULL,
  `asal_data` varchar(255) DEFAULT NULL,
  `nm_asaldata` varchar(255) DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `mk_konversi` varchar(255) DEFAULT NULL,
  `sks_aktivitas` varchar(255) DEFAULT NULL,
  `jadwal_ujian` date DEFAULT NULL,
  `jadwal_jam_selesai` time DEFAULT NULL,
  `jadwal_jam_mulai` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `aktivitas_mahasiswas_id_aktivitas_unique` (`id_aktivitas`),
  KEY `aktivitas_mahasiswas_id_jenis_aktivitas_foreign` (`id_jenis_aktivitas`),
  KEY `aktivitas_mahasiswas_id_prodi_foreign` (`id_prodi`),
  KEY `idx_semester_prodi` (`id_semester`,`id_prodi`),
  CONSTRAINT `aktivitas_mahasiswas_id_jenis_aktivitas_foreign` FOREIGN KEY (`id_jenis_aktivitas`) REFERENCES `jenis_aktivitas_mahasiswas` (`id_jenis_aktivitas_mahasiswa`) ON DELETE CASCADE,
  CONSTRAINT `aktivitas_mahasiswas_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE CASCADE,
  CONSTRAINT `aktivitas_mahasiswas_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `alat_transportasis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `alat_transportasis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_alat_transportasi` int(11) NOT NULL,
  `nama_alat_transportasi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alat_transportasis_id_alat_transportasi_unique` (`id_alat_transportasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `all_pts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `all_pts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_perguruan_tinggi` varchar(255) NOT NULL,
  `kode_perguruan_tinggi` varchar(255) DEFAULT NULL,
  `nama_perguruan_tinggi` text DEFAULT NULL,
  `nama_singkat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `all_pts_id_perguruan_tinggi_unique` (`id_perguruan_tinggi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `anggota_aktivitas_mahasiswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `anggota_aktivitas_mahasiswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `id_anggota` varchar(255) DEFAULT NULL,
  `id_aktivitas` varchar(255) NOT NULL,
  `judul` text DEFAULT NULL,
  `id_registrasi_mahasiswa` varchar(255) DEFAULT NULL,
  `nim` varchar(255) DEFAULT NULL,
  `nama_mahasiswa` varchar(255) DEFAULT NULL,
  `jenis_peran` varchar(255) DEFAULT NULL,
  `nama_jenis_peran` varchar(255) DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id_anggota` (`id_anggota`),
  UNIQUE KEY `unique_id_aktivitas_id_registrasi_mahasiswa` (`id_aktivitas`,`id_registrasi_mahasiswa`),
  KEY `anggota_aktivitas_mahasiswas_id_registrasi_mahasiswa_foreign` (`id_registrasi_mahasiswa`),
  CONSTRAINT `anggota_aktivitas_mahasiswas_id_aktivitas_foreign` FOREIGN KEY (`id_aktivitas`) REFERENCES `aktivitas_mahasiswas` (`id_aktivitas`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `anggota_aktivitas_mahasiswas_id_registrasi_mahasiswa_foreign` FOREIGN KEY (`id_registrasi_mahasiswa`) REFERENCES `riwayat_pendidikans` (`id_registrasi_mahasiswa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `asistensi_akhirs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `asistensi_akhirs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_aktivitas` varchar(255) DEFAULT NULL,
  `id_dosen` varchar(255) DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `tanggal` date NOT NULL,
  `uraian` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asistensi_akhirs_id_dosen_foreign` (`id_dosen`),
  KEY `asistensi_akhirs_id_aktivitas_foreign` (`id_aktivitas`),
  CONSTRAINT `asistensi_akhirs_id_aktivitas_foreign` FOREIGN KEY (`id_aktivitas`) REFERENCES `aktivitas_mahasiswas` (`id_aktivitas`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `asistensi_akhirs_id_dosen_foreign` FOREIGN KEY (`id_dosen`) REFERENCES `biodata_dosens` (`id_dosen`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `batas_isi_krs_manual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `batas_isi_krs_manual` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_registrasi_mahasiswa` varchar(255) DEFAULT NULL,
  `nim` varchar(255) NOT NULL,
  `nama_mahasiswa` varchar(255) NOT NULL,
  `id_semester` varchar(255) DEFAULT NULL,
  `batas_isi_krs` date NOT NULL,
  `status_bayar` varchar(255) NOT NULL DEFAULT '1',
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_mahasiswa_semester` (`id_registrasi_mahasiswa`,`id_semester`),
  KEY `batas_isi_krs_manual_id_semester_foreign` (`id_semester`),
  CONSTRAINT `batas_isi_krs_manual_id_registrasi_mahasiswa_foreign` FOREIGN KEY (`id_registrasi_mahasiswa`) REFERENCES `riwayat_pendidikans` (`id_registrasi_mahasiswa`) ON DELETE SET NULL,
  CONSTRAINT `batas_isi_krs_manual_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `beasiswa_mahasiswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `beasiswa_mahasiswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_registrasi_mahasiswa` varchar(255) DEFAULT NULL,
  `nim` varchar(255) NOT NULL,
  `nama_mahasiswa` varchar(255) NOT NULL,
  `id_jenis_beasiswa` bigint(20) unsigned DEFAULT NULL,
  `id_pembiayaan` bigint(20) unsigned DEFAULT NULL,
  `tanggal_mulai_beasiswa` date NOT NULL DEFAULT '1970-01-01',
  `tanggal_akhir_beasiswa` date NOT NULL DEFAULT '1970-01-01',
  `link_sk` text DEFAULT NULL,
  `status_beasiswa` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `beasiswa_mahasiswas_id_registrasi_mahasiswa_foreign` (`id_registrasi_mahasiswa`),
  KEY `beasiswa_mahasiswas_id_jenis_beasiswa_foreign` (`id_jenis_beasiswa`),
  KEY `beasiswa_mahasiswas_id_pembiayaan_foreign` (`id_pembiayaan`),
  CONSTRAINT `beasiswa_mahasiswas_id_jenis_beasiswa_foreign` FOREIGN KEY (`id_jenis_beasiswa`) REFERENCES `jenis_beasiswa_mahasiswas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `beasiswa_mahasiswas_id_pembiayaan_foreign` FOREIGN KEY (`id_pembiayaan`) REFERENCES `pembiayaans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `beasiswa_mahasiswas_id_registrasi_mahasiswa_foreign` FOREIGN KEY (`id_registrasi_mahasiswa`) REFERENCES `riwayat_pendidikans` (`id_registrasi_mahasiswa`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bebas_pustakas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `bebas_pustakas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_registrasi_mahasiswa` varchar(255) DEFAULT NULL,
  `file_bebas_pustaka` text NOT NULL,
  `link_repo` text NOT NULL,
  `verifikator` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bebas_pustakas_id_registrasi_mahasiswa_foreign` (`id_registrasi_mahasiswa`),
  KEY `bebas_pustakas_user_id_foreign` (`user_id`),
  CONSTRAINT `bebas_pustakas_id_registrasi_mahasiswa_foreign` FOREIGN KEY (`id_registrasi_mahasiswa`) REFERENCES `riwayat_pendidikans` (`id_registrasi_mahasiswa`) ON DELETE SET NULL,
  CONSTRAINT `bebas_pustakas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bimbing_mahasiswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `bimbing_mahasiswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `approved` tinyint(1) NOT NULL DEFAULT 1,
  `approved_dosen` tinyint(1) NOT NULL DEFAULT 1,
  `alasan_pembatalan` text DEFAULT NULL,
  `id_bimbing_mahasiswa` varchar(255) DEFAULT NULL,
  `id_aktivitas` varchar(255) DEFAULT NULL,
  `judul` text DEFAULT NULL,
  `id_kategori_kegiatan` int(11) DEFAULT NULL,
  `nama_kategori_kegiatan` text DEFAULT NULL,
  `id_dosen` varchar(255) DEFAULT NULL,
  `nidn` varchar(255) DEFAULT NULL,
  `nama_dosen` varchar(255) DEFAULT NULL,
  `pembimbing_ke` int(11) DEFAULT NULL,
  `nilai_proses_bimbingan` double DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_aktivitas_bimbing` (`id_aktivitas`,`id_dosen`),
  UNIQUE KEY `bimbing_mahasiswas_id_bimbing_mahasiswa_unique` (`id_bimbing_mahasiswa`),
  KEY `bimbing_mahasiswas_id_kategori_kegiatan_foreign` (`id_kategori_kegiatan`),
  KEY `bimbing_mahasiswas_id_dosen_foreign` (`id_dosen`),
  CONSTRAINT `bimbing_mahasiswas_id_dosen_foreign` FOREIGN KEY (`id_dosen`) REFERENCES `biodata_dosens` (`id_dosen`) ON DELETE SET NULL,
  CONSTRAINT `bimbing_mahasiswas_id_kategori_kegiatan_foreign` FOREIGN KEY (`id_kategori_kegiatan`) REFERENCES `kategori_kegiatans` (`id_kategori_kegiatan`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `biodata_dosens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `biodata_dosens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_dosen` varchar(255) DEFAULT NULL,
  `nama_dosen` varchar(255) DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` varchar(255) DEFAULT NULL,
  `id_agama` int(11) DEFAULT NULL,
  `nama_agama` varchar(255) DEFAULT NULL,
  `id_status_aktif` varchar(255) DEFAULT NULL,
  `nama_status_aktif` varchar(255) DEFAULT NULL,
  `nidn` varchar(255) DEFAULT NULL,
  `nama_ibu_kandung` varchar(255) DEFAULT NULL,
  `nik` varchar(255) DEFAULT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `npwp` varchar(255) DEFAULT NULL,
  `id_jenis_sdm` varchar(255) DEFAULT NULL,
  `nama_jenis_sdm` varchar(255) DEFAULT NULL,
  `no_sk_cpns` varchar(255) DEFAULT NULL,
  `tanggal_sk_cpns` varchar(255) DEFAULT NULL,
  `no_sk_pengangkatan` varchar(255) DEFAULT NULL,
  `mulai_sk_pengangkatan` varchar(255) DEFAULT NULL,
  `id_lembaga_pengangkatan` int(11) DEFAULT NULL,
  `nama_lembaga_pengangkatan` varchar(255) DEFAULT NULL,
  `id_pangkat_golongan` varchar(255) DEFAULT NULL,
  `nama_pangkat_golongan` varchar(255) DEFAULT NULL,
  `id_sumber_gaji` varchar(255) DEFAULT NULL,
  `nama_sumber_gaji` varchar(255) DEFAULT NULL,
  `jalan` text DEFAULT NULL,
  `dusun` varchar(255) DEFAULT NULL,
  `rt` varchar(255) DEFAULT NULL,
  `rw` varchar(255) DEFAULT NULL,
  `ds_kel` varchar(255) DEFAULT NULL,
  `kode_pos` varchar(255) DEFAULT NULL,
  `id_wilayah` varchar(255) DEFAULT NULL,
  `nama_wilayah` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `handphone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status_pernikahan` varchar(255) DEFAULT NULL,
  `nama_suami_istri` varchar(255) DEFAULT NULL,
  `nip_suami_istri` varchar(255) DEFAULT NULL,
  `tanggal_mulai_pns` varchar(255) DEFAULT NULL,
  `id_pekerjaan_suami_istri` varchar(255) DEFAULT NULL,
  `nama_pekerjaan_suami_istri` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `biodata_dosens_id_dosen_unique` (`id_dosen`),
  KEY `idx_dosen` (`id_dosen`),
  KEY `idx_wilayah` (`id_wilayah`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `biodata_mahasiswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `biodata_mahasiswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `nama_mahasiswa` varchar(255) NOT NULL,
  `jenis_kelamin` enum('L','P','*') NOT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` varchar(255) DEFAULT NULL,
  `id_mahasiswa` varchar(255) NOT NULL,
  `id_agama` varchar(255) NOT NULL,
  `nama_agama` varchar(255) NOT NULL,
  `nik` varchar(255) DEFAULT NULL,
  `nisn` varchar(255) DEFAULT NULL,
  `npwp` varchar(255) DEFAULT NULL,
  `id_negara` varchar(255) NOT NULL,
  `kewarganegaraan` varchar(255) NOT NULL,
  `jalan` text DEFAULT NULL,
  `dusun` varchar(255) DEFAULT NULL,
  `rt` varchar(255) DEFAULT NULL,
  `rw` varchar(255) DEFAULT NULL,
  `kelurahan` varchar(255) NOT NULL,
  `kode_pos` varchar(255) DEFAULT NULL,
  `id_wilayah` varchar(255) NOT NULL,
  `nama_wilayah` varchar(255) NOT NULL,
  `id_jenis_tinggal` varchar(255) DEFAULT NULL,
  `nama_jenis_tinggal` varchar(255) DEFAULT NULL,
  `id_alat_transportasi` varchar(255) DEFAULT NULL,
  `nama_alat_transportasi` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `handphone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `penerima_kps` tinyint(1) NOT NULL,
  `nomor_kps` varchar(255) DEFAULT NULL,
  `nik_ayah` varchar(255) DEFAULT NULL,
  `nama_ayah` varchar(255) DEFAULT NULL,
  `tanggal_lahir_ayah` varchar(255) DEFAULT NULL,
  `id_pendidikan_ayah` varchar(255) DEFAULT NULL,
  `nama_pendidikan_ayah` varchar(255) DEFAULT NULL,
  `id_pekerjaan_ayah` varchar(255) DEFAULT NULL,
  `nama_pekerjaan_ayah` varchar(255) DEFAULT NULL,
  `id_penghasilan_ayah` varchar(255) DEFAULT NULL,
  `nama_penghasilan_ayah` varchar(255) DEFAULT NULL,
  `nik_ibu` varchar(255) DEFAULT NULL,
  `nama_ibu_kandung` varchar(255) NOT NULL,
  `tanggal_lahir_ibu` varchar(255) DEFAULT NULL,
  `id_pendidikan_ibu` varchar(255) DEFAULT NULL,
  `nama_pendidikan_ibu` varchar(255) DEFAULT NULL,
  `id_pekerjaan_ibu` varchar(255) DEFAULT NULL,
  `nama_pekerjaan_ibu` varchar(255) DEFAULT NULL,
  `id_penghasilan_ibu` varchar(255) DEFAULT NULL,
  `nama_penghasilan_ibu` varchar(255) DEFAULT NULL,
  `nama_wali` varchar(255) DEFAULT NULL,
  `tanggal_lahir_wali` varchar(255) DEFAULT NULL,
  `id_pendidikan_wali` varchar(255) DEFAULT NULL,
  `nama_pendidikan_wali` varchar(255) DEFAULT NULL,
  `id_pekerjaan_wali` varchar(255) DEFAULT NULL,
  `nama_pekerjaan_wali` varchar(255) DEFAULT NULL,
  `id_penghasilan_wali` varchar(255) DEFAULT NULL,
  `nama_penghasilan_wali` varchar(255) DEFAULT NULL,
  `id_kebutuhan_khusus_mahasiswa` varchar(255) NOT NULL,
  `nama_kebutuhan_khusus_mahasiswa` varchar(255) NOT NULL,
  `id_kebutuhan_khusus_ayah` varchar(255) NOT NULL,
  `nama_kebutuhan_khusus_ayah` varchar(255) NOT NULL,
  `id_kebutuhan_khusus_ibu` varchar(255) NOT NULL,
  `nama_kebutuhan_khusus_ibu` varchar(255) NOT NULL,
  `status_sync` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `biodata_mahasiswas_id_mahasiswa_unique` (`id_mahasiswa`),
  KEY `idx_biodata` (`id_mahasiswa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cuti_kuliahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cuti_kuliahs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_cuti` varchar(255) NOT NULL,
  `id_registrasi_mahasiswa` varchar(255) NOT NULL,
  `nim` varchar(255) NOT NULL,
  `nama_mahasiswa` varchar(255) DEFAULT NULL,
  `id_semester` varchar(255) DEFAULT NULL,
  `nama_semester` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `handphone` varchar(255) DEFAULT NULL,
  `alasan_cuti` varchar(255) NOT NULL,
  `file_pendukung` varchar(255) NOT NULL,
  `approved` int(11) NOT NULL DEFAULT 0 COMMENT '0: belum di setujui, 1: disetujui prodi, 2: disetujui fakultas, 3: disetujui univ, 9: ditolak',
  `no_sk` varchar(255) DEFAULT NULL,
  `tanggal_sk` date DEFAULT NULL,
  `alasan_pembatalan` text DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cuti_kuliahs_id_cuti_unique` (`id_cuti`),
  UNIQUE KEY `unique_mahasiswa_semester` (`id_registrasi_mahasiswa`,`id_semester`),
  KEY `cuti_kuliahs_id_semester_foreign` (`id_semester`),
  KEY `cuti_kuliahs_id_prodi_foreign` (`id_prodi`),
  CONSTRAINT `cuti_kuliahs_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `riwayat_pendidikans` (`id_prodi`) ON DELETE SET NULL,
  CONSTRAINT `cuti_kuliahs_id_registrasi_mahasiswa_foreign` FOREIGN KEY (`id_registrasi_mahasiswa`) REFERENCES `riwayat_pendidikans` (`id_registrasi_mahasiswa`),
  CONSTRAINT `cuti_kuliahs_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `data_wisuda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_wisuda` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_perguruan_tinggi` varchar(255) DEFAULT NULL,
  `id_registrasi_mahasiswa` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `tgl_masuk` date DEFAULT NULL,
  `tgl_keluar` date DEFAULT NULL,
  `lama_studi` int(11) DEFAULT NULL,
  `no_peserta_ujian` varchar(255) DEFAULT NULL,
  `sks_diakui` varchar(255) DEFAULT NULL,
  `ipk` varchar(255) DEFAULT NULL,
  `no_ijazah` varchar(255) DEFAULT NULL,
  `wisuda_ke` varchar(255) DEFAULT NULL,
  `no_sk_yudisium` varchar(255) DEFAULT NULL,
  `tgl_sk_yudisium` date DEFAULT NULL,
  `id_aktivitas` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `angkatan` varchar(255) DEFAULT NULL,
  `nim` varchar(255) DEFAULT NULL,
  `nama_mahasiswa` varchar(255) DEFAULT NULL,
  `alamat_orang_tua` varchar(255) DEFAULT NULL,
  `kosentrasi` varchar(255) DEFAULT NULL,
  `tgl_sk_pembimbing` date DEFAULT NULL,
  `no_sk_pembimbing` varchar(255) DEFAULT NULL,
  `pas_foto` varchar(255) DEFAULT NULL,
  `lokasi_kuliah` varchar(255) DEFAULT NULL,
  `abstrak_ta` text DEFAULT NULL,
  `abstrak_file` varchar(255) DEFAULT NULL,
  `approved` int(11) NOT NULL DEFAULT 0 COMMENT 'Belum Diapproved = 0, Approved Prodi = 1, Approved Fakultas = 2, Approved BAK = 3, Ditolak Prodi = 97, Ditolak Fakultas = 98, Ditolak BAK = 99',
  `alasan_pembatalan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `data_wisuda_id_registrasi_mahasiswa_unique` (`id_registrasi_mahasiswa`),
  KEY `data_wisuda_id_perguruan_tinggi_foreign` (`id_perguruan_tinggi`),
  KEY `data_wisuda_id_prodi_foreign` (`id_prodi`),
  KEY `data_wisuda_id_aktivitas_foreign` (`id_aktivitas`),
  CONSTRAINT `data_wisuda_id_aktivitas_foreign` FOREIGN KEY (`id_aktivitas`) REFERENCES `aktivitas_mahasiswas` (`id_aktivitas`) ON DELETE SET NULL,
  CONSTRAINT `data_wisuda_id_perguruan_tinggi_foreign` FOREIGN KEY (`id_perguruan_tinggi`) REFERENCES `all_pts` (`id_perguruan_tinggi`) ON DELETE SET NULL,
  CONSTRAINT `data_wisuda_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE SET NULL,
  CONSTRAINT `data_wisuda_id_registrasi_mahasiswa_foreign` FOREIGN KEY (`id_registrasi_mahasiswa`) REFERENCES `riwayat_pendidikans` (`id_registrasi_mahasiswa`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dosen_pa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dosen_pa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `FMNIM` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_registrasi_mahasiswa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip_dosen_pa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_dosen_simak` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_dosen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dosen_pengajar_kelas_kuliahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dosen_pengajar_kelas_kuliahs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `id_aktivitas_mengajar` varchar(255) DEFAULT NULL,
  `id_registrasi_dosen` varchar(255) DEFAULT NULL,
  `id_dosen` varchar(255) DEFAULT NULL,
  `urutan` int(11) DEFAULT NULL,
  `nidn` varchar(255) DEFAULT NULL,
  `nama_dosen` varchar(255) DEFAULT NULL,
  `id_kelas_kuliah` varchar(255) DEFAULT NULL,
  `nama_kelas_kuliah` varchar(255) DEFAULT NULL,
  `id_substansi` varchar(255) DEFAULT NULL,
  `sks_substansi_total` varchar(255) DEFAULT NULL,
  `rencana_minggu_pertemuan` varchar(255) DEFAULT NULL,
  `realisasi_minggu_pertemuan` varchar(255) DEFAULT NULL,
  `id_jenis_evaluasi` int(11) DEFAULT NULL,
  `nama_jenis_evaluasi` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `id_semester` varchar(255) DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dosen_pengajar_kelas_kuliahs_id_aktivitas_mengajar_unique` (`id_aktivitas_mengajar`),
  KEY `idx_registrasi_dosen` (`id_registrasi_dosen`),
  KEY `idx_dosen` (`id_dosen`),
  KEY `dosen_pengajar_kelas_kuliahs_id_jenis_evaluasi_foreign` (`id_jenis_evaluasi`),
  KEY `dosen_pengajar_kelas_kuliahs_id_prodi_foreign` (`id_prodi`),
  KEY `dosen_pengajar_kelas_kuliahs_id_semester_foreign` (`id_semester`),
  KEY `idx_kelas_kuliah` (`id_kelas_kuliah`),
  CONSTRAINT `dosen_pengajar_kelas_kuliahs_id_jenis_evaluasi_foreign` FOREIGN KEY (`id_jenis_evaluasi`) REFERENCES `jenis_evaluasis` (`id_jenis_evaluasi`) ON DELETE SET NULL,
  CONSTRAINT `dosen_pengajar_kelas_kuliahs_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE SET NULL,
  CONSTRAINT `dosen_pengajar_kelas_kuliahs_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `fakultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `fakultas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_fakultas` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gelar_lulusans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `gelar_lulusans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_prodi` varchar(255) NOT NULL,
  `gelar` varchar(255) NOT NULL,
  `gelar_panjang` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gelar_lulusans_id_prodi_foreign` (`id_prodi`),
  CONSTRAINT `gelar_lulusans_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ikatan_kerjas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ikatan_kerjas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_ikatan_kerja` varchar(255) DEFAULT NULL,
  `nama_ikatan_kerja` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ikatan_kerjas_id_ikatan_kerja_unique` (`id_ikatan_kerja`),
  KEY `idx_ikatan_kerja` (`id_ikatan_kerja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jalur_masuks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jalur_masuks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_jalur_masuk` int(11) NOT NULL,
  `nama_jalur_masuk` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jalur_masuks_id_jalur_masuk_unique` (`id_jalur_masuk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jenis_aktivitas_mahasiswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_aktivitas_mahasiswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_jenis_aktivitas_mahasiswa` int(11) NOT NULL,
  `nama_jenis_aktivitas_mahasiswa` varchar(255) NOT NULL,
  `untuk_kampus_merdeka` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jenis_aktivitas_mahasiswas_id_jenis_aktivitas_mahasiswa_unique` (`id_jenis_aktivitas_mahasiswa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jenis_beasiswa_mahasiswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_beasiswa_mahasiswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_jenis_beasiswa` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jenis_daftars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_daftars` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_jenis_daftar` int(11) NOT NULL,
  `nama_jenis_daftar` varchar(255) NOT NULL,
  `untuk_daftar_sekolah` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jenis_daftars_id_jenis_daftar_unique` (`id_jenis_daftar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jenis_evaluasis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_evaluasis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_jenis_evaluasi` int(11) NOT NULL,
  `nama_jenis_evaluasi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jenis_evaluasis_id_jenis_evaluasi_unique` (`id_jenis_evaluasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jenis_keluars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_keluars` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_jenis_keluar` varchar(255) DEFAULT NULL,
  `jenis_keluar` varchar(255) NOT NULL,
  `apa_mahasiswa` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jenis_keluars_id_jenis_keluar_unique` (`id_jenis_keluar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jenis_prestasis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_prestasis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_jenis_prestasi` int(11) NOT NULL,
  `nama_jenis_prestasi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jenis_prestasis_id_jenis_prestasi_unique` (`id_jenis_prestasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jenis_substansis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_substansis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_jenis_substansi` int(11) DEFAULT NULL,
  `nama_jenis_substansi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jenis_substansis_id_jenis_substansi_unique` (`id_jenis_substansi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jurusans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jurusans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `jurusan_id` int(11) NOT NULL,
  `nama_jurusan_id` varchar(255) DEFAULT NULL,
  `nama_jurusan_en` varchar(255) DEFAULT NULL,
  `id_fakultas` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kategori_kegiatans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori_kegiatans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_kategori_kegiatan` int(11) NOT NULL,
  `nama_kategori_kegiatan` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kategori_kegiatans_id_kategori_kegiatan_unique` (`id_kategori_kegiatan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kelas_kuliahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas_kuliahs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ruang_perkuliahan_id` bigint(20) unsigned DEFAULT NULL,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `id_kelas_kuliah` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `id_semester` varchar(255) DEFAULT NULL,
  `nama_semester` varchar(255) DEFAULT NULL,
  `id_matkul` varchar(255) DEFAULT NULL,
  `kode_mata_kuliah` varchar(255) DEFAULT NULL,
  `nama_mata_kuliah` varchar(255) DEFAULT NULL,
  `nama_kelas_kuliah` varchar(255) NOT NULL,
  `bahasan` varchar(255) DEFAULT NULL,
  `tanggal_mulai_efektif` date DEFAULT NULL,
  `tanggal_akhir_efektif` date DEFAULT NULL,
  `kapasitas` int(11) DEFAULT NULL,
  `tanggal_tutup_daftar` varchar(255) DEFAULT NULL,
  `prodi_penyelenggara` varchar(255) DEFAULT NULL,
  `perguruan_tinggi_penyelenggara` varchar(255) DEFAULT NULL,
  `mode` varchar(255) DEFAULT NULL,
  `lingkup` int(11) DEFAULT NULL,
  `apa_untuk_pditt` tinyint(1) NOT NULL DEFAULT 0,
  `jadwal_hari` varchar(255) DEFAULT NULL,
  `jadwal_jam_mulai` time DEFAULT NULL,
  `jadwal_jam_selesai` time DEFAULT NULL,
  `lokasi_ujian_id` bigint(20) unsigned DEFAULT NULL,
  `jadwal_mulai_ujian` datetime DEFAULT NULL,
  `jadwal_selesai_ujian` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kelas_kuliahs_id_kelas_kuliah_unique` (`id_kelas_kuliah`),
  KEY `kelas_kuliahs_id_prodi_foreign` (`id_prodi`),
  KEY `kelas_kuliahs_id_semester_foreign` (`id_semester`),
  KEY `kelas_kuliahs_id_matkul_foreign` (`id_matkul`),
  KEY `kelas_kuliahs_ruang_perkuliahan_id_foreign` (`ruang_perkuliahan_id`),
  KEY `kelas_kuliahs_lokasi_ujian_id_foreign` (`lokasi_ujian_id`),
  CONSTRAINT `kelas_kuliahs_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE SET NULL,
  CONSTRAINT `kelas_kuliahs_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE SET NULL,
  CONSTRAINT `kelas_kuliahs_lokasi_ujian_id_foreign` FOREIGN KEY (`lokasi_ujian_id`) REFERENCES `ruang_perkuliahans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kelas_kuliahs_ruang_perkuliahan_id_foreign` FOREIGN KEY (`ruang_perkuliahan_id`) REFERENCES `ruang_perkuliahans` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `komponen_evaluasi_kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `komponen_evaluasi_kelas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `id_komponen_evaluasi` varchar(255) DEFAULT NULL,
  `id_kelas_kuliah` varchar(255) NOT NULL,
  `id_jenis_evaluasi` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nama_inggris` varchar(255) DEFAULT NULL,
  `nomor_urut` int(11) DEFAULT NULL,
  `bobot_evaluasi` double DEFAULT NULL,
  `last_update` varchar(255) DEFAULT NULL,
  `tgl_create` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `komponen_evaluasi_kelas_id_komponen_evaluasi_unique` (`id_komponen_evaluasi`),
  KEY `komponen_evaluasi_kelas_id_kelas_kuliah_foreign` (`id_kelas_kuliah`),
  KEY `komponen_evaluasi_kelas_id_jenis_evaluasi_foreign` (`id_jenis_evaluasi`),
  CONSTRAINT `komponen_evaluasi_kelas_id_jenis_evaluasi_foreign` FOREIGN KEY (`id_jenis_evaluasi`) REFERENCES `jenis_evaluasis` (`id_jenis_evaluasi`) ON UPDATE CASCADE,
  CONSTRAINT `komponen_evaluasi_kelas_id_kelas_kuliah_foreign` FOREIGN KEY (`id_kelas_kuliah`) REFERENCES `kelas_kuliahs` (`id_kelas_kuliah`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `konversi_aktivitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `konversi_aktivitas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `id_konversi_aktivitas` varchar(255) NOT NULL,
  `id_matkul` varchar(255) NOT NULL,
  `nama_mata_kuliah` varchar(255) DEFAULT NULL,
  `id_aktivitas` varchar(255) NOT NULL,
  `judul` text DEFAULT NULL,
  `id_anggota` varchar(255) DEFAULT NULL,
  `nama_mahasiswa` varchar(255) DEFAULT NULL,
  `nim` varchar(255) DEFAULT NULL,
  `sks_mata_kuliah` double(8,2) DEFAULT NULL,
  `nilai_angka` double(8,2) DEFAULT NULL,
  `nilai_indeks` double(8,2) DEFAULT NULL,
  `nilai_huruf` varchar(255) DEFAULT NULL,
  `id_semester` varchar(255) DEFAULT NULL,
  `nama_semester` varchar(255) DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `konversi_aktivitas_id_konversi_aktivitas_unique` (`id_konversi_aktivitas`),
  KEY `konversi_aktivitas_id_matkul_foreign` (`id_matkul`),
  KEY `konversi_aktivitas_id_semester_foreign` (`id_semester`),
  KEY `konversi_aktivitas_id_aktivitas_foreign` (`id_aktivitas`),
  CONSTRAINT `konversi_aktivitas_id_aktivitas_foreign` FOREIGN KEY (`id_aktivitas`) REFERENCES `aktivitas_mahasiswas` (`id_aktivitas`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `konversi_aktivitas_id_matkul_foreign` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliahs` (`id_matkul`),
  CONSTRAINT `konversi_aktivitas_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kuisoner_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `kuisoner_answers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kuisoner_question_id` bigint(20) unsigned NOT NULL,
  `id_kelas_kuliah` varchar(255) DEFAULT NULL,
  `id_registrasi_mahasiswa` varchar(255) DEFAULT NULL,
  `nilai` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kuisoner_answers_kuisoner_question_id_foreign` (`kuisoner_question_id`),
  KEY `kuisoner_answers_id_registrasi_mahasiswa_foreign` (`id_registrasi_mahasiswa`),
  KEY `kuisoner_answers_id_kelas_kuliah_foreign` (`id_kelas_kuliah`),
  CONSTRAINT `kuisoner_answers_id_kelas_kuliah_foreign` FOREIGN KEY (`id_kelas_kuliah`) REFERENCES `kelas_kuliahs` (`id_kelas_kuliah`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kuisoner_answers_id_registrasi_mahasiswa_foreign` FOREIGN KEY (`id_registrasi_mahasiswa`) REFERENCES `riwayat_pendidikans` (`id_registrasi_mahasiswa`) ON DELETE SET NULL,
  CONSTRAINT `kuisoner_answers_kuisoner_question_id_foreign` FOREIGN KEY (`kuisoner_question_id`) REFERENCES `kuisoner_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kuisoner_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `kuisoner_questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question_indonesia` text NOT NULL,
  `question_english` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `level_wilayahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `level_wilayahs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_level_wilayah` int(11) NOT NULL,
  `nama_level_wilayah` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `level_wilayahs_id_level_wilayah_unique` (`id_level_wilayah`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `list_kurikulums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `list_kurikulums` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_jenj_didik` int(11) DEFAULT NULL,
  `jml_sem_normal` int(11) DEFAULT NULL,
  `id_kurikulum` varchar(255) DEFAULT NULL,
  `nama_kurikulum` varchar(255) NOT NULL,
  `id_prodi` varchar(255) NOT NULL,
  `nama_program_studi` varchar(255) NOT NULL,
  `id_semester` varchar(255) NOT NULL,
  `semester_mulai_berlaku` varchar(255) NOT NULL,
  `jumlah_sks_lulus` int(11) NOT NULL,
  `jumlah_sks_wajib` int(11) NOT NULL,
  `jumlah_sks_pilihan` int(11) NOT NULL,
  `jumlah_sks_mata_kuliah_wajib` double(8,2) DEFAULT NULL,
  `jumlah_sks_mata_kuliah_pilihan` double(8,2) DEFAULT NULL,
  `status_sync` varchar(255) NOT NULL,
  `sk_kurikulum` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `nilai_usept` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id_kurikulum` (`id_kurikulum`),
  KEY `list_kurikulums_id_kurikulum_index` (`id_kurikulum`),
  KEY `list_kurikulums_id_prodi_foreign` (`id_prodi`),
  KEY `list_kurikulums_id_semester_foreign` (`id_semester`),
  CONSTRAINT `list_kurikulums_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE CASCADE,
  CONSTRAINT `list_kurikulums_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `lulus_dos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lulus_dos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `id_registrasi_mahasiswa` varchar(255) NOT NULL,
  `id_mahasiswa` varchar(255) DEFAULT NULL,
  `id_perguruan_tinggi` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `tgl_masuk_sp` date DEFAULT NULL,
  `tgl_keluar` date DEFAULT NULL,
  `skhun` varchar(255) DEFAULT NULL,
  `no_peserta_ujian` varchar(255) DEFAULT NULL,
  `no_seri_ijazah` varchar(255) DEFAULT NULL,
  `tgl_create` date DEFAULT NULL,
  `sks_diakui` varchar(255) DEFAULT NULL,
  `jalur_skripsi` varchar(255) DEFAULT NULL,
  `judul_skripsi` text DEFAULT NULL,
  `bln_awal_bimbingan` varchar(255) DEFAULT NULL,
  `bln_akhir_bimbingan` varchar(255) DEFAULT NULL,
  `sk_yudisium` varchar(255) DEFAULT NULL,
  `tgl_sk_yudisium` date DEFAULT NULL,
  `ipk` varchar(255) DEFAULT NULL,
  `sert_prof` varchar(255) DEFAULT NULL,
  `a_pindah_mhs_asing` varchar(255) DEFAULT NULL,
  `id_pt_asal` varchar(255) DEFAULT NULL,
  `id_prodi_asal` varchar(255) DEFAULT NULL,
  `nm_pt_asal` varchar(255) DEFAULT NULL,
  `nm_prodi_asal` varchar(255) DEFAULT NULL,
  `id_jns_daftar` varchar(255) DEFAULT NULL,
  `id_jns_keluar` varchar(255) DEFAULT NULL,
  `id_jalur_masuk` varchar(255) DEFAULT NULL,
  `id_pembiayaan` varchar(255) DEFAULT NULL,
  `id_minat_bidang` varchar(255) DEFAULT NULL,
  `bidang_mayor` varchar(255) DEFAULT NULL,
  `bidang_minor` varchar(255) DEFAULT NULL,
  `biaya_masuk_kuliah` varchar(255) DEFAULT NULL,
  `namapt` varchar(255) DEFAULT NULL,
  `id_jur` varchar(255) DEFAULT NULL,
  `nm_jns_daftar` varchar(255) DEFAULT NULL,
  `nm_smt` varchar(255) DEFAULT NULL,
  `nim` varchar(255) DEFAULT NULL,
  `nama_mahasiswa` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `angkatan` varchar(255) DEFAULT NULL,
  `id_jenis_keluar` varchar(255) DEFAULT NULL,
  `nama_jenis_keluar` varchar(255) DEFAULT NULL,
  `tanggal_keluar` varchar(255) DEFAULT NULL,
  `id_periode_keluar` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `no_sertifikat_profesi` varchar(255) DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id_registrasi_mahasiswa` (`id_registrasi_mahasiswa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `mata_kuliahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mata_kuliahs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_matkul` varchar(255) DEFAULT NULL,
  `kode_mata_kuliah` varchar(255) NOT NULL,
  `nama_mata_kuliah` varchar(255) NOT NULL,
  `nama_mata_kuliah_english` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) NOT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `id_jenis_mata_kuliah` varchar(255) DEFAULT NULL,
  `id_kelompok_mata_kuliah` varchar(255) DEFAULT NULL,
  `sks_mata_kuliah` double(8,2) NOT NULL,
  `sks_tatap_muka` double(8,2) DEFAULT NULL,
  `sks_praktek` double(8,2) DEFAULT NULL,
  `sks_praktek_lapangan` double(8,2) DEFAULT NULL,
  `sks_simulasi` double(8,2) DEFAULT NULL,
  `metode_kuliah` varchar(255) DEFAULT NULL,
  `ada_sap` tinyint(1) DEFAULT NULL,
  `ada_silabus` tinyint(1) DEFAULT NULL,
  `ada_bahan_ajar` tinyint(1) DEFAULT NULL,
  `ada_acara_praktek` tinyint(1) DEFAULT NULL,
  `ada_diktat` tinyint(1) DEFAULT NULL,
  `tanggal_mulai_efektif` date DEFAULT NULL,
  `tanggal_selesai_efektif` date DEFAULT NULL,
  `link_rps` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mata_kuliahs_id_matkul_unique` (`id_matkul`),
  KEY `mata_kuliahs_id_matkul_index` (`id_matkul`),
  KEY `mata_kuliahs_id_prodi_index` (`id_prodi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `matkul_kurikulums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `matkul_kurikulums` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tgl_create` varchar(255) DEFAULT NULL,
  `id_kurikulum` varchar(255) NOT NULL,
  `nama_kurikulum` varchar(255) DEFAULT NULL,
  `id_matkul` varchar(255) DEFAULT NULL,
  `kode_mata_kuliah` varchar(255) DEFAULT NULL,
  `nama_mata_kuliah` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `id_semester` varchar(255) DEFAULT NULL,
  `semester_mulai_berlaku` varchar(255) DEFAULT NULL,
  `sks_mata_kuliah` double(8,2) DEFAULT NULL,
  `sks_tatap_muka` double(8,2) DEFAULT NULL,
  `sks_praktek` double(8,2) DEFAULT NULL,
  `sks_praktek_lapangan` double(8,2) DEFAULT NULL,
  `sks_simulasi` double(8,2) DEFAULT NULL,
  `apakah_wajib` tinyint(1) DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_matkul_kurikulum` (`id_kurikulum`,`id_matkul`),
  KEY `matkul_kurikulums_id_matkul_foreign` (`id_matkul`),
  KEY `matkul_kurikulums_id_semester_foreign` (`id_semester`),
  KEY `idx_prodi_semester` (`id_prodi`,`id_semester`),
  CONSTRAINT `matkul_kurikulums_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `list_kurikulums` (`id_kurikulum`) ON DELETE CASCADE,
  CONSTRAINT `matkul_kurikulums_id_matkul_foreign` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliahs` (`id_matkul`) ON DELETE CASCADE,
  CONSTRAINT `matkul_kurikulums_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE CASCADE,
  CONSTRAINT `matkul_kurikulums_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `matkul_merdekas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `matkul_merdekas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_prodi` varchar(255) NOT NULL,
  `id_matkul` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `matkul_merdekas_id_matkul_unique` (`id_matkul`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `monev_status_mahasiswa_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `monev_status_mahasiswa_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `monev_status_mahasiswa_id` bigint(20) unsigned NOT NULL,
  `status` varchar(255) NOT NULL,
  `id_registrasi_mahasiswa` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `monev_status_mahasiswa_details_monev_status_mahasiswa_id_foreign` (`monev_status_mahasiswa_id`),
  KEY `monev_status_mahasiswa_details_id_registrasi_mahasiswa_foreign` (`id_registrasi_mahasiswa`),
  CONSTRAINT `monev_status_mahasiswa_details_id_registrasi_mahasiswa_foreign` FOREIGN KEY (`id_registrasi_mahasiswa`) REFERENCES `riwayat_pendidikans` (`id_registrasi_mahasiswa`) ON DELETE CASCADE,
  CONSTRAINT `monev_status_mahasiswa_details_monev_status_mahasiswa_id_foreign` FOREIGN KEY (`monev_status_mahasiswa_id`) REFERENCES `monev_status_mahasiswas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `monev_status_mahasiswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `monev_status_mahasiswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_semester` varchar(255) NOT NULL,
  `id_prodi` varchar(255) NOT NULL,
  `mahasiswa_lewat_semester` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id_semester_prodi` (`id_semester`,`id_prodi`),
  KEY `idx_prodi` (`id_prodi`),
  CONSTRAINT `monev_status_mahasiswas_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `monitoring_isi_krs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `monitoring_isi_krs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_semester` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) NOT NULL,
  `mahasiswa_aktif` int(11) NOT NULL DEFAULT 0,
  `mahasiswa_aktif_min_7` int(11) NOT NULL DEFAULT 0,
  `isi_krs` int(11) NOT NULL DEFAULT 0,
  `krs_approved` int(11) NOT NULL DEFAULT 0,
  `krs_not_approved` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `monitoring_isi_krs_id_prodi_id_semester_unique` (`id_prodi`,`id_semester`),
  KEY `monitoring_isi_krs_id_semester_foreign` (`id_semester`),
  CONSTRAINT `monitoring_isi_krs_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `negaras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `negaras` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_negara` varchar(255) NOT NULL,
  `nama_negara` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `negaras_id_negara_unique` (`id_negara`),
  KEY `idx_negara` (`id_negara`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `nilai_komponen_evaluasis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai_komponen_evaluasis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_registrasi_mahasiswa` varchar(255) NOT NULL,
  `id_komponen_evaluasi` varchar(255) NOT NULL,
  `nilai_komp_eval` double(8,2) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `id_periode` varchar(255) DEFAULT NULL,
  `id_matkul` varchar(255) DEFAULT NULL,
  `nama_mata_kuliah` varchar(255) DEFAULT NULL,
  `id_kelas` varchar(255) DEFAULT NULL,
  `nama_kelas_kuliah` varchar(255) DEFAULT NULL,
  `sks_mata_kuliah` double(8,2) DEFAULT NULL,
  `nim` varchar(255) DEFAULT NULL,
  `nama_mahasiswa` varchar(255) DEFAULT NULL,
  `id_jns_eval` int(11) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nama_inggris` varchar(255) DEFAULT NULL,
  `urutan` int(11) DEFAULT NULL,
  `bobot` decimal(8,2) DEFAULT NULL,
  `angkatan` varchar(255) DEFAULT NULL,
  `status_sync` varchar(255) NOT NULL,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nilai_komponen` (`id_registrasi_mahasiswa`,`id_komponen_evaluasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `nilai_perkuliahans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai_perkuliahans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `id_semester` varchar(255) DEFAULT NULL,
  `nama_semester` varchar(255) DEFAULT NULL,
  `id_matkul` varchar(255) DEFAULT NULL,
  `kode_mata_kuliah` varchar(255) DEFAULT NULL,
  `nama_mata_kuliah` varchar(255) DEFAULT NULL,
  `sks_mata_kuliah` int(11) DEFAULT NULL,
  `id_kelas_kuliah` varchar(255) DEFAULT NULL,
  `nama_kelas_kuliah` varchar(255) DEFAULT NULL,
  `id_registrasi_mahasiswa` varchar(255) DEFAULT NULL,
  `id_mahasiswa` varchar(255) DEFAULT NULL,
  `nim` varchar(255) DEFAULT NULL,
  `nama_mahasiswa` varchar(255) DEFAULT NULL,
  `jurusan` varchar(255) DEFAULT NULL,
  `angkatan` varchar(255) DEFAULT NULL,
  `nilai_angka` double(8,1) DEFAULT NULL,
  `nilai_indeks` double(8,2) DEFAULT NULL,
  `nilai_huruf` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_kelas_registrasi` (`id_kelas_kuliah`,`id_registrasi_mahasiswa`),
  KEY `nilai_perkuliahans_id_prodi_foreign` (`id_prodi`),
  KEY `nilai_perkuliahans_id_semester_foreign` (`id_semester`),
  KEY `idx_matkul` (`id_matkul`),
  KEY `idx_kelas_kuliah` (`id_kelas_kuliah`),
  KEY `nilai_perkuliahans_id_registrasi_mahasiswa_foreign` (`id_registrasi_mahasiswa`),
  CONSTRAINT `nilai_perkuliahans_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE CASCADE,
  CONSTRAINT `nilai_perkuliahans_id_registrasi_mahasiswa_foreign` FOREIGN KEY (`id_registrasi_mahasiswa`) REFERENCES `riwayat_pendidikans` (`id_registrasi_mahasiswa`) ON DELETE CASCADE,
  CONSTRAINT `nilai_perkuliahans_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `nilai_sidang_mahasiswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai_sidang_mahasiswa` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `approved_prodi` varchar(255) NOT NULL DEFAULT '0',
  `id_aktivitas` varchar(255) DEFAULT NULL,
  `id_dosen` varchar(255) DEFAULT NULL,
  `id_kategori_kegiatan` varchar(255) DEFAULT NULL,
  `nilai_kualitas_skripsi` double NOT NULL,
  `nilai_presentasi_dan_diskusi` double NOT NULL,
  `nilai_performansi` double NOT NULL,
  `nilai_akhir_dosen` double DEFAULT NULL,
  `tanggal_penilaian_sidang` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nilai_sidang_mahasiswa_id_dosen_foreign` (`id_dosen`),
  KEY `nilai_sidang_mahasiswa_id_aktivitas_foreign` (`id_aktivitas`),
  CONSTRAINT `nilai_sidang_mahasiswa_id_aktivitas_foreign` FOREIGN KEY (`id_aktivitas`) REFERENCES `aktivitas_mahasiswas` (`id_aktivitas`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nilai_sidang_mahasiswa_id_dosen_foreign` FOREIGN KEY (`id_dosen`) REFERENCES `biodata_dosens` (`id_dosen`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `nilai_transfer_pendidikans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai_transfer_pendidikans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `id_transfer` varchar(255) NOT NULL,
  `id_registrasi_mahasiswa` varchar(255) NOT NULL,
  `nim` varchar(255) DEFAULT NULL,
  `nama_mahasiswa` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) NOT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `id_periode_masuk` varchar(255) NOT NULL,
  `kode_mata_kuliah_asal` varchar(255) DEFAULT NULL,
  `nama_mata_kuliah_asal` varchar(255) DEFAULT NULL,
  `sks_mata_kuliah_asal` int(11) DEFAULT NULL,
  `nilai_huruf_asal` varchar(255) DEFAULT NULL,
  `id_matkul` varchar(255) NOT NULL,
  `kode_matkul_diakui` varchar(255) DEFAULT NULL,
  `nama_mata_kuliah_diakui` varchar(255) DEFAULT NULL,
  `sks_mata_kuliah_diakui` int(11) DEFAULT NULL,
  `nilai_huruf_diakui` varchar(255) DEFAULT NULL,
  `nilai_angka_diakui` double(8,2) DEFAULT NULL,
  `id_perguruan_tinggi` varchar(255) DEFAULT NULL,
  `id_aktivitas` varchar(255) DEFAULT NULL,
  `judul` text DEFAULT NULL,
  `id_jenis_aktivitas` int(11) DEFAULT NULL,
  `nama_jenis_aktivitas` varchar(255) DEFAULT NULL,
  `id_semester` varchar(255) DEFAULT NULL,
  `nama_semester` varchar(255) DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nilai_transfer_pendidikans_id_transfer_unique` (`id_transfer`),
  KEY `nilai_transfer_pendidikans_id_registrasi_mahasiswa_foreign` (`id_registrasi_mahasiswa`),
  KEY `nilai_transfer_pendidikans_id_prodi_foreign` (`id_prodi`),
  KEY `nilai_transfer_pendidikans_id_periode_masuk_foreign` (`id_periode_masuk`),
  KEY `nilai_transfer_pendidikans_id_jenis_aktivitas_foreign` (`id_jenis_aktivitas`),
  KEY `nilai_transfer_pendidikans_id_semester_foreign` (`id_semester`),
  KEY `idx_matkul` (`id_matkul`),
  KEY `nilai_transfer_pendidikans_id_aktivitas_foreign` (`id_aktivitas`),
  CONSTRAINT `nilai_transfer_pendidikans_id_aktivitas_foreign` FOREIGN KEY (`id_aktivitas`) REFERENCES `aktivitas_mahasiswas` (`id_aktivitas`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nilai_transfer_pendidikans_id_jenis_aktivitas_foreign` FOREIGN KEY (`id_jenis_aktivitas`) REFERENCES `jenis_aktivitas_mahasiswas` (`id_jenis_aktivitas_mahasiswa`) ON DELETE CASCADE,
  CONSTRAINT `nilai_transfer_pendidikans_id_periode_masuk_foreign` FOREIGN KEY (`id_periode_masuk`) REFERENCES `semesters` (`id_semester`) ON DELETE CASCADE,
  CONSTRAINT `nilai_transfer_pendidikans_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE CASCADE,
  CONSTRAINT `nilai_transfer_pendidikans_id_registrasi_mahasiswa_foreign` FOREIGN KEY (`id_registrasi_mahasiswa`) REFERENCES `riwayat_pendidikans` (`id_registrasi_mahasiswa`) ON DELETE CASCADE,
  CONSTRAINT `nilai_transfer_pendidikans_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `notulensi_sidang_mahasiswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notulensi_sidang_mahasiswa` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_aktivitas` varchar(255) DEFAULT NULL,
  `id_dosen` varchar(255) DEFAULT NULL,
  `lokasi` varchar(255) NOT NULL,
  `tanggal_sidang` date NOT NULL,
  `jam_mulai_sidang` time NOT NULL,
  `jam_selesai_sidang` time NOT NULL,
  `jam_mulai_presentasi` time NOT NULL,
  `jam_selesai_presentasi` time NOT NULL,
  `uraian` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notulensi_sidang_mahasiswa_id_dosen_foreign` (`id_dosen`),
  KEY `notulensi_sidang_mahasiswa_id_aktivitas_foreign` (`id_aktivitas`),
  CONSTRAINT `notulensi_sidang_mahasiswa_id_aktivitas_foreign` FOREIGN KEY (`id_aktivitas`) REFERENCES `aktivitas_mahasiswas` (`id_aktivitas`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `notulensi_sidang_mahasiswa_id_dosen_foreign` FOREIGN KEY (`id_dosen`) REFERENCES `biodata_dosens` (`id_dosen`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pejabat_fakultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pejabat_fakultas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_registrasi_dosen` varchar(255) NOT NULL,
  `id_dosen` varchar(255) NOT NULL,
  `id_jabatan` varchar(255) NOT NULL,
  `nama_jabatan` varchar(255) NOT NULL,
  `nidn` varchar(255) DEFAULT NULL,
  `nama_dosen` varchar(255) DEFAULT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `gelar_depan` varchar(255) DEFAULT NULL,
  `gelar_belakang` varchar(255) DEFAULT NULL,
  `id_fakultas` bigint(20) unsigned NOT NULL,
  `nama_fakultas` varchar(255) DEFAULT NULL,
  `tgl_mulai_jabatan` date NOT NULL,
  `tgl_selesai_jabatan` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_jabatan_fakultas` (`id_jabatan`,`id_fakultas`),
  KEY `idx_registrasi_dosen` (`id_registrasi_dosen`),
  KEY `pejabat_fakultas_id_dosen_foreign` (`id_dosen`),
  KEY `pejabat_fakultas_id_fakultas_foreign` (`id_fakultas`),
  CONSTRAINT `pejabat_fakultas_id_dosen_foreign` FOREIGN KEY (`id_dosen`) REFERENCES `biodata_dosens` (`id_dosen`) ON DELETE CASCADE,
  CONSTRAINT `pejabat_fakultas_id_fakultas_foreign` FOREIGN KEY (`id_fakultas`) REFERENCES `fakultas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pejabat_universitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pejabat_universitas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `jabatan_id` bigint(20) unsigned NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `gelar_depan` varchar(255) DEFAULT NULL,
  `gelar_belakang` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pejabat_universitas_jabatan_id_foreign` (`jabatan_id`),
  CONSTRAINT `pejabat_universitas_jabatan_id_foreign` FOREIGN KEY (`jabatan_id`) REFERENCES `pejabat_universitas_jabatans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pejabat_universitas_jabatans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pejabat_universitas_jabatans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pekerjaans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pekerjaans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_pekerjaan` int(11) NOT NULL,
  `nama_pekerjaan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pekerjaans_id_pekerjaan_unique` (`id_pekerjaan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pembayaran_manual_mahasiswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembayaran_manual_mahasiswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_registrasi_mahasiswa` varchar(255) DEFAULT NULL,
  `nim` varchar(255) NOT NULL,
  `id_semester` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `tanggal_pembayaran` date DEFAULT NULL,
  `nominal_ukt` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembayaran_manual_mahasiswas_id_semester_foreign` (`id_semester`),
  KEY `idx_id_reg_id_semester` (`id_registrasi_mahasiswa`,`id_semester`),
  CONSTRAINT `pembayaran_manual_mahasiswas_id_registrasi_mahasiswa_foreign` FOREIGN KEY (`id_registrasi_mahasiswa`) REFERENCES `riwayat_pendidikans` (`id_registrasi_mahasiswa`) ON DELETE SET NULL,
  CONSTRAINT `pembayaran_manual_mahasiswas_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pembiayaans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembiayaans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_pembiayaan` int(11) NOT NULL,
  `nama_pembiayaan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pembiayaans_id_pembiayaan_unique` (`id_pembiayaan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `penugasan_dosens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `penugasan_dosens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_registrasi_dosen` varchar(255) DEFAULT NULL,
  `jk` enum('L','P') DEFAULT NULL,
  `id_dosen` varchar(255) DEFAULT NULL,
  `nama_dosen` varchar(255) DEFAULT NULL,
  `nidn` varchar(255) DEFAULT NULL,
  `id_tahun_ajaran` varchar(255) DEFAULT NULL,
  `nama_tahun_ajaran` varchar(255) DEFAULT NULL,
  `id_perguruan_tinggi` varchar(255) DEFAULT NULL,
  `nama_perguruan_tinggi` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `nomor_surat_tugas` varchar(255) DEFAULT NULL,
  `tanggal_surat_tugas` varchar(255) DEFAULT NULL,
  `mulai_surat_tugas` varchar(255) DEFAULT NULL,
  `tgl_create` varchar(255) DEFAULT NULL,
  `tgl_ptk_keluar` varchar(255) DEFAULT NULL,
  `id_stat_pegawai` varchar(255) DEFAULT NULL,
  `id_jns_keluar` varchar(255) DEFAULT NULL,
  `id_ikatan_kerja` varchar(255) DEFAULT NULL,
  `a_sp_homebase` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `penugasan_dosens_id_tahun_ajaran_id_registrasi_dosen_unique` (`id_tahun_ajaran`,`id_registrasi_dosen`),
  KEY `penugasan_dosens_id_dosen_foreign` (`id_dosen`),
  KEY `idx_prodi_tahun_ajaran_dosen` (`id_prodi`,`id_tahun_ajaran`,`id_dosen`),
  CONSTRAINT `penugasan_dosens_id_dosen_foreign` FOREIGN KEY (`id_dosen`) REFERENCES `biodata_dosens` (`id_dosen`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `penundaan_bayars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `penundaan_bayars` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_registrasi_mahasiswa` varchar(255) DEFAULT NULL,
  `nim` varchar(255) NOT NULL,
  `id_semester` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `file_pendukung` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0: Diajukan, 2: Disetujui Prodi, 3: Disetujui Fakultas, 4: Disetujui BAK, 5: Ditolak',
  `alasan_pembatalan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penundaan_bayars_id_registrasi_mahasiswa_foreign` (`id_registrasi_mahasiswa`),
  KEY `penundaan_bayars_id_semester_foreign` (`id_semester`),
  CONSTRAINT `penundaan_bayars_id_registrasi_mahasiswa_foreign` FOREIGN KEY (`id_registrasi_mahasiswa`) REFERENCES `riwayat_pendidikans` (`id_registrasi_mahasiswa`) ON DELETE SET NULL,
  CONSTRAINT `penundaan_bayars_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `periode_perkuliahans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `periode_perkuliahans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `id_semester` varchar(255) DEFAULT NULL,
  `nama_semester` varchar(255) DEFAULT NULL,
  `jumlah_target_mahasiswa_baru` int(11) DEFAULT NULL,
  `jumlah_pendaftar_ikut_seleksi` int(11) DEFAULT NULL,
  `jumlah_pendaftar_lulus_seleksi` int(11) DEFAULT NULL,
  `jumlah_daftar_ulang` int(11) DEFAULT NULL,
  `jumlah_mengundurkan_diri` int(11) DEFAULT NULL,
  `tanggal_awal_perkuliahan` date DEFAULT NULL,
  `tanggal_akhir_perkuliahan` date DEFAULT NULL,
  `jumlah_minggu_pertemuan` int(11) DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id_prodi_id_semester` (`id_prodi`,`id_semester`),
  KEY `periode_perkuliahans_id_semester_foreign` (`id_semester`),
  CONSTRAINT `periode_perkuliahans_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE CASCADE,
  CONSTRAINT `periode_perkuliahans_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `periode_wisudas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `periode_wisudas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `periode` int(11) NOT NULL,
  `tanggal_wisuda` date NOT NULL,
  `tanggal_mulai_daftar` date NOT NULL,
  `tanggal_akhir_daftar` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `periode_wisudas_periode_unique` (`periode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `peserta_kelas_kuliahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `peserta_kelas_kuliahs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `submitted` tinyint(1) NOT NULL DEFAULT 1,
  `approved` tinyint(1) NOT NULL DEFAULT 1,
  `tanggal_approve` date DEFAULT NULL,
  `id_kelas_kuliah` varchar(255) DEFAULT NULL,
  `nama_kelas_kuliah` varchar(255) DEFAULT NULL,
  `id_registrasi_mahasiswa` varchar(255) DEFAULT NULL,
  `id_mahasiswa` varchar(255) DEFAULT NULL,
  `nim` varchar(255) DEFAULT NULL,
  `nama_mahasiswa` varchar(255) DEFAULT NULL,
  `id_matkul` varchar(255) DEFAULT NULL,
  `kode_mata_kuliah` varchar(255) DEFAULT NULL,
  `nama_mata_kuliah` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `angkatan` varchar(255) DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_peserta_kelas_kuliah` (`id_kelas_kuliah`,`id_registrasi_mahasiswa`),
  KEY `peserta_kelas_kuliahs_id_registrasi_mahasiswa_foreign` (`id_registrasi_mahasiswa`),
  KEY `idx_mahasiswa` (`id_mahasiswa`),
  KEY `peserta_kelas_kuliahs_id_prodi_foreign` (`id_prodi`),
  KEY `peserta_kelas_kuliahs_id_kelas_kuliah_index` (`id_kelas_kuliah`),
  KEY `peserta_kelas_kuliahs_id_matkul_foreign` (`id_matkul`),
  CONSTRAINT `peserta_kelas_kuliahs_id_matkul_foreign` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliahs` (`id_matkul`) ON DELETE CASCADE,
  CONSTRAINT `peserta_kelas_kuliahs_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE SET NULL,
  CONSTRAINT `peserta_kelas_kuliahs_id_registrasi_mahasiswa_foreign` FOREIGN KEY (`id_registrasi_mahasiswa`) REFERENCES `riwayat_pendidikans` (`id_registrasi_mahasiswa`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `prasyarat_matkuls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `prasyarat_matkuls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_matkul` varchar(255) NOT NULL,
  `id_matkul_prasyarat` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prasyarat_matkuls_id_matkul_id_matkul_prasyarat_unique` (`id_matkul`,`id_matkul_prasyarat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `prestasi_mahasiswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `prestasi_mahasiswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_prestasi` varchar(255) DEFAULT NULL,
  `id_mahasiswa` varchar(255) DEFAULT NULL,
  `nama_mahasiswa` varchar(255) DEFAULT NULL,
  `id_jenis_prestasi` int(11) DEFAULT NULL,
  `nama_jenis_prestasi` varchar(255) DEFAULT NULL,
  `id_tingkat_prestasi` int(11) DEFAULT NULL,
  `nama_tingkat_prestasi` varchar(255) DEFAULT NULL,
  `nama_prestasi` varchar(255) DEFAULT NULL,
  `tahun_prestasi` varchar(255) DEFAULT NULL,
  `penyelenggara` varchar(255) DEFAULT NULL,
  `peringkat` varchar(255) DEFAULT NULL,
  `id_aktivitas` varchar(255) DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prestasi_mahasiswas_id_prestasi_unique` (`id_prestasi`),
  KEY `prestasi_mahasiswas_id_mahasiswa_foreign` (`id_mahasiswa`),
  KEY `prestasi_mahasiswas_id_jenis_prestasi_foreign` (`id_jenis_prestasi`),
  KEY `prestasi_mahasiswas_id_tingkat_prestasi_foreign` (`id_tingkat_prestasi`),
  CONSTRAINT `prestasi_mahasiswas_id_jenis_prestasi_foreign` FOREIGN KEY (`id_jenis_prestasi`) REFERENCES `jenis_prestasis` (`id_jenis_prestasi`),
  CONSTRAINT `prestasi_mahasiswas_id_mahasiswa_foreign` FOREIGN KEY (`id_mahasiswa`) REFERENCES `biodata_mahasiswas` (`id_mahasiswa`),
  CONSTRAINT `prestasi_mahasiswas_id_tingkat_prestasi_foreign` FOREIGN KEY (`id_tingkat_prestasi`) REFERENCES `tingkat_prestasis` (`id_tingkat_prestasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `profil_pts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `profil_pts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_perguruan_tinggi` varchar(255) NOT NULL,
  `kode_perguruan_tinggi` varchar(255) DEFAULT NULL,
  `nama_perguruan_tinggi` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `faximile` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `jalan` varchar(255) DEFAULT NULL,
  `dusun` varchar(255) DEFAULT NULL,
  `rt_rw` varchar(255) DEFAULT NULL,
  `kelurahan` varchar(255) DEFAULT NULL,
  `kode_pos` varchar(255) DEFAULT NULL,
  `id_wilayah` varchar(255) DEFAULT NULL,
  `nama_wilayah` varchar(255) DEFAULT NULL,
  `lintang_bujur` varchar(255) DEFAULT NULL,
  `bank` varchar(255) DEFAULT NULL,
  `unit_cabang` varchar(255) DEFAULT NULL,
  `nomor_rekening` varchar(255) DEFAULT NULL,
  `mbs` varchar(255) DEFAULT NULL,
  `luas_tanah_milik` varchar(255) DEFAULT NULL,
  `luas_tanah_bukan_milik` varchar(255) DEFAULT NULL,
  `sk_pendirian` varchar(255) DEFAULT NULL,
  `tanggal_sk_pendirian` varchar(255) DEFAULT NULL,
  `id_status_milik` varchar(255) DEFAULT NULL,
  `nama_status_milik` varchar(255) DEFAULT NULL,
  `status_perguruan_tinggi` varchar(255) DEFAULT NULL,
  `sk_izin_operasional` varchar(255) DEFAULT NULL,
  `tanggal_izin_operasional` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profil_pts_id_perguruan_tinggi_unique` (`id_perguruan_tinggi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `program_studis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `program_studis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_jurusan` int(11) DEFAULT NULL,
  `id_prodi` varchar(255) NOT NULL,
  `nama_program_studi` varchar(255) NOT NULL,
  `kode_program_studi` varchar(15) NOT NULL,
  `status` varchar(255) NOT NULL,
  `id_jenjang_pendidikan` varchar(255) NOT NULL,
  `nama_jenjang_pendidikan` varchar(255) NOT NULL,
  `fakultas_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `program_studis_fakultas_id_foreign` (`fakultas_id`),
  KEY `idx_prodi` (`id_prodi`),
  CONSTRAINT `program_studis_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `rencana_evaluasis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rencana_evaluasis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_rencana_evaluasi` varchar(255) DEFAULT NULL,
  `id_jenis_evaluasi` int(11) DEFAULT NULL,
  `jenis_evaluasi` varchar(255) DEFAULT NULL,
  `id_matkul` varchar(255) DEFAULT NULL,
  `nama_mata_kuliah` varchar(255) DEFAULT NULL,
  `kode_mata_kuliah` varchar(255) DEFAULT NULL,
  `sks_mata_kuliah` double(10,2) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `nama_evaluasi` varchar(255) DEFAULT NULL,
  `deskripsi_indonesia` text DEFAULT NULL,
  `deskrips_inggris` text DEFAULT NULL,
  `nomor_urut` int(11) DEFAULT NULL,
  `bobot_evaluasi` double(10,4) DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rencana_evaluasis_id_rencana_evaluasi_unique` (`id_rencana_evaluasi`),
  KEY `rencana_evaluasis_id_jenis_evaluasi_foreign` (`id_jenis_evaluasi`),
  KEY `rencana_evaluasis_id_matkul_foreign` (`id_matkul`),
  KEY `rencana_evaluasis_id_prodi_foreign` (`id_prodi`),
  CONSTRAINT `rencana_evaluasis_id_jenis_evaluasi_foreign` FOREIGN KEY (`id_jenis_evaluasi`) REFERENCES `jenis_evaluasis` (`id_jenis_evaluasi`) ON DELETE CASCADE,
  CONSTRAINT `rencana_evaluasis_id_matkul_foreign` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliahs` (`id_matkul`) ON DELETE CASCADE,
  CONSTRAINT `rencana_evaluasis_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `rencana_pembelajarans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rencana_pembelajarans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `id_rencana_ajar` varchar(255) DEFAULT NULL,
  `id_matkul` varchar(255) NOT NULL,
  `nama_mata_kuliah` varchar(255) DEFAULT NULL,
  `kode_mata_kuliah` varchar(255) DEFAULT NULL,
  `sks_mata_kuliah` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `pertemuan` int(11) DEFAULT NULL,
  `materi_indonesia` text DEFAULT NULL,
  `materi_inggris` text DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rencana_pembelajarans_id_rencana_ajar_unique` (`id_rencana_ajar`),
  KEY `rencana_pembelajarans_id_matkul_foreign` (`id_matkul`),
  KEY `rencana_pembelajarans_id_prodi_foreign` (`id_prodi`),
  CONSTRAINT `rencana_pembelajarans_id_matkul_foreign` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliahs` (`id_matkul`) ON DELETE CASCADE,
  CONSTRAINT `rencana_pembelajarans_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `revisi_sidang_mahasiswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `revisi_sidang_mahasiswa` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_aktivitas` varchar(255) DEFAULT NULL,
  `id_dosen` varchar(255) DEFAULT NULL,
  `approved` varchar(255) NOT NULL DEFAULT '0',
  `tanggal_batas_revisi` date NOT NULL,
  `uraian` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `revisi_sidang_mahasiswa_id_dosen_foreign` (`id_dosen`),
  KEY `revisi_sidang_mahasiswa_id_aktivitas_foreign` (`id_aktivitas`),
  CONSTRAINT `revisi_sidang_mahasiswa_id_aktivitas_foreign` FOREIGN KEY (`id_aktivitas`) REFERENCES `aktivitas_mahasiswas` (`id_aktivitas`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `revisi_sidang_mahasiswa_id_dosen_foreign` FOREIGN KEY (`id_dosen`) REFERENCES `biodata_dosens` (`id_dosen`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `riwayat_fungsional_dosens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `riwayat_fungsional_dosens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_dosen` varchar(255) NOT NULL,
  `nidn` varchar(255) DEFAULT NULL,
  `nama_dosen` varchar(255) DEFAULT NULL,
  `id_jabatan_fungsional` varchar(255) NOT NULL,
  `nama_jabatan_fungsional` varchar(255) DEFAULT NULL,
  `sk_jabatan_fungsional` varchar(255) DEFAULT NULL,
  `mulai_sk_jabatan` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_id_dosen_id_jabatan_fungsional` (`id_dosen`,`id_jabatan_fungsional`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `riwayat_pendidikan_dosens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `riwayat_pendidikan_dosens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_dosen` varchar(255) DEFAULT NULL,
  `nidn` varchar(255) DEFAULT NULL,
  `nama_dosen` varchar(255) DEFAULT NULL,
  `id_bidang_studi` int(11) DEFAULT NULL,
  `nama_bidang_studi` text DEFAULT NULL,
  `id_jenjang_pendidikan` varchar(255) DEFAULT NULL,
  `nama_jenjang_pendidikan` varchar(255) DEFAULT NULL,
  `id_gelar_akademik` int(11) DEFAULT NULL,
  `nama_gelar_akademik` varchar(255) DEFAULT NULL,
  `id_perguruan_tinggi` varchar(255) DEFAULT NULL,
  `nama_perguruan_tinggi` text DEFAULT NULL,
  `fakultas` varchar(255) DEFAULT NULL,
  `tahun_lulus` varchar(255) DEFAULT NULL,
  `sks_lulus` varchar(255) DEFAULT NULL,
  `ipk` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_id_dosen_id_jenjang_pendidikan` (`id_dosen`,`id_jenjang_pendidikan`,`id_bidang_studi`),
  KEY `idx_id_dosen` (`id_dosen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `riwayat_pendidikans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `riwayat_pendidikans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_registrasi_mahasiswa` varchar(255) NOT NULL,
  `id_mahasiswa` varchar(255) NOT NULL,
  `nim` varchar(255) NOT NULL,
  `nama_mahasiswa` varchar(255) NOT NULL,
  `id_jenis_daftar` varchar(255) NOT NULL,
  `nama_jenis_daftar` varchar(255) NOT NULL,
  `id_jalur_daftar` varchar(255) DEFAULT NULL,
  `id_periode_masuk` varchar(255) NOT NULL,
  `nama_periode_masuk` varchar(255) DEFAULT NULL,
  `id_jenis_keluar` varchar(255) DEFAULT NULL,
  `keterangan_keluar` varchar(255) DEFAULT NULL,
  `id_perguruan_tinggi` varchar(255) NOT NULL,
  `nama_perguruan_tinggi` varchar(255) NOT NULL,
  `id_prodi` varchar(255) NOT NULL,
  `nama_program_studi` varchar(255) NOT NULL,
  `sks_diakui` varchar(255) DEFAULT NULL,
  `id_perguruan_tinggi_asal` varchar(255) DEFAULT NULL,
  `nama_perguruan_tinggi_asal` varchar(255) DEFAULT NULL,
  `id_prodi_asal` varchar(255) DEFAULT NULL,
  `nama_program_studi_asal` varchar(255) DEFAULT NULL,
  `jenis_kelamin` enum('L','P','*') NOT NULL,
  `tanggal_daftar` date NOT NULL,
  `nama_ibu_kandung` varchar(255) NOT NULL,
  `id_pembiayaan` varchar(255) DEFAULT NULL,
  `nama_pembiayaan_awal` varchar(255) DEFAULT NULL,
  `biaya_masuk` varchar(255) DEFAULT NULL,
  `id_bidang_minat` varchar(255) DEFAULT NULL,
  `nm_bidang_minat` varchar(255) DEFAULT NULL,
  `id_periode_keluar` varchar(255) DEFAULT NULL,
  `tanggal_keluar` date DEFAULT NULL,
  `last_update` varchar(255) NOT NULL,
  `tgl_create` varchar(255) NOT NULL,
  `status_sync` varchar(255) NOT NULL,
  `dosen_pa` varchar(255) DEFAULT NULL,
  `id_kurikulum` varchar(255) DEFAULT NULL,
  `sks_maks_pmm` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id_registrasi_mahasiswa` (`id_registrasi_mahasiswa`),
  KEY `idx_riwayat` (`id_registrasi_mahasiswa`),
  KEY `idx_biodata` (`id_mahasiswa`),
  KEY `idx_jenis_daftar` (`id_jenis_daftar`),
  KEY `idx_periode_masuk` (`id_periode_masuk`),
  KEY `riwayat_pendidikans_id_prodi_foreign` (`id_prodi`),
  KEY `idx_jenis_kelamin` (`jenis_kelamin`),
  KEY `riwayat_pendidikans_dosen_pa_foreign` (`dosen_pa`),
  CONSTRAINT `riwayat_pendidikans_dosen_pa_foreign` FOREIGN KEY (`dosen_pa`) REFERENCES `biodata_dosens` (`id_dosen`),
  CONSTRAINT `riwayat_pendidikans_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ruang_perkuliahans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ruang_perkuliahans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_ruang` varchar(255) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `fakultas_id` bigint(20) unsigned DEFAULT NULL,
  `kapasitas_ruang` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ruang_perkuliahans_nama_ruang_lokasi_id_prodi_unique` (`nama_ruang`,`lokasi`,`id_prodi`),
  KEY `ruang_perkuliahans_id_prodi_foreign` (`id_prodi`),
  KEY `ruang_perkuliahans_fakultas_id_foreign` (`fakultas_id`),
  CONSTRAINT `ruang_perkuliahans_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ruang_perkuliahans_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `semester_aktifs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `semester_aktifs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_semester` varchar(255) NOT NULL,
  `semester_allow` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`semester_allow`)),
  `krs_mulai` date NOT NULL,
  `krs_selesai` date NOT NULL,
  `mulai_isi_nilai` date DEFAULT NULL,
  `batas_isi_nilai` date DEFAULT NULL,
  `batas_bayar_ukt` date DEFAULT NULL,
  `tanggal_mulai_kprs` date DEFAULT NULL,
  `tanggal_akhir_kprs` date DEFAULT NULL,
  `tgl_mulai_pengajuan_cuti` date DEFAULT NULL,
  `tgl_selesai_pengajuan_cuti` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `semester_aktifs_id_semester_foreign` (`id_semester`),
  CONSTRAINT `semester_aktifs_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semesters` (`id_semester`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `semesters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `semesters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_semester` varchar(255) NOT NULL,
  `id_tahun_ajaran` year(4) NOT NULL,
  `nama_semester` varchar(255) NOT NULL,
  `semester` int(11) NOT NULL,
  `a_periode_aktif` tinyint(1) NOT NULL,
  `tanggal_mulai` varchar(255) DEFAULT NULL,
  `tanggal_selesai` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `semesters_id_semester_unique` (`id_semester`),
  KEY `idx_semester` (`id_semester`),
  KEY `idx_tahun_ajaran` (`id_tahun_ajaran`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `skala_nilais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `skala_nilais` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_bobot_nilai` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `nilai_huruf` varchar(255) DEFAULT NULL,
  `nilai_indeks` double(8,2) DEFAULT NULL,
  `bobot_minimum` double(8,2) DEFAULT NULL,
  `bobot_maksimum` double(8,2) DEFAULT NULL,
  `tanggal_mulai_efektif` date DEFAULT NULL,
  `tanggal_akhir_efektif` date DEFAULT NULL,
  `tgl_create` varchar(255) DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `skala_nilais_id_bobot_nilai_unique` (`id_bobot_nilai`),
  KEY `skala_nilais_id_prodi_foreign` (`id_prodi`),
  CONSTRAINT `skala_nilais_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `status_mahasiswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `status_mahasiswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_status_mahasiswa` varchar(255) NOT NULL,
  `nama_status_mahasiswa` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status_mahasiswas_id_status_mahasiswa_unique` (`id_status_mahasiswa`),
  KEY `idx_status_mahasiswa` (`id_status_mahasiswa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `substansi_kuliahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `substansi_kuliahs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_substansi` varchar(255) DEFAULT NULL,
  `id_prodi` varchar(255) DEFAULT NULL,
  `nama_program_studi` varchar(255) DEFAULT NULL,
  `nama_substansi` varchar(255) DEFAULT NULL,
  `sks_mata_kuliah` varchar(255) DEFAULT NULL,
  `sks_tatap_muka` varchar(255) DEFAULT NULL,
  `sks_praktek` varchar(255) DEFAULT NULL,
  `sks_praktek_lapangan` varchar(255) DEFAULT NULL,
  `sks_simulasi` varchar(255) DEFAULT NULL,
  `id_jenis_substansi` varchar(255) DEFAULT NULL,
  `nama_jenis_substansi` varchar(255) DEFAULT NULL,
  `tgl_create` varchar(255) DEFAULT NULL,
  `last_update` varchar(255) DEFAULT NULL,
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `substansi_kuliahs_id_substansi_unique` (`id_substansi`),
  KEY `substansi_kuliahs_id_prodi_foreign` (`id_prodi`),
  KEY `idx_jenis_substansi` (`id_jenis_substansi`),
  CONSTRAINT `substansi_kuliahs_id_prodi_foreign` FOREIGN KEY (`id_prodi`) REFERENCES `program_studis` (`id_prodi`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sync_errors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sync_errors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tingkat_prestasis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tingkat_prestasis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_tingkat_prestasi` int(11) NOT NULL,
  `nama_tingkat_prestasi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tingkat_prestasis_id_tingkat_prestasi_unique` (`id_tingkat_prestasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `transkrip_mahasiswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `transkrip_mahasiswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `id_registrasi_mahasiswa` varchar(255) NOT NULL,
  `id_matkul` varchar(255) NOT NULL,
  `id_kelas_kuliah` varchar(255) DEFAULT NULL,
  `id_nilai_transfer` varchar(255) DEFAULT NULL,
  `id_konversi_aktivitas` varchar(255) DEFAULT NULL,
  `smt_diambil` varchar(255) DEFAULT NULL,
  `kode_mata_kuliah` varchar(255) DEFAULT NULL,
  `nama_mata_kuliah` varchar(255) DEFAULT NULL,
  `sks_mata_kuliah` varchar(255) DEFAULT NULL,
  `nilai_angka` double(8,2) DEFAULT NULL,
  `nilai_huruf` varchar(255) DEFAULT NULL,
  `nilai_indeks` double(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transkrip_mahasiswas_id_registrasi_mahasiswa_id_matkul_unique` (`id_registrasi_mahasiswa`,`id_matkul`),
  KEY `transkrip_mahasiswas_id_matkul_foreign` (`id_matkul`),
  KEY `idx_kelas_kuliah` (`id_kelas_kuliah`),
  KEY `transkrip_mahasiswas_id_nilai_transfer_foreign` (`id_nilai_transfer`),
  KEY `transkrip_mahasiswas_id_konversi_aktivitas_foreign` (`id_konversi_aktivitas`),
  CONSTRAINT `transkrip_mahasiswas_id_konversi_aktivitas_foreign` FOREIGN KEY (`id_konversi_aktivitas`) REFERENCES `konversi_aktivitas` (`id_konversi_aktivitas`),
  CONSTRAINT `transkrip_mahasiswas_id_matkul_foreign` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliahs` (`id_matkul`),
  CONSTRAINT `transkrip_mahasiswas_id_nilai_transfer_foreign` FOREIGN KEY (`id_nilai_transfer`) REFERENCES `nilai_transfer_pendidikans` (`id_transfer`),
  CONSTRAINT `transkrip_mahasiswas_id_registrasi_mahasiswa_foreign` FOREIGN KEY (`id_registrasi_mahasiswa`) REFERENCES `riwayat_pendidikans` (`id_registrasi_mahasiswa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `uji_mahasiswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `uji_mahasiswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feeder` tinyint(1) NOT NULL DEFAULT 1,
  `id_uji` varchar(255) DEFAULT NULL,
  `id_aktivitas` varchar(255) NOT NULL,
  `judul` text DEFAULT NULL,
  `id_kategori_kegiatan` int(11) DEFAULT NULL,
  `nama_kategori_kegiatan` text DEFAULT NULL,
  `id_dosen` varchar(255) DEFAULT NULL,
  `nidn` varchar(255) DEFAULT NULL,
  `nama_dosen` varchar(255) DEFAULT NULL,
  `penguji_ke` int(11) DEFAULT NULL,
  `status_uji_mahasiswa` varchar(255) NOT NULL DEFAULT '2' COMMENT '0:Belum di Setujui, 1:Sudah di Setujui Prodi, 2:Sudah di Setujui Dosen Penguji, 3:Dibatalkan Oleh Dosen Penguji',
  `status_sync` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_aktivitas_uji` (`id_aktivitas`,`id_dosen`),
  UNIQUE KEY `uji_mahasiswas_id_uji_unique` (`id_uji`),
  KEY `uji_mahasiswas_id_kategori_kegiatan_foreign` (`id_kategori_kegiatan`),
  KEY `uji_mahasiswas_id_dosen_foreign` (`id_dosen`),
  CONSTRAINT `uji_mahasiswas_id_aktivitas_foreign` FOREIGN KEY (`id_aktivitas`) REFERENCES `aktivitas_mahasiswas` (`id_aktivitas`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `uji_mahasiswas_id_dosen_foreign` FOREIGN KEY (`id_dosen`) REFERENCES `biodata_dosens` (`id_dosen`) ON DELETE SET NULL,
  CONSTRAINT `uji_mahasiswas_id_kategori_kegiatan_foreign` FOREIGN KEY (`id_kategori_kegiatan`) REFERENCES `kategori_kegiatans` (`id_kategori_kegiatan`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role` enum('dosen','mahasiswa','prodi','fakultas','univ','admin','bak','perpus') NOT NULL,
  `fk_id` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `wilayahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wilayahs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_wilayah` varchar(255) NOT NULL,
  `id_level_wilayah` int(11) NOT NULL,
  `id_negara` varchar(255) NOT NULL,
  `nama_wilayah` varchar(255) NOT NULL,
  `id_induk_wilayah` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wilayahs_id_wilayah_unique` (`id_wilayah`),
  KEY `idx_wilayah` (`id_wilayah`),
  KEY `wilayahs_id_level_wilayah_foreign` (`id_level_wilayah`),
  KEY `idx_negara` (`id_negara`),
  KEY `idx_induk_wilayah` (`id_induk_wilayah`),
  CONSTRAINT `wilayahs_id_level_wilayah_foreign` FOREIGN KEY (`id_level_wilayah`) REFERENCES `level_wilayahs` (`id_level_wilayah`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `wisuda_checklists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wisuda_checklists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `urutan` int(11) NOT NULL,
  `checklist` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wisuda_checklists_urutan_unique` (`urutan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `wisuda_syarat_adms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wisuda_syarat_adms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `urutan` int(11) NOT NULL,
  `syarat` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wisuda_syarat_adms_urutan_unique` (`urutan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

/*M!999999\- enable the sandbox mode */ 
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2014_10_12_100000_create_password_reset_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2014_10_12_100000_create_password_resets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2023_12_12_064122_create_fakultas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2023_12_12_155804_create_program_studis_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2023_12_15_144059_create_list_kurikulums_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2023_12_15_152329_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2023_12_15_152412_create_job_batches_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2023_12_17_083354_create_mata_kuliahs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2023_12_17_111128_create_matkul_kurikulums_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2023_12_19_071154_create_jurusans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2024_01_05_012347_create_riwayat_pendidikans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2024_01_05_023414_create_biodata_mahasiswas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2024_01_05_025921_create_level_wilayahs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2024_01_05_025927_create_wilayahs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2024_01_05_042900_create_negaras_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2024_01_16_134908_create_status_mahasiswas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2024_01_16_142309_create_semesters_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2024_01_16_163915_create_jenis_keluars_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2024_01_16_164815_create_jenis_daftars_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2024_01_16_165105_create_jalur_masuks_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2024_01_17_122703_create_semester_aktifs_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2024_01_17_142141_create_kelas_kuliahs_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2024_01_18_032921_create_jenis_evaluasis_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2024_01_18_055439_create_ruang_perkuliahans_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2024_01_20_064137_create_biodata_dosens_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2024_01_20_125555_create_ikatan_kerjas_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2024_01_21_125227_create_penugasan_dosens_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2024_01_22_025654_create_jenis_substansis_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2024_01_22_030710_create_substansi_kuliahs_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2024_01_22_033509_create_peserta_kelas_kuliahs_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2024_01_22_040910_create_dosen_pengajar_kelas_kuliahs_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2024_01_22_054520_add_id_ruang_perkuliahan_to_kelas_kuliahs_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2024_01_23_052911_create_pembiayaans_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2024_01_23_071318_add_unique_column_to_riwayat_pendidikans_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2024_01_23_093235_create_aktivitas_kuliah_mahasiswas_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2024_01_24_083848_add_unique_index_to_list_kurikulums_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2024_01_25_021822_create_matkul_kurikulums_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2024_01_25_070801_create_jenis_aktivitas_mahasiswas_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2024_01_25_071914_create_aktivitas_mahasiswas_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2024_01_26_050249_create_anggota_aktivitas_mahasiswas_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2024_01_26_054409_create_kategori_kegiatans_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2024_01_26_055013_create_bimbing_mahasiswas_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2024_01_26_060854_create_uji_mahasiswas_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2024_01_26_093213_create_rencana_pembelajarans_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2024_01_26_095025_create_rencana_evaluasis_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2024_01_29_020544_create_agamas_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2024_01_29_021818_create_alat_transportasis_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2024_01_29_022610_create_pekerjaans_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2024_01_30_073507_create_nilai_transfer_pendidikans_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2024_01_31_025031_change_peserta_kelas_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2024_01_31_025620_change_peserta_kelas_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2024_01_31_060133_change_kelas_kuliahs_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2024_01_31_062900_add_index_to_dosen_pengajar_kelas_kuliahs_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2024_02_01_070527_create_nilai_perkuliahans_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2024_02_02_023433_add_column_to_semester_aktifs_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2024_02_02_024223_create_periode_perkuliahans_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2024_02_05_084419_add_column_to_kelas_kuliahs_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2024_02_05_143818_create_skala_nilais_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2024_02_06_101451_create_jenis_prestasis_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2024_02_06_101825_create_tingkat_prestasis_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2024_02_06_104031_create_prestasi_mahasiswas_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2024_02_06_112227_create_sync_errors_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2024_02_06_125956_drop_forein_id_matkul_in_nilai_transfer_pendidikans_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2024_02_12_120901_add_dosen_pa_column_to_riwayat_pendidikans_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2024_02_21_121837_create_komponen_evaluasi_kelas_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (69,'2024_02_21_124751_add_column_to_akitivitas_mahasiswas_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (70,'2024_02_22_151930_create_konversi_aktivitas_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2024_02_26_163916_create_transkrip_mahasiswas_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2024_02_27_114115_change_id_matkul_in_kelas_kuliahs_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2024_02_27_123928_add_column_to_mata_kuliahs_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (74,'2024_02_29_143508_add_unique_column_to_komponen_evaluasi_kelas_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2024_04_01_135134_create_prasyarat_matkuls_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (76,'2024_04_01_135628_create_matkul_merdekas_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (77,'2024_05_16_153503_change_column_to_komponen_evaluasi_kelas',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (78,'2024_05_16_184427_add_column_to_list_kurikulums_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (79,'2024_05_21_125903_add_column_to_riwayat_pendidikans_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (80,'2024_05_22_150709_create_nilai_komponen_evaluasis_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (81,'2024_05_23_143653_add_column_approved_to_peserta_kelas_kuliahs_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (82,'2024_05_24_151342_add_column_to_komponen_evaluasi_kelas_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (83,'2024_05_24_162158_add_column_to_program_studis_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (84,'2024_05_25_153429_create_jurusans_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (85,'2024_05_26_154611_create_riwayat_pendidikan_dosens_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (86,'2024_05_26_161745_create_riwayat_fungsional_dosens_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (87,'2024_05_26_194737_add_column_to_table_aktivitas_and_bimbing',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (88,'2024_05_27_145407_add_column_to_bimbing_mahasiswas_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (89,'2024_05_27_161218_add_column_to_aktivitas_mahasiswas_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (90,'2024_05_27_193743_add_column_to_bimbing_mahasiswas_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (91,'2024_05_30_085119_add_column_to_rencana_pembelajarans_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (92,'2024_05_31_153338_create_lulus_dos_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (93,'2024_06_05_100441_add_column_to_mata_kuliahs_table',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (94,'2024_06_06_102046_create_asistensi_akhirs_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (95,'2024_06_06_135814_add_column_to_asistensi_akhirs_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (96,'2024_06_10_183530_create_kuisoner_questions_table',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (97,'2024_06_11_004006_create_kuisoner_answers_table',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (98,'2024_07_17_164348_create_cuti_kuliahs_table',26);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (99,'2024_07_30_175653_create_jenis_beasiswa_mahasiswas_table',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (100,'2024_07_30_175717_create_beasiswa_mahasiswas_table',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (101,'2024_07_30_215922_create_aktivitas_magangs_table',28);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (102,'2024_08_01_153847_add_column_to_cuti_kuliahs_table',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (103,'2024_08_03_142345_change_email_column_to_users_table',30);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (104,'2024_08_03_142649_remove_unique_index_to_users_table',30);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (105,'2024_08_03_182221_add_column_uji_mahasiswa',31);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (106,'2024_08_07_135331_create_aktivitas_konversi_table',32);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (107,'2024_08_09_105136_add_column_to_aktivitas_konversi_table',33);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (108,'2024_08_12_094826_add_column_feeder_aktivitas_kuliah_mahasiswa',34);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (109,'2024_08_13_104942_add_column_tanggal_approved_to_peserta_kelas_kuliahs_table',35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (110,'2024_08_14_104519_add_column_id_pembiayaan_beasiswa_mahasiswas',36);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (111,'2024_08_14_102639_add_column_tanggal_approved_to_aktivitas_mahasiswas_table',37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (112,'2024_08_14_111000_add_column_batas_bayar_ukt_to_semester_aktifs_table',37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (113,'2024_08_15_123946_create_penundaan_bayars_table',38);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (114,'2024_08_16_151944_add_column_tgl_mulai_dan_tgl_akhir_kprs_tabel_semester_aktif',39);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (115,'2024_08_19_145649_add_column_alasan_pembatalan_tabel_bimbing_mahasiswa',40);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (116,'2024_08_20_122157_add_column_sks_aktivitas_to_aktivitas_mahasiswas_table',41);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (117,'2024_08_26_153028_create_pembayaran_manual_mahasiswas_table',42);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (118,'2024_08_26_192129_add_column_tanggal_pembayaran_to_pembayaran_manual_mahasiswas_table',43);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (119,'2024_08_30_092708_create_monitoring_isi_krs_table',44);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (120,'2024_09_05_130626_add_nilai_usept_column_to_list_kurikulums_table',45);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (121,'2024_09_06_140501_add_column_uji_mahasiswa',46);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (122,'2024_09_09_153239_add_column_alasan_pembatalan_to_cuti_kuliahs_table',47);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (123,'2024_09_09_165907_create_batas_isi_krs_manual_table',48);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (124,'2024_09_10_131652_create_nilai_sidang_mahasiswa',49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (125,'2024_09_10_154214_add_column_nilai_proses_bimbingan',49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (126,'2024_09_10_155349_create_revisi_sidang_mahasiswa',49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (127,'2024_09_10_160040_create_notulensi_sidang_mahasiswa',49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (128,'2024_09_11_145423_add_nominal_ukt_column_to_pembayaran_manual_mahasiswas_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (129,'2024_09_12_130822_add_column_id_dosen_and_nilai_akhir',51);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (130,'2024_09_12_131547_add_column_id_kategori_kegiatan_and_nama_kategori_kegiatan',51);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (131,'2024_09_12_184229_add_column_konversi_aktivitas',51);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (132,'2024_09_13_014319_add_status_sync_column_to_kelas_kuliahs_table',52);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (133,'2024_09_13_041920_add_feeder_column_to_peserta_kelas_kuliahs_table',52);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (134,'2024_09_16_231523_add_on_update_cascade_to_komponen_evaluasi_kelas_table',53);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (135,'2024_09_17_021147_add_status_sync_column_to_komponen_evaluasi_kelas_table',54);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (136,'2024_09_17_042803_add_feeder_column_to_nilai_perkuliahans_table',55);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (137,'2024_09_17_043330_add_status_sync_column_to_nilai_perkuliahans_table',56);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (138,'2024_09_18_160112_add_column_aktivitas_konversi',57);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (139,'2024_09_20_124700_create_bebas_pustakas_table',58);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (140,'2024_09_20_152150_add_column_user_id_to_bebas_pustakas_table',59);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (141,'2024_09_23_133833_create_all_pts_table',60);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (142,'2024_09_23_154336_change_approved_column_in_cuti_kuliahs_table',61);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (143,'2024_09_26_101325_add_verifikator_column_to_bebas_pustakas_table',62);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (144,'2024_09_25_144235_create_pejabat_fakultas_table',63);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (145,'2024_10_01_104721_add_keterangan_column_to_penundaan_bayars_table',64);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (146,'2024_10_02_113016_change_column_to_asistensi_akhirs_table',65);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (147,'2024_10_02_114027_change_foreign_on_anggota_aktivitas_mahasiswas_table',65);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (148,'2024_10_09_121938_change_column_to_pejabat_fakultas_table',66);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (149,'2024_10_14_134639_add_no_sk_to_cuti_kuliahs_table',67);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (150,'2024_10_14_162838_add_unique_constraint_to_cuti_kuliahs',67);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (151,'2024_10_18_094937_change_column_to_jenis_keluars_table',68);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (152,'2024_10_28_093701_add_submitted_column_to_peserta_kelas_kuliahs_table',69);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (153,'2024_10_28_094322_add_submitted_column_to_aktivitas_mahasiswas_table',69);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (154,'2024_11_11_172550_add_column_mulai_isi_nilai_to_semester_aktifs_table',70);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (155,'2024_11_21_115724_add_column_to_kelas_kuliahs_table',71);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (156,'2024_11_22_150558_add_column_feeder_nilai_transfer_pendidikan',72);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (157,'2024_12_03_132432_add_column_to_riwayat_pendidikans_table',73);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (158,'2024_12_06_114836_add_column_fakultas_id_dan_kapasitas_ruang_table_ruang_perkuliahans',74);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (159,'2024_12_09_123720_add_column_to_semester_aktifs_table',75);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (160,'2024_12_17_105133_add_column_to_batas_isi_krs_manual_table',76);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (161,'2024_12_18_140315_change_id_prodi_structure_in_ruang_perkuliahan_table',77);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (162,'2024_12_19_101208_change_penilaian_langsung_data_type_aktivitas_konversis_table',78);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (163,'2024_12_22_152922_add_on_change_id_kelas_kuliah_to_kuisoner_answers_table',79);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (164,'2024_12_27_105117_add_status_sync_column_to_dosen_pengajar_kelas_kuliahs_table',80);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (165,'2024_12_28_214434_add_column_to_semester_aktifs_table',81);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (166,'2025_01_02_103140_add_feeder_column_to_periode_perkuliahans_table',82);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (167,'2025_01_03_102638_add_link_sk_column_to_beasiswa_mahasiswas_table',83);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (168,'2025_01_10_234821_add_index_to_pembayaran_manual_mahasiswas_table',84);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (169,'2025_02_06_140452_wisuda_table',85);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (170,'2025_02_18_103232_create_pejabat_universitas_jabatans_table',85);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (171,'2025_02_18_103240_create_pejabat_universitas_table',85);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (172,'2025_02_18_113732_update_data_wisuda_table',85);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (173,'2025_02_19_101541_create_periode_wisudas_table',85);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (174,'2025_02_19_145257_comment_column_data_wisuda_table',85);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (175,'2025_02_20_111906_add_uniqe_key_to_periode_wisudas_table',85);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (176,'2025_02_21_123652_add_feeder_column_to_lulus_dos_table',85);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (177,'2025_02_21_134354_create_gelar_lulusans_table',85);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (178,'2025_02_24_105508_add_column_lama_studi_to_data_wisuda_table',86);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (179,'2025_02_26_092900_add_id_semester_column_to_monitoring_isi_krs_table',87);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (180,'2025_02_27_093052_create_wisuda_syarat_adms_table',88);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (181,'2025_02_27_093104_create_wisuda_checklists_table',88);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (182,'2025_02_28_150643_add_index_to_aktivitas_kuliah_mahasiswas_table',89);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (183,'2025_03_04_100300_change_submitted_column_to_peserta_kelas_kuliahs_table',89);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (184,'2025_03_04_100352_change_submitted_column_to_aktivitas_mahasiswas_table',89);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (185,'2025_03_06_113304_add_column_alasan_pembatalan_sidang_aktivitas_mahasiswa',90);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (186,'2025_03_14_101918_add_column_to_penundaan_bayars_table',91);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (187,'2025_03_20_092104_add_missing_data_to_jalur_masuks_table',92);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (188,'2025_03_20_122154_create_profil_pts_table',93);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (189,'2025_03_20_095456_add_alamat_orang_tua_to_data_wisuda_table',94);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (190,'2025_03_20_124106_add_feeder_to_biodata_mahasiswas_table',94);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (191,'2025_03_24_134619_create_monev_status_mahasiswas_table',94);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (194,'2025_03_24_134623_create_monev_status_mahasiswa_details_table',95);
