/*

IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT!

Some of the code is commented, namely the one for creating, altering, deleting, etc. data,
which should be executed just once.
Keep in mind that some of the code later on uses the tables that should be created with the commented code.

IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! 

*/


/*
Ex 1: Write a SQL query to find the names and salaries of the employees
that take the minimal salary in the company. Use a nested SELECT statement.
*/
SELECT FirstName, LastName, MiddleName, Salary
FROM Employees
WHERE Salary = (
	SELECT MIN(Salary)
	FROM Employees
);

GO

/*
Ex 2: Write a SQL query to find the names and salaries of the employees
that have a salary that is up to 10% higher than the minimal salary for the company.
*/
SELECT FirstName, LastName, MiddleName, Salary
FROM Employees
WHERE Salary <= (
	SELECT MIN(Salary)
	FROM Employees
) * 1.1;

GO

/*
Ex 3: Write a SQL query to find the full name, salary, and department of the employees
that take the minimal salary in their department. Use a nested SELECT statement.
*/
SELECT deps.Name,
	emps.FirstName + ' ' + ISNULL(emps.MiddleName + ' ', '') + emps.LastName AS [Full Name],
	emps.Salary
FROM (
		SELECT MIN(Salary) AS [Minimal Salary],
			DepartmentID
		FROM Employees
		GROUP BY DepartmentID
	) AS minSals,
	Employees AS emps
	JOIN
	Departments AS deps
		ON emps.DepartmentID = deps.DepartmentID
WHERE emps.Salary = minSals.[Minimal Salary] AND 
	emps.DepartmentID = minSals.DepartmentID
ORDER BY deps.Name ASC;

/*
NOTE: To find both min and max salary you can use this code:

SELECT deps.Name,
	emps.FirstName + ' ' + ISNULL(emps.MiddleName + ' ', '') + emps.LastName AS [Full Name],
	emps.Salary
FROM (
		SELECT MIN(Salary) AS [Minimal Salary],
			MAX(Salary) AS [Max Salary],
			DepartmentID
		FROM Employees
		GROUP BY DepartmentID
	) AS minSals,
	Employees AS emps
	JOIN
	Departments AS deps
		ON emps.DepartmentID = deps.DepartmentID
WHERE (emps.Salary = minSals.[Minimal Salary] OR emps.Salary = minSals.[Max Salary]) AND 
	emps.DepartmentID = minSals.DepartmentID
ORDER BY deps.Name ASC, emps.Salary DESC;

Also NOTE that sorting (ORDER BY) is slow operation, especially on big data sets.
*/

GO

/*
Ex 4: Write a SQL query to find the average salary in the department #1.
*/
SELECT DepartmentID,
	AVG(Salary) AS [Avarage Salary]
FROM Employees
GROUP BY DepartmentID
HAVING DepartmentID = 1;

GO

/*
Ex 5: Write a SQL query to find the average salary in the "Sales" department.
*/
SELECT deps.Name,
	AVG(emps.Salary) AS [Avarage Salary]
FROM Employees AS emps
	JOIN
	Departments AS deps
		ON emps.DepartmentID = deps.DepartmentID
GROUP BY deps.Name
HAVING deps.Name = 'Sales';

GO

/*
Ex 6: Write a SQL query to find the number of employees in the "Sales" department.
*/
SELECT deps.Name,
	COUNT(emps.EmployeeID) AS [Employees Count]
FROM Employees AS emps
	JOIN
	Departments AS deps
		ON emps.DepartmentID = deps.DepartmentID
GROUP BY deps.Name
HAVING deps.Name = 'Sales';

GO

/*
Ex 7: Write a SQL query to find the number of all employees that have manager.
*/
SELECT COUNT(EmployeeID) AS [Employees Count with Manager]
FROM Employees
WHERE ManagerID IS NOT NULL;

GO

