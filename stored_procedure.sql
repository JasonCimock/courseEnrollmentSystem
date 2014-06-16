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

