#this script will be run to create the tables needed in the database
drop table if exists User cascade;
create table User(
	user_id int(10) primary key,
	upwd int(10),
	role varchar(30));

drop table if exists Teacher_Profile cascade;
create table Teacher_Profile(
	school varchar(30),
	title varchar(30),
	user_id int(10) references User(user_id));
	

drop table if exists Student_Profile cascade;
create table Student_Profile(
	major varchar(30),
	year year,
	user_id int(10) references User(user_id));
	
drop table if exists Examination cascade;
create table Examination(
	exam_id int(10) primary key,
	user_id int(10) references User(user_id),
	epwd int(10),
	status varchar(30), #may need to be a radio button of some sort
	time_limit int(10)); #assuming this will be in minutes

drop table if exists Question cascade;
create table Question(
	question_id int(10) primary key,
	exam_id int(10) references Examination(exam_id),
	q_text varchar(300)); # I made this long in case the question is long

drop table if exists Answer cascade;
create table Answer(
	answer_id int(10) primary key,
	question_id(10) references Question(question_id),
	a_text varchar(300), 
	if_correct bit);



