delete from jogador_jogo;
delete from jogo;
alter table jogo auto_increment =1;
alter table jogador_jogo auto_increment =1;
update jogador set status = 1 where status =2;

select * from  jogador
 