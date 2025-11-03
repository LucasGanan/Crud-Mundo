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

insert into paises (nome, continente, populacao, idioma) values
('Brasil', 'América do Sul', 214000000, 'Português'),
('Estados Unidos', 'América do Norte', 331000000, 'Inglês'),
('França', 'Europa', 68000000, 'Francês'),
('Japão', 'Ásia', 125000000, 'Japonês'),
('Itália', 'Europa', 59000000, 'Italiano'),
('Canadá', 'América do Norte', 39000000, 'Inglês e Francês'),
('Alemanha', 'Europa', 83000000, 'Alemão'),
('Austrália', 'Oceania', 26000000, 'Inglês'),
('México', 'América do Norte', 128000000, 'Espanhol'),
('Egito', 'África', 109000000, 'Árabe');

insert into cidades (nome, populacao, id_pais) values
-- Brasil
('São Paulo', 12300000, 1),
('Rio de Janeiro', 6748000, 1),
('Brasília', 3050000, 1),
('Salvador', 2887000, 1),
('Fortaleza', 2687000, 1),

-- Estados Unidos
('Nova York', 8419000, 2),
('Los Angeles', 3980000, 2),
('Chicago', 2716000, 2),
('Houston', 2328000, 2),
('Miami', 467900, 2),

-- França
('Paris', 2148000, 3),
('Marselha', 870000, 3),
('Lyon', 520000, 3),
('Toulouse', 480000, 3),
('Nice', 340000, 3),

-- Japão
('Tóquio', 13960000, 4),
('Osaka', 2700000, 4),
('Quioto', 1475000, 4),
('Nagoya', 2300000, 4),
('Hiroshima', 1190000, 4),

-- Itália
('Roma', 2860000, 5),
('Milão', 1366000, 5),
('Nápoles', 960000, 5),
('Turim', 870000, 5),
('Florença', 380000, 5),

-- Canadá
('Toronto', 2800000, 6),
('Montreal', 1700000, 6),
('Vancouver', 660000, 6),
('Calgary', 1239000, 6),
('Ottawa', 1010000, 6),

-- Alemanha
('Berlim', 3800000, 7),
('Hamburgo', 1840000, 7),
('Munique', 1480000, 7),
('Colônia', 1080000, 7),
('Frankfurt', 763000, 7),

-- Austrália
('Sydney', 5312000, 8),
('Melbourne', 5078000, 8),
('Brisbane', 2560000, 8),
('Perth', 2110000, 8),
('Adelaide', 1370000, 8),

-- México
('Cidade do México', 9200000, 9),
('Guadalajara', 1500000, 9),
('Monterrey', 1130000, 9),
('Puebla', 1500000, 9),
('Cancún', 888000, 9),

-- Egito
('Cairo', 9900000, 10),
('Alexandria', 5200000, 10),
('Giza', 8700000, 10),
('Luxor', 1200000, 10),
('Assuã', 320000, 10);

select * from paises;
select * from cidades;