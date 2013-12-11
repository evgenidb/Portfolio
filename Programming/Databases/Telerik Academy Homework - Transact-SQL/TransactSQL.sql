/*

IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT!

Queries that create, alter, delete or otherwise manipulate data are commented,
since they might throw Errors if they are run multiple times.

IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT! IMPORTANT!

*/


USE SimpleBankAccounts

GO


/*
Ex 1: Create a database with two tables:
Persons(Id(PK), FirstName, LastName, SSN)
and
Accounts(Id(PK), PersonId(FK), Balance).
Insert few records for testing.
Write a stored procedure that selects the full names of all persons.
*/

/* NOTE: Tables created in the diagram. */

/*
CREATE PROC usp_SelectFullNamesOfAllPersons
AS
	SELECT FirstName + ' ' + LastName AS [Full Name]
	FROM Persons;

GO
*/

-- Test

EXEC usp_SelectFullNamesOfAllPersons;

GO

/*
NOTE: If you need to delete the Procedure usp_SelectFullNamesOfAllPersons, use:

DROP PROC usp_SelectFullNamesOfAllPersons;

GO

*/



/*
Ex 2: Create a stored procedure that accepts a number as a parameter and returns
all persons who have more money in their accounts than the supplied number.
*/

/*
CREATE PROC usp_GetBalanceGreaterThan (
	@minimalBalance money
)
AS
	SELECT 
		per.Id,
		per.FirstName,
		per.LastName,
		COUNT(per.Id) AS [Accounts Count]
	FROM Persons as per
		JOIN
		Accounts as acc
			ON per.Id = acc.PersonId
	WHERE acc.Balance >= @minimalBalance
	GROUP BY per.Id, per.FirstName, per.LastName;
*/

GO

-- Tests

EXEC usp_GetBalanceGreaterThan 0;

GO

EXEC usp_GetBalanceGreaterThan 1000;

GO

EXEC usp_GetBalanceGreaterThan 1000000;

GO

/*
NOTE: If you need to delete the Procedure usp_GetBalanceGreaterThan, use:

DROP PROC usp_GetBalanceGreaterThan;

GO

*/



/*
Ex 3: Create a function that accepts as parameters:
	- sum;
	- yearly interest rate;
	- number of months.
It should calculate and return the new sum.
Write a SELECT to test whether the function works as expected.
*/
/*
CREATE FUNCTION ufn_SimpleYearlyInterest (
	@sum money,
	@interestRate real,
	@durationInMonths smallint
)
RETURNS money
AS
BEGIN
	DECLARE @interest money;
	SET @interest = (@interestRate / (12 * 100)) * @sum * @durationInMonths;

	RETURN @sum + @interest;
END
*/

GO

-- Tests

DECLARE @startingMoney money;
DECLARE @annualInterest real;
DECLARE @months smallint;

SET @startingMoney = 1;
SET @annualInterest = 10;
SET @months = 100;


SELECT @startingMoney AS [Starting Capital],
	dbo.ufn_SimpleYearlyInterest(@startingMoney, @annualInterest, @months) AS [After Interest];

GO

DECLARE @startingMoney money;
DECLARE @annualInterest real;
DECLARE @months smallint;

SET @startingMoney = 1000;
SET @annualInterest = 5;
SET @months = 12;


SELECT @startingMoney AS [Starting Capital],
	dbo.ufn_SimpleYearlyInterest(@startingMoney, @annualInterest, @months) AS [After Interest];

GO

DECLARE @startingMoney money;
DECLARE @annualInterest real;
DECLARE @months smallint;

SET @startingMoney = 1000000;
SET @annualInterest = 4.3;
SET @months = 36;


SELECT @startingMoney AS [Starting Capital],
	dbo.ufn_SimpleYearlyInterest(@startingMoney, @annualInterest, @months) AS [After Interest];

GO


/*
NOTE: If you need to delete the Function ufn_SimpleYearlyInterest, use:

DROP FUNCTION ufn_SimpleYearlyInterest;

GO

*/



/*
Ex 4: Create a stored procedure that uses the function from the previous example
to give an interest to a person's account for one month.
It should take the AccountId and the interest rate as parameters.
*/
/*
CREATE PROC usp_UpdateBalance (
	@accountId bigint,
	@interestRate real
)
AS
	DECLARE @oldBalance money;
	SET @oldBalance = (
		SELECT Balance
		FROM Accounts
		WHERE Id = @accountId
	);

	UPDATE Accounts
	SET Balance = dbo.ufn_SimpleYearlyInterest(@oldBalance, @interestRate, 1)
	WHERE Id = @accountId;
*/

GO

-- Tests

GO

DECLARE @accountId bigint;
DECLARE @interestRate real;

SET @accountId = 2;
SET @interestRate = 4;

SELECT
	Id AS [AccountId],
	Balance AS [Old Balance]
FROM Accounts
WHERE Id = @accountId;

EXEC usp_UpdateBalance @accountId, @interestRate;

SELECT
	Id AS [AccountId],
	Balance AS [New Balance]
FROM Accounts
WHERE Id = @accountId;

GO


/*
NOTE: If you need to delete the Procedure usp_UpdateBalance, use:

DROP PROC usp_UpdateBalance;

GO

*/



