create database bd_mundo;
use bd_mundo;

create table paises (
    id_pais int auto_increment primary key,
    nome varchar(100) not null,
    continente varchar(50) not null,
    populacao bigint not null,
    idioma varchar(50) not null
);

create table cidades (
    id_cidade int auto_increment primary key,
    nome varchar(100) not null,
    populacao bigint not null,
    id_pais int not null,
    foreign key (id_pais) references paises(id_pais)
);

insert into paises (nome, continente, populacao, idioma) VALUES
('Brasil', 'América do Sul', 214000000, 'Português'),
('Estados Unidos', 'América do Norte', 331000000, 'Inglês'),
('França', 'Europa', 68000000, 'Francês'),
('Japão', 'Ásia', 125000000, 'Japonês'),
('Itália', 'Europa', 59000000, 'Italiano');

insert into cidades (nome, populacao, id_pais) values
('São Paulo', 12300000, 1),
('Rio de Janeiro', 6748000, 1),
('Nova York', 8419000, 2),
('Los Angeles', 3980000, 2),
('Paris', 2148000, 3),
('Tóquio', 13960000, 4),
('Roma', 2860000, 5);

select * from paises;
select * from cidades;