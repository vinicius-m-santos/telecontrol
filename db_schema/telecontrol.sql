-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Tempo de geração: 09/08/2024 às 07:31
-- Versão do servidor: 5.7.44
-- Versão do PHP: 8.2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `telecontrol`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `client`
--

CREATE TABLE `client` (
  `id` int(14) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `address` text NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `client`
--

INSERT INTO `client` (`id`, `name`, `cpf`, `address`, `updated_at`, `created_at`) VALUES
(9, 'Consumidor Teste2', '539.821.190-06', 'Endereco consumidor teste2', '2024-08-09 05:41:11', '2024-08-09 03:10:56'),
(11, 'fdssafsa2', '329.390.550-17', 'Rua teste, 123', '2024-08-09 07:21:45', '2024-08-09 06:20:38'),
(16, 'dsfsadf', '335.559.190-40', 'dfasdfasd', '2024-08-09 06:26:57', '2024-08-09 06:26:57'),
(18, 'Consumidor Teste', '766.167.130-16', '', '2024-08-09 07:05:25', '2024-08-09 07:05:25');

-- --------------------------------------------------------

--
-- Estrutura para tabela `product`
--

CREATE TABLE `product` (
  `id` int(14) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `warranty_time` text NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `product`
--

INSERT INTO `product` (`id`, `code`, `description`, `status`, `warranty_time`, `updated_at`, `created_at`) VALUES
(1, 'dfasd', 'sdafdsa', 1, '21312', '2024-08-09 03:08:32', '2024-08-09 03:08:32'),
(2, 'produto4', 'Uma bela descricao2.', 1, '3 anos', '2024-08-09 07:21:55', '2024-08-09 03:09:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `service_order`
--

CREATE TABLE `service_order` (
  `id` int(14) UNSIGNED NOT NULL,
  `order_number` int(14) NOT NULL,
  `opening_date` date NOT NULL,
  `consumer_id` int(14) UNSIGNED NOT NULL,
  `consumer_name` varchar(255) NOT NULL,
  `consumer_cpf` varchar(14) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `service_order`
--

INSERT INTO `service_order` (`id`, `order_number`, `opening_date`, `consumer_id`, `consumer_name`, `consumer_cpf`, `updated_at`, `created_at`) VALUES
(2, 1232, '2024-08-08', 9, 'Consumidor Teste', '766.167.130-16', '2024-08-09 03:10:56', '2024-08-09 03:10:56'),
(3, 444, '2024-08-09', 9, 'Consumidor Teste2', '539.821.190-06', '2024-08-09 05:41:46', '2024-08-09 05:41:46'),
(5, 1232, '2024-08-08', 18, 'Consumidor Teste', '766.167.130-16', '2024-08-09 07:05:25', '2024-08-09 07:05:25');

-- --------------------------------------------------------

--
-- Estrutura para tabela `service_order_product`
--

CREATE TABLE `service_order_product` (
  `id` int(14) UNSIGNED NOT NULL,
  `service_order_id` int(14) UNSIGNED NOT NULL,
  `product_id` int(14) UNSIGNED NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `service_order_product`
--

INSERT INTO `service_order_product` (`id`, `service_order_id`, `product_id`, `updated_at`, `created_at`) VALUES
(2, 2, 1, '2024-08-09 03:10:56', '2024-08-09 03:10:56'),
(3, 2, 2, '2024-08-09 03:10:56', '2024-08-09 03:10:56'),
(4, 3, 2, '2024-08-09 05:41:46', '2024-08-09 05:41:46'),
(28, 5, 2, '2024-08-09 07:16:19', '2024-08-09 07:16:19');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `service_order`
--
ALTER TABLE `service_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_service_order_client` (`consumer_id`);

--
-- Índices de tabela `service_order_product`
--
ALTER TABLE `service_order_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_service_order_products_service_order` (`service_order_id`),
  ADD KEY `fk_service_order_products_product` (`product_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `client`
--
ALTER TABLE `client`
  MODIFY `id` int(14) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `product`
--
ALTER TABLE `product`
  MODIFY `id` int(14) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `service_order`
--
ALTER TABLE `service_order`
  MODIFY `id` int(14) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `service_order_product`
--
ALTER TABLE `service_order_product`
  MODIFY `id` int(14) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `service_order`
--
ALTER TABLE `service_order`
  ADD CONSTRAINT `fk_service_order_client` FOREIGN KEY (`consumer_id`) REFERENCES `client` (`id`);

--
-- Restrições para tabelas `service_order_product`
--
ALTER TABLE `service_order_product`
  ADD CONSTRAINT `fk_service_order_products_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `fk_service_order_products_service_order` FOREIGN KEY (`service_order_id`) REFERENCES `service_order` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
