-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Person (FirstName, LastName, UserId, Password) VALUES ('Teppo', 'Testaaja', 'teppo', 'tt');
INSERT INTO TaskClass (PersonKey,TaskClassName,TaskClassDescription) VALUES (1,'Opiskelu','Opiskeluun liittyvät hommelit');
INSERT INTO TaskClass (PersonKey,TaskClassName,TaskClassDescription) VALUES (1,'Kumpula','Kumpulassa tapahtuvat jutut');
INSERT INTO PriorityClass (PersonKey,PriorityClassName,SortOrder) VALUES (1,'Kriittinen',1);
INSERT INTO Task (PersonKey,TaskName,TaskDescription) VALUES (1, 'Tsoha 2', 'Muista palauttaa viikon 2 osuus viim maanantaina');
INSERT INTO TaskClassOfTask (TaskKey,TaskClassKey) VALUES (1,1);
INSERT INTO TaskClassOfTask (TaskKey,TaskClassKey) VALUES (1,2);