/*
Ex 8: Write a SQL query to find the number of all employees that have no manager.
*/
SELECT COUNT(EmployeeID) AS [Employees Count with Manager]
FROM Employees
WHERE ManagerID IS NULL;

GO

/*
Ex 9: Write a SQL query to find all departments and the average salary for each of them.
*/
SELECT deps.Name,
	AVG(emps.Salary) AS [Avarage Salary]
FROM Employees AS emps
	JOIN
	Departments AS deps
		ON emps.DepartmentID = deps.DepartmentID
GROUP BY deps.Name
ORDER BY deps.Name;

GO

/*
Ex 10: Write a SQL query to find the count of all employees in each department and for each town.
*/
SELECT Towns.Name AS [Town Name],
	deps.Name AS [Department Name],
	COUNT(emps.EmployeeID) AS [Employees Count]
FROM Employees AS emps
	JOIN
	Departments AS deps
		ON emps.DepartmentID = deps.DepartmentID
	JOIN
	Addresses AS addr
		ON emps.AddressID = addr.AddressID
	JOIN
	Towns
		ON addr.TownID = Towns.TownID
GROUP BY deps.Name, Towns.Name
ORDER BY deps.Name, Towns.Name;

GO

/*
Ex 11: Write a SQL query to find all managers that have exactly 5 employees.
Display their first name and last name.
*/
SELECT mngr.EmployeeID AS [Manager ID],
	mngr.FirstName AS [Manager First Name],
	mngr.LastName AS [Manager Last Name],
	COUNT(emps.EmployeeID) AS [Employees Count]
FROM Employees AS emps
	JOIN
	Employees AS mngr
		ON emps.ManagerID = mngr.EmployeeID
GROUP BY mngr.EmployeeID, mngr.FirstName, mngr.LastName
HAVING COUNT(emps.EmployeeID) = 5;

GO

/*
Ex 12: Write a SQL query to find all employees along with their managers.
For employees that do not have manager display the value "(no manager)".
*/
SELECT emps.FirstName + ' ' + ISNULL(emps.MiddleName + ' ', '') + emps.LastName AS [Employee Full Name],
	ISNULL(mngrs.FirstName + ' ' + ISNULL(mngrs.MiddleName + ' ', '') + mngrs.LastName, '(no manager)') AS [Manager Full Name]
FROM Employees AS emps
	LEFT JOIN
	Employees AS mngrs
		ON emps.ManagerID = mngrs.EmployeeID;

GO

/*
Ex 13: Write a SQL query to find the names of all employees
whose last name is exactly 5 characters long.
Use the built-in LEN(str) function.
*/
SELECT LastName + ', ' + FirstName + ' ' + LastName AS [Bond, James Bond :)]
FROM Employees
GROUP BY LastName, FirstName
HAVING LEN(LastName) = 5;

GO

/*
Ex 14: Write a SQL query to display the current date and time in the following format:
"day.month.year hour:minutes:seconds:milliseconds".
Search in Google to find how to format dates in SQL Server.
*/
SELECT CONVERT(nvarchar(10), GETDATE(), 104) + ' ' +
	CONVERT(nvarchar(12), GETDATE(), 114) AS CurrentDateTime;

GO

/*
Ex 15: Write a SQL statement to create a table Users.
Users should have username, password, full name and last login time.
Choose appropriate data types for the table fields.
Define a primary key column with a primary key constraint.
Define the primary key column as identity to facilitate inserting records.
Define unique constraint to avoid repeating usernames.
Define a check constraint to ensure the password is at least 5 characters long.
*/
/*
CREATE TABLE Users (
	UserID bigint IDENTITY,
	Username nvarchar(32) NOT NULL UNIQUE,
	UserPassword nvarchar(128) NOT NULL,
	[Full Name] nvarchar(64),
	[Last Login Time] DATETIME,
	CONSTRAINT PK_Users PRIMARY KEY (UserID),
	CONSTRAINT Chk_PasswordLength CHECK (LEN(UserPassword) >= 5)
);

GO
*/