/*
Ex 5: Add two more stored procedures
	- WithdrawMoney (AccountId, money);
	- DepositMoney (AccountId, money);
that operate in transactions.
*/
/*
CREATE PROC usp_WithdrawMoney (
	@accountId bigint,
	@money money
)
AS
BEGIN
	BEGIN TRY
		BEGIN TRANSACTION Withdraw;
		
		UPDATE Accounts
		SET Balance = Balance - @money
		WHERE Id = @accountId;
		
		COMMIT TRANSACTION Withdraw;
	END TRY
	BEGIN CATCH
		IF @@TRANCOUNT > 0	-- If the transaction failed
		BEGIN
			ROLLBACK TRANSACTION Withdraw;
		END
		
		DECLARE @ErrorMessage NVARCHAR(4000);
		DECLARE @ErrorSeverity INT;
		DECLARE @ErrorState INT;

		SELECT 
			@ErrorMessage = ERROR_MESSAGE(),
			@ErrorSeverity = ERROR_SEVERITY(),
			@ErrorState = ERROR_STATE();

		RAISERROR (@ErrorMessage, -- Message text.
				   @ErrorSeverity, -- Severity.
				   @ErrorState -- State.
				   );
	END CATCH
END

GO

CREATE PROC usp_DepositMoney (
	@accountId bigint,
	@money money
)
AS
BEGIN
	BEGIN TRY
		BEGIN TRANSACTION Deposit;
		
		UPDATE Accounts
		SET Balance = Balance + @money
		WHERE Id = @accountId;
		
		COMMIT TRANSACTION Deposit;
	END TRY
	BEGIN CATCH
		IF @@TRANCOUNT > 0	-- If the transaction failed
		BEGIN
			ROLLBACK TRANSACTION Deposit;
		END
		
		DECLARE @ErrorMessage NVARCHAR(4000);
		DECLARE @ErrorSeverity INT;
		DECLARE @ErrorState INT;

		SELECT 
			@ErrorMessage = ERROR_MESSAGE(),
			@ErrorSeverity = ERROR_SEVERITY(),
			@ErrorState = ERROR_STATE();

		RAISERROR (@ErrorMessage, -- Message text.
				   @ErrorSeverity, -- Severity.
				   @ErrorState -- State.
				   );
	END CATCH
END
*/
GO

-- Tests

GO

DECLARE @accountId bigint;
DECLARE @money real;

SET @accountId = 2;
SET @money = 20;

SELECT
	Id AS [AccountId],
	Balance AS [Old Balance]
FROM Accounts
WHERE Id = @accountId;

EXEC usp_WithdrawMoney @accountId, @money;

SELECT
	Id AS [AccountId],
	Balance AS [New Balance]
FROM Accounts
WHERE Id = @accountId;

EXEC usp_DepositMoney @accountId, @money;

SELECT
	Id AS [AccountId],
	Balance AS [Restored Balance]
FROM Accounts
WHERE Id = @accountId;

GO


/*
NOTE: If you need to delete the Procedure usp_WithdrawMoney or usp_DepositMoney, use:

DROP PROC usp_WithdrawMoney;

GO

DROP PROC usp_DepositMoney;

GO

*/



/*
Ex 6: Create another table:
	- Logs (LogID, AccountID, OldSum, NewSum).

Add a trigger to the Accounts table that enters a new entry into the Logs table
every time the sum on an account changes.
*/
/*
CREATE TRIGGER tr_AccountBalanceInsert ON Accounts FOR INSERT
AS
BEGIN
	DECLARE @accountId bigint;
	DECLARE	@oldBalance money;
	DECLARE @newBalance money;

	SET @accountId = (SELECT Id FROM INSERTED);
	SET @oldBalance = NULL;
	SET @newBalance = (SELECT Balance FROM INSERTED);

	INSERT INTO Logs (AccountId, OldBalance, NewBalance)
	VALUES (@accountId, @oldBalance, @newBalance);
END

GO

CREATE TRIGGER tr_AccountBalanceUpdate ON Accounts FOR UPDATE
AS
BEGIN
	DECLARE @accountId bigint;
	DECLARE	@oldBalance money;
	DECLARE @newBalance money;

	SET @accountId = (SELECT Id FROM DELETED);
	SET @oldBalance = (SELECT Balance FROM DELETED);
	SET @newBalance = (SELECT Balance FROM INSERTED);

	INSERT INTO Logs (AccountId, OldBalance, NewBalance)
	VALUES (@accountId, @oldBalance, @newBalance);
END

GO
*/

-- Test Insert and then Update it

GO

SELECT Id,
	AccountId,
	OldBalance AS [Old Balance],
	NewBalance AS [New Balance]
FROM Logs;

GO

INSERT INTO Accounts (PersonId, Balance)
VALUES (5, 1000);

GO

SELECT Id,
	AccountId,
	OldBalance AS [Old Balance],
	NewBalance AS [New Balance]
FROM Logs;

GO

DECLARE @accountID bigint;
SET @accountID = (
	SELECT TOP 1 AccountId
	FROM Logs
	ORDER BY Id DESC
);

UPDATE Accounts
SET Balance = 2000
WHERE Id = @accountId;

GO

SELECT Id,
	AccountId,
	OldBalance AS [Old Balance],
	NewBalance AS [New Balance]
FROM Logs;

GO


-- Test Update

GO

SELECT Id,
	AccountId,
	OldBalance AS [Old Balance],
	NewBalance AS [New Balance]
FROM Logs;

GO

DECLARE @accountId bigint;
DECLARE @money real;

SET @accountId = 2;
SET @money = 20;

EXEC usp_WithdrawMoney @accountId, @money;

EXEC usp_DepositMoney @accountId, @money;

GO

SELECT Id,
	AccountId,
	OldBalance AS [Old Balance],
	NewBalance AS [New Balance]
FROM Logs;

GO

/*
NOTE: If you need to delete the Triggers tr_AccountBalanceInsert or tr_AccountBalanceUpdate, use:

GO

DROP TRIGGER tr_AccountBalanceInsert;

GO

DROP TRIGGER tr_AccountBalanceUpdate;

GO
*/