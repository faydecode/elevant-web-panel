-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 21, 2017 at 11:33 AM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `demj1233_bbpbat`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `proc_cari_program` (IN `p_username` VARCHAR(50), IN `p_program` VARCHAR(20), IN `p_wilayah` VARCHAR(20))  BEGIN
	DECLARE _iduser VARCHAR(20);
	DECLARE _count INT DEFAULT 0;
    
	SELECT id_user, COUNT(id_user) INTO _iduser, _count 
	FROM m_user WHERE USERNAME = p_username
	GROUP BY id_user;
    
    insert into log values (_count);
    insert into log values (_iduser);
    insert into log values (p_username);
    
	IF _count = 0 THEN
		SELECT 'RC02' AS RC, 'USER TIDAK DITEMUKAN' AS PESANDB FROM DUAL;
	ELSE
		SELECT 'RC01' AS RC, a.`ID_PROGRES`, a.`PERSEN_PROGRESS`, a.`STOK_PROGRESS`, a.`TGL_PROGRESS`, b.`NAMA_WILAYAH`, c.`NAMA_PROGRAM`, 
		SUBSTR(a.`KET_PROGRESS`, 1, 20) AS KET, SUBSTR(d.`LOG_KET_PROGRESS`, 1, 20) AS VERIFIED_KET
		FROM progres_program a, m_wilayah b, m_program c, log_verifikasi d
		WHERE (a.`ID_PROGRAM` = c.`ID_PROGRAM`
		AND a.`ID_WILAYAH` = b.`ID_WILAYAH`
        AND a.`ID_PROGRES` = d.`LOG_ID_PROGRESS`)
		AND a.`ID_USER` = _iduser AND a.`ID_PROGRAM` = p_program AND a.`ID_WILAYAH` = p_wilayah AND a.IS_AGREE = 1;
	end if;
    END$$

CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `proc_delusermobile` (IN `p_iduser` VARCHAR(20))  BEGIN
	DELETE FROM mobile_user_akses WHERE id_user = p_iduser;
	delete from m_user where id_user = p_iduser;
    END$$

CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `proc_load_history` (IN `p_username` VARCHAR(50))  BEGIN
	declare _iduser varchar(20);
	DECLARE _count int default 0;
	select id_user, count(id_user) into _iduser, _count 
	from m_user where USERNAME = p_username
	group by id_user;
    insert into log VALUES(p_username);
	if _count = 0 then
		SELECT 'RC02' AS RC, 'USER TIDAK DITEMUKAN' AS PESANDB FROM DUAL;
	else
		SELECT 'RC01' AS RC, a.`ID_PROGRES`, a.`PERSEN_PROGRESS`, a.`STOK_PROGRESS`, a.`TGL_PROGRESS`, b.`NAMA_WILAYAH`, c.`NAMA_PROGRAM`,
		SUBSTR(a.`KET_PROGRESS`, 1, 20) AS KET,
        SUBSTR(d.`LOG_KET_PROGRESS`, 1, 20) AS VERIFIED_KET
		FROM progres_program a, m_wilayah b, m_program c, log_verifikasi d
		WHERE (a.`ID_PROGRAM` = c.`ID_PROGRAM`
		AND a.`ID_WILAYAH` = b.`ID_WILAYAH`
        AND a.`ID_PROGRES` = d.`LOG_ID_PROGRESS`)
		AND a.`ID_USER` = _iduser;
	end if;
    END$$

CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `proc_load_path_foto` (IN `p_status` VARCHAR(5), IN `p_idprogress` VARCHAR(20))  BEGIN
	DECLARE _count INT DEFAULT 0;
	IF p_status = '1' THEN 	
		SELECT COUNT(*) INTO _count FROM `LOG_FOTO_VERIFIKASI` WHERE `LOG_FOTO_ID_PROGRESS` = p_idprogress;
		
		IF _count = 0 THEN 			SELECT 'BELUM ADA FOTO YANG DI UPLOAD' AS PESANDB, 'RC002' AS RC FROM DUAL;
		ELSE
			SELECT LOG_FOTO_ID_PROGRESS AS id_progress, `LOG_FOTO_NAME` AS path_foto, 'RC001' AS RC
			FROM LOG_FOTO_VERIFIKASI
			WHERE `LOG_FOTO_ID_PROGRESS` = p_idprogress;
		END IF;
	ELSEIF p_status = '0' THEN 	
		SELECT COUNT(*) INTO _count FROM `log_foto_progress` WHERE `LOG_ID_FOTO_PROGRESS` = p_idprogress;
		
		IF _count = 0 THEN 			SELECT 'BELUM ADA FOTO YANG DI UPLOAD' AS PESANDB, 'RC002' AS RC FROM DUAL;
		ELSE
			SELECT LOG_ID_FOTO_PROGRESS AS id_progress, `PATH_FOTO_PROGRESS` AS path_foto, 'RC001' AS RC 
			FROM log_foto_progress
			WHERE `LOG_ID_FOTO_PROGRESS` = p_idprogress;
		END IF;
	ELSE
		SELECT 'PARAMETER STATUS HARUS 0 / 1' AS PESANDB, 'RC002' AS RC FROM DUAL;
	END IF;
	
    END$$

CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `proc_load_unverified_progress` ()  NO SQL
BEGIN	
	SELECT 'RC01' AS RC, a.`ID_PROGRES`, a.`PERSEN_PROGRESS`, a.`STOK_PROGRESS`, a.`TGL_PROGRESS`, b.`NAMA_WILAYAH`, c.`NAMA_PROGRAM`, 
	SUBSTR(a.`KET_PROGRESS`, 1, 20) AS KET, d.`NAMA_USER`
	FROM progres_program a, m_wilayah b, m_program c, m_user d
	WHERE (a.`ID_PROGRAM` = c.`ID_PROGRAM`
	AND a.`ID_WILAYAH` = b.`ID_WILAYAH`
        AND a.`ID_USER` = d.`ID_USER`)
        AND a.`IS_AGREE` IS NULL;
END$$

CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `proc_login_mobile` (IN `p_username` VARCHAR(50), IN `p_password` VARCHAR(150), IN `p_imei` VARCHAR(50))  BEGIN
	insert into log values (p_username);
    insert into log values (p_password);
    insert into log values (md5(p_password));
    insert into log values (p_imei);
    
        
	SELECT a.`ID_USER`, a.`ID_AKSES`, a.`ID_UNIT`, a.`USERNAME`, a.`NAMA_USER`,  b.`NAMA_AKSES`, c.`NAMA_UNIT`, a.`EMAIL_USER`, a.`PROFILE_PICTURE`
	FROM m_user a, hak_akses b, m_unit c
	WHERE (a.`ID_AKSES` = b.`ID_AKSES` AND b.`TIPE_AKSES`= 'MOBILE') AND (a.`ID_UNIT` = c.`ID_UNIT`) AND
	a.USERNAME = p_username AND a.password = p_password;
    END$$

CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `proc_newusermobile` (IN `p_akses` VARCHAR(20), IN `p_unit` VARCHAR(20), IN `p_username` VARCHAR(50), IN `p_pwd` VARCHAR(150), IN `p_nama` VARCHAR(20), IN `p_tlpn` VARCHAR(20), IN `p_status` VARCHAR(2), IN `p_email` VARCHAR(20), IN `p_program` VARCHAR(20), IN `p_wilayah` VARCHAR(20))  BEGIN
	declare _isAny int default 0;
	DECLARE _id INT DEFAULT 0;
	DECLARE `_rollback` BOOL DEFAULT 0;
	DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
	START TRANSACTION;
	
	select count(*) into _isAny 
	from m_user 
	where `USERNAME` = p_username;
	if _isAny > 0 then
		select 'RC02' as RC, 'USERNAME SUDAH ADA' as PESANDB from dual;
	else
		select count(*) into _id from m_user;
		
		insert into m_user 
		(ID_USER, ID_AKSES, ID_UNIT, USERNAME, PASSWORD, NAMA_USER, TLPN_USER, STATUS_USER, CREATEDATE_USER, EMAIL_USER)
		values (concat('IDUSR', (_id + 1)), p_akses, p_unit, p_username, md5(p_pwd), p_nama, 
		p_tlpn, p_status, curdate(), p_email);
		
		
		
		IF `_rollback` THEN
			ROLLBACK;
			SELECT 'RC99' AS RCDB, 'GAGAL MENYIMPAN USER' AS PESANDB
			FROM DUAL;
		ELSE
			COMMIT;
			INSERT INTO mobile_user_akses (`ID_PROGRAM`, `ID_USER`, `ID_WILAYAH`)
			VALUES (p_program, CONCAT('IDUSR', (_id + 1)), p_wilayah);
			commit;
			SELECT 'RC01' AS RCDB, 'USER BERHASIL DITAMBAHKAN' AS PESANDB
			FROM DUAL;
		END IF;	
	end if;
	
    END$$

CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `proc_program_user_mobile` (IN `p_username` VARCHAR(50))  BEGIN
	SELECT a.`ID_USER`, a.`ID_PROGRAM`, e.`NAMA_PROGRAM`
	FROM mobile_user_akses a, map_prog_wil d, m_program e 
	WHERE (d.`IS_PROG_ACTIVE` = '1') AND 
	a.`ID_WILAYAH` = d.`ID_WILAYAH` AND 
	a.`ID_PROGRAM` = d.`ID_PROGRAM` and 
	a.`ID_PROGRAM` = e.`ID_PROGRAM` AND 
	d.`ID_PROGRAM` = e.`ID_PROGRAM` AND 
	a.ID_USER = p_username
	GROUP BY a.`ID_USER`, a.`ID_PROGRAM`, e.`NAMA_PROGRAM`;
    END$$

CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `proc_reset_password_mobile` (IN `p_username` VARCHAR(50), IN `p_email` VARCHAR(50), IN `p_new_random_password` VARCHAR(100))  BEGIN
	DECLARE _isterdaftar int DEFAULT 0;
	DECLARE `_rollback` BOOL DEFAULT 0;
	DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
	START TRANSACTION;
	
	select count(*) into _isterdaftar from m_user WHERE USERNAME = p_username AND EMAIL_USER = p_email;
	select _isterdaftar as RCDB, 'USER TIDAK TERDAFTAR' as PESANDB from dual;
	if _isterdaftar = 0 then
		select 'RC01' as RCDB, 'USER TIDAK TERDAFTAR' as PESANDB from dual;
	else
		update m_user
		set password = p_new_random_password
		WHERE USERNAME = p_username AND EMAIL_USER = p_email;
		IF `_rollback` THEN
			ROLLBACK;
			SELECT 'RC02' AS RCDB, 'GAGAL RESET PASSWORD' AS PESANDB
			FROM DUAL;
		ELSE
			COMMIT;
			SELECT 'RC03' AS RCDB, 'USER BERHASIL RUBAH PASSWORD' AS PESANDB
			FROM DUAL;
		END IF;	
	end if;
	
	
    END$$

CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `proc_send_progress` (IN `p_iduser` VARCHAR(20), IN `p_program` VARCHAR(20), IN `p_wilayah` VARCHAR(20), IN `p_stok` VARCHAR(20), IN `p_persen` VARCHAR(20), IN `p_keterangan` VARCHAR(255), IN `p_total_foto` INT)  BEGIN
	DECLARE _count INT DEFAULT 0;
	DECLARE _listfoto VARCHAR(500);
	DECLARE `_rollback` BOOL DEFAULT 0;
	DECLARE _counter INT UNSIGNED DEFAULT 1;
	DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
	START TRANSACTION;
	
	SELECT COUNT(*) INTO _count FROM progres_program;
	WHILE _counter <= p_total_foto DO
		
	SET _counter=_counter+1;
	END WHILE;
	
	INSERT INTO progres_program (`ID_PROGRES`, `ID_USER`, `ID_PROGRAM`, `ID_WILAYAH`, `TGL_PROGRESS`, `STOK_PROGRESS`, `PERSEN_PROGRESS`,
	`KET_PROGRESS`, `FOTO_PROGRESS`)
	VALUES (CONCAT('PROG_', _count + 1), p_iduser, p_program, p_wilayah, CURDATE, p_stok, p_persen, p_keterangan, _listfoto);
	
	IF `_rollback` THEN
		ROLLBACK;
		SELECT 'RC01' AS RCDB, 'GAGAL SIMPAN DATA' AS PESANDB, '' AS IDPROG
		FROM DUAL;
	ELSE
		COMMIT;
		SELECT 'RC00' AS RCDB, 'DATA BERHASIL DISIMPAN' AS PESANDB, CONCAT('PROG_', _count + 1) AS IDPROG
		FROM DUAL;
	END IF;	
	
	
    END$$

CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `proc_send_progress_new` (IN `p_iduser` VARCHAR(20), IN `p_program` VARCHAR(20), IN `p_wilayah` VARCHAR(20), IN `p_stok` VARCHAR(20), IN `p_persen` VARCHAR(20), IN `p_keterangan` VARCHAR(255), IN `p_total_path_foto` INT, IN `p_list_path_foto` VARCHAR(1000))  BEGIN
	DECLARE _count INT DEFAULT 0;
	DECLARE _listfoto VARCHAR(500);
	DECLARE `_rollback` BOOL DEFAULT 0;
	DECLARE _counter INT UNSIGNED DEFAULT 1;
	DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
	START TRANSACTION;
	SELECT COUNT(*) INTO _count FROM progres_program;
	
	INSERT INTO progres_program (`ID_PROGRES`, `ID_USER`, `ID_PROGRAM`, `ID_WILAYAH`, `TGL_PROGRESS`, `STOK_PROGRESS`, `PERSEN_PROGRESS`,
	`KET_PROGRESS`)
	VALUES (CONCAT('PROG_', (_count + 1)), p_iduser, p_program, p_wilayah, CURDATE(), p_stok, p_persen, p_keterangan);
	
	WHILE _counter <= p_total_path_foto DO
		
		SELECT SPLIT_STR(p_list_path_foto, '#',_counter) INTO _listfoto FROM DUAL;
		
		INSERT INTO log_foto_progress(LOG_ID_FOTO_PROGRESS, PATH_FOTO_PROGRESS)
		VALUES (CONCAT('PROG_', _count + 1), _listfoto);
		
		SET _counter=_counter+1;
	END WHILE;
	
	IF `_rollback` THEN
		ROLLBACK;
		SELECT 'RC01' AS RCDB, 'GAGAL SIMPAN DATA' AS PESANDB, '' AS IDPROG
		FROM DUAL;
	ELSE
		COMMIT;
		SELECT 'RC00' AS RCDB, 'DATA BERHASIL DISIMPAN' AS PESANDB, CONCAT('PROG_', _count + 1) AS IDPROG
		FROM DUAL;
	END IF;	
	
	
    END$$

CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `proc_verifikasi` (IN `p_idprogres` VARCHAR(20), IN `p_keterangan` VARCHAR(255), IN `p_jumlahpathfoto` INT, IN `p_listfoto` VARCHAR(500))  BEGIN
	DECLARE _isany INT DEFAULT 0;
	DECLARE `_rollback` BOOL DEFAULT 0;
	DECLARE _counter INT UNSIGNED DEFAULT 1;
	DECLARE _listfoto VARCHAR(500);
	DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
	START TRANSACTION;
	SELECT COUNT(*) INTO _isany FROM LOG_VERIFIKASI WHERE `LOG_ID_PROGRESS` = p_idprogres;
	
	IF _isany = 1 THEN
		SELECT 'RC002' RCDB, 'DATA INI SUDAH DI VERIFIKASI' AS PESANDB FROM DUAL;
	ELSE
		UPDATE progres_program
		SET `IS_EDIT` = '1',
		`IS_AGREE` = '1',
		`DATE_EDIT` = CURDATE()
		WHERE `ID_PROGRES` = p_idprogres;
        
        INSERT INTO log_verifikasi VALUES(p_idprogres, p_keterangan);
		WHILE _counter <= p_jumlahpathfoto DO
		
			SELECT SPLIT_STR(p_listfoto, '#',_counter) INTO _listfoto FROM DUAL;
			
			INSERT INTO log_foto_verifikasi(LOG_FOTO_ID_PROGRESS, LOG_FOTO_NAME)
			VALUES (p_idprogres, _listfoto);
			
			SET _counter=_counter+1;
		END WHILE;
		
		IF `_rollback` THEN
			ROLLBACK;
			SELECT 'RC01' AS RCDB, 'GAGAL VERIFIKASI DATA' AS PESANDB
			FROM DUAL;
		ELSE
			COMMIT;
			SELECT 'RC00' AS RCDB, 'DATA BERHASIL DIVERIFIKASI' AS PESANDB
			FROM DUAL;
		END IF;	
	END IF;
	
    END$$

CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `proc_view_detail_progress` (IN `p_username` VARCHAR(50), IN `p_program` VARCHAR(20), IN `p_wilayah` VARCHAR(20))  BEGIN
	DECLARE _iduser VARCHAR(20);
	DECLARE _count INT DEFAULT 0;
	SELECT id_user, COUNT(id_user) INTO _iduser, _count 
	FROM m_user WHERE USERNAME = p_username
	GROUP BY id_user;
	IF _count = 0 THEN
		SELECT 'RC002' AS RCDB, 'USER TIDAK DITEMUKAN' AS PESANDB FROM DUAL;
	ELSE
		SELECT 'RC01' AS RCDB, a.`ID_PROGRES`, a.`PERSEN_PROGRESS`, a.`STOK_PROGRESS`, a.`TGL_PROGRESS`, b.`NAMA_WILAYAH`, c.`NAMA_PROGRAM`, 
		SUBSTR(a.`KET_PROGRESS`, 1, 20) AS KET
		FROM progres_program a, m_wilayah b, m_program c
		WHERE (a.`ID_PROGRAM` = c.`ID_PROGRAM`
		AND a.`ID_WILAYAH` = b.`ID_WILAYAH`)
		AND a.`ID_USER` = _iduser AND a.`ID_PROGRAM` = p_program AND a.`ID_WILAYAH` = p_wilayah
		AND a.`IS_AGREE` = '1';
	END IF;
    END$$

CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `proc_view_history_verifikasi` ()  BEGIN
	SELECT a.`ID_PROGRES`, a.`KET_PROGRESS`, a.`PERSEN_PROGRESS`, a.`STOK_PROGRESS`, a.`TGL_PROGRESS`, 
	b.`NAMA_WILAYAH`, c.`NAMA_PROGRAM`, 
	CASE 
		WHEN a.`IS_AGREE` = '1' AND a.`IS_EDIT` = '1' THEN 'SUDAH DIVERIFIKASI'
		ELSE 'BELUM DI VERIFIKASI'
	END AS STATUS
	FROM progres_program a, m_wilayah b, m_program c
	WHERE (a.`ID_PROGRAM` = c.`ID_PROGRAM`
	AND a.`ID_WILAYAH` = b.`ID_WILAYAH`);
    END$$

CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `proc_view_program_history` (IN `p_idprogres` VARCHAR(20))  BEGIN
	SELECT a.`ID_PROGRES`, a.`KET_PROGRESS`, a.`PERSEN_PROGRESS`, a.`STOK_PROGRESS`, a.`TGL_PROGRESS`, b.`NAMA_WILAYAH`, c.`NAMA_PROGRAM`
	FROM progres_program a, m_wilayah b, m_program c
	WHERE (a.`ID_PROGRAM` = c.`ID_PROGRAM`
	AND a.`ID_WILAYAH` = b.`ID_WILAYAH`)
	AND a.`ID_PROGRES` = p_idprogres;
    END$$

CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `proc_wilayah_user_mobile` (IN `p_username` VARCHAR(50), IN `p_program` VARCHAR(20))  BEGIN
	SELECT a.`ID_USER`, a.`ID_WILAYAH`, e.`NAMA_WILAYAH`
	FROM mobile_user_akses a, map_prog_wil d, m_wilayah e 
	WHERE (d.`IS_PROG_ACTIVE` = '1') AND
	(a.`ID_WILAYAH` = d.`ID_WILAYAH` AND 
	a.`ID_WILAYAH` = e.`ID_WILAYAH` AND 
	a.`ID_PROGRAM` = d.`ID_PROGRAM` AND 
	d.`ID_WILAYAH` = e.`ID_WILAYAH` ) AND 
	a.ID_USER = p_username and a.ID_PROGRAM = p_program
	GROUP BY a.`ID_USER`, a.`ID_PROGRAM`, e.`NAMA_WILAYAH`;
    END$$

