USE [master]
GO
/****** Object:  Database [SimpleBankAccounts]    Script Date: 15.7.2013 г. 20:52:47 ч. ******/
CREATE DATABASE [SimpleBankAccounts]
 CONTAINMENT = NONE
 ON  PRIMARY 
( NAME = N'SimpleBankAccounts', FILENAME = N'D:\Evgeni\MS SQL Server\Microsoft SQL Server 2012 Developer with Service Pack 1\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\SimpleBankAccounts.mdf' , SIZE = 5120KB , MAXSIZE = UNLIMITED, FILEGROWTH = 1024KB )
 LOG ON 
( NAME = N'SimpleBankAccounts_log', FILENAME = N'D:\Evgeni\MS SQL Server\Microsoft SQL Server 2012 Developer with Service Pack 1\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\SimpleBankAccounts_log.ldf' , SIZE = 2048KB , MAXSIZE = 2048GB , FILEGROWTH = 10%)
GO
ALTER DATABASE [SimpleBankAccounts] SET COMPATIBILITY_LEVEL = 110
GO
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled'))
begin
EXEC [SimpleBankAccounts].[dbo].[sp_fulltext_database] @action = 'enable'
end
GO
ALTER DATABASE [SimpleBankAccounts] SET ANSI_NULL_DEFAULT OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET ANSI_NULLS OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET ANSI_PADDING OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET ANSI_WARNINGS OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET ARITHABORT OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET AUTO_CLOSE OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET AUTO_CREATE_STATISTICS ON 
GO
ALTER DATABASE [SimpleBankAccounts] SET AUTO_SHRINK OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET AUTO_UPDATE_STATISTICS ON 
GO
ALTER DATABASE [SimpleBankAccounts] SET CURSOR_CLOSE_ON_COMMIT OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET CURSOR_DEFAULT  GLOBAL 
GO
ALTER DATABASE [SimpleBankAccounts] SET CONCAT_NULL_YIELDS_NULL OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET NUMERIC_ROUNDABORT OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET QUOTED_IDENTIFIER OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET RECURSIVE_TRIGGERS OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET  DISABLE_BROKER 
GO
ALTER DATABASE [SimpleBankAccounts] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET DATE_CORRELATION_OPTIMIZATION OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET TRUSTWORTHY OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET ALLOW_SNAPSHOT_ISOLATION OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET PARAMETERIZATION SIMPLE 
GO
ALTER DATABASE [SimpleBankAccounts] SET READ_COMMITTED_SNAPSHOT OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET HONOR_BROKER_PRIORITY OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET RECOVERY FULL 
GO
ALTER DATABASE [SimpleBankAccounts] SET  MULTI_USER 
GO
ALTER DATABASE [SimpleBankAccounts] SET PAGE_VERIFY CHECKSUM  
GO
ALTER DATABASE [SimpleBankAccounts] SET DB_CHAINING OFF 
GO
ALTER DATABASE [SimpleBankAccounts] SET FILESTREAM( NON_TRANSACTED_ACCESS = OFF ) 
GO
ALTER DATABASE [SimpleBankAccounts] SET TARGET_RECOVERY_TIME = 0 SECONDS 
GO
EXEC sys.sp_db_vardecimal_storage_format N'SimpleBankAccounts', N'ON'
GO
USE [SimpleBankAccounts]
GO
/****** Object:  StoredProcedure [dbo].[usp_DepositMoney]    Script Date: 15.7.2013 г. 20:52:48 ч. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROC [dbo].[usp_DepositMoney] (
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


GO
/****** Object:  StoredProcedure [dbo].[usp_GetBalanceGreaterThan]    Script Date: 15.7.2013 г. 20:52:48 ч. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROC [dbo].[usp_GetBalanceGreaterThan] (
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
GO
/****** Object:  StoredProcedure [dbo].[usp_SelectFullNamesOfAllPersons]    Script Date: 15.7.2013 г. 20:52:48 ч. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROC [dbo].[usp_SelectFullNamesOfAllPersons]
AS
	SELECT FirstName + ' ' + LastName AS [Full Name]
	FROM Persons;
GO
/****** Object:  StoredProcedure [dbo].[usp_UpdateBalance]    Script Date: 15.7.2013 г. 20:52:48 ч. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROC [dbo].[usp_UpdateBalance] (
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


GO
/****** Object:  StoredProcedure [dbo].[usp_WithdrawMoney]    Script Date: 15.7.2013 г. 20:52:48 ч. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROC [dbo].[usp_WithdrawMoney] (
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
/****** Object:  UserDefinedFunction [dbo].[ufn_SimpleYearlyInterest]    Script Date: 15.7.2013 г. 20:52:48 ч. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[ufn_SimpleYearlyInterest] (
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
GO
/****** Object:  Table [dbo].[Accounts]    Script Date: 15.7.2013 г. 20:52:48 ч. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Accounts](
	[Id] [bigint] IDENTITY(1,1) NOT NULL,
	[PersonId] [bigint] NOT NULL,
	[Balance] [money] NOT NULL,
 CONSTRAINT [PK_Accounts] PRIMARY KEY CLUSTERED 
(
	[Id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[Logs]    Script Date: 15.7.2013 г. 20:52:48 ч. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Logs](
	[Id] [bigint] IDENTITY(1,1) NOT NULL,
	[AccountId] [bigint] NOT NULL,
	[OldBalance] [money] NULL,
	[NewBalance] [money] NOT NULL,
 CONSTRAINT [PK_Logs] PRIMARY KEY CLUSTERED 
(
	[Id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[Persons]    Script Date: 15.7.2013 г. 20:52:48 ч. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Persons](
	[Id] [bigint] IDENTITY(1,1) NOT NULL,
	[FirstName] [nvarchar](64) NOT NULL,
	[LastName] [nvarchar](64) NOT NULL,
	[SSN] [nvarchar](64) NULL,
 CONSTRAINT [PK_Persons] PRIMARY KEY CLUSTERED 
(
	[Id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
ALTER TABLE [dbo].[Accounts] ADD  CONSTRAINT [DF_Accounts_Balance]  DEFAULT ((0.0)) FOR [Balance]
GO
ALTER TABLE [dbo].[Accounts]  WITH CHECK ADD  CONSTRAINT [FK_Accounts_Persons] FOREIGN KEY([PersonId])
REFERENCES [dbo].[Persons] ([Id])
GO
ALTER TABLE [dbo].[Accounts] CHECK CONSTRAINT [FK_Accounts_Persons]
GO
ALTER TABLE [dbo].[Logs]  WITH CHECK ADD  CONSTRAINT [FK_Logs_Accounts] FOREIGN KEY([AccountId])
REFERENCES [dbo].[Accounts] ([Id])
GO
ALTER TABLE [dbo].[Logs] CHECK CONSTRAINT [FK_Logs_Accounts]
GO
USE [master]
GO
ALTER DATABASE [SimpleBankAccounts] SET  READ_WRITE 
GO
