ALTER TABLE `dispositivo` ADD `baja_logica` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `guardia` ADD `baja_logica` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `item_ronda` ADD `baja_logica` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `log_alarma` ADD `baja_logica` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `log_mota` ADD `baja_logica` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `log_rfid` ADD `baja_logica` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `ronda` ADD `baja_logica` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `sala` ADD `baja_logica` TINYINT NOT NULL DEFAULT '0';