CREATE DEFINER=`demj1233`@`localhost` PROCEDURE `web_proc_loadverifikasi` (IN `p_params` VARCHAR(200))  BEGIN
	SELECT 'RC01' AS RC, a.`ID_PROGRES`, a.`PERSEN_PROGRESS`, a.`STOK_PROGRESS`, a.`TGL_PROGRESS`, b.`NAMA_WILAYAH`, c.`NAMA_PROGRAM`, 
	SUBSTR(a.`KET_PROGRESS`, 1, 20) AS KET, d.`NAMA_USER`
	FROM progres_program a, m_wilayah b, m_program c, m_user d
	WHERE (a.`ID_PROGRAM` = c.`ID_PROGRAM`
	AND a.`ID_WILAYAH` = b.`ID_WILAYAH`
        AND a.`ID_USER` = d.`ID_USER`)
        AND a.`IS_AGREE` IS NULL
        and a.`KET_PROGRESS` like p_params ;
    END$$

--
-- Functions
--
CREATE DEFINER=`demj1233`@`localhost` FUNCTION `SPLIT_STR` (`X` VARCHAR(255), `delim` VARCHAR(12), `pos` INT) RETURNS VARCHAR(255) CHARSET latin1 RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(X, delim, pos),
       LENGTH(SUBSTRING_INDEX(X, delim, pos -1)) + 1),
       delim, '')$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `hak_akses`
--

