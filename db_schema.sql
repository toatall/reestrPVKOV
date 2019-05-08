create table reestr
(
	id int primary key identity,
	code_no varchar(4) not null,
	org_inspection varchar(250) not null,
	period_inspection varchar(50) not null,
	year_inspection numeric(4,0) not null,
	theme_inspection varchar(500) null,
	question_inspection varchar(1000) null,
	violations text null,
	answers_no varchar(1000) null,
	mark_elimination_violation varchar(1000) null,
	measures text null,
	description text null,
	date_create datetime default getdate(),
	date_edit datetime null,
	log_change text null,
)


create table ifns
(
	code_no varchar(4) primary key,
	name_no varchar(500) not null,
	disable_no bit default 0,
	date_create datetime default getdate(),
	date_edit datetime null,
	log_change text null,	
)

create table files
(
	id int primary key identity,
	id_reestr int not null,
	name_file varchar(500) not null,
	date_create datetime default getdate(),
	date_edit datetime null,
	log_change text null,
)