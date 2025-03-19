-- skeleton.toles definition

CREATE TABLE `toles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(40) NOT NULL,
  `no_commande_guinault` varchar(40) NOT NULL,
  `quantite_servie` int(10) unsigned DEFAULT NULL,
  `poids` int(10) unsigned DEFAULT NULL,
  `date_fabrication` datetime DEFAULT NULL,
  `date_reception_guinault` datetime DEFAULT NULL,
  `date_reception_dee` datetime NOT NULL DEFAULT current_timestamp(),
  `quantite_rebut` int(10) unsigned DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `modifed_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;