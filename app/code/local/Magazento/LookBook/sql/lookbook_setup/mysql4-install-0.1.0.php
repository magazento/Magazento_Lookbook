<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('magazento_lookbook')};
CREATE TABLE {$this->getTable('magazento_lookbook')} (
  `lookbook_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `descr` text NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  PRIMARY KEY (`lookbook_id`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('magazento_lookbook_products')};
CREATE TABLE  {$this->getTable('magazento_lookbook_products')}  (
  `lookbook_entity_id` int(11) NOT NULL AUTO_INCREMENT,
  `lookbook_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`lookbook_entity_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('magazento_lookbook_images')};
CREATE TABLE  {$this->getTable('magazento_lookbook_images')} (
  `image_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `position` smallint(5) DEFAULT '0',
  `is_main` tinyint(1) DEFAULT '0',
  `disabled` tinyint(1) DEFAULT '1',
  `lookbook_id` smallint(6) DEFAULT '0',
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Magazento Lookbook Images' ;

DROP TABLE IF EXISTS {$this->getTable('magazento_lookbook_stores')};
CREATE TABLE {$this->getTable('magazento_lookbook_stores')} (
  `lookbook_id` smallint(6) NOT NULL,
  `store_id` smallint(6) NOT NULL,
  PRIMARY KEY (`lookbook_id`,`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lookbook Stores';

    ");

$installer->endSetup(); 