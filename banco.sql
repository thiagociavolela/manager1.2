CREATE TABLE `config_api` (
  `id` int NOT NULL,
  `url_api` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `api_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `contas` (
  `id` int NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `resenha` varchar(255) NOT NULL,
  `cpf` varchar(15) NOT NULL,
  `whatsapp` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `contas` (`id`, `nome`, `email`, `senha`, `resenha`, `cpf`, `whatsapp`, `file_name`, `token`) VALUES
(1, 'Admin', 'admin@cursodev.com', '5ea30dbe60956c6ac9c0f49fc8655cf3', '5ea30dbe60956c6ac9c0f49fc8655cf3', '000.000.000-00', '(11)91708-0051', '', '3283f99b7b62748b5b64ab7aa171440a');


ALTER TABLE `config_api`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `contas`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `config_api`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

ALTER TABLE `contas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

