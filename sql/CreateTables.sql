CREATE TABLE Maps
(
	MapID int NOT NULL AUTO_INCREMENT,
	UserID int,

	Name varchar(255),
	Description text,
	MouseName varchar(255),
	MouseStrain varchar(255),
	BodyWeight decimal(8,4),
	DateOfBirth datetime,

	BregmaX decimal(7,4),
	BregmaY decimal(7,4),
	BregmaZ decimal(7,4),
	LambdaX decimal(7,4),
	LambdaY decimal(7,4),
	LambdaZ decimal(7,4),
	MidlineX decimal(7,4),
	MidlineY decimal(7,4),
	MidlineZ decimal(7,4),

	DateCreated datetime,	
	PRIMARY KEY (MapID)
);

CREATE TABLE Points
(
	PointID int NOT NULL AUTO_INCREMENT,
	MapID int NOT NULL,

	Name varchar(255),
	Notes text,
	XCoordinate decimal(7,4),
	YCoordinate decimal(7,4),

	DateCreated datetime,	
	PRIMARY KEY (PointID),
	FOREIGN KEY (MapID) REFERENCES Maps(MapID)
);