/*
NOTE: If you want to completely delete the table Users, you can use this:

DROP Table Users;

GO


But if you only want to delete its data, without dropping the table itslef, you can use this:

TRUNCATE TABLE Users;

GO

*/


/*
Ex 16: Write a SQL statement to create a view that displays the users from the Users table
that have been in the system today. Test if the view works correctly.
*/
/*
CREATE View UsersToday AS
SELECT UserID, Username, [Full Name], [Last Login Time]
FROM Users
WHERE CAST([Last Login Time] AS DATE) = CAST(GETDATE() AS DATE);

GO
*/

/*
NOTE: If you want to delete the view UsersToday, you can use this:

DROP View UsersToday;

GO
*/

-- Run this script to populate the Users database with old users if it's empty.
/*
INSERT INTO Users(Username, UserPassword, [Full Name], [Last Login Time])
VALUES ('oldUsername1', 'password', 'Old Username the I', '2000.01.01 01:01:01');

GO

INSERT INTO Users(Username, UserPassword, [Full Name], [Last Login Time])
VALUES ('oldUsername2', 'passsentence', 'Old Username the II', '2001.02.02 02:02:02');

GO

INSERT INTO Users(Username, UserPassword, [Full Name], [Last Login Time])
VALUES ('oldUsername3', 'passparagraph', 'Old Username the III', '2002.03.03 03:03:03');

GO

INSERT INTO Users(Username, UserPassword, [Full Name], [Last Login Time])
VALUES ('oldUsername4', 'passchapter', 'Old Username the IV', '2003.04.04 04:04:04');

GO

INSERT INTO Users(Username, UserPassword, [Full Name], [Last Login Time])
VALUES ('oldUsername5', 'passbook', 'Old Username the V', '2004.05.05 05:05:05');

GO

INSERT INTO Users(Username, UserPassword, [Full Name], [Last Login Time])
VALUES ('oldUsername6', 'passlibrary', 'Old Username the VI', '2005.06.06 06:06:06');

GO

-- Use this to populate with users with current date
INSERT INTO Users(Username, UserPassword, [Full Name], [Last Login Time])
VALUES ('currentUsername1' + CONVERT(CHAR(10), GETDATE(), 113), 'current', 'New User''s Full Name', GETDATE());

GO

INSERT INTO Users(Username, UserPassword, [Full Name], [Last Login Time])
VALUES ('currentUsername2' + CONVERT(CHAR(10), GETDATE(), 113), 'current', 'New User''s Full Name', GETDATE());

GO

-- Test the View UsersToday
SELECT UserID, Username, [Full Name], [Last Login Time]
FROM UsersToday;

GO
*/


/*
Ex 17: Write a SQL statement to create a table Groups.
Groups should have unique name (use unique constraint).
Define primary key and identity column.
*/
/*
CREATE TABLE Groups (
	GroupID bigint IDENTITY PRIMARY KEY,
	Name nvarchar(32) NOT NULL,
	CONSTRAINT uc_Name UNIQUE (Name)
);

GO
*/

/*
NOTE: To delete the table Groups use:

DROP Table Groups;

GO


But if you only want to delete its data, without dropping the table itslef, you can use this:

TRUNCATE TABLE Groups;

GO

*/



/*
Ex 18: Write a SQL statement to add a column GroupID to the table Users.
Fill some data in this new column and as well in the Groups table.
Write a SQL statement to add a foreign key constraint between tables Users and Groups tables.
*/
/*
ALTER TABLE Users
ADD GroupID bigint;

GO

ALTER TABLE Users
ADD CONSTRAINT FK_Users_Groups
	FOREIGN KEY (GroupID)
	REFERENCES Groups (GroupID);

GO

-- Add some data to Groups Table
INSERT INTO Groups(Name)
VALUES ('Admin');

GO

INSERT INTO Groups(Name)
VALUES ('Junior User');

GO

INSERT INTO Groups(Name)
VALUES ('Regular User');

GO

INSERT INTO Groups(Name)
VALUES ('Senior User');

GO
*/

