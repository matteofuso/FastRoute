create database fastroute;
use fastroute;

create table sedi(
                     id int primary key auto_increment,
                     nome varchar(100) not null,
                     citta varchar(100) not null,
                     unique (nome, citta)
);

create table clienti(
                        id int primary key auto_increment,
                        email varchar(100) unique not null,
                        punti_utilizzato int not null,
                        numero varchar(20) not null,
                        indirizzo varchar(200) not null,
                        nome varchar(100) not null,
                        cognome varchar(100) not null
);

create table plichi(
                       id int primary key auto_increment,
                       dimensione varchar(50) not null,
                       peso decimal(10,2) not null,
                       destinazione int not null,
                       origine int not null,
                       cliente int not null,
                       consegna datetime,
                       partenza datetime,
                       ritiro datetime,
                       arrivo datetime,
                       foreign key (destinazione) references sedi(id),
                       foreign key (origine) references sedi(id),
                       foreign key (cliente) references clienti(id)
);

create table destinatari(
                            plico int primary key,
                            nome varchar(100),
                            cognome varchar(100),
                            indirizzo varchar(200),
                            foreign key (plico) references plichi(id)
);

create table personale(
                          id int primary key auto_increment,
                          email varchar(100) unique not null,
                          password varchar(200) not null,
                          nome varchar(100) not null,
                          cognome varchar(100) not null,
                          ruolo varchar(100) not null,
                          sede_lavoro int not null,
                          foreign key (sede_lavoro) references sedi(id)
);

insert into sedi(nome, citta) values ("Amazon", "Bologna");
-- Pass: Armadillo11
insert into personale(email, password, nome, cognome, ruolo, sede_lavoro) values ("mfuso011@gmail.com", "$2y$10$yraJeDmeibnxqTBBT.bIGet2E0X.6XvlJkx0oO5vc.ydeMAVTpND.", "Matteo", "Fuso", "Amministratore", 1);