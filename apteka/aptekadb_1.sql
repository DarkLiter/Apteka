-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 28 2023 г., 16:31
-- Версия сервера: 8.0.24
-- Версия PHP: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `aptekadb`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'Dima', '$2y$10$xE2ZcXUMmRsrxIHqRDLMl.mMU2GtfHhGpCxAnG4OfKZ11LmBCRXTa');

-- --------------------------------------------------------

--
-- Структура таблицы `zapros`
--

CREATE TABLE `zapros` (
  `id_request` int NOT NULL,
  `id_employee` int NOT NULL,
  `id_product` int NOT NULL,
  `name_product` varchar(20) NOT NULL,
  `quantity` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `zapros`
--

INSERT INTO `zapros` (`id_request`, `id_employee`, `id_product`, `name_product`, `quantity`) VALUES
(1, 2, 2, 'Пентальгин', '2');

-- --------------------------------------------------------

--
-- Структура таблицы `клиенты`
--

CREATE TABLE `клиенты` (
  `id_client` int NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `surname` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `клиенты`
--

INSERT INTO `клиенты` (`id_client`, `first_name`, `last_name`, `surname`, `number`) VALUES
(1, 'Милена', 'Лапина', 'Анатольевна', '89048572849'),
(2, 'Диана', 'Васильевна', 'Леонидовна', '89048028482'),
(3, 'Стелла', 'Дмитриева', 'Фидосеевна', '89049027492'),
(4, 'Борис', 'Андреев', 'Миронович', '89048729447'),
(5, 'Леонид', 'Игнатов', 'Евгеньевич', '89048572349'),
(6, 'Мирон', 'Орлов', 'Оскарович', '89048572849');

-- --------------------------------------------------------

--
-- Структура таблицы `поставки`
--

CREATE TABLE `поставки` (
  `id_supply` int NOT NULL,
  `id_provider` int NOT NULL,
  `id_product` int NOT NULL,
  `id_request` int NOT NULL,
  `date_supply` date NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `поставки`
--

INSERT INTO `поставки` (`id_supply`, `id_provider`, `id_product`, `id_request`, `date_supply`, `quantity`) VALUES
(2, 3, 2, 1, '2023-05-03', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `поставщики`
--

CREATE TABLE `поставщики` (
  `id_provider` int NOT NULL,
  `organization` varchar(30) NOT NULL,
  `supervisor` varchar(20) NOT NULL,
  `number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `поставщики`
--

INSERT INTO `поставщики` (`id_provider`, `organization`, `supervisor`, `number`) VALUES
(1, 'Dignul', 'Рудаков', '89058294833'),
(2, 'Kuho', 'Митяев', '89058294833'),
(3, 'Juno', 'Казунов', '89058294833'),
(4, 'Lunar', 'Матроскин', '89058294833'),
(5, 'Fumos', 'Фарачкин', '89058294833');

-- --------------------------------------------------------

--
-- Структура таблицы `заказы`
--

CREATE TABLE `заказы` (
  `id_order` int NOT NULL,
  `id_application` int NOT NULL,
  `id_client` int NOT NULL,
  `id_product` int NOT NULL,
  `qyantity` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `заказы`
--

INSERT INTO `заказы` (`id_order`, `id_application`, `id_client`, `id_product`, `qyantity`) VALUES
(1, 1, 1, 2, '2');

-- --------------------------------------------------------

--
-- Структура таблицы `заявки`
--

CREATE TABLE `заявки` (
  `id_application` int NOT NULL,
  `id_employee` int NOT NULL,
  `id_client` int NOT NULL,
  `date_application` date NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `заявки`
--

INSERT INTO `заявки` (`id_application`, `id_employee`, `id_client`, `date_application`, `status`) VALUES
(1, 2, 1, '2023-05-10', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `сотрудники`
--

CREATE TABLE `сотрудники` (
  `id_employee` int NOT NULL,
  `job_title` varchar(30) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `сотрудники`
--

INSERT INTO `сотрудники` (`id_employee`, `job_title`, `first_name`, `last_name`, `surname`, `number`) VALUES
(1, 'Руководитель', 'Дмитрий', 'Маряшин', 'Максимович', '89046719136'),
(2, 'Фармацевт', 'Ева', 'Антонова', 'Христофоровна', '89092749023'),
(3, 'Фармацевт', 'Елена', 'Лебедева', 'Михаиловна', '89092474924');

-- --------------------------------------------------------

--
-- Структура таблицы `товары`
--

CREATE TABLE `товары` (
  `id_product` int NOT NULL,
  `name_product` varchar(20) NOT NULL,
  `cost` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `товары`
--

INSERT INTO `товары` (`id_product`, `name_product`, `cost`) VALUES
(1, 'Риностоп', '33.00'),
(2, 'Пентальгин', '151.00'),
(3, 'Кагоцел', '217.00'),
(4, 'Винпоцетин', '38.00'),
(5, 'Лактофильтрум', '247.00'),
(6, 'Терафлекс', '202.00'),
(7, 'Мезим', '59.00'),
(8, 'Афобазол', '349.00'),
(9, 'Некст', '165.00'),
(10, 'Привет, Дима', '1.00'),
(11, 'Проверка', '25.00');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `zapros`
--
ALTER TABLE `zapros`
  ADD PRIMARY KEY (`id_request`) USING BTREE,
  ADD UNIQUE KEY `id_employee_2` (`id_employee`),
  ADD UNIQUE KEY `id_product` (`id_product`),
  ADD KEY `id_employee` (`id_employee`,`id_product`);

--
-- Индексы таблицы `клиенты`
--
ALTER TABLE `клиенты`
  ADD PRIMARY KEY (`id_client`) USING BTREE;

--
-- Индексы таблицы `поставки`
--
ALTER TABLE `поставки`
  ADD PRIMARY KEY (`id_supply`) USING BTREE,
  ADD UNIQUE KEY `id_provider_2` (`id_provider`),
  ADD UNIQUE KEY `id_product` (`id_product`),
  ADD UNIQUE KEY `id_request_2` (`id_request`),
  ADD KEY `id_provider` (`id_provider`,`id_product`),
  ADD KEY `id_request` (`id_request`);

--
-- Индексы таблицы `поставщики`
--
ALTER TABLE `поставщики`
  ADD PRIMARY KEY (`id_provider`);

--
-- Индексы таблицы `заказы`
--
ALTER TABLE `заказы`
  ADD PRIMARY KEY (`id_order`) USING BTREE,
  ADD UNIQUE KEY `id_application_2` (`id_application`),
  ADD UNIQUE KEY `id_client` (`id_client`),
  ADD UNIQUE KEY `id_product` (`id_product`),
  ADD KEY `id_application` (`id_application`,`id_client`,`id_product`);

--
-- Индексы таблицы `заявки`
--
ALTER TABLE `заявки`
  ADD PRIMARY KEY (`id_application`) USING BTREE,
  ADD UNIQUE KEY `id_client_2` (`id_client`),
  ADD UNIQUE KEY `id_employee_2` (`id_employee`),
  ADD KEY `id_employee` (`id_employee`,`id_client`),
  ADD KEY `id_client` (`id_client`);

--
-- Индексы таблицы `сотрудники`
--
ALTER TABLE `сотрудники`
  ADD PRIMARY KEY (`id_employee`);

--
-- Индексы таблицы `товары`
--
ALTER TABLE `товары`
  ADD PRIMARY KEY (`id_product`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `zapros`
--
ALTER TABLE `zapros`
  MODIFY `id_request` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `клиенты`
--
ALTER TABLE `клиенты`
  MODIFY `id_client` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `поставки`
--
ALTER TABLE `поставки`
  MODIFY `id_supply` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `поставщики`
--
ALTER TABLE `поставщики`
  MODIFY `id_provider` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `заказы`
--
ALTER TABLE `заказы`
  MODIFY `id_order` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `заявки`
--
ALTER TABLE `заявки`
  MODIFY `id_application` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `сотрудники`
--
ALTER TABLE `сотрудники`
  MODIFY `id_employee` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `товары`
--
ALTER TABLE `товары`
  MODIFY `id_product` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `поставки`
--
ALTER TABLE `поставки`
  ADD CONSTRAINT `Поставки_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `товары` (`id_product`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `Поставки_ibfk_2` FOREIGN KEY (`id_provider`) REFERENCES `поставщики` (`id_provider`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `Поставки_ibfk_3` FOREIGN KEY (`id_request`) REFERENCES `zapros` (`id_request`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `заказы`
--
ALTER TABLE `заказы`
  ADD CONSTRAINT `Заказы_ibfk_1` FOREIGN KEY (`id_application`) REFERENCES `заявки` (`id_application`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `Заказы_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `товары` (`id_product`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `заявки`
--
ALTER TABLE `заявки`
  ADD CONSTRAINT `Заявки_ibfk_1` FOREIGN KEY (`id_employee`) REFERENCES `сотрудники` (`id_employee`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `Заявки_ibfk_2` FOREIGN KEY (`id_client`) REFERENCES `клиенты` (`id_client`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
