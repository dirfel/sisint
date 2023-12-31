-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28/12/2023 às 21:10
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `agenda`
--
CREATE DATABASE IF NOT EXISTS `agenda` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `agenda`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `evento`
--

CREATE TABLE `evento` (
  `id` int(11) NOT NULL,
  `autor` int(11) NOT NULL,
  `titulo` varchar(30) NOT NULL,
  `descricao` varchar(3000) NOT NULL,
  `anexo` varchar(200) NOT NULL DEFAULT '',
  `datahorainicio` datetime NOT NULL,
  `datahorafim` datetime NOT NULL,
  `viz` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `evento`
--


--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `evento`
--
ALTER TABLE `evento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;
--
-- Banco de dados: `arranchamento`
--
CREATE DATABASE IF NOT EXISTS `arranchamento` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `arranchamento`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `arranchado`
--

CREATE TABLE `arranchado` (
  `id` int(8) NOT NULL,
  `data` varchar(10) NOT NULL,
  `iduser` int(4) NOT NULL,
  `idpgrad` int(3) NOT NULL,
  `idsu` int(3) NOT NULL,
  `nomeguerra` varchar(40) NOT NULL,
  `cafe` varchar(3) NOT NULL,
  `almoco` varchar(3) NOT NULL,
  `jantar` varchar(3) NOT NULL,
  `datagrava` varchar(10) NOT NULL,
  `horagrava` varchar(10) NOT NULL,
  `quemgrava` varchar(30) NOT NULL,
  `modo` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `arranchado`
--


-- --------------------------------------------------------

--
-- Estrutura para tabela `cardapio`
--

CREATE TABLE `cardapio` (
  `id` int(6) NOT NULL,
  `data` varchar(10) NOT NULL,
  `cafe` mediumtext NOT NULL,
  `almoco` mediumtext NOT NULL,
  `jantar` mediumtext NOT NULL,
  `responsavel` varchar(50) NOT NULL,
  `datacadastro` varchar(10) NOT NULL,
  `horacadastro` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `cardapio`
--


--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `arranchado`
--
ALTER TABLE `arranchado`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `cardapio`
--
ALTER TABLE `cardapio`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `arranchado`
--
ALTER TABLE `arranchado`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78101;

--
-- AUTO_INCREMENT de tabela `cardapio`
--
ALTER TABLE `cardapio`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Banco de dados: `controlepessoal`
--
CREATE DATABASE IF NOT EXISTS `controlepessoal` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `controlepessoal`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `dependentes_fusex`
--

CREATE TABLE `dependentes_fusex` (
  `id` int(11) NOT NULL COMMENT 'autoincrement',
  `id_titular` int(11) NOT NULL COMMENT 'id da tabela membros.usuarios ou id x -1 da tabela guarda.visitante',
  `prec_cp` varchar(11) NOT NULL,
  `parentesco` varchar(15) NOT NULL,
  `id_visitante` int(11) NOT NULL,
  `nascimento` varchar(10) NOT NULL,
  `obs` varchar(100) NOT NULL DEFAULT '-'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `dependentes_fusex`
--


-- --------------------------------------------------------

--
-- Estrutura para tabela `ferias`
--

CREATE TABLE `ferias` (
  `id` int(11) NOT NULL,
  `id_usr` int(11) NOT NULL COMMENT 'id do usuário de referência',
  `tipo` int(11) NOT NULL COMMENT '0 = cadastro inicial, 1 = ferias, 2 = desconto em férias',
  `anoref` int(11) NOT NULL COMMENT 'ano de referencia das férias',
  `datainicio` date NOT NULL COMMENT 'caso tipo = 0, indica a data que começa a fazer jus a primeira férias no sistema (data incorporação)',
  `datafim` date DEFAULT NULL COMMENT 'pode ser nulo somente se tipo = 0',
  `gozado` int(11) NOT NULL COMMENT '0 = não iniciado, -1=iniciado e não apresentado, outros = nr de dias'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `dependentes_fusex`
--
ALTER TABLE `dependentes_fusex`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `ferias`
--
ALTER TABLE `ferias`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `dependentes_fusex`
--
ALTER TABLE `dependentes_fusex`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'autoincrement', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `ferias`
--
ALTER TABLE `ferias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Banco de dados: `guarda`
--
CREATE DATABASE IF NOT EXISTS `guarda` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `guarda`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `liv_partes_ofdia`
--

CREATE TABLE `liv_partes_ofdia` (
  `id` int(11) NOT NULL,
  `idusuario` int(5) NOT NULL,
  `data` date NOT NULL,
  `idofdia` int(5) NOT NULL,
  `idofdia_anterior` int(5) NOT NULL,
  `idofdia_proximo` int(5) NOT NULL,
  `bi` int(3) NOT NULL,
  `bi_data` date NOT NULL,
  `parada` varchar(1) NOT NULL,
  `parada_obs` text DEFAULT NULL,
  `punidos` varchar(1) NOT NULL,
  `instalacoes` varchar(1) NOT NULL,
  `instalacoes_obs` text DEFAULT NULL,
  `carga` varchar(1) NOT NULL,
  `carga_obs` text DEFAULT NULL,
  `idleituras` int(5) NOT NULL DEFAULT 0,
  `rancho` varchar(1) NOT NULL,
  `rancho_fiscdia` varchar(1) NOT NULL,
  `rancho_cozdia` varchar(1) NOT NULL,
  `rancho_obs` text DEFAULT NULL,
  `idsobrasresiduos` int(5) NOT NULL DEFAULT 0,
  `abastecimento` varchar(1) NOT NULL,
  `abastecimento_obs` text DEFAULT NULL,
  `apresentacaomil` varchar(1) NOT NULL,
  `apresentacaomil_obs` text DEFAULT NULL,
  `ocorrencias` varchar(1) NOT NULL,
  `ocorrencias_obs` text DEFAULT NULL,
  `anexos` text NOT NULL,
  `editar` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `liv_partes_ofdia_leituras`
--

CREATE TABLE `liv_partes_ofdia_leituras` (
  `id_leituras` int(11) NOT NULL,
  `energia_anterior` int(8) NOT NULL COMMENT 'Recebimento do Sv',
  `energia1` int(8) NOT NULL DEFAULT 0 COMMENT 'Leitura das 12:00',
  `energia2` int(8) NOT NULL DEFAULT 0 COMMENT 'Leitura das 18:00',
  `energia_atual` int(8) NOT NULL COMMENT 'Passagem do Sv',
  `agua_int_anterior` int(8) NOT NULL,
  `agua_int_atual` int(8) NOT NULL,
  `agua_ext_anterior` int(8) NOT NULL,
  `agua_ext_atual` int(8) NOT NULL,
  `temp1` int(11) DEFAULT NULL,
  `temp2` int(11) DEFAULT NULL,
  `temp3` int(11) DEFAULT NULL,
  `temp5` int(11) DEFAULT NULL,
  `temp6` int(11) DEFAULT NULL,
  `temp7` int(11) DEFAULT NULL,
  `umid1` int(11) DEFAULT NULL,
  `umid2` int(11) DEFAULT NULL,
  `umid3` int(11) DEFAULT NULL,
  `umid5` int(11) DEFAULT NULL,
  `umid6` int(11) DEFAULT NULL,
  `umid7` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `liv_partes_ofdia_punidos`
--

CREATE TABLE `liv_partes_ofdia_punidos` (
  `id_punidos` int(11) NOT NULL,
  `idlivro` int(5) NOT NULL DEFAULT 0,
  `idpunido` int(5) NOT NULL,
  `punicao` varchar(30) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_termino` date NOT NULL,
  `p_bi` int(3) NOT NULL,
  `p_bi_data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `liv_partes_ofdia_sobrasresiduos`
--

CREATE TABLE `liv_partes_ofdia_sobrasresiduos` (
  `id_sobrasresiduos` int(11) NOT NULL,
  `sobras` int(8) NOT NULL,
  `residuos` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_vtr`
--

CREATE TABLE `pedido_vtr` (
  `id` int(11) NOT NULL COMMENT 'um id é gerado para cada viatura solicitada. Um pedido com mais de uma viatura criará mais de uma linha na tabela',
  `id_viatura` text NOT NULL COMMENT 'id da viatura da tabela viatura serializado',
  `id_solicitante` int(11) NOT NULL,
  `natureza` varchar(50) NOT NULL,
  `itinerario` varchar(50) NOT NULL,
  `distancia` int(11) NOT NULL,
  `total_passageiros` int(2) NOT NULL,
  `datahora_saida` datetime NOT NULL,
  `datahora_chegada` datetime NOT NULL,
  `abastecimento` varchar(1) NOT NULL,
  `alojamento` varchar(1) NOT NULL,
  `arranchamento` varchar(1) NOT NULL,
  `situacao` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `rel_alojamento`
--

CREATE TABLE `rel_alojamento` (
  `id` int(11) NOT NULL,
  `idmembro` int(5) NOT NULL,
  `data` varchar(10) NOT NULL,
  `hora` varchar(10) NOT NULL,
  `idusuario` int(5) NOT NULL,
  `situacao` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `rel_militares`
--

CREATE TABLE `rel_militares` (
  `id` int(11) NOT NULL,
  `idmembro` int(5) NOT NULL,
  `data` varchar(10) NOT NULL,
  `hora` varchar(10) NOT NULL,
  `idusuario` int(5) NOT NULL,
  `situacao` varchar(50) NOT NULL,
  `bicicleta` varchar(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `rel_pernoite`
--

CREATE TABLE `rel_pernoite` (
  `id` int(11) NOT NULL,
  `idmembro` int(5) NOT NULL,
  `idvisitante` int(5) NOT NULL,
  `data` varchar(10) NOT NULL,
  `idusuario` int(5) NOT NULL,
  `situacao` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `rel_rot_guarda`
--

CREATE TABLE `rel_rot_guarda` (
  `id` int(11) NOT NULL,
  `idmembro` int(5) NOT NULL,
  `data` varchar(10) NOT NULL,
  `idusuario` int(5) NOT NULL,
  `idfuncao` int(4) NOT NULL,
  `armamento1` varchar(50) NOT NULL,
  `armamento2` varchar(50) NOT NULL,
  `num_armamento1` int(10) NOT NULL,
  `num_armamento2` int(10) NOT NULL,
  `idquarto` int(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `rel_rot_postos`
--

CREATE TABLE `rel_rot_postos` (
  `id` int(11) NOT NULL,
  `idusuario` int(5) NOT NULL,
  `idquartohora` int(4) NOT NULL,
  `data` varchar(10) NOT NULL,
  `p1` int(4) NOT NULL,
  `p2` int(4) NOT NULL,
  `p3` int(4) NOT NULL,
  `p4` int(4) NOT NULL,
  `p5` int(4) NOT NULL,
  `p6` int(4) NOT NULL,
  `aloj1` int(4) NOT NULL,
  `aloj2` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `rel_rot_ronda`
--

CREATE TABLE `rel_rot_ronda` (
  `id` int(11) NOT NULL,
  `idmembro` int(5) NOT NULL,
  `idusuario` int(5) NOT NULL,
  `tipo` varchar(5) NOT NULL DEFAULT 'ambos' COMMENT 'ronda, perma, ambos',
  `data_p` varchar(10) DEFAULT NULL,
  `hora_p` varchar(10) DEFAULT NULL,
  `idfuncao` int(4) NOT NULL,
  `alteracao` int(4) NOT NULL DEFAULT 0,
  `obs` text NOT NULL,
  `data_r` varchar(10) DEFAULT NULL,
  `hora_r` varchar(10) DEFAULT NULL,
  `p1` int(4) DEFAULT NULL,
  `p2` int(4) DEFAULT NULL,
  `p3` int(4) DEFAULT NULL,
  `p4` int(4) DEFAULT NULL,
  `p5` int(4) NOT NULL DEFAULT 0,
  `p6` int(4) NOT NULL DEFAULT 0,
  `aloj1` int(4) DEFAULT NULL,
  `aloj2` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `rel_viaturas`
--

CREATE TABLE `rel_viaturas` (
  `id` int(11) NOT NULL,
  `idvtr` int(5) NOT NULL,
  `data` varchar(10) NOT NULL,
  `hora` varchar(10) NOT NULL,
  `idusuario` int(5) NOT NULL,
  `situacao` varchar(50) NOT NULL,
  `ficha` varchar(10) NOT NULL,
  `idchvtr` int(5) NOT NULL,
  `idmtr` int(5) NOT NULL,
  `odometro` int(6) NOT NULL,
  `destino` varchar(50) NOT NULL,
  `idsaida` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `rel_visitantes`
--

CREATE TABLE `rel_visitantes` (
  `id` int(11) NOT NULL,
  `idvisitante` int(5) NOT NULL,
  `data` varchar(10) NOT NULL,
  `hora` varchar(10) NOT NULL,
  `idusuario` int(5) NOT NULL,
  `situacao` varchar(50) NOT NULL,
  `idveiculo` int(5) NOT NULL,
  `destino` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `rot_guarda_funcao`
--

CREATE TABLE `rot_guarda_funcao` (
  `id` int(11) NOT NULL,
  `idfuncao` int(4) NOT NULL,
  `nomefuncao` varchar(50) NOT NULL,
  `nomefuncaosimples` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `rot_guarda_funcao`
--

INSERT INTO `rot_guarda_funcao` (`id`, `idfuncao`, `nomefuncao`, `nomefuncaosimples`) VALUES
(1, 1, 'Oficial de Dia / ORC', 'Of Dia / ORC'),
(2, 2, 'Auxiliar do Cmt Gda', 'Aux Cmt Gda'),
(3, 3, 'Adjunto', 'Adj'),
(4, 4, 'Sargento de Dia', 'Sgt Dia'),
(5, 5, 'Comandante da Guarda', 'Cmt Gda'),
(6, 6, 'Cabo da Guarda', 'Cb Gda'),
(7, 7, 'Motorista de Dia', 'Motr Dia'),
(8, 8, 'Cabo de Dia', 'Cb Dia'),
(9, 9, 'Plantão', 'Plantão'),
(10, 10, 'Sentinela', 'Sentinela'),
(11, 11, 'Reforço da Guarda', 'Rfr Gda'),
(12, 12, 'Anotador', 'Anotador'),
(13, 13, 'Permanência Bia Msl', 'Perm Bia Msl');

-- --------------------------------------------------------

--
-- Estrutura para tabela `rot_guarda_quarto`
--

CREATE TABLE `rot_guarda_quarto` (
  `id` int(11) NOT NULL,
  `idquarto` int(4) NOT NULL,
  `nomequarto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `rot_guarda_quarto`
--

INSERT INTO `rot_guarda_quarto` (`id`, `idquarto`, `nomequarto`) VALUES
(1, 0, 'Nenhum'),
(2, 1, 'Primeiro Quarto'),
(3, 2, 'Segundo Quarto'),
(4, 3, 'Terceiro Quarto');

-- --------------------------------------------------------

--
-- Estrutura para tabela `rot_postos_quartohora`
--

CREATE TABLE `rot_postos_quartohora` (
  `id` int(11) NOT NULL,
  `quartohora` varchar(20) NOT NULL,
  `tipo` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `rot_postos_quartohora`
--

INSERT INTO `rot_postos_quartohora` (`id`, `quartohora`, `tipo`) VALUES
(1, '08:00 - 10:00', 'P'),
(2, '10:00 - 12:00', 'P'),
(3, '12:00 - 14:00', 'P'),
(4, '14:00 - 16:00', 'P'),
(5, '16:00 - 18:00', 'P'),
(6, '18:00 - 20:00', 'P'),
(7, '20:00 - 22:00', 'P'),
(8, '22:00 - 24:00', 'P'),
(9, '00:00 - 02:00', 'P'),
(10, '02:00 - 04:00', 'P'),
(11, '04:00 - 06:00', 'P'),
(12, '06:00 - 08:00', 'P'),
(13, '08:00 - 10:00', 'P'),
(14, '10:00 - 12:00', 'P'),
(15, '12:00 - 14:00', 'P'),
(16, '14:00 - 16:00', 'P'),
(17, '16:00 - 18:00', 'P'),
(18, '18:00 - 20:00', 'P'),
(19, '20:00 - 22:00', 'P'),
(20, '22:00 - 24:00', 'P'),
(21, '00:00 - 02:00', 'P'),
(22, '02:00 - 04:00', 'P'),
(23, '04:00 - 06:00', 'P'),
(24, '06:00 - 08:00', 'P'),
(25, '07:00 - 09:00', 'I'),
(26, '09:00 - 11:00', 'I'),
(27, '11:00 - 13:00', 'I'),
(28, '13:00 - 15:00', 'I'),
(29, '15:00 - 17:00', 'I'),
(30, '17:00 - 19:00', 'I'),
(31, '19:00 - 21:00', 'I'),
(32, '21:00 - 23:00', 'I'),
(33, '23:00 - 01:00', 'I'),
(34, '01:00 - 03:00', 'I'),
(35, '03:00 - 05:00', 'I'),
(36, '05:00 - 07:00', 'I'),
(37, '07:00 - 09:00', 'I'),
(38, '09:00 - 11:00', 'I'),
(39, '11:00 - 13:00', 'I'),
(40, '13:00 - 15:00', 'I'),
(41, '15:00 - 17:00', 'I'),
(42, '17:00 - 19:00', 'I'),
(43, '19:00 - 21:00', 'I'),
(44, '21:00 - 23:00', 'I'),
(45, '23:00 - 01:00', 'I'),
(46, '01:00 - 03:00', 'I'),
(47, '03:00 - 05:00', 'I'),
(48, '05:00 - 07:00', 'I');

-- --------------------------------------------------------

--
-- Estrutura para tabela `veiculo`
--

CREATE TABLE `veiculo` (
  `id` int(11) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `cor` varchar(50) NOT NULL,
  `situacao` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `viatura`
--

CREATE TABLE `viatura` (
  `id` int(11) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `situacao` int(1) NOT NULL DEFAULT 0,
  `baixada` int(1) NOT NULL DEFAULT 0,
  `idchvtr` int(5) NOT NULL DEFAULT 0,
  `idmtr` int(5) NOT NULL DEFAULT 0,
  `combustivel` char(1) NOT NULL,
  `consumo` int(2) NOT NULL,
  `total_ocupantes` int(11) NOT NULL DEFAULT 2,
  `odometro` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `visitante`
--

CREATE TABLE `visitante` (
  `id` int(11) NOT NULL,
  `identidade` varchar(15) NOT NULL,
  `cpf` varchar(15) DEFAULT NULL,
  `idpgrad` int(11) NOT NULL DEFAULT 30,
  `nomecompleto` varchar(100) NOT NULL,
  `datanascimento` varchar(10) DEFAULT NULL,
  `datanascimento2` varchar(10) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `tipo` varchar(50) NOT NULL,
  `userativo` varchar(1) NOT NULL DEFAULT 'S',
  `situacao` int(1) NOT NULL DEFAULT 0,
  `idveiculo` int(5) NOT NULL DEFAULT 0,
  `cracha` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `liv_partes_ofdia`
--
ALTER TABLE `liv_partes_ofdia`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `liv_partes_ofdia_leituras`
--
ALTER TABLE `liv_partes_ofdia_leituras`
  ADD PRIMARY KEY (`id_leituras`);

--
-- Índices de tabela `liv_partes_ofdia_punidos`
--
ALTER TABLE `liv_partes_ofdia_punidos`
  ADD PRIMARY KEY (`id_punidos`);

--
-- Índices de tabela `liv_partes_ofdia_sobrasresiduos`
--
ALTER TABLE `liv_partes_ofdia_sobrasresiduos`
  ADD PRIMARY KEY (`id_sobrasresiduos`);

--
-- Índices de tabela `pedido_vtr`
--
ALTER TABLE `pedido_vtr`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `rel_alojamento`
--
ALTER TABLE `rel_alojamento`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `rel_militares`
--
ALTER TABLE `rel_militares`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `rel_pernoite`
--
ALTER TABLE `rel_pernoite`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `rel_rot_guarda`
--
ALTER TABLE `rel_rot_guarda`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `rel_rot_postos`
--
ALTER TABLE `rel_rot_postos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `rel_rot_ronda`
--
ALTER TABLE `rel_rot_ronda`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `rel_viaturas`
--
ALTER TABLE `rel_viaturas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `rel_visitantes`
--
ALTER TABLE `rel_visitantes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `rot_guarda_funcao`
--
ALTER TABLE `rot_guarda_funcao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `rot_guarda_quarto`
--
ALTER TABLE `rot_guarda_quarto`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `rot_postos_quartohora`
--
ALTER TABLE `rot_postos_quartohora`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `veiculo`
--
ALTER TABLE `veiculo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE` (`placa`);

--
-- Índices de tabela `viatura`
--
ALTER TABLE `viatura`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE` (`placa`);

--
-- Índices de tabela `visitante`
--
ALTER TABLE `visitante`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `liv_partes_ofdia`
--
ALTER TABLE `liv_partes_ofdia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1248;

--
-- AUTO_INCREMENT de tabela `liv_partes_ofdia_leituras`
--
ALTER TABLE `liv_partes_ofdia_leituras`
  MODIFY `id_leituras` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1255;

--
-- AUTO_INCREMENT de tabela `liv_partes_ofdia_punidos`
--
ALTER TABLE `liv_partes_ofdia_punidos`
  MODIFY `id_punidos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT de tabela `liv_partes_ofdia_sobrasresiduos`
--
ALTER TABLE `liv_partes_ofdia_sobrasresiduos`
  MODIFY `id_sobrasresiduos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1255;

--
-- AUTO_INCREMENT de tabela `pedido_vtr`
--
ALTER TABLE `pedido_vtr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'um id é gerado para cada viatura solicitada. Um pedido com mais de uma viatura criará mais de uma linha na tabela', AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `rel_alojamento`
--
ALTER TABLE `rel_alojamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83072;

--
-- AUTO_INCREMENT de tabela `rel_militares`
--
ALTER TABLE `rel_militares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60444;

--
-- AUTO_INCREMENT de tabela `rel_pernoite`
--
ALTER TABLE `rel_pernoite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11596;

--
-- AUTO_INCREMENT de tabela `rel_rot_guarda`
--
ALTER TABLE `rel_rot_guarda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35969;

--
-- AUTO_INCREMENT de tabela `rel_rot_postos`
--
ALTER TABLE `rel_rot_postos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18458;

--
-- AUTO_INCREMENT de tabela `rel_rot_ronda`
--
ALTER TABLE `rel_rot_ronda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9229;

--
-- AUTO_INCREMENT de tabela `rel_viaturas`
--
ALTER TABLE `rel_viaturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25767;

--
-- AUTO_INCREMENT de tabela `rel_visitantes`
--
ALTER TABLE `rel_visitantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52489;

--
-- AUTO_INCREMENT de tabela `rot_guarda_funcao`
--
ALTER TABLE `rot_guarda_funcao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `rot_guarda_quarto`
--
ALTER TABLE `rot_guarda_quarto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `rot_postos_quartohora`
--
ALTER TABLE `rot_postos_quartohora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de tabela `veiculo`
--
ALTER TABLE `veiculo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2268;

--
-- AUTO_INCREMENT de tabela `viatura`
--
ALTER TABLE `viatura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de tabela `visitante`
--
ALTER TABLE `visitante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7383;
--
-- Banco de dados: `helpdesk`
--
CREATE DATABASE IF NOT EXISTS `helpdesk` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `helpdesk`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `chamado`
--

CREATE TABLE `chamado` (
  `id` int(6) NOT NULL,
  `numchamado` varchar(20) NOT NULL,
  `situacao` varchar(1) NOT NULL,
  `idservico` int(6) NOT NULL,
  `idsolicitante` int(6) NOT NULL,
  `idsecao` int(6) NOT NULL,
  `dataabertura` varchar(10) NOT NULL,
  `datafechamento` varchar(10) NOT NULL,
  `horaabertura` varchar(8) NOT NULL,
  `horafechamento` varchar(8) NOT NULL,
  `tecnico` int(6) NOT NULL,
  `assunto` varchar(100) NOT NULL,
  `idetiqueta` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `etiqueta`
--

CREATE TABLE `etiqueta` (
  `id` int(6) NOT NULL,
  `numero` varchar(30) NOT NULL,
  `disponivel` varchar(1) NOT NULL,
  `totalchamados` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico`
--

CREATE TABLE `historico` (
  `id` int(6) NOT NULL,
  `numchamado` varchar(20) NOT NULL,
  `texto` text NOT NULL,
  `anexo` varchar(200) NOT NULL,
  `data` varchar(10) NOT NULL,
  `hora` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `secao`
--

CREATE TABLE `secao` (
  `id` int(6) NOT NULL,
  `secao` varchar(100) NOT NULL,
  `qtdchamados` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `secao`
--

INSERT INTO `secao` (`id`, `secao`, `qtdchamados`) VALUES
(1, 'Almoxarifado', 0),
(2, 'Seção de Aquisição Licitações e Contratos (SALC)', 0),
(3, 'Bateria Míssil ', 0),
(4, 'Sargenteação', 0),
(5, 'Sala de Comunicações', 0),
(6, 'RBS 70', 0),
(7, 'Reserva de Armamento', 0),
(8, '4ª Seção', 0),
(9, 'Fiscalização Administrativa', 0),
(10, 'Tesouraria', 0),
(11, 'Setor de Aprovisionamento', 0),
(12, 'Pelotão de Obras', 0),
(13, 'Setor de Manutenção e Transporte', 0),
(14, '3ª Seção', 0),
(15, 'Secretaria', 0),
(16, '1ª Seção', 0),
(17, 'Seção Mobilizadora', 0),
(18, 'Conformidade Registro e Gestão', 0),
(19, 'Gabinete Odontológico', 0),
(20, 'Encarregado de Material', 0),
(21, 'Seção de Saúde', 0),
(22, 'Hotel de Trânsito', 0),
(23, 'FUSEX', 0),
(24, 'Órgão Pagador', 0),
(25, 'Seção de Pagamento Pessoal', 0),
(26, 'Comunicação Social', 0),
(27, 'Guarda', 0),
(28, '2ª Seção', 0),
(29, 'SFPC', 0),
(30, 'Comando', 0),
(31, 'Seção de Tecnologia da Informação', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `servico`
--

CREATE TABLE `servico` (
  `id` int(6) NOT NULL,
  `servico` varchar(100) NOT NULL,
  `qtdservico` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `servico`
--

INSERT INTO `servico` (`id`, `servico`, `qtdservico`) VALUES
(1, 'Manutenção de Computador', 62),
(2, 'Impressora', 50),
(3, 'SPED', 5),
(4, 'Suporte Técnico', 9),
(5, 'Problemas de rede', 15),
(6, 'Sistemas Internos - Aniversariantes, Arranchamento, Help Desk e Plano de Chamada', 0),
(7, 'Zimbra', 0),
(8, 'Telefonia', 17),
(9, 'SISBOL', 0),
(10, 'ARQUIVOS', 2),
(11, 'Internet ou Intranet', 64),
(12, 'Outros', 48),
(13, 'Servidores', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `sistoper`
--

CREATE TABLE `sistoper` (
  `id` int(6) NOT NULL,
  `sistema` varchar(100) NOT NULL,
  `qtdsisop` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `sistoper`
--

INSERT INTO `sistoper` (`id`, `sistema`, `qtdsisop`) VALUES
(2, 'Ubuntu 16.04 i386', 0),
(3, 'Ubuntu 16.04 amd64', 0),
(4, 'Windows 7 amd64', 0),
(5, 'Windows 7 i386', 0),
(6, 'Windows 10 amd64', 0),
(7, 'Debian 8.3 i386', 0),
(9, 'Debian 9.4 amd64', 0),
(11, 'Debian 8.3 amd64', 0),
(12, 'Ubuntu 18.04 amd64', 0),
(13, 'Ubuntu 20.04', 0),
(14, 'debian 11', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `chamado`
--
ALTER TABLE `chamado`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `etiqueta`
--
ALTER TABLE `etiqueta`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `historico`
--
ALTER TABLE `historico`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `secao`
--
ALTER TABLE `secao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `servico`
--
ALTER TABLE `servico`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `sistoper`
--
ALTER TABLE `sistoper`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `chamado`
--
ALTER TABLE `chamado`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=271;

--
-- AUTO_INCREMENT de tabela `etiqueta`
--
ALTER TABLE `etiqueta`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- AUTO_INCREMENT de tabela `historico`
--
ALTER TABLE `historico`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=675;

--
-- AUTO_INCREMENT de tabela `secao`
--
ALTER TABLE `secao`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `servico`
--
ALTER TABLE `servico`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `sistoper`
--
ALTER TABLE `sistoper`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- Banco de dados: `membros`
--
CREATE DATABASE IF NOT EXISTS `membros` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `membros`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `afastamentos`
--

CREATE TABLE `afastamentos` (
  `af_id` int(11) NOT NULL,
  `inicio` varchar(15) NOT NULL,
  `fim` varchar(15) NOT NULL,
  `destino` varchar(30) NOT NULL,
  `fonecelular` varchar(20) NOT NULL,
  `motivo` int(1) NOT NULL,
  `obs` text NOT NULL,
  `militar` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `bairros`
--

CREATE TABLE `bairros` (
  `id` int(5) NOT NULL,
  `bairro` varchar(50) NOT NULL,
  `setor` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `bairros`
--

INSERT INTO `bairros` (`id`, `bairro`, `setor`) VALUES
(1, 'Alto da Boa Vista', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fo`
--

CREATE TABLE `fo` (
  `id_fo` int(11) NOT NULL,
  `idmembro` int(5) NOT NULL,
  `id_usuario` int(5) NOT NULL,
  `tipo` varchar(1) NOT NULL,
  `obs` text NOT NULL,
  `datahora` datetime NOT NULL,
  `foativo` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `logins`
--

CREATE TABLE `logins` (
  `id` int(11) NOT NULL,
  `idmembro` int(5) NOT NULL,
  `data` varchar(10) NOT NULL,
  `hora` varchar(10) NOT NULL,
  `sistema` varchar(50) DEFAULT NULL,
  `ip` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `logins`
--

INSERT INTO `logins` (`id`, `idmembro`, `data`, `hora`, `sistema`, `ip`) VALUES
(82414, 0, '28/12/2023', '16:06:22', 'N', '127.0.0.1'),
(82415, 1, '28/12/2023', '16:06:38', 'S', '127.0.0.1');

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorios`
--

CREATE TABLE `relatorios` (
  `id` int(11) NOT NULL,
  `data` varchar(10) NOT NULL,
  `hora` varchar(10) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `responsavel` varchar(30) NOT NULL,
  `sistema` varchar(50) NOT NULL,
  `obs` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Estrutura para tabela `setores`
--

CREATE TABLE `setores` (
  `id` int(4) NOT NULL,
  `setor` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `setores`
--

INSERT INTO `setores` (`id`, `setor`) VALUES
(1, 'Alfa');

-- --------------------------------------------------------

--
-- Estrutura para tabela `subunidade`
--

CREATE TABLE `subunidade` (
  `id` int(4) NOT NULL,
  `descricao` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `subunidade`
--

INSERT INTO `subunidade` (`id`, `descricao`) VALUES
(1, 'Estado Maior');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nomecompleto` varchar(100) NOT NULL,
  `nomeguerra` varchar(50) NOT NULL,
  `idpgrad` int(5) NOT NULL,
  `endereco` varchar(100) NOT NULL,
  `bairro` int(5) NOT NULL,
  `cidade` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `idsubunidade` int(4) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fixo` varchar(15) NOT NULL,
  `celular` varchar(15) NOT NULL,
  `datanascimento` varchar(10) NOT NULL,
  `cpf` varchar(40) DEFAULT NULL,
  `prec_cp` varchar(15) NOT NULL DEFAULT '',
  `identidade` varchar(50) NOT NULL,
  `acessorancho` varchar(1) NOT NULL,
  `contarancho` varchar(1) NOT NULL,
  `acessoguarda` varchar(1) NOT NULL,
  `contaguarda` varchar(1) NOT NULL,
  `nivelacessocautela` varchar(2) NOT NULL DEFAULT '0' COMMENT '0 = usuario comum, outros valores são para o administrador do deposito com o id do respectivo dep',
  `acessohd` varchar(1) NOT NULL,
  `contahd` varchar(1) NOT NULL,
  `acessopchamada` varchar(1) NOT NULL,
  `contapchamada` varchar(1) NOT NULL,
  `acessosistcomsoc` varchar(1) NOT NULL DEFAULT 'N',
  `contasistcomsoc` varchar(1) NOT NULL DEFAULT '0' COMMENT '0 = comum, 1 = conformador, 2 = ht, 3 = cmt, 4 = adm',
  `acessoscd` varchar(2) NOT NULL DEFAULT '0' COMMENT '0 = não possui acesso, número x é pq tem acesso ao sistema x',
  `acessoservico` varchar(1) NOT NULL,
  `contaservico` varchar(1) NOT NULL,
  `is_super_user_servico` varchar(1) NOT NULL DEFAULT 'N',
  `userativo` varchar(1) NOT NULL,
  `hashsenha` varchar(150) NOT NULL,
  `ult_troca_senha` date DEFAULT NULL,
  `ult_atlz_dados` date DEFAULT NULL,
  `datanascimento2` varchar(10) NOT NULL,
  `foto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nomecompleto`, `nomeguerra`, `idpgrad`, `endereco`, `bairro`, `cidade`, `estado`, `idsubunidade`, `email`, `fixo`, `celular`, `datanascimento`, `cpf`, `prec_cp`, `identidade`, `acessorancho`, `contarancho`, `acessoguarda`, `contaguarda`, `nivelacessocautela`, `acessohd`, `contahd`, `acessopchamada`, `contapchamada`, `acessosistcomsoc`, `contasistcomsoc`, `acessoscd`, `acessoservico`, `contaservico`, `is_super_user_servico`, `userativo`, `hashsenha`, `ult_troca_senha`, `ult_atlz_dados`, `datanascimento2`, `foto`) VALUES
(1, 'GENISIS', 'GENISIS', -1, 'aaa', 1, 'Três Lagoas', 'Mato Grosso do Sul', 1, 'meu_email@hotmail.com', '00-0000-0000', '00-00000-0000', '01/01/2024', 'MTIzMTIzMTIzMDA=', '', 'MTIzMTIzMTIzMA==', 'N', '1', 'N', '1', '0', 'S', '3', 'S', '3', 'N', '0', '0', 'N', '1', 'N', 'S', '$2y$10$win6gKxcssnlv8gEPAF4k.aoUpnCEkk7i6Zgc9rDrF4kUUv2SaffK', NULL, NULL, '2024-01-01', '');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `afastamentos`
--
ALTER TABLE `afastamentos`
  ADD PRIMARY KEY (`af_id`);

--
-- Índices de tabela `bairros`
--
ALTER TABLE `bairros`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fo`
--
ALTER TABLE `fo`
  ADD PRIMARY KEY (`id_fo`);

--
-- Índices de tabela `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `relatorios`
--
ALTER TABLE `relatorios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `setores`
--
ALTER TABLE `setores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `subunidade`
--
ALTER TABLE `subunidade`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `afastamentos`
--
ALTER TABLE `afastamentos`
  MODIFY `af_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT de tabela `bairros`
--
ALTER TABLE `bairros`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT de tabela `fo`
--
ALTER TABLE `fo`
  MODIFY `id_fo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1851;

--
-- AUTO_INCREMENT de tabela `logins`
--
ALTER TABLE `logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82416;

--
-- AUTO_INCREMENT de tabela `relatorios`
--
ALTER TABLE `relatorios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `setores`
--
ALTER TABLE `setores`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `subunidade`
--
ALTER TABLE `subunidade`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=571;
--
-- Banco de dados: `siscautela`
--
CREATE DATABASE IF NOT EXISTS `siscautela` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `siscautela`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cautela`
--

CREATE TABLE `cautela` (
  `id` int(6) NOT NULL,
  `id_deposito` int(11) NOT NULL,
  `militar` int(5) NOT NULL COMMENT 'id do usuário cadastrado no banco de dados global da intranet',
  `material` int(4) NOT NULL COMMENT 'id do material salvo na tabela material',
  `quantidade` int(4) NOT NULL,
  `nr_serie` varchar(50) NOT NULL DEFAULT '-',
  `data_cautela` datetime NOT NULL DEFAULT current_timestamp(),
  `situacao_cautela` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'S/A',
  `extravio` int(11) NOT NULL DEFAULT 0,
  `operador` int(11) NOT NULL COMMENT 'quem operou o sistema para fazer a cautela'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `depositos`
--

CREATE TABLE `depositos` (
  `id` int(11) NOT NULL,
  `nome_dep` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'nome que vai aparecer no cabeçalho dos relatórios',
  `responsavel` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'nome que aparecerá no rodapé, formato: Fulano de Tal - Sten',
  `func_responsavel` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'aparecerá no rodapé, ex: Enc Mat Bia Msl'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `listamat`
--

CREATE TABLE `listamat` (
  `id` int(5) NOT NULL,
  `descricao` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `quant` int(11) NOT NULL,
  `inclusao` text DEFAULT NULL,
  `dep_id` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cautela`
--
ALTER TABLE `cautela`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `depositos`
--
ALTER TABLE `depositos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `listamat`
--
ALTER TABLE `listamat`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cautela`
--
ALTER TABLE `cautela`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `depositos`
--
ALTER TABLE `depositos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `listamat`
--
ALTER TABLE `listamat`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- Banco de dados: `sistcomsoc`
--
CREATE DATABASE IF NOT EXISTS `sistcomsoc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sistcomsoc`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `conformador`
--

CREATE TABLE `conformador` (
  `id` int(11) NOT NULL,
  `id_conformador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `conformidade`
--

CREATE TABLE `conformidade` (
  `id` int(11) NOT NULL,
  `status_ug1` varchar(1) NOT NULL,
  `descricao_ug1` varchar(1000) NOT NULL,
  `status_ug2` varchar(1) NOT NULL,
  `descricao_ug2` varchar(1000) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `escala_permanencia`
--

CREATE TABLE `escala_permanencia` (
  `id` int(11) NOT NULL,
  `id_perm` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `gestor_ht`
--

CREATE TABLE `gestor_ht` (
  `id` int(11) NOT NULL,
  `id_gestor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `hospedes`
--

CREATE TABLE `hospedes` (
  `id` int(11) NOT NULL,
  `nomecompleto` varchar(100) NOT NULL,
  `nomeguerra` varchar(50) NOT NULL,
  `postograd` varchar(2) NOT NULL,
  `om` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefone` varchar(11) NOT NULL,
  `idtcivil` varchar(15) NOT NULL,
  `idtmil` varchar(10) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `datanascimento` varchar(10) NOT NULL,
  `necessidadesesp` varchar(1) NOT NULL,
  `genero` varchar(1) NOT NULL,
  `veiculomodelo` varchar(50) NOT NULL,
  `veiculocor` varchar(20) NOT NULL,
  `veiculoplaca` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `hospedes`
--

INSERT INTO `hospedes` (`id`, `nomecompleto`, `nomeguerra`, `postograd`, `om`, `email`, `telefone`, `idtcivil`, `idtmil`, `cpf`, `datanascimento`, `necessidadesesp`, `genero`, `veiculomodelo`, `veiculocor`, `veiculoplaca`) VALUES
(1, 'Pedro Joaquim', 'Pedro', '3', '99º Regimento de Cavalaria Mecanizado', 'pedrojoaquim@eb.mil.br', '99999999999', '8888888888', '7777777777', '6666666666', '15/03/1968', 'N', 'M', 'Fiat Punto', 'Branco', 'ABC-1234');

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentados`
--

CREATE TABLE `movimentados` (
  `id` int(11) NOT NULL,
  `horaregistro` datetime NOT NULL DEFAULT current_timestamp(),
  `nomecompleto` varchar(100) NOT NULL,
  `idpgrad` varchar(2) NOT NULL,
  `nomeguerra` varchar(50) NOT NULL,
  `datanascimento` varchar(10) NOT NULL,
  `cidadenatal` varchar(50) NOT NULL,
  `estadonatal` varchar(2) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `idtmil` varchar(12) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `omorigem` varchar(100) NOT NULL,
  `cidadeomorigem` varchar(50) NOT NULL,
  `datapraca` varchar(10) NOT NULL,
  `dataultimapromocao` varchar(10) NOT NULL,
  `ultimafuncao` varchar(50) NOT NULL,
  `cursos` text NOT NULL,
  `principaisfuncoes` text NOT NULL,
  `nomepai` varchar(100) NOT NULL,
  `nomemae` varchar(100) NOT NULL,
  `estadocivil` varchar(1) NOT NULL,
  `nomeconjuge` varchar(100) NOT NULL,
  `quantfilhos` varchar(2) NOT NULL,
  `nomesfilhos` varchar(100) NOT NULL,
  `sitferias` text NOT NULL,
  `outrasinfo` text NOT NULL,
  `dataapresentacao` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL COMMENT 'id da reserva',
  `id_hospede` int(11) NOT NULL COMMENT 'positivo = militar da om; negativo = visitante',
  `om` varchar(50) NOT NULL,
  `cpf` varchar(100) NOT NULL,
  `data_checkin` varchar(10) NOT NULL,
  `hora_checkin` varchar(5) NOT NULL,
  `data_checkout` varchar(10) NOT NULL,
  `hora_checkout` varchar(5) NOT NULL,
  `veiculo_id` int(11) NOT NULL,
  `motivo_reserva` varchar(100) NOT NULL,
  `quarto` varchar(3) NOT NULL,
  `acompanhantes` int(11) NOT NULL,
  `gp_tarifa` varchar(20) NOT NULL,
  `adicional_tarifa` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `conformador`
--
ALTER TABLE `conformador`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `conformidade`
--
ALTER TABLE `conformidade`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `escala_permanencia`
--
ALTER TABLE `escala_permanencia`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `gestor_ht`
--
ALTER TABLE `gestor_ht`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `hospedes`
--
ALTER TABLE `hospedes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `movimentados`
--
ALTER TABLE `movimentados`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `conformador`
--
ALTER TABLE `conformador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `conformidade`
--
ALTER TABLE `conformidade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `escala_permanencia`
--
ALTER TABLE `escala_permanencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `gestor_ht`
--
ALTER TABLE `gestor_ht`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `hospedes`
--
ALTER TABLE `hospedes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `movimentados`
--
ALTER TABLE `movimentados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id da reserva';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