/* Change the user data in the Users Table (add data to GroupID). */
/*
UPDATE Users
SET GroupID = 1
WHERE UserID = 1;

GO

UPDATE Users
SET GroupID = 4
WHERE UserID = 2;

GO

UPDATE Users
SET GroupID = 3
WHERE UserID = 3;

GO

UPDATE Users
SET GroupID = 2
WHERE UserID > 3;

GO
*/



/*
Ex 19: Write SQL statements to insert several records in the Users and Groups tables.
*/
/*
BEGIN TRANSACTION
	DECLARE @GroupID int;
	DECLARE @NewGroupName nvarchar(32);

	SET @NewGroupName = 'Banned';

	INSERT INTO Groups(Name)
		VALUES (@NewGroupName);

	SET @GroupID = (
		SELECT GroupID
		FROM Groups
		WHERE Name = @NewGroupName);

	INSERT INTO Users (Username, UserPassword, [Full Name], [Last Login Time], GroupID)
		VALUES ('Banned User', 'Banned', 'Banned Hacker User', '1995.06.06 06:06:06', @GroupID);
COMMIT

BEGIN TRANSACTION
	DECLARE @GroupID int;
	DECLARE @NewGroupName nvarchar(32);

	SET @NewGroupName = 'Rookie';

	INSERT INTO Groups(Name)
		VALUES (@NewGroupName);

	SET @GroupID = (
		SELECT GroupID
		FROM Groups
		WHERE Name = @NewGroupName);

	INSERT INTO Users (Username, UserPassword, [Full Name], [Last Login Time], GroupID)
		VALUES ('Rookie User', 'Rookie', 'Rookie User', '1995.06.06 06:06:06', @GroupID);
COMMIT

GO

INSERT INTO Groups(Name)
	VALUES ('Telerik');

GO

INSERT INTO Users (Username, UserPassword, [Full Name], [Last Login Time], GroupID)
VALUES ('Another', 'Users', 'Another User', '2012.06.06 06:06:06', 2);

GO

INSERT INTO Users (Username, UserPassword, [Full Name], [Last Login Time], GroupID)
VALUES ('Yet Another', 'Users', 'Yet Another User', '2012.06.06 06:06:06', 3);

GO
*/


/*
Ex 20: Write SQL statements to update some of the records in the Users and Groups tables.
*/
/*
UPDATE Groups
SET Name = 'Hacker'
WHERE Name = 'Banned';

GO

SELECT GroupID, Name
FROM Groups;

GO

UPDATE Groups
SET Name = 'Banned'
WHERE Name = 'Hacker';

GO

SELECT GroupID, Name
FROM Groups;

GO

SELECT UserID, Username, GroupID
FROM Users;

GO

UPDATE Users
SET GroupID = 7
WHERE Username IN ('Another', 'Yet Another');

GO

SELECT UserID, Username, GroupID
FROM Users;

GO
*/


/*
Ex 21: Write SQL statements to delete some of the records from the Users and Groups tables.
*/
/*
-- Checking...
SELECT UserID, Username, GroupID
FROM Users;

GO

SELECT GroupID, Name
FROM Groups;

GO

-- Deleting...
DELETE FROM Users
WHERE GroupID = 7;

GO

DELETE FROM Groups
WHERE Name = 'Rookie';

GO

-- Checking again...
SELECT UserID, Username, GroupID
FROM Users;

GO

SELECT GroupID, Name
FROM Groups;

GO
*/



/*
Ex 22: Write SQL statements to insert in the Users table
the names of all employees from the Employees table.
Combine the first and last names as a full name.
For username use the first letter of the first name + the last name (in lowercase).
Use the same for the password, and NULL for last login time.
*/
/*
INSERT INTO Users (Username, UserPassword, [Full Name], GroupID)
SELECT LOWER(FirstName + LastName), LOWER(FirstName + LastName), FirstName + ' ' + LastName, Telerik.GroupID
FROM Employees, (
	SELECT GroupID
	FROM Groups
	WHERE Name = 'Telerik'
) AS Telerik;

GO

SELECT UserID, Username, UserPassword, [Full Name], [Last Login Time], GroupID
FROM Users;

GO
*/


