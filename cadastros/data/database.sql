create table estados
(
    id serial
        constraint estados_pkey
            primary key,
    nome varchar(50) not null,
    uf char(2) not null
);

alter table estados owner to postgres;

create table municipio
(
    id serial
        constraint municipio_pkey
            primary key,
    nome varchar(255) not null,
    id_estado integer
        constraint id_estado
            references estados
);

alter table municipio owner to postgres;

create table cliente
(
    id serial
        constraint cliente_pkey
            primary key,
    nome varchar(80) not null,
    cpf varchar(14) not null
        constraint cliente_cpf_key
            unique,
    rg varchar(10) not null
        constraint cliente_rg_key
            unique,
    telefone varchar(15),
    email varchar(80),
    logradouro varchar(100) not null,
    numero varchar(10),
    bairro varchar(100) not null,
    id_cidade integer not null
        constraint cliente_municipio_id_fk
            references municipio
);

alter table cliente owner to postgres;

create unique index cliente_telefone_uindex
    on cliente (telefone);

