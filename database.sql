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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_actions`
--


-- --------------------------------------------------------

--
-- Table structure for table `shop_addresses`
--

CREATE TABLE IF NOT EXISTS `shop_addresses` (
  `id` int(11) NOT NULL auto_increment,
  `first_name` varchar(255) collate utf8_unicode_ci default NULL,
  `last_name` varchar(255) collate utf8_unicode_ci default NULL,
  `address` varchar(255) collate utf8_unicode_ci default NULL,
  `apt` varchar(255) collate utf8_unicode_ci default NULL,
  `city` varchar(255) collate utf8_unicode_ci default NULL,
  `region` varchar(255) collate utf8_unicode_ci default NULL,
  `postal_code` varchar(255) collate utf8_unicode_ci default NULL,
  `country` varchar(255) collate utf8_unicode_ci default NULL,
  `tel` varchar(255) collate utf8_unicode_ci default NULL,
  `tel2` varchar(255) collate utf8_unicode_ci default NULL,
  `email` varchar(255) collate utf8_unicode_ci default NULL,
  `active` tinyint(1) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_addresses`
--

-- --------------------------------------------------------

--
-- Table structure for table `shop_coupons`
--

CREATE TABLE IF NOT EXISTS `shop_coupons` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(255) collate utf8_unicode_ci default NULL,
  `shop_promotion_id` int(11) default NULL,
  `shop_order_id` int(11) default NULL,
  `status` varchar(255) collate utf8_unicode_ci default NULL,
  `active` tinyint(1) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop_orders`
--

CREATE TABLE IF NOT EXISTS `shop_orders` (
  `id` int(11) NOT NULL auto_increment,
  `discount` float default NULL,
  `discount_prc` float default NULL,
  `sub_total` float default NULL,
  `currency` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
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
  `shipping_age` varchar(255) collate utf8_unicode_ci default NULL,
  `shipping_newsletter` tinyint(1) default NULL,
  `shipping_lang` varchar(255) collate utf8_unicode_ci default NULL,
  `supplement_choices` text collate utf8_unicode_ci,
  `dev_mode` tinyint(1) default NULL,
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
  `id` int(11) NOT NULL auto_increment,
  `order_id` int(11) default NULL,
  `product_id` int(11) default NULL,
  `nb` int(11) default NULL,
  `item_title` varchar(255) collate utf8_unicode_ci default NULL,
  `item_desc` varchar(255) collate utf8_unicode_ci default NULL,
  `comment` text collate utf8_unicode_ci,
  `item_price` float default NULL,
  `item_tax_applied` text collate utf8_unicode_ci,
  `final_price` float default NULL,
  `active` tinyint(1) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_orders_items`
--

-- --------------------------------------------------------

--
-- Table structure for table `shop_orders_payments`
--

