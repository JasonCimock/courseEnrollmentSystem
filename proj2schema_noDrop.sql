create table users (
  username varchar(8) primary key,
  passw varchar(12) not null,
  isstudent varchar(1) not null,
  isadmin varchar(1) not null
);

create table students (
  id	      varchar(8) primary key,
  username    varchar(8) unique not null,
  firstname   varchar(20),
  lastname    varchar(20),
  address     varchar(30),
  studenttype varchar(1) not null,
  status      varchar(1) not null,
  foreign key(username) references users(username)
);

create table usersession (
  sessionid varchar(32) primary key,
  username varchar(8) not null,
  sessiondate date,
  foreign key(username) references users(username)
);

create table enrollmentdates (
  semester	varchar(1),
  year		int(4),
  edate		date,
  primary key(semester, year)
);

create table courses (
  coursenumber varchar(6) primary key,
  coursename   varchar(50),
  coursedesc   varchar(50),
  credits      int(1) 
);

create table sections (
  seqid		varchar(5) primary key,
  seats         int(2),
  time          varchar(25),
  semester      varchar(1),
  coursenumber	varchar(6) not null,
  year		int(4),
  foreign key(coursenumber) references courses(coursenumber),
  foreign key(semester, year) references enrollmentdates(semester, year)
);

create table taken (
  id	varchar(8),
  seqid varchar(5),
  grade decimal(2,1),
  primary key(id, seqid),
  foreign key(id) references students(id),
  foreign key(seqid) references sections(seqid)
);

create table prereq (
  basecoursenumber varchar(6),
  reqcoursenumber  varchar(6),
  primary key(basecoursenumber, reqcoursenumber),
  foreign key(basecoursenumber) references courses(coursenumber),
  foreign key(reqcoursenumber) references courses (coursenumber)
);

create table idtrack (
  maxid		varchar(3) primary key,
  maxint	int(6)
);


insert into users values ('a', 'a', 'y', 'n');
insert into users values ('b', 'b', 'n', 'y');
insert into users values ('c', 'c', 'y', 'y');
insert into users values ('d', 'd', 'y', 'n');
insert into users values ('e', 'e', 'y', 'n');

insert into enrollmentdates values ('f', '2013', '2013-08-26');
insert into enrollmentdates values ('s', '2014', '2014-12-25');
insert into enrollmentdates values ('u', '2014', '2014-12-25');

insert into students values ('aj000000', 'a', 'Adam', 'Jones', '100 Main St.', 'u', 'g');
insert into students values ('kl000001', 'c', 'Keith', 'Long', '200 Main St.', 'g', 'g');
insert into students values ('jc000002', 'd', 'Jeremy', 'Cimock', '300 Main St.', 'u', 'p');
insert into students values ('al000003', 'e', 'Amanda', 'Lemon', '400 Main St.', 'u', 'g');

insert into courses values ('cs1000', 'Intro to Programming', 'First course for CS majors', '3');
insert into courses values ('cs1001', 'Beginning Programming', 'Second course for CS majors', '3');
insert into courses values ('cs2000', 'Programming 1', 'Third course for CS majors', '3');
insert into courses values ('cs2001', 'Programming 2', 'Fourth course for CS majors', '3');
insert into courses values ('cs3000', 'Computer Organization', 'Fifth course for CS majors', '3');
insert into courses values ('cs3001', 'Object-Oriented Programming', 'Sixth course for CS majors', '3');
insert into courses values ('cs3002', 'Data Structures and Algorithms', 'Seventh course for CS majors', '3');
insert into courses values ('cs3003', 'Software Engineering', 'Eighth course for CS majors', '3');
insert into courses values ('cs4000', 'Software Design and Development', 'Ninth course for CS majors', '3');

insert into sections values ('00000', '5','MW 9:00-10:15','f', 'cs1000', '2013');
insert into sections values ('00001', '5','MW 9:00-10:15','f', 'cs1001', '2013');
insert into sections values ('00002', '5','MW 9:00-10:15','f', 'cs2000', '2013');
insert into sections values ('00003', '5','MW 9:00-10:15','f', 'cs2001', '2013');
insert into sections values ('00004', '2','MW 9:00-10:15','f', 'cs3000', '2013');
insert into sections values ('00005', '5','MW 9:00-10:15','f', 'cs3001', '2013');
insert into sections values ('00006', '5','MW 9:00-10:15','f', 'cs3002', '2013');
insert into sections values ('00007', '5','MW 9:00-10:15','f', 'cs3003', '2013');
insert into sections values ('00008', '5','MW 9:00-10:15','f', 'cs4000', '2013');
insert into sections values ('00009', '5','MW 9:00-10:15','s', 'cs1000', '2014');
insert into sections values ('00010', '5','MW 9:00-10:15','s', 'cs1001', '2014');
insert into sections values ('00011', '5','MW 9:00-10:15','s', 'cs2000', '2014');
insert into sections values ('00012', '5','MW 9:00-10:15','s', 'cs2001', '2014');
insert into sections values ('00013', '5','MW 9:00-10:15','s', 'cs3000', '2014');
insert into sections values ('00014', '5','MW 9:00-10:15','s', 'cs3001', '2014');
insert into sections values ('00015', '5','MW 9:00-10:15','s', 'cs3002', '2014');
insert into sections values ('00016', '5','MW 9:00-10:15','s', 'cs3003', '2014');
insert into sections values ('00017', '1','MW 9:00-10:15','s', 'cs4000', '2014');
insert into sections values ('00018', '5','MW 9:00-10:15','u', 'cs1000', '2014');
insert into sections values ('00019', '5','MW 9:00-10:15','u', 'cs1001', '2014');
insert into sections values ('00020', '5','MW 9:00-10:15','u', 'cs2000', '2014');

insert into taken values ('aj000000', '00000', '4.0');
insert into taken values ('kl000001', '00000', '4.0');
insert into taken values ('jc000002', '00000', '2.0');
insert into taken values ('al000003', '00000', '4.0');
insert into taken values ('aj000000', '00001', '3.0');
insert into taken values ('kl000001', '00001', '4.0');
insert into taken values ('jc000002', '00001', '2.0');
insert into taken values ('al000003', '00001', '3.5');
insert into taken values ('aj000000', '00002', '4.0');
insert into taken values ('kl000001', '00002', '4.0');
insert into taken values ('jc000002', '00002', '2.0');
insert into taken values ('al000003', '00002', '4.0');
insert into taken values ('kl000001', '00004', '4.0');
insert into taken values ('jc000002', '00004', '1.5');

insert into prereq values ('cs3000', 'cs2000');
insert into prereq values ('cs3001', 'cs2001');
insert into prereq values ('cs3002', 'cs2001');
insert into prereq values ('cs3002', 'cs3001');

insert into idtrack values ('max', '000003');

create or replace view seat_count(seqid, seat_count) as 
select seqid, count(*)
from taken t 
group by seqid 
order by seqid;

commit;

