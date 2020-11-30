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

 Date: 09/10/2020 10:40:16
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uri` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `permission` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_menu
-- ----------------------------
INSERT INTO `admin_menu` VALUES (1, 0, 1, 'Dashboard', 'fa-bar-chart', 'dashboard', NULL, NULL, '2020-09-17 15:02:47');
INSERT INTO `admin_menu` VALUES (2, 0, 2, '管理员', 'fa-tasks', NULL, NULL, NULL, '2020-09-19 15:32:23');
INSERT INTO `admin_menu` VALUES (3, 2, 3, '用户', 'fa-users', 'auth/users', NULL, NULL, '2020-09-19 15:32:43');
INSERT INTO `admin_menu` VALUES (4, 2, 4, '角色', 'fa-user', 'auth/roles', NULL, NULL, '2020-09-19 15:33:19');
INSERT INTO `admin_menu` VALUES (5, 2, 5, '权限', 'fa-ban', 'auth/permissions', NULL, NULL, '2020-09-19 15:33:30');
INSERT INTO `admin_menu` VALUES (6, 2, 6, '菜单', 'fa-bars', 'auth/menu', NULL, NULL, '2020-09-19 15:33:40');
INSERT INTO `admin_menu` VALUES (7, 2, 7, '操作日志', 'fa-history', 'auth/logs', NULL, NULL, '2020-09-19 15:34:03');
INSERT INTO `admin_menu` VALUES (8, 0, 0, '会员中心', 'fa-bars', NULL, NULL, '2020-09-15 20:01:53', '2020-09-17 12:11:39');
INSERT INTO `admin_menu` VALUES (9, 8, 0, '会员管理', 'fa-bars', '/users/', NULL, '2020-09-15 20:02:26', '2020-09-17 12:12:31');
INSERT INTO `admin_menu` VALUES (10, 0, 0, '游戏应用中心', 'fa-bars', NULL, NULL, '2020-09-16 10:36:10', '2020-09-17 12:11:50');
INSERT INTO `admin_menu` VALUES (11, 0, 7, '系统助手', 'fa-gears', NULL, NULL, '2020-09-16 12:16:41', '2020-09-19 15:34:27');
INSERT INTO `admin_menu` VALUES (12, 11, 8, 'Scaffold', 'fa-keyboard-o', 'helpers/scaffold', NULL, '2020-09-16 12:16:41', '2020-09-16 12:16:41');
INSERT INTO `admin_menu` VALUES (13, 11, 9, 'Database terminal', 'fa-database', 'helpers/terminal/database', NULL, '2020-09-16 12:16:41', '2020-09-16 12:16:41');
INSERT INTO `admin_menu` VALUES (14, 11, 10, 'Laravel artisan', 'fa-terminal', 'helpers/terminal/artisan', NULL, '2020-09-16 12:16:41', '2020-09-16 12:16:41');
INSERT INTO `admin_menu` VALUES (15, 11, 11, 'Routes', 'fa-list-alt', 'helpers/routes', NULL, '2020-09-16 12:16:41', '2020-09-16 12:16:41');
INSERT INTO `admin_menu` VALUES (16, 10, 0, '游戏应用管理', 'fa-bars', '/game-apps', NULL, '2020-09-16 15:37:53', '2020-09-17 12:12:40');
INSERT INTO `admin_menu` VALUES (17, 10, 0, '游戏应用类型管理', 'fa-bars', 'support-game-types', NULL, '2020-09-16 15:38:25', '2020-09-17 12:12:53');
INSERT INTO `admin_menu` VALUES (18, 0, 0, '发布渠道中心', 'fa-bars', NULL, NULL, '2020-09-16 16:53:08', '2020-09-17 12:12:18');
INSERT INTO `admin_menu` VALUES (19, 18, 0, '渠道sdk版本管理', 'fa-bars', '/post-channel-sdk-versions', NULL, '2020-09-16 16:53:59', '2020-09-17 12:13:04');
INSERT INTO `admin_menu` VALUES (20, 18, 0, '渠道管理', 'fa-bars', '/post-channels', NULL, '2020-09-16 16:54:48', '2020-09-17 12:13:16');
INSERT INTO `admin_menu` VALUES (21, 10, 0, '游戏渠道sdk参数值管理', 'fa-bars', 'game-app-posted-channel-arguments', NULL, '2020-09-17 20:26:12', '2020-09-17 20:46:17');
INSERT INTO `admin_menu` VALUES (22, 18, 0, '渠道sdk参数管理', 'fa-bars', '/post-channel-arguments', NULL, '2020-09-17 20:44:59', '2020-09-17 20:44:59');
INSERT INTO `admin_menu` VALUES (23, 0, 0, '游戏中心', 'fa-bars', NULL, NULL, '2020-09-19 10:40:24', '2020-09-19 10:40:24');
INSERT INTO `admin_menu` VALUES (24, 23, 0, '玩家注册登录管理', 'fa-bars', NULL, NULL, '2020-09-19 10:41:10', '2020-09-19 16:00:16');
INSERT INTO `admin_menu` VALUES (25, 23, 0, '充值管理', 'fa-bars', NULL, NULL, '2020-09-19 10:41:29', '2020-09-19 10:41:29');
INSERT INTO `admin_menu` VALUES (26, 24, 0, '注册记录', 'fa-bars', 'register-player-logs', NULL, '2020-09-19 10:47:10', '2020-09-19 10:47:10');
INSERT INTO `admin_menu` VALUES (27, 24, 0, '登录记录', 'fa-bars', 'player-login-logs', NULL, '2020-09-19 12:55:11', '2020-09-19 12:55:11');
INSERT INTO `admin_menu` VALUES (28, 23, 0, '游戏角色管理', 'fa-bars', NULL, NULL, '2020-09-19 13:02:18', '2020-09-19 15:59:51');
INSERT INTO `admin_menu` VALUES (29, 28, 0, '创角记录', 'fa-bars', '/player-create-game-role-logs', NULL, '2020-09-19 13:02:36', '2020-09-19 14:07:34');
INSERT INTO `admin_menu` VALUES (30, 28, 0, '角色进入游戏记录', 'fa-bars', '/player-game-role-enter-game-logs', NULL, '2020-09-19 13:02:52', '2020-09-19 14:07:56');
INSERT INTO `admin_menu` VALUES (32, 25, 0, '订单列表', 'fa-bars', '/order-logs', NULL, '2020-09-19 14:08:58', '2020-09-19 14:08:58');
INSERT INTO `admin_menu` VALUES (33, 23, 0, '消息推送', 'fa-bars', NULL, NULL, '2020-09-22 10:15:15', '2020-09-22 10:15:15');
INSERT INTO `admin_menu` VALUES (34, 33, 0, '通知研发玩家完成充值日志', 'fa-bars', '/notify-cp-order-finish-logs', NULL, '2020-09-22 10:16:14', '2020-09-22 10:55:44');

SET FOREIGN_KEY_CHECKS = 1;
