CREATE TABLE IF NOT EXISTS `PRESTA_PREFIX_tappz_paypal` (
  `id_tappz_paypal` int(11) NOT NULL AUTO_INCREMENT,
  `paypal_secret` varchar(255) NOT NULL,
  `paypal_client_id` varchar(255) NOT NULL,
  `sandbox` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_tappz_paypal`,`paypal_client_id`)
) ENGINE=PRESTA_DB_ENGINE DEFAULT CHARSET=utf8;

CREATE TABLE  IF NOT EXISTS  `PRESTA_PREFIX_tappz_user_agreement` (
  `id_tappz_user_agreement` int(11) NOT NULL AUTO_INCREMENT,
  `user_agreement` text NOT NULL,
  PRIMARY KEY (`id_tappz_user_agreement`)
) ENGINE=PRESTA_DB_ENGINE DEFAULT CHARSET=utf8;

CREATE TABLE  IF NOT EXISTS  `PRESTA_PREFIX_tappz_token` (
 `token` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`token`)
) ENGINE=PRESTA_DB_ENGINE DEFAULT CHARSET=utf8;