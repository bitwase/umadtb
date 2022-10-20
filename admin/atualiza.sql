delete FROM `diretorios` where diretorio = 'financeiro';
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 2;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 3;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 4;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 5;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 6;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 7;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 8;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 9;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 15;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 18;
UPDATE `diretorios` SET `arquivo` = 'cadastros', `diretorio` = 'admin', `titulo` = 'Cadastros' WHERE `diretorios`.`id` = 1;
INSERT INTO `diretorios` (`id`, `arquivo`, `diretorio`, `titulo`) VALUES (NULL, 'inscritos', 'eventos', 'Inscritos');


DELETE FROM `tb_grid` WHERE `tb_grid`.`id` = 1;
DELETE FROM `tb_grid` WHERE `tb_grid`.`id` = 2;
DELETE FROM `tb_grid` WHERE `tb_grid`.`id` = 3;
DELETE FROM `tb_grid` WHERE `tb_grid`.`id` = 4;
DELETE FROM `tb_grid` WHERE `tb_grid`.`id` = 5;
DELETE FROM `tb_grid` WHERE `tb_grid`.`id` = 6;
DELETE FROM `tb_grid` WHERE `tb_grid`.`id` = 7;
DELETE FROM `tb_grid` WHERE `tb_grid`.`id` = 8;
DELETE FROM `tb_grid` WHERE `tb_grid`.`id` = 9;
DELETE FROM `tb_grid` WHERE `tb_grid`.`id` = 10;
DELETE FROM `tb_grid` WHERE `tb_grid`.`id` = 11;
DELETE FROM `tb_grid` WHERE `tb_grid`.`id` = 14;
DELETE FROM `tb_grid` WHERE `tb_grid`.`id` = 15;
DELETE FROM `tb_grid` WHERE `tb_grid`.`id` = 16;
DELETE FROM `tb_grid` WHERE `tb_grid`.`id` = 17;

UPDATE `tb_menu` SET `menu` = 'Cadastros', `href` = '?pg=cadastros', `file` = 'cadastros' WHERE `tb_menu`.`id` = 38
UPDATE `tb_menu` SET `menu` = 'Eventos' WHERE `tb_menu`.`id` = 47;
UPDATE `tb_menu` SET `ico` = 'fa fa-calendar-days fa-lg ' WHERE `tb_menu`.`id` = 47;
UPDATE `tb_menu` SET `menu` = 'Inscritos' WHERE `tb_menu`.`id` = 48;
UPDATE `tb_menu` SET `href` = '?pg=inscritos' WHERE `tb_menu`.`id` = 48;
UPDATE `tb_menu` SET `file` = 'inscritos' WHERE `tb_menu`.`id` = 48;
DELETE FROM `tb_menu` WHERE `tb_menu`.`id` = 39;
DELETE FROM `tb_menu` WHERE `tb_menu`.`id` = 43;
DELETE FROM `tb_menu` WHERE `tb_menu`.`id` = 44;
DELETE FROM `tb_menu` WHERE `tb_menu`.`id` = 45;
DELETE FROM `tb_menu` WHERE `tb_menu`.`id` = 46;
DELETE FROM `tb_menu` WHERE `tb_menu`.`id` = 56;
DELETE FROM `tb_menu` WHERE `tb_menu`.`id` = 57;
DELETE FROM `tb_menu` WHERE `tb_menu`.`id` = 59;
DELETE FROM `tb_menu` WHERE `tb_menu`.`id` = 49;
DELETE FROM `tb_menu` WHERE `tb_menu`.`id` = 50;
DELETE FROM `tb_menu` WHERE `tb_menu`.`id` = 51;
DELETE FROM `tb_menu` WHERE `tb_menu`.`id` = 54;
DELETE FROM `tb_menu` WHERE `tb_menu`.`id` = 55;

delete from tb_usuario where id > 1;

UPDATE `diretorios` SET `arquivo` = 'total.inscritos' WHERE `diretorios`.`id` = 22;
UPDATE `diretorios` SET `titulo` = 'Total de Inscritos' WHERE `diretorios`.`id` = 22;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 23;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 24;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 25;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 26;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 27;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 28;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 29;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 30;
DELETE FROM `diretorios` WHERE `diretorios`.`id` = 31;