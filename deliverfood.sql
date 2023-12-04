-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2023 at 07:54 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `deliverfood`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cid` int(11) NOT NULL,
  `name` text NOT NULL,
  `phone` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cid`, `name`, `phone`, `email`, `password`) VALUES
(2, 'somsak', '32255', 'somsak@blabal.com', '$2y$10$UljcLzX14DpfT8Tdi/QLTOqm62OSaraXjU1/URziwm4cdLXmW6ytG'),
(3, 'rak', '0000', 'rak@blaba.com', '$2y$10$G6DDPUI9dMrSb3ippKl3ze0zCziwsT6QELaAGe.elpjZUoQk1ncqy'),
(5, 'naritzaa', '0123654', 'ployzaa@zap.com', '$2y$10$3hQvRowkOB3M9XFcqWUnd.M8Oi0hNYORSFvWgWJSVQhA7y9l0UtDm'),
(6, 'jazz', '123654', 'adama@food.com', '$2y$10$Pzc76gVSEpXtqSu4cUbMXeHGwqpFL.1ut5o4NodRfD/1g597jTtve'),
(7, 'Nairtza', '0621459228', 'naritza@gmail.com', '$2y$10$nR89U39zbrjcmfRIJT1xTebkYt/pWeNGLuXsJZbSO2DnC4GDz9uBG');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `foodid` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `name` text NOT NULL,
  `price` int(11) NOT NULL,
  `image` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`foodid`, `type`, `name`, `price`, `image`, `description`) VALUES
