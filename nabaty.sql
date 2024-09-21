-- MariaDB dump 10.19  Distrib 10.4.24-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: nabaty
-- ------------------------------------------------------
-- Server version	10.4.24-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alamat`
--

DROP TABLE IF EXISTS `alamat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alamat` (
  `id_alamat` varchar(4) NOT NULL,
  `nama_alamat` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(50) DEFAULT NULL,
  `kabupaten` varchar(50) DEFAULT NULL,
  `id_pelanggan` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id_alamat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alamat`
--

LOCK TABLES `alamat` WRITE;
/*!40000 ALTER TABLE `alamat` DISABLE KEYS */;
INSERT INTO `alamat` VALUES ('E001','Samping Masjid Raya Simawang, Jl. Ombilin-Sulit Air','Rambatan','Tanah Datar','P001'),('E002','Jl. Kertamukti No. 37, RT. 05 /RW.6','Padang Panjang Timur','Padang Panjang','P001'),('E003','Jl. Ombilin-Bukittinggi','Batipuh','Tanah Datar','P002');
/*!40000 ALTER TABLE `alamat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bibit_tanaman`
--

DROP TABLE IF EXISTS `bibit_tanaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bibit_tanaman` (
  `id_bibit_tanaman` varchar(4) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `deskripsi_bibit` varchar(300) DEFAULT NULL,
  `gambar` varchar(200) DEFAULT NULL,
  `harga` int(10) DEFAULT NULL,
  `stok` int(5) DEFAULT NULL,
  PRIMARY KEY (`id_bibit_tanaman`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bibit_tanaman`
--

LOCK TABLES `bibit_tanaman` WRITE;
/*!40000 ALTER TABLE `bibit_tanaman` DISABLE KEYS */;
INSERT INTO `bibit_tanaman` VALUES ('A001','Bibit Mangga Harum Manis','Bibit mangga harum manis berasal dari perkembang biakan biji. Cepat berbuah dan lebat. Rasa buahnya manis. Daging buah tebal. Bisa ditanam di pot atau di tanah.','bibit_tanaman/A001.jpg',10000,500),('A002','Kakao','Bibit kakao unggul, berbuah lebat. Tinggi bibit sekitar 40cm. Mudah ditanam. Bisa berbuah di usia 1-2 tahun.','bibit_tanaman/A002.jpg',10000,300),('A003','Durian Montong','Durian montong, dikembangkan melalui perkawinan. Ditanam didalam polybag. Mudah dirawat dan cepat berbuah.','bibit_tanaman/A003.jpg',10000,20),('A004','Jeruk Bali A','Bibit jeruk bali, dikembangkan melalui perkawinan. Ditanam didalam polybag. Mudah dirawat dan cepat berbuah.','bibit_tanaman/A004.jpg',10000,0);
/*!40000 ALTER TABLE `bibit_tanaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bibit_tanaman_dipesan`
--

DROP TABLE IF EXISTS `bibit_tanaman_dipesan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bibit_tanaman_dipesan` (
  `id_pesanan` int(7) unsigned zerofill DEFAULT NULL,
  `id_bibit_tanaman` varchar(4) DEFAULT NULL,
  `jumlah_pesan` int(5) DEFAULT NULL,
  `harga` int(10) DEFAULT NULL,
  `subtotal` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bibit_tanaman_dipesan`
--

LOCK TABLES `bibit_tanaman_dipesan` WRITE;
/*!40000 ALTER TABLE `bibit_tanaman_dipesan` DISABLE KEYS */;
INSERT INTO `bibit_tanaman_dipesan` VALUES (0000001,'A001',5,50000,50000),(0000001,'A002',5,50000,50000),(0000002,'A003',10,100000,100000),(0000002,'A001',5,50000,50000);
/*!40000 ALTER TABLE `bibit_tanaman_dipesan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat` (
  `id_chat` varchar(7) NOT NULL,
  `pesan` varchar(500) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `baca` int(1) DEFAULT NULL,
  `id_pengirim` varchar(4) DEFAULT NULL,
  `id_conversation` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id_chat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat`
--

LOCK TABLES `chat` WRITE;
/*!40000 ALTER TABLE `chat` DISABLE KEYS */;
INSERT INTO `chat` VALUES ('G000001','Selamat pagi','2024-08-13 03:05:47',0,'D001','H001'),('G000002','Perkenalkan saya Arnalis, saya ingin bertanya mengenai pesanan bibit ibu.','2024-08-13 03:07:55',0,'D001','H001'),('G000003','Apakah ibu jadi memesan bibit jeruk balinya?','2024-08-13 03:08:20',0,'D001','H001'),('G000004','Selamat pagi juga pak','2024-08-13 03:08:55',1,'P001','H001'),('G000005','Selamat pagi bapak','2024-08-13 03:11:54',0,'D001','H002'),('G000006','Pagi pak, ada yang bisa saya bantu?','2024-08-13 03:12:54',1,'P002','H002'),('G000007','hAI','2024-09-20 09:33:41',1,'P002','H002'),('G000008','HELLO SIR','2024-09-20 09:34:18',0,'D001','H002'),('G000012','WHAT\'S UP','2024-09-21 13:39:21',1,'P002','H002'),('G000013','A','2024-09-21 13:40:32',0,'D001','H002');
/*!40000 ALTER TABLE `chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversation`
--

DROP TABLE IF EXISTS `conversation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conversation` (
  `id_conversation` varchar(4) NOT NULL,
  `id_pengirim` varchar(4) DEFAULT NULL,
  `id_penerima` varchar(4) DEFAULT NULL,
  `tgl_submit` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_conversation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversation`
--

LOCK TABLES `conversation` WRITE;
/*!40000 ALTER TABLE `conversation` DISABLE KEYS */;
INSERT INTO `conversation` VALUES ('H001','D001','P001','2024-08-13 03:04:15'),('H002','D001','P002','2024-08-13 03:09:58');
/*!40000 ALTER TABLE `conversation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `diskon`
--

DROP TABLE IF EXISTS `diskon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `diskon` (
  `id_diskon` varchar(4) NOT NULL,
  `nama_diskon` varchar(50) DEFAULT NULL,
  `deskripsi_diskon` varchar(300) DEFAULT NULL,
  `besar_potongan` decimal(6,5) DEFAULT NULL,
  `minimal_pembelian` int(5) DEFAULT NULL,
  `waktu_mulai` datetime DEFAULT NULL,
  `waktu_akhir` datetime DEFAULT NULL,
  `id_bibit_tanaman` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id_diskon`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diskon`
--

LOCK TABLES `diskon` WRITE;
/*!40000 ALTER TABLE `diskon` DISABLE KEYS */;
INSERT INTO `diskon` VALUES ('B001','Diskon 5%','Diskon ini memberikan potongan harga 5% terhadap harga bibit tanaman.',0.05000,3,'2024-03-01 00:00:00','2024-04-01 00:00:00','A001'),('B002','Diskon 10%','Diskon ini memberikan potongan harga 5% terhadap harga bibit tanaman.',0.10000,10,'2024-03-01 00:00:00','2024-03-29 00:00:00','A002'),('B003','Diskon Gercep','Diskon ini memberikan potongan sebesar 10% kepada setiap item dengan minimal pembelian 10 item.',0.10000,10,'2024-04-23 00:00:00','2024-05-24 00:00:00','A001'),('B004','Diskon 2%','Diskon sebesar 2% dengan minimal pembelian 3 bibit',0.02000,5,'2024-05-27 00:00:00','2024-05-28 23:59:59','A004'),('B005','Diskon 4%','ada',0.04000,5,'2024-05-25 00:00:00','2024-05-26 23:59:59','A001');
/*!40000 ALTER TABLE `diskon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keranjang`
--

DROP TABLE IF EXISTS `keranjang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keranjang` (
  `id_keranjang` varchar(4) NOT NULL,
  `id_bibit_tanaman` varchar(4) DEFAULT NULL,
  `jumlah` int(5) DEFAULT NULL,
  `subtotal` int(10) DEFAULT NULL,
  `id_pelanggan` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id_keranjang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keranjang`
--

LOCK TABLES `keranjang` WRITE;
/*!40000 ALTER TABLE `keranjang` DISABLE KEYS */;
/*!40000 ALTER TABLE `keranjang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kurir`
--

DROP TABLE IF EXISTS `kurir`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kurir` (
  `id_kurir` varchar(4) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `no_telepon` varchar(15) NOT NULL,
  `tgl_lahir` date NOT NULL,
  PRIMARY KEY (`id_kurir`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kurir`
--

LOCK TABLES `kurir` WRITE;
/*!40000 ALTER TABLE `kurir` DISABLE KEYS */;
/*!40000 ALTER TABLE `kurir` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifikasi`
--

DROP TABLE IF EXISTS `notifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifikasi` (
  `id_notifikasi` int(7) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `id_pesanan` int(7) unsigned zerofill DEFAULT NULL,
  `id_bibit_tanaman` varchar(4) DEFAULT NULL,
  `waktu_notifikasi` datetime DEFAULT NULL,
  `seen_notifikasi` int(1) DEFAULT NULL,
  `tipe_notifikasi` varchar(50) DEFAULT NULL,
  `id_penerima` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id_notifikasi`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifikasi`
--

LOCK TABLES `notifikasi` WRITE;
/*!40000 ALTER TABLE `notifikasi` DISABLE KEYS */;
INSERT INTO `notifikasi` VALUES (0000001,0000001,NULL,'2024-05-26 10:33:45',1,'tgl_pemesanan','D001'),(0000002,0000002,NULL,'2024-05-27 10:50:50',1,'tgl_pemesanan','D001'),(0000003,0000001,NULL,'2024-05-28 15:47:22',1,'pembaruan','D001'),(0000004,0000001,NULL,'2024-05-28 15:51:22',1,'pembatalan','D001'),(0000005,0000001,NULL,'2024-05-28 15:52:22',1,'selesai','D001'),(0000006,0000001,NULL,'2024-05-28 15:52:22',1,'pengembalian','D001'),(0000007,NULL,'A004','2024-05-28 16:51:35',1,'kosong','D001'),(0000008,0000001,NULL,'2024-05-28 15:52:22',1,'pengembalian','D001'),(0000009,0000001,NULL,'2024-05-28 15:52:22',1,'pengembalian','D001'),(0000010,0000002,NULL,'2024-05-31 12:51:50',0,'pembaruan','D001');
/*!40000 ALTER TABLE `notifikasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pelanggan`
--

DROP TABLE IF EXISTS `pelanggan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pelanggan` (
  `id_pelanggan` varchar(4) NOT NULL,
  `nama_pelanggan` varchar(50) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `jenis_kelamin` varchar(1) DEFAULT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pelanggan`
--

LOCK TABLES `pelanggan` WRITE;
/*!40000 ALTER TABLE `pelanggan` DISABLE KEYS */;
INSERT INTO `pelanggan` VALUES ('P001','Husna','1995-02-22','P','0895377559538','moriza74@gmail.com','r4hasia'),('P002','Yosrizal','2005-10-13','L','085769512213','anaknyakasda@gmail.com','r4hasia');
/*!40000 ALTER TABLE `pelanggan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesanan`
--

DROP TABLE IF EXISTS `pesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pesanan` (
  `id_pesanan` int(7) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `tgl_pemesanan` datetime DEFAULT NULL,
  `biaya_pengiriman` int(10) DEFAULT NULL,
  `total_harga` int(10) DEFAULT NULL,
  `besar_potongan` int(10) DEFAULT NULL,
  `total_pembayaran` int(10) DEFAULT NULL,
  `status_pesanan` varchar(30) DEFAULT NULL,
  `dikirim` datetime DEFAULT NULL,
  `selesai` datetime DEFAULT NULL,
  `pembatalan` datetime DEFAULT NULL,
  `alasan_pembatalan` varchar(300) DEFAULT NULL,
  `pengembalian` datetime DEFAULT NULL,
  `alasan_pengembalian` varchar(300) DEFAULT NULL,
  `id_pelanggan` varchar(4) DEFAULT NULL,
  `id_voucher` varchar(4) DEFAULT NULL,
  `id_alamat` varchar(4) NOT NULL,
  PRIMARY KEY (`id_pesanan`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesanan`
--

LOCK TABLES `pesanan` WRITE;
/*!40000 ALTER TABLE `pesanan` DISABLE KEYS */;
INSERT INTO `pesanan` VALUES (0000001,'2024-05-27 10:33:45',20000,120000,3000,117000,'1',NULL,NULL,NULL,NULL,NULL,NULL,'P001','C004','E002'),(0000002,'2024-05-27 10:50:50',20000,150000,3000,167000,'1',NULL,NULL,NULL,NULL,NULL,NULL,'P002','C004','E003');
/*!40000 ALTER TABLE `pesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `petani`
--

DROP TABLE IF EXISTS `petani`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `petani` (
  `id_petani` varchar(4) NOT NULL,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  PRIMARY KEY (`id_petani`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `petani`
--

LOCK TABLES `petani` WRITE;
/*!40000 ALTER TABLE `petani` DISABLE KEYS */;
INSERT INTO `petani` VALUES ('D001','p3tani','$2y$10$bWQN/eubwy.h5oiihfSWxOs7qk/kjFIuwm4xgYVoDN5n5FXor6WB2','petani@nabaty.com','Arnalis','089531279934','1955-03-16');
/*!40000 ALTER TABLE `petani` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voucher`
--

DROP TABLE IF EXISTS `voucher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voucher` (
  `id_voucher` varchar(4) NOT NULL,
  `kode_voucher` varchar(50) DEFAULT NULL,
  `deskripsi_voucher` varchar(300) DEFAULT NULL,
  `minimal_pembelian` int(10) DEFAULT NULL,
  `besar_potongan` int(10) DEFAULT NULL,
  `waktu_mulai` datetime DEFAULT NULL,
  `waktu_akhir` datetime DEFAULT NULL,
  PRIMARY KEY (`id_voucher`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voucher`
--

LOCK TABLES `voucher` WRITE;
/*!40000 ALTER TABLE `voucher` DISABLE KEYS */;
INSERT INTO `voucher` VALUES ('C001','Jumat Sale','Voucher ini memberikan diskon sebesar Rp.2000 untuk minimal pembelian Rp. 100.000, untuk semua produk bibit tanaman.',100000,2000,'2024-05-18 00:00:00','2024-06-01 00:00:00'),('C002','Flash Sale','Voucheri ini memberikan potongan sebesar Rp. 2000 untukm minimal pembelian Rp. 75.000, untuk semua produk bibit tanaman.',75000,2000,'2024-05-15 00:00:00','2024-05-29 00:00:00'),('C003','Berkah Sale','Kupon/voucher belanja sebesar Rp 3000 untuk minimal belanja Rp 50000 untuk bibit apa saja',50000,3000,'2024-05-22 00:00:00','2024-05-25 23:59:59'),('C004','Tebus Murah','ada',50000,3000,'2024-05-25 00:00:00','2024-05-27 23:59:59');
/*!40000 ALTER TABLE `voucher` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-21 21:02:23
