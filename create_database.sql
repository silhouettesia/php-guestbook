create database guestbook DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
use guestbook;
create table header
(
	postid int unsigned not null auto_increment primary key,
	poster char(20) not null,
	posttime datetime not null,
	parent int not null,
	child int default 0 not null,
	email char(100),
	url char(255),
	admin int not null
) engine=InnoDB;

create table body
(
	postid int unsigned not null primary key,
	message text
)engine=InnoDB;

create table admin
(
	user char(20) not null primary key,
	passwd char(40) not null,
	email char(100) not null,
	url char(255)
)

grant select, insert, update, delete
on guestbook.*
to gbadmin@localhost identified by '12345';