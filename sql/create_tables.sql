CREATE TABLE Person(
  PersonKey SERIAL PRIMARY KEY,
  FirstName varchar(20) NOT NULL, 
  LastName varchar(50) NOT NULL,
  UserId varchar(20) NOT NULL,
  PassWord varchar(20) NOT NULL
);

CREATE TABLE TaskClass(  
  TaskClassKey SERIAL PRIMARY KEY,
  PersonKey INTEGER REFERENCES Person(PersonKey),
  TaskClassName varchar(20) NOT NULL, 
  TaskClassDescription varchar(1000)
);

CREATE TABLE PriorityClass(
  PriorityClassKey SERIAL PRIMARY KEY,
  PersonKey INTEGER REFERENCES Person(PersonKey),  
  PriorityClassName varchar(50) NOT NULL, 
  SortOrder INTEGER NOT NULL
);


CREATE TABLE Task(
	TaskKey SERIAL PRIMARY KEY,	
	PersonKey INTEGER REFERENCES Person(PersonKey),
	PriorityClassKey INTEGER REFERENCES PriorityClass(PriorityClassKey),
	TaskName varchar(50) NOT NULL,
	TaskDescription varchar(1000)
) ;

CREATE TABLE TaskClassOfTask(
	TaskClassOfClassKey SERIAL PRIMARY KEY,
	TaskKey INTEGER REFERENCES Task(TaskKey),
	TaskClassKey INTEGER REFERENCES TaskClass(TaskClassKey)	

)