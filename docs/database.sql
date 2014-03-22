drop table user;
create table user(
    ID integer auto_increment, 
    user VARCHAR(32) not null, 
    password VARCHAR(64) not null, 
    role int(1) default 2, 
    primary key(ID)
);
insert into user(user,password,role) values('test','b8ffd16722f742ef29e9e8f0174379d83ef5b5c9',1),('test2','89c4db0ea3ed2b6446208398bfa41a6ff0b9692f',2);
