create table if not exists `user` (
  `id` integer primary key not null,
  `email` varchar(50) not null,
  `password` varchar(255) not null,
  `timeCreated` unsigned int(10) not null,
  `nameFirst` varchar(75) default null,
  `nameLast` varchar(75) default null/*,
  primary key (`id`)*/
)/* engine=InnoDB default charset=utf8*/;

create table if not exists `userLog` (
  `id` integer primary key not null,
  `userId` unsigned int(10) default null,
  `logId` unsigned int(10) default null/*,
  primary key (`id`)*/
)/* engine=InnoDB default charset=utf8*/;

create table if not exists `log` (
  `id` integer primary key not null,
  `content` text not null,
  `timeCreated` unsigned int(10) not null/*,
  primary key (`id`)*/
)/* engine=InnoDB default charset=utf8*/;