(1, 1, 'ผัดกะเพราหมูย่าง', 150, 'https://img.thaibuffer.com/u/2015/surauch/Cook/hit2.jpg', 'หมูย่าง ราดบนข้าวสวยร้อน ๆ โปะไข่ดาวสักหน่อย ฟินเวอร์'),
(2, 1, 'ข้าวหมูแดง', 120, 'https://www.smeleader.com/wp-content/uploads/2019/08/DSC_0343.jpg', 'หมูแดง เป็นอาหารที่ได้จากการย่างเนื้อหมูที่ปรุงรส นิยมใช้เนื้อหมูส่วนสันนอก, สามชั้น, สันคอหรือคอหมู[1] ในประเทศจีนเรียกว่า ชาสิ่ว (叉燒) โดยจะหมักเนื้อหมูกับน้ำผึ้ง, ผงพะโล้, ข้าวแดง, เต้าหู้ยี้, ซอสถั่วเหลือง, ซอสฮอยซิน และอาจผสมเหล้าเชอร์รีหรือเหล้าหวงจิ่ว ก่อนจะนำไปย่าง หมูแดงของจีนใช้เป็นส่วนประกอบในเมนูบะหมี่ ข้าวหรือซาลาเปาหน้าแตก ในฮ่องกงจะขายหมูแดงในร้านซิวเหม่ย (燒味) ร่วมกับไก่หรือห่านย่า'),
(3, 1, 'ข้าวผัดต้มยำกุ้ง', 130, 'https://www.taokaecafe.com/asp-bin/pic_taokae/sh19643055.jpg', 'เมื่อชาวอินเดียและเปอร์เซียมาติดต่อค้าขายกับประเทศไทย ได้นำข้าวหมกมาเผยแพร่ด้วย ดังมีปรากฏใน กาพย์เห่ชมเครื่องคาวหวาน ของพระบาทสมเด็จพระพุทธเลิศหล้านภาลัยว่า \"ข้าวหุงปรุงอย่างเทศ รสพิเศษใส่ลูกเอ็น\" ข้าวหมกแบบเปอร์เซีย-อาหรับหุงกับเครื่องเทศ เมื่อสุกแล้วโรยหอมแดงเจียว ลูกเกดและอัลมอนด์ ส่วนข้าวหมกที่ใส่ผงขมิ้น สีเหลืองสุกแล้วกินกับเนื้อสัตว์อบ คนไทยเรียกข้าวบุหรี่ ในปัจจุบัน ข้าวหมกที่คนไทยรู้จักกันดีที่สุดคือข้าวหมกไก่ ซึ่งตรงกับข้าวหมกประเภทบิรยานีของอินเดีย'),
(4, 1, 'ข้าวผัดต้มยำกุ้ง', 150, 'https://krua.co/wp-content/uploads/2020/07/RT1572_ImageBanner_1140x507-01-scaled.jpg', 'ข้าวผัดต้มยำกุ้ง  เป็น อาหารที่มีรสความจัดจ้านของ ต้มยำกุ้ง ผสมผสานกับข้าวหอมๆ นำมาผัด รวมกัน ได้ ข้าวผัด ที่มีความเผ็ดและความเปรี้ยว ผสมกันอยู่ เสน่ห์ของเมนูข้าวผัด และ ต้มยำกุ้ง'),
(5, 1, 'หมี่ผัดผักกระเฉดกุ้ง', 150, 'https://f.ptcdn.info/339/044/000/oa8wipjbwJziI436RWB-o.jpg', 'หมี่ผัดผักกระเฉดกุ้ง อร่อย เริ่ดๆ เวร่อๆ'),
(6, 1, 'เกี๊ยวกรอบผัดไทย', 150, 'https://krua.co/wp-content/uploads/2020/12/RT1612_ImageBannerMobile_960x633_New_-6.jpg', 'เบื่อผัดไทยธรรมดามาลองผัดไทยเกี๊ยวกรอบดูบ้าง เกี๊ยวทอดกรุบกรอบไส้ตูมๆผัดกับเครื่องผัดไทยให้น้ำซอสเคลือบตัวเกี๊ยวจนทั่ว กินร้อนๆคู่กับใบกุยช่ายและถั่วงอก เป็นเมนูอาหารธรรมดาแต่รสชาติไม่ธรรมดาแน่นอน'),
(7, 2, '\0ไข่ลูกสะใภ้', 170, 'https://www.noobeebee.com/wp-content/uploads/2017/08/21151507_736724953180882_4351946396468790212_n.jpg', ' “เมนูไข่ลูกสะใภ้” เมนูไข่ทอดกรอบ ๆ ราดซอสมะขามรสเปรี้ยวอมหวาน '),
(8, 2, 'เต้าหู้ไข่น้ำแดง', 150, 'https://www.rakluke.com/images/2020/kid-nutrition/3579.webp', 'เต้าหู้น้ำแดง อาหารจีนเก่าแก่ ที่คนจีนทุกเพศทุกวัยชื่นชอบ มีความอร่อย รสชาติไม่จัดเกินไป ทานง่าย ยิ่งคนสูงอายุแล้วยิ่งชอบใหญ่เพราะคล่องคอและแทนการทานโปรตีนจากเนื้อสัตว์ได้อีกด้วย'),
(9, 2, '\0ผัดฉ่าทะเล ', 160, 'https://s.isanook.com/wo/0/rp/r/w850/ya0xa0m1w0/aHR0cHM6Ly9zLmlzYW5vb2suY29tL3dvLzAvdWQvMzQvMTc0NzY1L2Zvb2QuanBn.jpg', 'กลิ่นหอม น่าอร่อย สดใหม่จากทะเล ต้องลองเลย!!!'),
(10, 2, 'หอยลายผัดพริกเผา', 200, 'https://i.ytimg.com/vi/A7GhOsROyzM/maxresdefault.jpg', 'อาหารสูตรเริ่ดๆ เมนูแนะนำ หอยลายผัดพริกเผากลิ่นหอมมากกก'),
(11, 2, '\0กุ้งคั่วพริกเกลือ', 260, 'https://krua.co/wp-content/uploads/2020/09/RT1132_ImageBannerMobile_960x633_New_-01-scaled.jpg', 'กุ้งคั่วพริกเกลือหอมๆเนื้อกุ้งเด้งเเน่นๆเน้นๆ รสชาติเข้มข้นจัดจ้าน ยิ่งกินกับข้าวสวยร้อนๆบอกเลยว่าเด็ดมาก'),
(12, 2, '\0ห่อหมกปลากราย', 100, 'https://www.smeleader.com/wp-content/uploads/2022/03/%E0%B8%AA%E0%B8%B9%E0%B8%95%E0%B8%A3%E0%B8%AB%E0%B9%88%E0%B8%AD%E0%B8%AB%E0%B8%A1%E0%B8%81-1.png', 'ห่อหมกปลากราย อร่อย'),
(13, 2, 'กะหล่ำปลีผัดน้ำปลา', 120, 'https://www.naibann.com/wp-content/uploads/2017/06/1111111.jpg', 'กะหล่ำปลีผัดน้ำปลา เมนูนี้เหมือนจะธรรมดาแต่ไม่ธรรมดา'),
(14, 3, '\0ขนมปังกระเทียมครีมชีส', 140, 'https://krua.co/wp-content/uploads/2020/08/RI1526_ImageBanner_1140x507-01.jpg', 'อร่อยๆๆ เริ่ดที่เมนูใหม่'),
(15, 3, '\0เกี๊ยวซ่า', 200, 'https://rimage.gnst.jp/livejapan.com/public/article/detail/a/00/00/a0000454/img/basic/a0000454_main.jpg?20170412195628', 'ของทานเล่นสุดฟิน เมนูเกี๊ยวซ่าแสนอร่อย'),
(16, 3, '\0กล้วยทอด มันทอด', 188, 'https://i.ytimg.com/vi/aQ1nIEuNMQ8/maxresdefault.jpg', 'อร่อยสุดฟิน'),
(17, 3, 'สตรอเบอร์รี่เคลือบน้ำตาล', 90, 'https://img.wongnai.com/p/1920x0/2019/07/08/55ffc1769d554d98bedf6174c0818b6c.jpg', 'ถังหูลู่ สตรอเบอร์รี่เคลือบน้ำตาล อันยองงงง ถ้าพูดถึงประเทศเกาหลีใต้สายกินหลายคนคงคิดถึง สตรีทฟู้ด เกาหลี'),
(18, 3, '\0กล้วยทับ ราดน้ำกะทิ', 150, 'https://i.ytimg.com/vi/Wq4e7-_qpw0/maxresdefault.jpg', 'กล้วยทับ ราดน้ำกะทิ หอมหวาน น่าทาน สุดฟิน'),
(19, 3, 'กุ้งฝอยทอดกรอบ', 240, 'https://blog.samanthasmommy.com/wp-content/uploads/2013/10/%E0%B8%81%E0%B8%B8%E0%B9%89%E0%B8%87%E0%B8%97%E0%B8%AD%E0%B8%94%E0%B8%81%E0%B8%A3%E0%B8%AD%E0%B8%9A4.jpg', 'กุ้งฝอยทอดกรอบ หอม กรอบ อร่อยสุดฟิน'),
(20, 3, 'นักเก็ตไก่', 129, 'https://food.mthai.com/app/uploads/2017/08/NUGGET.jpg', 'นักเก็ตไก่ เจ้าดีเจ้าดังยกให้ร้านนี้ อร่อยสุดฟินไปเลย'),
(21, 4, '\0ทาร์ตบราวนี', 139, 'https://img.wongnai.com/p/1920x0/2019/01/28/84e7ba5d632940bb81506e15bfef2150.jpg', 'ทาร์ตบราวนี เนื้อละมุน หอมหวาน ทานได้ไม่เบื่อยกให้เมนูนี้เลย'),
(22, 4, 'เครปเค้ก', 159, 'https://img.wongnai.com/p/1920x0/2018/01/15/4025aa2489d14cbab81913d9989415ec.jpg', 'เครปเค้ก แป้งเครปสุดหอม เนื้อเนียน อร่อยสุดฟิน'),
(23, 4, '\0แพนเค้กกล้วยหอม', 99, 'https://img-global.cpcdn.com/recipes/8ffff9eda7fd6ccd/1200x630cq70/photo.jpg', 'แพนเค้กเนื้อนุ่ม อบกลิ่นกล้วยหอมชวนหลงไหล ลองเลย'),
(24, 4, '\0วาฟเฟิลกล้วยช็อกโกแลต', 79, 'https://3.bp.blogspot.com/-57sCwTPzMzg/WyI1-479_qI/AAAAAAAAeIc/2pfUwYY9UQwMEkYPYiWz2IS0U0AxoSJLgCLcBGAs/s1600/CHOCOLATE%2BCHIPS%2BWAFFLE_2.jpg', 'วาฟเฟิลกรอบนอกนุ่มใน หอมหวานน่าทาน'),
(25, 4, 'ชอร์ตเค้กสตรอว์เบอร์รี', 199, 'https://krua.co/wp-content/uploads/2020/10/RB0407_ImageBannerMobile_960x633_New.jpg', 'ชอร์ตเค้กสตรอว์เบอร์รี กลิ่นหอมหวานน่าทาน ลองเลย'),
(26, 4, '\0บัวลอยไข่หวาน', 119, 'https://img.kapook.com/u/2022/wanwanat/1079595941.jpg', 'บัวลอยไข่หวาน น้ำกะทิหอมหวานชวนลอง อร่อยสุดฟิน'),
(27, 4, 'สาคูเปียกมะพร้าวอ่อน', 99, 'https://i.ytimg.com/vi/0W7mwHNKUZQ/sddefault.jpg', 'สาคูเปียกมะพร้าวอ่อน หอมมะพร้าวอ่อน น่าทาน ชวนให้ลอง ลองเลย');

