-- phpMyAdmin SQL Dump
-- version 3.1.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 04, 2011 at 11:49 AM
-- Server version: 5.1.32
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


-- --------------------------------------------------------

--
-- Table structure for table `shop_actions`
--

CREATE TABLE IF NOT EXISTS `shop_actions` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(255) collate utf8_unicode_ci default NULL,
  `status` varchar(255) collate utf8_unicode_ci default NULL,
  `component` varchar(255) collate utf8_unicode_ci default NULL,
  `function` varchar(255) collate utf8_unicode_ci default NULL,
  `params` varchar(255) collate utf8_unicode_ci default NULL,
  `form_element` varchar(255) collate utf8_unicode_ci default NULL,
  `ui` text collate utf8_unicode_ci,
  `active` tinyint(1) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `shop_actions`
--


-- --------------------------------------------------------

--
-- Table structure for table `shop_addresses`
--

CREATE TABLE IF NOT EXISTS `shop_addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `region` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_addresses`
--


-- --------------------------------------------------------

--
-- Table structure for table `shop_orders`
--

CREATE TABLE IF NOT EXISTS `shop_orders` (
  `id` int(11) NOT NULL auto_increment,
  `discount` float default NULL,
  `discount_prc` float default NULL,
  `sub_total` float default NULL,
  `taxe_subs` text collate utf8_unicode_ci,
  `taxes` text collate utf8_unicode_ci,
  `total_taxes` float default NULL,
  `total_shipping` float default NULL,
  `supplements` text collate utf8_unicode_ci,
  `total_supplements` float default NULL,
  `total` float default NULL,
  `confirm` smallint(1) default NULL,
  `amount_paid` float default NULL,
  `status` varchar(255) collate utf8_unicode_ci default NULL,
  `date` datetime default NULL,
  `billing_first_name` varchar(255) collate utf8_unicode_ci default NULL,
  `billing_last_name` varchar(255) collate utf8_unicode_ci default NULL,
  `billing_title` varchar(255) collate utf8_unicode_ci default NULL,
  `billing_enterprise` varchar(255) collate utf8_unicode_ci default NULL,
  `billing_address` text collate utf8_unicode_ci,
  `billing_apt` varchar(255) collate utf8_unicode_ci default NULL,
  `billing_city` varchar(255) collate utf8_unicode_ci default NULL,
  `billing_region` varchar(255) collate utf8_unicode_ci default NULL,
  `billing_country` varchar(255) collate utf8_unicode_ci default NULL,
  `billing_postal_code` varchar(255) collate utf8_unicode_ci default NULL,
  `billing_tel` varchar(255) collate utf8_unicode_ci default NULL,
  `billing_tel2` varchar(255) collate utf8_unicode_ci default NULL,
  `billing_email` varchar(255) collate utf8_unicode_ci default NULL,
  `shipping_first_name` varchar(255) collate utf8_unicode_ci default NULL,
  `shipping_last_name` varchar(255) collate utf8_unicode_ci default NULL,
  `shipping_title` varchar(255) collate utf8_unicode_ci default NULL,
  `shipping_enterprise` varchar(255) collate utf8_unicode_ci default NULL,
  `shipping_address` text collate utf8_unicode_ci,
  `shipping_apt` varchar(255) collate utf8_unicode_ci default NULL,
  `shipping_city` varchar(255) collate utf8_unicode_ci default NULL,
  `shipping_region` varchar(255) collate utf8_unicode_ci default NULL,
  `shipping_country` varchar(255) collate utf8_unicode_ci default NULL,
  `shipping_postal_code` varchar(255) collate utf8_unicode_ci default NULL,
  `shipping_tel` varchar(255) collate utf8_unicode_ci default NULL,
  `shipping_tel2` varchar(255) collate utf8_unicode_ci default NULL,
  `shipping_email` varchar(255) collate utf8_unicode_ci default NULL,
  `shipping_type` varchar(255) collate utf8_unicode_ci default NULL,
  `supplement_choices` text collate utf8_unicode_ci,
  `active` tinyint(1) default NULL,
  `lock` int(11) NOT NULL default '0',
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_orders`
--


-- --------------------------------------------------------

--
-- Table structure for table `shop_orders_items`
--

CREATE TABLE IF NOT EXISTS `shop_orders_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `nb` int(11) DEFAULT NULL,
  `descr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `item_price` float DEFAULT NULL,
  `item_tax_applied` text COLLATE utf8_unicode_ci,
  `active` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_orders_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `shop_orders_subitems`
--

CREATE TABLE IF NOT EXISTS `shop_orders_subitems` (
  `id` int(11) NOT NULL auto_increment,
  `shop_orders_item_id` int(11) default NULL,
  `shop_subproduct_id` int(11) default NULL,
  `descr` varchar(255) collate utf8_unicode_ci default NULL,
  `nb` int(11) default NULL,
  `item_price` float default NULL,
  `item_operator` varchar(255) collate utf8_unicode_ci default NULL,
  `lft` int(11) default NULL,
  `rght` int(11) default NULL,
  `parent_id` int(11) default NULL,
  `active` tinyint(1) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=40 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop_orders_payments`
--

CREATE TABLE IF NOT EXISTS `shop_orders_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_orders_payments`
--


-- --------------------------------------------------------

--
-- Table structure for table `shop_payments`
--

CREATE TABLE IF NOT EXISTS `shop_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `active` tinyint(1) DEFAULT NULL,
  `lock` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_payments`
--


-- --------------------------------------------------------

--
-- Table structure for table `shop_products`
--

CREATE TABLE IF NOT EXISTS `shop_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `foreign_id` int(11) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `currency_prices` text collate utf8_unicode_ci,
  `shipping_req` tinyint(1) DEFAULT NULL,
  `needed_data` text COLLATE utf8_unicode_ci,
  `tax_applied` text COLLATE utf8_unicode_ci,
  `active` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_products`
--


-- --------------------------------------------------------

--
-- Table structure for table `shop_promotions`
--

CREATE TABLE IF NOT EXISTS `shop_promotions` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(255) collate utf8_unicode_ci default NULL,
  `title_fre` varchar(255) collate utf8_unicode_ci default NULL,
  `title_eng` varchar(255) collate utf8_unicode_ci default NULL,
  `desc_fre` text collate utf8_unicode_ci,
  `desc_eng` text collate utf8_unicode_ci,
  `val` float default NULL,
  `operator` int(11) NOT NULL default '1' COMMENT '1:equal, 2:add, 3:multiply',
  `action_id` int(11) default NULL,
  `action_params` text collate utf8_unicode_ci,
  `active` tinyint(1) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `shop_promotions`
--


-- --------------------------------------------------------

--
-- Table structure for table `shop_taxes`
--

CREATE TABLE IF NOT EXISTS `shop_taxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `region` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name_fre` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name_eng` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rate` float DEFAULT NULL,
  `apply_prev` tinyint(1) DEFAULT NULL,
  `apply` tinyint(1) DEFAULT '1',
  `active` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;

--
-- Dumping data for table `shop_taxes`
--

INSERT INTO `shop_taxes` (`id`, `country`, `region`, `code`, `name_fre`, `name_eng`, `rate`, `apply_prev`, `apply`, `active`, `created`, `modified`) VALUES
(14, 'CA', NULL, 'TPS', NULL, NULL, 0.05, 0, 1, 1, NULL, NULL),
(17, 'CA', 'NB', 'TVH', NULL, NULL, 0.13, 0, 1, 1, NULL, NULL),
(18, 'CA', 'NL', 'TVH', NULL, NULL, 0.13, 0, 1, 1, NULL, NULL),
(19, 'CA', 'NS', 'TVH', NULL, NULL, 0.13, 0, 1, 1, NULL, NULL),
(27, 'CA', 'BC', 'PST', NULL, NULL, 0.07, 0, 1, 1, NULL, NULL),
(28, 'CA', 'MB', 'PST', NULL, NULL, 0.07, 0, 1, 1, NULL, NULL),
(29, 'CA', 'ON', 'PST', NULL, NULL, 0.08, 0, 1, 1, NULL, NULL),
(30, 'CA', 'PE', 'PST', NULL, NULL, 0.1, 1, 1, 1, NULL, NULL),
(31, 'CA', 'QC', 'TVQ', NULL, NULL, 0.085, 1, 1, 1, NULL, NULL),
(32, 'CA', 'SK', 'PST', NULL, NULL, 0.05, 0, 1, 1, NULL, NULL),
(33, 'CA', 'NB', 'TPS', NULL, NULL, 0, 0, 0, 1, NULL, NULL),
(34, 'CA', 'NL', 'TPS', NULL, NULL, 0, 0, 0, 1, NULL, NULL),
(35, 'CA', 'NS', 'TPS', NULL, NULL, 0, 0, 0, 1, NULL, NULL);



-- --------------------------------------------------------

--
-- Table structure for table `shop_product_subproducts`
--

CREATE TABLE IF NOT EXISTS `shop_product_subproducts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_product_id` int(11) DEFAULT NULL,
  `parent_subproduct_id` int(11) DEFAULT NULL,
  `shop_subproduct_id` int(11) DEFAULT NULL,
  `default` tinyint(1) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_product_subproducts`
--


-- --------------------------------------------------------

--
-- Table structure for table `shop_subproducts`
--

CREATE TABLE IF NOT EXISTS `shop_subproducts` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(255) collate utf8_unicode_ci default NULL,
  `code` varchar(255) collate utf8_unicode_ci default NULL,
  `label_fre` varchar(255) collate utf8_unicode_ci default NULL,
  `label_eng` varchar(255) collate utf8_unicode_ci default NULL,
  `model` varchar(255) collate utf8_unicode_ci default NULL,
  `foreign_id` int(11) default NULL,
  `price` float default NULL,
  `data` text collate utf8_unicode_ci,
  `operator` varchar(255) collate utf8_unicode_ci default NULL,
  `active` tinyint(1) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=23 ;

--
-- Dumping data for table `shop_subproducts`
--