/*
Ex 23: Write a SQL statement that changes the password to NULL
for all users that have not been in the system since 10.03.2010.
*/
/*
ALTER TABLE Users
DROP CONSTRAINT Chk_PasswordLength;

GO

ALTER TABLE Users
ALTER COLUMN UserPassword nvarchar(128) NULL;

GO

UPDATE Users
SET UserPassword = NULL
WHERE [Last Login Time] < CAST('10.03.2010' AS date);

GO


SELECT UserID, Username, UserPassword, [Full Name], [Last Login Time], GroupID
FROM Users;

GO
*/

/*
NOTE: Since UserPassword doesn't allow NULLs,
I had to update the Column and drop the constraint for Password Length.
*/


/*
Ex 24: Write a SQL statement that deletes all users without passwords (NULL password).
*/
/*
SELECT UserID, Username, UserPassword, [Full Name], [Last Login Time], GroupID
FROM Users;

GO

DELETE FROM Users
WHERE UserPassword IS NULL;

GO

SELECT UserID, Username, UserPassword, [Full Name], [Last Login Time], GroupID
FROM Users;

GO
*/



/*
Ex 25: Write a SQL query to display the average employee salary by department and job title.
*/
SELECT deps.Name AS [Department],
	emps.JobTitle AS [Job Title],
	AVG(emps.Salary) AS [Avarage Salary]
FROM Employees AS emps
	JOIN
	Departments AS deps
		ON emps.DepartmentID = deps.DepartmentID
GROUP BY deps.Name, emps.JobTitle;

GO



/*
Ex 26: Write a SQL query to display the minimal employee salary by department and job title
along with the name of some of the employees that take it.
*/
SELECT minSal.[Department],
	minSal.[Job Title],
	empls.FirstName + ' ' + empls.LastName AS [Name],
	empls.Salary
FROM Employees AS empls,
	(SELECT deps.Name AS [Department],
		emps.JobTitle AS [Job Title],
		MIN(emps.Salary) AS [Minimal Salary]
	FROM Employees AS emps
		JOIN
		Departments AS deps
			ON emps.DepartmentID = deps.DepartmentID
	GROUP BY deps.Name, emps.JobTitle
	) AS minSal
WHERE empls.Salary = minSal.[Minimal Salary];

GO


/*
Ex 27: Write a SQL query to display the town where maximal number of employees work.
*/
SELECT empsCount.[Town Name], empsCount.[Employees Count]
FROM (
	SELECT Towns.Name AS [Town Name],
		COUNT(EmployeeID) AS [Employees Count]
	FROM Employees AS emps
		JOIN
		Addresses AS addr
			ON emps.AddressID = addr.AddressID
		JOIN
		Towns
			ON addr.TownID = Towns.TownID
	GROUP BY Towns.Name
	) AS empsCount,
	(
		SELECT MAX(innerEmpsCount.[Employees Count]) AS [Employees Max]
		FROM
		(
			SELECT Towns.Name AS [Town Name],
				COUNT(EmployeeID) AS [Employees Count]
			FROM Employees AS emps
				JOIN
				Addresses AS addr
					ON emps.AddressID = addr.AddressID
				JOIN
				Towns
					ON addr.TownID = Towns.TownID
			GROUP BY Towns.Name
		) AS innerEmpsCount
	) AS empsMax
WHERE empsCount.[Employees Count] = empsMax.[Employees Max];

/*
NOTE: Very ugly solution, with lots of repetition, but I couldn't find a better one!
*/

GO


/*
Ex 28: Write a SQL query to display the number of managers from each town.
*/