CREATE TABLE `hak_akses` (
  `ID_AKSES` varchar(20) NOT NULL,
  `NAMA_AKSES` varchar(40) DEFAULT NULL,
  `TIPE_AKSES` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hak_akses`
--

INSERT INTO `hak_akses` (`ID_AKSES`, `NAMA_AKSES`, `TIPE_AKSES`) VALUES
('AKS1', 'PIC', 'MOBILE'),
('AKS2', 'VERIFIKASI', 'MOBILE');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `log` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`log`) VALUES
('1'),
('IDUSR1'),
('hanafi'),
('1'),
('IDUSR1'),
('hanafi'),
('1'),
('IDUSR1'),
('hanafi'),
('1'),
('IDUSR1'),
('hanafi'),
('1'),
('IDUSR1'),
('hanafi'),
('1'),
('IDUSR1'),
('hanafi'),
('1'),
('IDUSR1'),
('hanafi'),
('1'),
('IDUSR1'),
('hanafi'),
('1'),
('IDUSR1'),
('hanafi'),
('1'),
('IDUSR1'),
('hanafi'),
('1'),
('IDUSR1'),
('hanafi'),
('1'),
('IDUSR1'),
('hanafi'),
('1'),
('IDUSR1'),
('hanafi'),
('1'),
('IDUSR1'),
('hanafi'),
('hanafi'),
('hanafi'),
('hanafi'),
('hanafi'),
('hanafi'),
('hanafi'),
('asd'),
('7815696ecbf1c96e6894b779456d330e'),
(''),
('hanafi'),
('asd'),
('7815696ecbf1c96e6894b779456d330e'),
(''),
('hanafi'),
('asd'),
('7815696ecbf1c96e6894b779456d330e'),
('');

-- --------------------------------------------------------

--
-- Table structure for table `log_foto_progress`
--

CREATE TABLE `log_foto_progress` (
  `LOG_ID_FOTO_PROGRESS` varchar(20) DEFAULT NULL,
  `PATH_FOTO_PROGRESS` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `log_foto_progress`
--

INSERT INTO `log_foto_progress` (`LOG_ID_FOTO_PROGRESS`, `PATH_FOTO_PROGRESS`) VALUES
('PROG_5', 'r50ppScreenshot_13.jpeg'),
('PROG_5', 'adOuzIMG-20171003-WA0000.jpeg'),
('PROG_6', 'CqrltIMG-20171005-WA0010.jpeg'),
('PROG_6', 'wFoPSIMG-20171005-WA0009.jpeg'),
('PROG_7', 'aXcA3Screenshot_20.png'),
('PROG_7', 'KICclScreenshot_19.png'),
('PROG_7', 'ESXAHIMG-20171006-WA0005.jpg'),
('PROG_7', 'Xn0eNIMG-20171006-WA0004.jpg'),
('PROG_7', 'FQnllScreenshot_21.png'),
('PROG_7', 'ezoekIMG-20171006-WA0003.jpg'),
('PROG_8', '8GU03IMG-20171006-WA0005.jpg'),
('PROG_8', '16ZioIMG-20171006-WA0004.jpg'),
('PROG_8', 'ooTTXScreenshot_19.png'),
('PROG_9', 'IHzzeIMG-20171007-WA0001.jpg'),
('PROG_9', 'HVLPRIMG-20171007-WA0000.jpg'),
('PROG_9', 'zV9tEScreenshot_19.png'),
('PROG_9', '3ZIwpScreenshot_21.png'),
('PROG_9', 'Y6wlhIMG-20171006-WA0005.jpg'),
('PROG_9', 'CgVJTScreenshot_20.png'),
('PROG_10', 'ld3HKIMG-20171007-WA0001.jpg'),
('PROG_10', 'uR3i3IMG-20171007-WA0000.jpg'),
('PROG_10', 'bqhbeScreenshot_19.png'),
('PROG_10', 'pTk0cScreenshot_21.png'),
('PROG_10', 'RVK2cIMG-20171006-WA0004.jpg'),
('PROG_10', 'rv9x1IMG-20171006-WA0003.jpg'),
('PROG_11', 'Dk7UyIMG-20171016-WA0014.jpg'),
('PROG_11', 'DO32aIMG-20171016-WA0011.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `log_foto_verifikasi`
--

CREATE TABLE `log_foto_verifikasi` (
  `LOG_FOTO_ID_PROGRESS` varchar(20) DEFAULT NULL,
  `LOG_FOTO_NAME` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log_foto_verifikasi`
--

INSERT INTO `log_foto_verifikasi` (`LOG_FOTO_ID_PROGRESS`, `LOG_FOTO_NAME`) VALUES
('PROG_5', 'fsdfasfa'),
('PROG_5', 'fasfdafa'),
('PROG_11', 'Dk7UyIMG-20171016-WA0014.jpg'),
('PROG_11', 'DO32aIMG-20171016-WA0011.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `log_verifikasi`
--

CREATE TABLE `log_verifikasi` (
  `LOG_ID_PROGRESS` varchar(20) DEFAULT NULL,
  `LOG_KET_PROGRESS` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log_verifikasi`
--

INSERT INTO `log_verifikasi` (`LOG_ID_PROGRESS`, `LOG_KET_PROGRESS`) VALUES
('PROG_5', 'fasdfafa'),
('PROG_4', 'fasfa'),
('PROG_3', 'fasdfafa'),
('PROG_2', 'Keteranganfasdfasf'),
('PROG_11', 'vzhzg\n');

-- --------------------------------------------------------

--
-- Table structure for table `map_prog_wil`
--

CREATE TABLE `map_prog_wil` (
  `ID_PROGRAM` varchar(20) NOT NULL,
  `ID_WILAYAH` varchar(20) NOT NULL,
  `IS_PROG_ACTIVE` char(1) DEFAULT NULL,
  `TGL_ACTIVE_PROG` date DEFAULT NULL,
  `TGL_NONACTIVE_PROG` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `map_prog_wil`
--

INSERT INTO `map_prog_wil` (`ID_PROGRAM`, `ID_WILAYAH`, `IS_PROG_ACTIVE`, `TGL_ACTIVE_PROG`, `TGL_NONACTIVE_PROG`) VALUES
('PROG1', 'WIL1', '1', '2017-08-29', NULL),
('PROG1', 'WIL2', '1', '2017-08-29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mobile_user_akses`
--

CREATE TABLE `mobile_user_akses` (
  `ID_PROGRAM` varchar(20) NOT NULL,
  `ID_WILAYAH` varchar(20) NOT NULL,
  `ID_USER` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mobile_user_akses`
--

INSERT INTO `mobile_user_akses` (`ID_PROGRAM`, `ID_WILAYAH`, `ID_USER`) VALUES
('PROG1', 'WIL1', 'IDUSR1'),
('PROG1', 'WIL2', 'IDUSR1');

-- --------------------------------------------------------

--
-- Table structure for table `m_menu`
--

CREATE TABLE `m_menu` (
  `menu_id` char(3) NOT NULL,
  `kms_menu_id` char(3) DEFAULT NULL,
  `menu_nama` varchar(30) DEFAULT NULL,
  `menu_url` varchar(50) DEFAULT NULL,
  `menu_keterangan` varchar(250) DEFAULT NULL,
  `menu_icon` varchar(20) DEFAULT NULL,
  `menu_urutan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `m_otoritas_menu`
--

CREATE TABLE `m_otoritas_menu` (
  `roles_id` char(3) NOT NULL,
  `menu_id` char(3) NOT NULL,
  `is_view` char(1) DEFAULT NULL,
  `is_add` char(1) DEFAULT NULL,
  `is_edit` char(1) DEFAULT NULL,
  `is_delete` char(1) DEFAULT NULL,
  `is_export` char(1) DEFAULT NULL,
  `is_import` char(1) DEFAULT 'f',
  `is_approve` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `m_program`
--

CREATE TABLE `m_program` (
  `ID_PROGRAM` varchar(20) NOT NULL,
  `NAMA_PROGRAM` varchar(50) DEFAULT NULL,
  `KET_PROGRAM` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_program`
--

INSERT INTO `m_program` (`ID_PROGRAM`, `NAMA_PROGRAM`, `KET_PROGRAM`) VALUES
('PROG1', 'MANIPADI', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_unit`
--

CREATE TABLE `m_unit` (
  `ID_UNIT` varchar(20) NOT NULL,
  `NAMA_UNIT` varchar(50) DEFAULT NULL,
  `KET_UNIT` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_unit`
--

INSERT INTO `m_unit` (`ID_UNIT`, `NAMA_UNIT`, `KET_UNIT`) VALUES
('UNT1', 'BBPBAT', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_user`
--

CREATE TABLE `m_user` (
  `ID_USER` varchar(20) NOT NULL,
  `ID_AKSES` varchar(20) NOT NULL,
  `ID_UNIT` varchar(20) NOT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(150) DEFAULT NULL,
  `NAMA_USER` varchar(50) DEFAULT NULL,
  `TLPN_USER` varchar(20) DEFAULT NULL,
  `IMEI_USER` varchar(50) DEFAULT NULL,
  `PROFILE_PICTURE` text NOT NULL,
  `IS_LOGIN` char(1) DEFAULT NULL,
  `STATUS_USER` char(1) DEFAULT NULL,
  `CREATEDATE_USER` date DEFAULT NULL,
  `EMAIL_USER` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_user`
--

INSERT INTO `m_user` (`ID_USER`, `ID_AKSES`, `ID_UNIT`, `USERNAME`, `PASSWORD`, `NAMA_USER`, `TLPN_USER`, `IMEI_USER`, `PROFILE_PICTURE`, `IS_LOGIN`, `STATUS_USER`, `CREATEDATE_USER`, `EMAIL_USER`) VALUES
('IDUSR1', 'AKS1', 'UNT1', 'hanafi', '7815696ecbf1c96e6894b779456d330e', 'Hanafi', '089670617970', '1111', '8AZI6IMG-20171006-WA0005.jpeg', '0', '1', NULL, 'laura@gmail.com'),
('IDUSR2', 'AKS2', 'UNT1', 'veri', '7815696ecbf1c96e6894b779456d330e', 'Verificator', '2131313', '1111', '', '0', '1', NULL, 'verificator@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `m_wilayah`
--

CREATE TABLE `m_wilayah` (
  `ID_WILAYAH` varchar(20) NOT NULL,
  `NAMA_WILAYAH` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_wilayah`
--

INSERT INTO `m_wilayah` (`ID_WILAYAH`, `NAMA_WILAYAH`) VALUES
('WIL1', 'Sukabumi'),
('WIL2', 'Bandung');

-- --------------------------------------------------------

--
-- Table structure for table `progres_program`
--

CREATE TABLE `progres_program` (
  `ID_PROGRES` varchar(20) NOT NULL,
  `ID_PROGRAM` varchar(20) NOT NULL,
  `ID_WILAYAH` varchar(20) NOT NULL,
  `ID_USER` varchar(20) NOT NULL,
  `TGL_PROGRESS` date DEFAULT NULL,
  `STOK_PROGRESS` varchar(20) DEFAULT NULL,
  `PERSEN_PROGRESS` varchar(5) DEFAULT NULL,
  `KET_PROGRESS` varchar(255) DEFAULT NULL,
  `FOTO_PROGRESS` int(11) DEFAULT NULL,
  `IS_EDIT` char(1) DEFAULT NULL,
  `IS_AGREE` char(1) DEFAULT NULL,
  `DATE_EDIT` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `progres_program`
--

INSERT INTO `progres_program` (`ID_PROGRES`, `ID_PROGRAM`, `ID_WILAYAH`, `ID_USER`, `TGL_PROGRESS`, `STOK_PROGRESS`, `PERSEN_PROGRESS`, `KET_PROGRESS`, `FOTO_PROGRESS`, `IS_EDIT`, `IS_AGREE`, `DATE_EDIT`) VALUES
('PROG_10', 'PROG1', 'WIL1', 'IDUSR1', '2017-10-07', '312', '13', '132131', NULL, NULL, NULL, NULL),
('PROG_11', 'PROG1', 'WIL1', 'IDUSR1', '2017-10-17', '9547', '967', 'vzhzg\n', NULL, '1', '1', '2017-10-17 00:00:00'),
('PROG_2', 'PROG1', 'WIL1', 'IDUSR1', '2017-10-02', '20', '20%', 'Keterangan', NULL, '1', '1', '2017-10-05 00:00:00'),
('PROG_3', 'PROG1', 'WIL1', 'IDUSR1', '2017-10-03', '5', '20%', 'fasdfafa', NULL, '1', '1', '2017-10-04 00:00:00'),
('PROG_4', 'PROG1', 'WIL1', 'IDUSR1', '2017-10-03', '123', '13', 'fasfa', NULL, '1', '1', '2017-10-04 00:00:00'),
('PROG_5', 'PROG1', 'WIL1', 'IDUSR1', '2017-10-04', '312', '100', 'fasfa', NULL, '1', '1', '2017-10-04 00:00:00'),
('PROG_6', 'PROG1', 'WIL1', 'IDUSR1', '2017-10-06', '1231', '31231', 'fasfa', NULL, NULL, NULL, NULL),
('PROG_7', 'PROG1', 'WIL1', 'IDUSR1', '2017-10-06', '132', '1231', 'fsafa', NULL, NULL, NULL, NULL),
('PROG_8', 'PROG1', 'WIL1', 'IDUSR1', '2017-10-06', '13', '123', 'afas', NULL, NULL, NULL, NULL),
('PROG_9', 'PROG1', 'WIL1', 'IDUSR1', '2017-10-07', '1231', '1231', 'fasfas', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `roles_id` char(3) NOT NULL,
  `roles_nama` varchar(50) DEFAULT NULL,
  `roles_keterangan` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `value` varchar(200) DEFAULT NULL,
  `setting` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_webadmin`
--

CREATE TABLE `user_webadmin` (
  `user_id` char(10) NOT NULL,
  `loker_id` char(3) NOT NULL,
  `roles_id` char(3) NOT NULL,
  `user_nip` varchar(25) DEFAULT '0',
  `user_nama` varchar(100) DEFAULT NULL,
  `user_username` varchar(30) DEFAULT NULL,
  `user_password` varchar(50) DEFAULT NULL,
  `user_status` char(1) DEFAULT NULL,
  `user_security_question` varchar(50) DEFAULT NULL,
  `user_security_answer` varchar(50) DEFAULT NULL,
  `user_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_reset` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hak_akses`
--
ALTER TABLE `hak_akses`
  ADD PRIMARY KEY (`ID_AKSES`);

--
-- Indexes for table `map_prog_wil`
--
ALTER TABLE `map_prog_wil`
  ADD PRIMARY KEY (`ID_PROGRAM`,`ID_WILAYAH`),
  ADD KEY `FK_RELATIONSHIP_5` (`ID_WILAYAH`);

--
-- Indexes for table `mobile_user_akses`
--
ALTER TABLE `mobile_user_akses`
  ADD PRIMARY KEY (`ID_PROGRAM`,`ID_WILAYAH`,`ID_USER`),
  ADD KEY `FK_RELATIONSHIP_3` (`ID_USER`);

--
-- Indexes for table `m_menu`
--
ALTER TABLE `m_menu`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `fk_rel_modul_modul_parent` (`kms_menu_id`);

--
-- Indexes for table `m_otoritas_menu`
--
ALTER TABLE `m_otoritas_menu`
  ADD PRIMARY KEY (`roles_id`,`menu_id`),
  ADD KEY `fk_rel_modul_modul` (`menu_id`);

--
-- Indexes for table `m_program`
--
ALTER TABLE `m_program`
  ADD PRIMARY KEY (`ID_PROGRAM`);

--
-- Indexes for table `m_unit`
--
ALTER TABLE `m_unit`
  ADD PRIMARY KEY (`ID_UNIT`);

--
-- Indexes for table `m_user`
--
ALTER TABLE `m_user`
  ADD PRIMARY KEY (`ID_USER`),
  ADD KEY `FK_RELATIONSHIP_1` (`ID_UNIT`),
  ADD KEY `FK_RELATIONSHIP_7` (`ID_AKSES`);

--
-- Indexes for table `m_wilayah`
--
ALTER TABLE `m_wilayah`
  ADD PRIMARY KEY (`ID_WILAYAH`);

--
-- Indexes for table `progres_program`
--
ALTER TABLE `progres_program`
  ADD PRIMARY KEY (`ID_PROGRES`),
  ADD KEY `FK_RELATIONSHIP_8` (`ID_PROGRAM`,`ID_WILAYAH`,`ID_USER`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`roles_id`);

--
-- Indexes for table `user_webadmin`
--
ALTER TABLE `user_webadmin`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `fk_rel_lokasi_user` (`loker_id`),
  ADD KEY `fk_rel_role_user` (`roles_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `map_prog_wil`
--
ALTER TABLE `map_prog_wil`
  ADD CONSTRAINT `FK_RELATIONSHIP_4` FOREIGN KEY (`ID_PROGRAM`) REFERENCES `m_program` (`ID_PROGRAM`),
  ADD CONSTRAINT `FK_RELATIONSHIP_5` FOREIGN KEY (`ID_WILAYAH`) REFERENCES `m_wilayah` (`ID_WILAYAH`);

--
-- Constraints for table `mobile_user_akses`
--
ALTER TABLE `mobile_user_akses`
  ADD CONSTRAINT `FK_RELATIONSHIP_3` FOREIGN KEY (`ID_USER`) REFERENCES `m_user` (`ID_USER`),
  ADD CONSTRAINT `FK_RELATIONSHIP_6` FOREIGN KEY (`ID_PROGRAM`,`ID_WILAYAH`) REFERENCES `map_prog_wil` (`ID_PROGRAM`, `ID_WILAYAH`);

--
-- Constraints for table `m_menu`
--
ALTER TABLE `m_menu`
  ADD CONSTRAINT `fk_rel_modul_modul_parent` FOREIGN KEY (`kms_menu_id`) REFERENCES `m_menu` (`menu_id`);

--
-- Constraints for table `m_otoritas_menu`
--
ALTER TABLE `m_otoritas_menu`
  ADD CONSTRAINT `fk_rel_modul_modul` FOREIGN KEY (`menu_id`) REFERENCES `m_menu` (`menu_id`),
  ADD CONSTRAINT `fk_rel_role_om` FOREIGN KEY (`roles_id`) REFERENCES `role` (`roles_id`);

--
-- Constraints for table `m_user`
--
ALTER TABLE `m_user`
  ADD CONSTRAINT `FK_RELATIONSHIP_1` FOREIGN KEY (`ID_UNIT`) REFERENCES `m_unit` (`ID_UNIT`),
  ADD CONSTRAINT `FK_RELATIONSHIP_7` FOREIGN KEY (`ID_AKSES`) REFERENCES `hak_akses` (`ID_AKSES`);

--
-- Constraints for table `progres_program`
--
ALTER TABLE `progres_program`
  ADD CONSTRAINT `FK_RELATIONSHIP_8` FOREIGN KEY (`ID_PROGRAM`,`ID_WILAYAH`,`ID_USER`) REFERENCES `mobile_user_akses` (`ID_PROGRAM`, `ID_WILAYAH`, `ID_USER`);

--
-- Constraints for table `user_webadmin`
--
ALTER TABLE `user_webadmin`
  ADD CONSTRAINT `fk_rel_lokasi_user` FOREIGN KEY (`loker_id`) REFERENCES `m_loker` (`loker_id`),
  ADD CONSTRAINT `fk_rel_role_user` FOREIGN KEY (`roles_id`) REFERENCES `role` (`roles_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
