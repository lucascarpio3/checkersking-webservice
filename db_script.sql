create table jogador(
	id int not null auto_increment,
	nome varchar(50) not null,
	senha varchar(255) not null,
	email varchar(255) not null,
	data_nascimento date,
	status int not null,
	avatarlink varchar(100) default 'nophoto.jpg',
	
	PRIMARY KEY (id),
	UNIQUE KEY (nome)
);

create table Jogo(
	id int not null auto_increment,
	vencedor int (7),
	data timestamp not null,
	status int (2) default 0,
	
	PRIMARY KEY (id)
);

create table jogador_jogo (
	id int not null auto_increment,
	jogoid int(7) not null,
	jogadorid int(7) not null,
	status int(2) not null,
	
	PRIMARY KEY (id),
	FOREIGN KEY (jogoid) REFERENCES jogo(id),
	FOREIGN KEY (jogadorid) REFERENCES jogador(id)
);