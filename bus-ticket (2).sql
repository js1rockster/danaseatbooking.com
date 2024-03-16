
CREATE TABLE `bookings` (
  `id` int(100) NOT NULL,
  `booking_id` varchar(255) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `route_id` varchar(255) NOT NULL,
  `customer_route` varchar(200) NOT NULL,
  `travel_purpose` varchar(200) NOT NULL,
  `booked_seat` varchar(100) NOT NULL,
  `booking_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `bookings` (`id`, `booking_id`, `customer_id`, `route_id`, `customer_route`, `travel_purpose`, `booked_seat`, `booking_created`) VALUES
(111, 'GLAJ8111', 'CUST-4043241', 'RT-4177168', 'KITENGELA &rarr;  UNIT7', 'Appoitmnent with the manager', '3', '2023-11-22 23:38:29');


CREATE TABLE `buses` (
  `id` int(100) NOT NULL,
  `bus_no` varchar(255) NOT NULL,
  `bus_cap` varchar(50) NOT NULL,
  `bus_assigned` tinyint(1) NOT NULL DEFAULT 0,
  `bus_created` datetime NOT NULL DEFAULT current_timestamp(),
  `bus_assigned_d` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `buses` (`id`, `bus_no`, `bus_cap`, `bus_assigned`, `bus_created`, `bus_assigned_d`) VALUES
(56, 'KDM 254K', '14', 0, '2023-08-29 12:02:29', 1),
(57, 'KCC 121M', '14', 1, '2023-08-29 12:05:53', 1),
(58, 'KDK 101M', '14', 1, '2023-08-29 12:06:57', 0);


CREATE TABLE `customers` (
  `id` int(100) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `customer_name` varchar(30) NOT NULL,
  `customer_phone` varchar(10) NOT NULL,
  `customer_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `customers` (`id`, `customer_id`, `customer_name`, `customer_phone`, `customer_created`) VALUES
(41, 'CUST-4043241', 'Jojo michael', '0977955927', '2023-08-21 16:22:58'),
(42, 'CUST-7925642', 'Ice Mulir0', '0928736488', '2023-08-22 15:00:21'),
(43, 'CUST-2587643', 'Barret Arrt', '0712345678', '2023-08-24 12:58:07'),
(44, 'CUST-4934844', 'Har Nia', '2345678909', '2023-08-28 17:43:37'),
(45, 'CUST-5419245', 'Muthoki Lumumb', '1234567899', '2023-09-06 14:48:30'),
(49, 'CUST-1332449', 'joan  juma', '0746276588', '2023-11-10 08:37:29');



CREATE TABLE `drivers` (
  `id` int(100) NOT NULL,
  `d_id` varchar(255) NOT NULL,
  `d_name` varchar(30) NOT NULL,
  `d_contact` varchar(10) NOT NULL,
  `bus_no` varchar(155) NOT NULL,
  `d_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `drivers` (`id`, `d_id`, `d_name`, `d_contact`, `bus_no`, `d_created`) VALUES
(33, 'DRv-9660533', 'michael ojiambo               ', '0707061627', ' KDM 254K', '2023-11-10 01:11:23'),
(34, 'DRv-4192834', 'joan  juma                    ', '0746256526', ' KCC 121K', '2023-11-10 01:12:16');



CREATE TABLE `routes` (
  `id` int(100) NOT NULL,
  `route_id` varchar(255) NOT NULL,
  `bus_no` varchar(155) NOT NULL,
  `route_cities` varchar(255) NOT NULL,
  `route_dep_date` date NOT NULL,
  `route_dep_time` time NOT NULL,
  `route_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `routes` (`id`, `route_id`, `bus_no`, `route_cities`, `route_dep_date`, `route_dep_time`, `route_created`) VALUES
(58, 'RT-753558', 'KBJ 454K', ' 	UNIT 1,NAIROBI', '0000-00-00', '12:00:00', '2021-10-18 00:04:42'),
(59, 'RT-6028759', 'KBD 321K', 'UNIT 2,UNIT 1', '0000-00-00', '13:55:00', '2021-10-18 00:07:50'),
(60, 'RT-5887160', 'KDL 131K', 'UNIT 1,UNIT 2', '0000-00-00', '10:50:00', '2021-10-18 09:38:30'),
(61, 'RT-7881861', 'KCC 121K', 'UNIT 2,NAIROBI', '0000-00-00', '13:00:00', '2023-08-22 15:10:02'),
(68, 'RT-4177168', 'KDK 101K', 'KITENGELA, UNIT7', '0000-00-00', '12:03:00', '2023-11-10 00:37:17');


CREATE TABLE `seats` (
  `bus_no` varchar(155) NOT NULL,
  `seat_booked` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `seats` (`bus_no`, `seat_booked`) VALUES
('COD 243R', NULL),
('KBJ 4534', NULL),
('KBJ 454K', ''),
('KBS 15', NULL),
('KCC 121K', ''),
('KCM 321K', NULL),
('KDK 101K', '3'),
('KDL 131K', ''),
('KDM 254K', '3'),
('KPB 4567', NULL),
('NBS4455', NULL);


CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_fullname` varchar(100) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `users` (`user_id`, `user_fullname`, `user_name`, `user_password`, `user_created`) VALUES
(1, 'Liam Moore', 'admin', '$2y$10$8u1av48r4vwuEhoMpxATP.CbBaVhAVvI0pI/LmBtDZhNlBhyNV/RC', '2021-06-02 13:55:21'),
(2, 'Test Admin', 'testadmin', '$2y$10$A2eGOu1K1TSBqMwjrEJZg.lgy.FmCUPl/l5ugcYOXv4qKWkFEwcqS', '2021-10-17 21:10:07'),
(3, 'Joel Nsenga', 'NSENGA', '$2y$10$XlL7mNPA9GDzxWoPEsc0YO8hjZFzzhyd41heO0W8dVHJ4gkmmuaeS', '2023-08-21 09:26:34'),
(11, 'michael ojiambo', 'namdi', '$2y$10$omvx/wZVG4ZiapdpVBYeNu5Cli59212w8SCYt0WeJ56svPau/0kb2', '2023-11-09 14:35:31');
