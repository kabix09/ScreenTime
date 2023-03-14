/*
 * Author: kabix09
 *
 * Script to create login & user account with specific granted permissions
 *
 * ToDo:
 *      Refactor using https://stackoverflow.com/questions/727788/how-to-use-a-variable-for-the-database-name-in-t-sql
 */

IF EXISTS (SELECT * FROM sys.databases WHERE name = 'ActorsInMovieTime')
BEGIN
	USE master;
	
	DROP DATABASE ActorsInMovieTime;
END
go

IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'ActorsInMovieTime')
BEGIN
	CREATE DATABASE ActorsInMovieTime;
END
go

USE ActorsInMovieTime;
go


/* create login & user and add roles */
IF EXISTS (SELECT * FROM sys.sql_logins WHERE name = 'webPageLogin')
BEGIN
	DROP LOGIN webPageLogin;
END
go

CREATE LOGIN webPageLogin 
	WITH PASSWORD = 'webPageLogin12',
	DEFAULT_DATABASE = ActorsInMovieTime;
go

IF EXISTS (SELECT * FROM sys.database_principals WHERE type_desc = 'SQL_USER' AND name = 'webPageUser')
BEGIN
	ALTER ROLE db_owner DROP MEMBER webPageUser;

	USE master;
	DROP USER webPageUser;
	USE ActorsInMovieTime;
END
go

CREATE USER webPageUser FOR LOGIN webPageLogin;
go

ALTER ROLE db_owner ADD MEMBER webPageUser;
go
