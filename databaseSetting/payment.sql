/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     01/03/2022 13:36:26                          */
/*==============================================================*/


drop table if exists ADMIN;

drop table if exists COMPTE;

drop table if exists TRANSACTION;

drop table if exists USER;

/*==============================================================*/
/* Table: ADMIN                                                 */
/*==============================================================*/
create table ADMIN
(
   LOGIN                varchar(15) not null,
   ADMINPASSWORD        varchar(15) not null,
   primary key (LOGIN)
);

/*==============================================================*/
/* Table: COMPTE                                                */
/*==============================================================*/
create table COMPTE
(
   IDCOMPTE             int not null,
   EMAIL                varchar(50) not null,
   NUMCOMPTE            varchar(100) not null,
   primary key (IDCOMPTE)
);

/*==============================================================*/
/* Table: TRANSACTION                                           */
/*==============================================================*/
create table TRANSACTION
(
   IDCOMPTE             int not null,
   COM_IDCOMPTE         int not null,
   DATE                 date not null,
   HEURE                time not null,
   TYPE                 varchar(1024) not null,
   primary key (IDCOMPTE, COM_IDCOMPTE)
);

/*==============================================================*/
/* Table: USER                                                  */
/*==============================================================*/
create table USER
(
   EMAIL                varchar(50) not null,
   NOM                  varchar(100),
   PRENOM               varchar(100),
   USERPASSWORD         varchar(15) not null,
   PHOTO                longblob,
   TEL                  varchar(10),
   primary key (EMAIL)
);

alter table COMPTE add constraint FK_POSSEDER foreign key (EMAIL)
      references USER (EMAIL) on delete restrict on update restrict;

alter table TRANSACTION add constraint FK_TRANSACTION foreign key (COM_IDCOMPTE)
      references COMPTE (IDCOMPTE) on delete restrict on update restrict;

alter table TRANSACTION add constraint FK_TRANSACTION2 foreign key (IDCOMPTE)
      references COMPTE (IDCOMPTE) on delete restrict on update restrict;

