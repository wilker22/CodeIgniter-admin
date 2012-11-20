-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 20/11/2012 às 21:55:13
-- Versão do Servidor: 5.5.25
-- Versão do PHP: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Banco de Dados: `site`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imagem` varchar(100) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `ordem` int(11) NOT NULL,
  `ativo` set('S','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sessoes`
--

CREATE TABLE `sessoes` (
  `id` char(32) NOT NULL,
  `dth_inicio` datetime DEFAULT NULL,
  `dth_termino` datetime DEFAULT NULL,
  `url` tinytext,
  `ip` varchar(15) DEFAULT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `login` varchar(30) NOT NULL,
  `ativo` set('S','N') NOT NULL DEFAULT 'N',
  `senha` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `usuarios`
-- login: admin / senha: 1

INSERT INTO `usuarios` (`id`, `nome`, `login`, `ativo`, `senha`) VALUES
(1, 'Administrador do Sistema', 'admin', 'S', '356a192b7913b04c54574d18c28d46e6395428ab');
