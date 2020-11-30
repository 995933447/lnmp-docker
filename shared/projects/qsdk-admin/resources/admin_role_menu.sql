/*
 Navicat Premium Data Transfer

 Source Server         : vmware-centos
 Source Server Type    : MySQL
 Source Server Version : 50713
 Source Host           : 192.168.2.130:3306
 Source Schema         : merge_vendor_sdk

 Target Server Type    : MySQL
 Target Server Version : 50713
 File Encoding         : 65001

 Date: 09/10/2020 10:40:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_menu`;
CREATE TABLE `admin_role_menu`  (
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `admin_role_menu_role_id_menu_id_index`(`role_id`, `menu_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_role_menu
-- ----------------------------
INSERT INTO `admin_role_menu` VALUES (1, 2, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
