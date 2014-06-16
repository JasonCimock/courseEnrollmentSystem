create or replace trigger maxidinsert
after insert of students
for each row
declare
  max number;
begin
  select maxnumber into number from idtrack where maxid = 'max';
  number := number + 1;
  update idtrack set maxnumber = number where maxid = 'max';
  commit;
end;
/

create or replace trigger maxidinsert
after insert on students
begin
  select maxnumber into number from idtrack where maxid = 'max';
  number:= number + 1;
  update idtrack set maxnumber = number where maxid = 'max';
  commit;
end;
/

select t.grade, c.credits from taken t, sections s, courses c
      where t.id = 'jc000002' and t.seqid = s.seqid and s.coursenumber = c.coursenumber;

select t.grade, c.credits from taken t, sections s, courses c
      where t.id = 'jc000002'and t.seqid = s.seqid and s.coursenumber = c.coursenumber
      and not exists (select * from taken n where n.seqid = s.seqid and n.grade is null);






create or replace procedure checkgpa(my_id in varchar2) as
  cursor c1 is
    select t.grade, c.credits from taken t, sections s, courses c
      where t.id = my_id and t.seqid = s.seqid and s.coursenumber = c.coursenumber
      and not exists (select * from taken n where n.seqid = s.seqid and n.grade is null);
  my_credits number(1);
  my_grade number(2,1);
  my_average NUMBER := 0;
  my_credit_sum number := 0;
  my_temp_sum number := 0;
  real_id varchar2(8) := my_id;
begin
  open c1;
  for i in c1 loop
    fetch c1 into my_grade, my_credits;
    exit when c1%notfound; /* in case the number requested */
                           /* is more than the total */
                           /* number of enrolled students */
    my_credit_sum := my_credit_sum + my_credits;
    my_temp_sum := (my_grade * my_credits) + my_temp_sum;
  end loop;
  my_average :=  my_temp_sum / my_credit_sum;
  close c1;
  if my_average > 2 then
    update students set status = 'g' where id = real_id;
  else
    update students set status = 'p' where id = real_id;
  end if; 
end;
/

