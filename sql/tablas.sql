create table autores(
    id int auto_increment primary key,
    nombre_completo varchar(250) unique,
    pais varchar(100),
    fecha date
);

create table libros(
    isbn varchar(13) primary key,
    titulo varchar(100) unique not null,
    fecha date,
    portada varchar(120) default '/img/default.png',
    autor_id int not null,
    constraint fk_autor_id foreign key(autor_id) references autores(id) on delete cascade on update cascade
);