CREATE TABLE IF NOT EXISTS `shop_orders_payments` (
  `id` int(11) NOT NULL auto_increment,
  `order_id` int(11) default NULL,
  `payment_id` int(11) default NULL,
  `amount` float default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


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
-- Table structure for table `shop_payments`
--

CREATE TABLE IF NOT EXISTS `shop_payments` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(255) collate utf8_unicode_ci default NULL,
  `amount` float default NULL,
  `currency` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(255) collate utf8_unicode_ci default NULL,
  `data` text collate utf8_unicode_ci,
  `dev_mode` tinyint(1) default NULL,
  `active` tinyint(1) default NULL,
  `lock` int(11) NOT NULL default '0',
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_payments`
--


-- --------------------------------------------------------

--
-- Table structure for table `shop_products`
--

CREATE TABLE IF NOT EXISTS `shop_products` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(255) collate utf8_unicode_ci default NULL,
  `model` varchar(255) collate utf8_unicode_ci default NULL,
  `foreign_id` int(11) default NULL,
  `price` float default NULL,
  `currency_prices` text collate utf8_unicode_ci,
  `shipping_req` tinyint(1) default NULL,
  `needed_data` text collate utf8_unicode_ci,
  `tax_applied` text collate utf8_unicode_ci,
  `active` tinyint(1) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_products`
--

-- --------------------------------------------------------

--
-- Table structure for table `shop_product_subproducts`
--

CREATE TABLE IF NOT EXISTS `shop_product_subproducts` (
  `id` int(11) NOT NULL auto_increment,
  `shop_product_id` int(11) default NULL,
  `parent_subproduct_id` int(11) default NULL,
  `shop_subproduct_id` int(11) default NULL,
  `default` tinyint(1) default NULL,
  `active` tinyint(1) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


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
  `code_needed` tinyint(1) NOT NULL default '0',
  `limited_coupons` tinyint(1) NOT NULL default '0',
  `coupon_code_needed` tinyint(1) NOT NULL default '0',
  `method` varchar(255) collate utf8_unicode_ci default NULL,
  `method_params` text collate utf8_unicode_ci,
  `cond` text collate utf8_unicode_ci,
  `cond_params` text collate utf8_unicode_ci,
  `val` float default NULL,
  `operator` int(11) NOT NULL default '1' COMMENT '1:equal, 2:add, 3:multiply',
  `action_id` int(11) default NULL,
  `action_params` text collate utf8_unicode_ci,
  `active` tinyint(1) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_promotions`
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

-- --------------------------------------------------------

--
-- Table structure for table `shop_taxes`
--

CREATE TABLE IF NOT EXISTS `shop_taxes` (
  `id` int(11) NOT NULL auto_increment,
  `country` varchar(100) collate utf8_unicode_ci default NULL,
  `region` varchar(100) collate utf8_unicode_ci default NULL,
  `code` varchar(50) collate utf8_unicode_ci default NULL,
  `name_fre` varchar(255) collate utf8_unicode_ci default NULL,
  `name_eng` varchar(255) collate utf8_unicode_ci default NULL,
  `rate` float default NULL,
  `apply_prev` tinyint(1) default NULL,
  `apply` tinyint(1) default '1',
  `active` tinyint(1) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `shop_taxes`
--

INSERT INTO `shop_taxes` (`id`, `country`, `region`, `code`, `name_fre`, `name_eng`, `rate`, `apply_prev`, `apply`, `active`, `created`, `modified`) VALUES
(1, 'CA', NULL, 'TPS', NULL, NULL, 0.05, 0, 1, 1, NULL, NULL),
(2, 'CA', 'NB', 'TVH', NULL, NULL, 0.13, 0, 1, 1, NULL, NULL),
(3, 'CA', 'NL', 'TVH', NULL, NULL, 0.13, 0, 1, 1, NULL, NULL),
(4, 'CA', 'NS', 'TVH', NULL, NULL, 0.15, 0, 1, 1, NULL, NULL),
(5, 'CA', 'BC', 'TVH', NULL, NULL, 0.12, 0, 1, 1, NULL, NULL),
(6, 'CA', 'ON', 'TPS', NULL, NULL, 0, 0, 0, 1, NULL, NULL),
(7, 'CA', 'ON', 'TVH', NULL, NULL, 0.13, 0, 1, 1, NULL, NULL),
(8, 'CA', 'QC', 'TVQ', NULL, NULL, 0.095, 1, 1, 1, NULL, NULL),
(9, 'CA', 'NB', 'TPS', NULL, NULL, 0, 0, 0, 1, NULL, NULL),
(10, 'CA', 'NL', 'TPS', NULL, NULL, 0, 0, 0, 1, NULL, NULL),
(11, 'CA', 'NS', 'TPS', NULL, NULL, 0, 0, 0, 1, NULL, NULL),
(12, 'CA', 'BC', 'TPS', NULL, NULL, 0, 0, 0, 1, NULL, NULL);



-- --------------------------------------------------------



