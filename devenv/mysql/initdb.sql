create user 'fitness'@'%' identified by '4757';
grant all privileges on *.* to 'fitness'@'%' with grant option;
create database fitness_ntf character set utf8 collate utf8_general_ci;