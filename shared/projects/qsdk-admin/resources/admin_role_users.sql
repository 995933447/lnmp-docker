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

 Date: 09/10/2020 10:31:24
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_role_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_users`;
CREATE TABLE `admin_role_users`  (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `admin_role_users_role_id_user_id_index`(`role_id`, `user_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_role_users
-- ----------------------------
INSERT INTO `admin_role_users` VALUES (1, 1, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
