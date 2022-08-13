SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `short_link`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `suffix` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `link` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ip` int(11) UNSIGNED NOT NULL,
  `ban` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `suffix`(`suffix` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '链.ml' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