-- --------------------------------------------------------

--
-- Table structure for table `iorder`
--

CREATE TABLE `iorder` (
  `id` int(11) NOT NULL,
  `cusid` int(11) NOT NULL,
  `date` date NOT NULL,
  `note` text NOT NULL,
  `address` text NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `iorder`
--

INSERT INTO `iorder` (`id`, `cusid`, `date`, `note`, `address`, `status`) VALUES
(1, 7, '2023-03-18', '', '104 msu', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orderamount`
--

CREATE TABLE `orderamount` (
  `orderid` int(11) NOT NULL,
  `foodid` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderamount`
--

INSERT INTO `orderamount` (`orderid`, `foodid`, `amount`) VALUES
(1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `phone` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`id`, `name`, `email`, `password`, `phone`) VALUES
(1, 'naritzaa', 'narit@admin.com', '$2y$10$Pleq25uNysjaDuVuDKtekO.IeE.fg49M2X1SDXlhrx0j4igz.114e', '023654'),
(2, 'jamjam', 'peapea@admin.com', '$2y$10$Zg8JzCX31MUMMZGcXC698.mAsVBI1X9IuNuuHjHmbJkZYjDQpch9C', '023654254');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `ids` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`ids`, `status`) VALUES
(1, 'ยังไม่ส่ง'),
(2, 'จัดส่งแล้ว');

-- --------------------------------------------------------

--
-- Table structure for table `typefood`
--

CREATE TABLE `typefood` (
  `typeid` int(11) NOT NULL,
  `type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `typefood`
--

INSERT INTO `typefood` (`typeid`, `type`) VALUES
(1, 'อาหารจานเดียว'),
(2, 'กับข้าว'),
(3, 'ทานเล่น'),
(4, 'ของหวาน');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`foodid`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `iorder`
--
ALTER TABLE `iorder`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer` (`cusid`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `orderamount`
--
ALTER TABLE `orderamount`
  ADD PRIMARY KEY (`orderid`,`foodid`),
  ADD KEY `foodid` (`foodid`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`ids`);

--
-- Indexes for table `typefood`
--
ALTER TABLE `typefood`
  ADD PRIMARY KEY (`typeid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `foodid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `iorder`
--
ALTER TABLE `iorder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `ids` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `typefood`
--
ALTER TABLE `typefood`
  MODIFY `typeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `food`
--
ALTER TABLE `food`
  ADD CONSTRAINT `type` FOREIGN KEY (`type`) REFERENCES `typefood` (`typeid`) ON DELETE CASCADE;

--
-- Constraints for table `iorder`
--
ALTER TABLE `iorder`
  ADD CONSTRAINT `customer` FOREIGN KEY (`cusid`) REFERENCES `customer` (`cid`) ON DELETE CASCADE,
  ADD CONSTRAINT `status` FOREIGN KEY (`status`) REFERENCES `status` (`ids`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orderamount`
--
ALTER TABLE `orderamount`
  ADD CONSTRAINT `foodid` FOREIGN KEY (`foodid`) REFERENCES `food` (`foodid`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderid` FOREIGN KEY (`orderid`) REFERENCES `iorder` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
