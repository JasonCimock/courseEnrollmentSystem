drop table idtrack;
drop table prereq;
drop table taken;
drop table sections;
drop table courses;
drop table usersession;
drop table students;
drop table users;
drop table enrollmentdates;

create table users (
  username varchar2(8) primary key,
  password varchar2(12) not null,
  isstudent varchar2(1) not null,
  isadmin varchar2(1) not null
);

create table students (
  id	      varchar2(8) primary key,
  username    varchar2(8) unique not null,
  firstname   varchar2(20),
  lastname    varchar2(20),
  address     varchar2(30),
  studenttype varchar2(1) not null,
  status      varchar2(1) not null,
  foreign key(username) references users(username)
);

create table usersession (
  sessionid varchar2(32) primary key,
  username varchar2(8) not null,
  sessiondate date,
  foreign key(username) references users(username)
);

create table enrollmentdates (
  semester	varchar2(1),
  year		number(4),
  edate		date,
  primary key(semester, year)
);

create table courses (
  coursenumber varchar2(6) primary key,
  coursename   varchar2(50),
  coursedesc   varchar2(50),
  credits      number(1) 
);

create table sections (
  seqid		varchar2(5) primary key,
  seats         number(2),
  time          varchar2(25),
  semester      varchar2(1),
  coursenumber	varchar2(6) not null,
  year		number(4),
  foreign key(coursenumber) references courses(coursenumber),
  foreign key(semester, year) references enrollmentdates(semester, year)
);

create table taken (
  id	varchar2(8),
  seqid varchar2(5),
  grade number(2,1),
  primary key(id, seqid),
  foreign key(id) references students(id),
  foreign key(seqid) references sections(seqid)
);

create table prereq (
  basecoursenumber varchar2(6),
  reqcoursenumber  varchar2(6),
  primary key(basecoursenumber, reqcoursenumber),
  foreign key(basecoursenumber) references courses(coursenumber),
  foreign key(reqcoursenumber) references courses (coursenumber)
);

create table idtrack (
  maxid		varchar2(3) primary key,
  maxnumber	number(6)
);

create or replace procedure checkgpa(my_id in varchar2) as
  cursor c1 is
    select t.grade, c.credits from taken t, sections s, courses c
      where t.id = my_id and t.seqid = s.seqid and s.coursenumber = c.coursenumber
      and not exists (select * from taken n where n.seqid = s.seqid and n.grade is null);
  my_average NUMBER := 0;
  my_credit_sum number := 0;
  my_temp_sum number := 0;
  real_id varchar2(8) := my_id;
begin
  for i in c1 loop
    my_credit_sum := my_credit_sum + i.credits;
    my_temp_sum := (i.grade * i.credits) + my_temp_sum;
  end loop;
  my_average :=  my_temp_sum / my_credit_sum;
  if my_average > 2 then
    update students set status = 'g' where id = real_id;
    commit;
  else
    update students set status = 'p' where id = real_id;
    commit;
  end if; 
end;
/

insert into users values ('a', 'a', 'y', 'n');
insert into users values ('b', 'b', 'n', 'y');
insert into users values ('c', 'c', 'y', 'y');
insert into users values ('d', 'd', 'y', 'n');
insert into users values ('e', 'e', 'y', 'n');

insert into enrollmentdates values ('f', '2013', '26-AUG-13');
insert into enrollmentdates values ('s', '2014', '15-MAY-14');
insert into enrollmentdates values ('u', '2014', '1-JUN-14');

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

create or replace trigger maxidinsert
after insert on students
declare
  maxn number;
begin
  select maxnumber into maxn from idtrack where maxid = 'max';
  maxn:= maxn + 1;
  update idtrack set maxnumber = maxn where maxid = 'max';
end;
/

create or replace view seat_count(seqid, seat_count) as 
select seqid, count(*)
from taken t 
group by seqid 
order by seqid;

commit;

