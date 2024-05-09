-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2024 at 10:43 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.3.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `Account_Id` int(250) NOT NULL,
  `Account_Name` varchar(80) NOT NULL,
  `Account_Date_Open` date DEFAULT NULL,
  `Account_Date_Expire` date NOT NULL,
  `Account_IBAN` varchar(40) DEFAULT NULL,
  `Account_Currency` varchar(80) NOT NULL,
  `Account_Type_Id` int(250) NOT NULL,
  `Bank_Id` int(250) NOT NULL,
  `Account_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `account_type`
--

CREATE TABLE `account_type` (
  `Account_Type_Id` int(250) NOT NULL,
  `Account_Desc` varchar(150) NOT NULL,
  `Account_Type_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `Act_Id` int(250) NOT NULL,
  `Act_No` int(150) DEFAULT NULL,
  `Act_Name` varchar(80) NOT NULL,
  `Act_Code` varchar(50) NOT NULL,
  `Act_Category` varchar(40) DEFAULT NULL,
  `Act_Emp_Ratio` decimal(50,2) DEFAULT NULL,
  `Act_Output_Ratio` decimal(50,2) DEFAULT NULL,
  `Act_Status` bit(1) NOT NULL,
  `Act_Cat_Id` int(250) DEFAULT NULL,
  `Dept_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `activity_category`
--

CREATE TABLE `activity_category` (
  `Act_Cat_Id` int(250) NOT NULL,
  `Act_Cat_Name` varchar(50) NOT NULL,
  `Act_Cat_Status` bit(1) NOT NULL,
  `Dept_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `activity_standard`
--

CREATE TABLE `activity_standard` (
  `Act_Standard_Id` int(250) NOT NULL,
  `Act_Manpower_Bgt` int(100) NOT NULL,
  `Act_Standard_Emp_Ratio` decimal(50,2) NOT NULL,
  `Act_Standard_Output_Ratio` decimal(50,2) DEFAULT NULL,
  `Act_Id` int(250) NOT NULL,
  `Prj_Id` int(250) NOT NULL,
  `Act_Standard_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `additional`
--

CREATE TABLE `additional` (
  `ADD_ID` int(250) NOT NULL,
  `ADD_NAME` varchar(50) NOT NULL,
  `ADD_AMT` decimal(30,3) NOT NULL,
  `PAYSLIP_ID` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `allowance`
--

CREATE TABLE `allowance` (
  `ALW_ID` int(250) NOT NULL,
  `ALW_NAME` varchar(50) DEFAULT NULL,
  `ALW_AMT` decimal(30,3) DEFAULT NULL,
  `PAYSLIP_ID` int(250) DEFAULT NULL,
  `EMP_ID` int(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `asgn_emp_to_prj`
--

CREATE TABLE `asgn_emp_to_prj` (
  `Asgd_Emp_to_Prj` int(250) NOT NULL,
  `Prj_Id` int(250) NOT NULL,
  `User_Id` int(250) NOT NULL,
  `Emp_Id` int(250) NOT NULL,
  `Asgd_Emp_to_Prj_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `asgn_mat_to_act`
--

CREATE TABLE `asgn_mat_to_act` (
  `Asgd_Mat_to_Act_Id` int(250) NOT NULL,
  `Asgd_Mat_to_Act_Qty` decimal(50,3) NOT NULL,
  `Asgn_Mat_to_Act_Status` bit(1) NOT NULL,
  `Asgd_Act_Id` int(250) NOT NULL,
  `Asgd_Mat_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `asgn_mp`
--

CREATE TABLE `asgn_mp` (
  `Asgn_MP_Id` int(250) NOT NULL,
  `Asgn_MP_Performance` decimal(30,2) DEFAULT NULL,
  `DE_Id` int(250) NOT NULL,
  `Asgn_MP_Status` bit(1) NOT NULL,
  `MP_Id` int(250) NOT NULL,
  `Asgn_MP_Qty` int(11) NOT NULL,
  `Asgn_MP_Total` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `asgn_subcon`
--

CREATE TABLE `asgn_subcon` (
  `Asgn_SB_Id` int(250) NOT NULL,
  `Asgn_SB_Qty` int(11) NOT NULL,
  `Asgn_SB_Performance` decimal(30,2) DEFAULT NULL,
  `DE_Id` int(250) NOT NULL,
  `SB_Id` int(250) NOT NULL,
  `Asgn_SB_Status` bit(1) NOT NULL,
  `Asgn_SB_Total` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `asgn_worker`
--

CREATE TABLE `asgn_worker` (
  `Asgd_Worker_Id` int(250) NOT NULL,
  `Asgd_Worker_Performace` decimal(30,2) DEFAULT NULL,
  `Emp_Id` int(250) NOT NULL,
  `DE_Id` int(250) NOT NULL,
  `Asgd_Worker_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `assigned_activity`
--

CREATE TABLE `assigned_activity` (
  `Asgd_Act_Id` int(250) NOT NULL,
  `Flat_Id` int(250) NOT NULL,
  `Act_Id` int(250) NOT NULL,
  `Act_Cat_Id` int(250) NOT NULL,
  `Asgd_Pct_Done` decimal(10,2) NOT NULL,
  `Asgd_Act_Date_Completed` date NOT NULL,
  `Asgd_Act_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `assigned_material`
--

CREATE TABLE `assigned_material` (
  `Asgd_Mat_Id` int(250) NOT NULL,
  `Act_Id` int(250) NOT NULL,
  `Mat_Id` int(250) NOT NULL,
  `Asgd_Mat_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `Bank_Id` int(250) NOT NULL,
  `Bank_Code` varchar(40) NOT NULL,
  `Bank_Name` varchar(100) NOT NULL,
  `Bank_Balance` decimal(50,2) NOT NULL,
  `Bank_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `building`
--

CREATE TABLE `building` (
  `Blg_Id` int(250) NOT NULL,
  `Blg_Code` varchar(50) NOT NULL,
  `Blg_Name` varchar(50) DEFAULT NULL,
  `Blg_Status` bit(1) NOT NULL,
  `Plx_Id` int(250) DEFAULT NULL,
  `Prj_Id` int(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `chq_code`
--

CREATE TABLE `chq_code` (
  `Chq_Code_Id` int(250) NOT NULL,
  `Chq_Code` varchar(40) NOT NULL,
  `Chq_Code_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `Client_Id` int(250) NOT NULL,
  `Client_Name` varchar(100) NOT NULL,
  `Client_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`Client_Id`, `Client_Name`, `Client_Status`) VALUES
(1, 'Client name', b'0'),
(2, 'M/s. Jumeirah Golf Estates', b'1'),
(3, 'Mr. Mohammad Saleh Ahmed Al Mullah ', b'1'),
(4, 'Mr. Mohamed Aqil Ali Al Zarooni', b'1'),
(5, 'M/s. MAA Real Estate', b'1'),
(6, 'Mr. Mohamed Hassan ', b'1'),
(7, 'Mr. H.H Sheikh Sultan Bin Saqr Al Qasmi', b'1'),
(8, 'test', b'1'),
(9, 'jkjk', b'1'),
(10, 'jkjk', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `Comp_Id` int(250) NOT NULL,
  `Comp_Name` varchar(70) NOT NULL,
  `Comp_Type` varchar(50) NOT NULL,
  `Comp_Scope_Auth` varchar(70) NOT NULL,
  `Company_Website` varchar(80) NOT NULL,
  `Comp_Contact_Person` varchar(50) NOT NULL,
  `Comp_Contact_Position` varchar(50) NOT NULL,
  `Comp_Contact_Mobile` int(20) NOT NULL,
  `Comp_Contact_Landline` int(20) NOT NULL,
  `Comp_Contact_Email` varchar(50) NOT NULL,
  `Comp_Manager_Name` varchar(50) NOT NULL,
  `Comp_Manager_Mobile` int(20) NOT NULL,
  `Comp_Manager_Landline` int(20) NOT NULL,
  `Comp_Manager_Email` varchar(50) NOT NULL,
  `Comp_TRN` varchar(30) DEFAULT NULL,
  `Comp_Emirate_TRL` varchar(50) NOT NULL,
  `Comp_TRN_Issue_Date` date DEFAULT NULL,
  `Comp_Reg_End_Date` date DEFAULT NULL,
  `Comp_Approved_Date` date NOT NULL,
  `Comp_Account_Name` int(70) DEFAULT NULL,
  `Comp_Account_Number` int(50) DEFAULT NULL,
  `Comp_Stamp` varchar(10) NOT NULL,
  `Comp_Sig_Name1` varchar(60) NOT NULL,
  `s1_1` varchar(10) NOT NULL,
  `s1_2` varchar(10) NOT NULL,
  `s1_3` varchar(10) NOT NULL,
  `Comp_Sig_Name2` varchar(60) NOT NULL,
  `s2_1` varchar(10) NOT NULL,
  `s2_2` varchar(10) NOT NULL,
  `s2_3` varchar(10) NOT NULL,
  `Comp_Sig_Name3` varchar(60) NOT NULL,
  `s3_1` varchar(10) NOT NULL,
  `s3_2` varchar(10) NOT NULL,
  `s3_3` varchar(10) NOT NULL,
  `Comp_Reg_Date` datetime DEFAULT current_timestamp(),
  `User_Id` int(250) NOT NULL,
  `Comp_Approval` varchar(2) NOT NULL,
  `Comp_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `comp_department`
--

CREATE TABLE `comp_department` (
  `Comp_Dept_Id` int(250) NOT NULL,
  `Comp_Id` int(250) NOT NULL,
  `Dept_Id` int(250) NOT NULL,
  `Comp_Dept_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `consultant`
--

CREATE TABLE `consultant` (
  `Consultant_Id` int(250) NOT NULL,
  `Consultant_Name` varchar(100) NOT NULL,
  `Consultant_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `consultant`
--

INSERT INTO `consultant` (`Consultant_Id`, `Consultant_Name`, `Consultant_Status`) VALUES
(1, 'consultant 1 edited', b'0'),
(2, 'Rice Perry  Ellis', b'1'),
(3, 'Al Gurg  Consultants', b'1'),
(4, 'BNHS  Engineering  Consultants', b'1'),
(5, 'AE Prime  Engineering  Consultant', b'1'),
(6, 'Inspiration  Engineering  Consultants', b'1'),
(7, 'jfie9', b'1'),
(8, 'jfie9', b'1'),
(9, 'erere', b'1'),
(10, 'cons1', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `daily_entry`
--

CREATE TABLE `daily_entry` (
  `DE_Id` int(250) NOT NULL,
  `DE_Date_Entry` date NOT NULL,
  `DE_Pct_Done` decimal(30,3) NOT NULL,
  `DE_Date_Insert` timestamp NOT NULL DEFAULT current_timestamp(),
  `DE_Status` bit(1) NOT NULL,
  `User_Id` int(250) NOT NULL,
  `Asgd_Act_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `daily_entry2`
--

CREATE TABLE `daily_entry2` (
  `DE_Id2` int(250) NOT NULL,
  `Area_Id` int(250) NOT NULL,
  `DE_Date2` date NOT NULL,
  `DE_Date_Inserted` date NOT NULL,
  `DE_Emp_No` decimal(65,2) NOT NULL,
  `DE_MP_No` decimal(65,2) NOT NULL,
  `DE_SB_No` decimal(65,2) NOT NULL,
  `DE_Output_No` decimal(65,2) NOT NULL,
  `DE_Day_Type` varchar(50) NOT NULL,
  `DE_Status2` bit(1) NOT NULL,
  `User_Id` int(250) NOT NULL,
  `Act_Id` int(250) NOT NULL,
  `Prj_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `deduction`
--

CREATE TABLE `deduction` (
  `DEDUC_ID` int(250) NOT NULL,
  `DEDUC_NAME` varchar(50) DEFAULT NULL,
  `DEDUC_AMT` decimal(30,3) DEFAULT NULL,
  `PAYSLIP_ID` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `Dept_Id` int(250) NOT NULL,
  `Dept_Name` varchar(50) NOT NULL,
  `Dept_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `email`
--

CREATE TABLE `email` (
  `Email_Id` int(250) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `User_Id` int(250) DEFAULT NULL,
  `Email_Status` bit(1) NOT NULL,
  `Email_Grp_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `email_group`
--

CREATE TABLE `email_group` (
  `Email_Grp_Id` int(250) NOT NULL,
  `Email_Grp_Name` varchar(50) NOT NULL,
  `Email_Grp_Desc` varchar(60) DEFAULT NULL,
  `Email_Grp_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `EMP_ID` int(250) NOT NULL,
  `EMP_NO` varchar(50) DEFAULT NULL,
  `EMP_FNAME` varchar(50) NOT NULL,
  `EMP_LNAME` varchar(50) DEFAULT NULL,
  `EMP_MNAME` varchar(50) DEFAULT NULL,
  `EMP_SNAME` varchar(50) DEFAULT NULL,
  `EMP_PAYMODE` varchar(20) DEFAULT NULL,
  `EMP_LOCATION` varchar(80) DEFAULT NULL,
  `EMP_DESIGNATION` varchar(80) DEFAULT NULL,
  `EMP_DOJ` date DEFAULT NULL,
  `EMP_BANK` varchar(80) DEFAULT NULL,
  `EMP_ACCNO` varchar(80) DEFAULT NULL,
  `EMP_IBANNO` varchar(80) DEFAULT NULL,
  `EMP_STATUS` bit(1) DEFAULT NULL,
  `USER_ID` int(250) DEFAULT NULL,
  `EMP_BASIC_SAL` decimal(30,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `estimate`
--

CREATE TABLE `estimate` (
  `Estimate_Id` int(250) NOT NULL,
  `Prj_Est_Id` int(250) NOT NULL,
  `Prj_Sys_Id` int(250) NOT NULL,
  `Est_No_Appartment` decimal(60,2) NOT NULL,
  `Est_No_Bathroom` decimal(60,2) NOT NULL,
  `Est_Connected_Load` decimal(60,2) NOT NULL,
  `Est_Total_Tonnage` decimal(60,2) NOT NULL,
  `Est_Ave_BUA` decimal(60,2) NOT NULL,
  `Est_Total_BUA` decimal(60,2) NOT NULL,
  `HVAC_sp` decimal(60,2) DEFAULT NULL,
  `Electric_sp` decimal(60,2) DEFAULT NULL,
  `Plumbing_sp` decimal(60,2) DEFAULT NULL,
  `FF_sp` decimal(60,2) DEFAULT NULL,
  `FA_sp` decimal(60,2) DEFAULT NULL,
  `LPG_sp` decimal(60,2) DEFAULT NULL,
  `Est_Total_Price` decimal(60,2) NOT NULL,
  `Estimate_Status_Id` int(250) NOT NULL,
  `Est_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `estimate`
--

INSERT INTO `estimate` (`Estimate_Id`, `Prj_Est_Id`, `Prj_Sys_Id`, `Est_No_Appartment`, `Est_No_Bathroom`, `Est_Connected_Load`, `Est_Total_Tonnage`, `Est_Ave_BUA`, `Est_Total_BUA`, `HVAC_sp`, `Electric_sp`, `Plumbing_sp`, `FF_sp`, `FA_sp`, `LPG_sp`, `Est_Total_Price`, `Estimate_Status_Id`, `Est_Status`) VALUES
(1, 3, 4, '1.00', '2.00', '3.00', '4.00', '11.00', '12.00', '5.00', '6.00', '7.00', '8.00', '9.00', '10.00', '13.00', 4, b'1'),
(4, 5, 3, '50.00', '500.00', '1000.00', '5.00', '1000.00', '5008.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '65454.00', 6, b'1'),
(5, 6, 2, '50.00', '50.00', '1000.00', '5000.00', '2500.00', '60000.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '500001.00', 6, b'1'),
(6, 7, 3, '50.00', '50.00', '1000.00', '5000.00', '900.00', '5008.00', NULL, NULL, NULL, NULL, NULL, NULL, '500003.00', 1, b'0'),
(8, 12, 2, '9.00', '10.00', '11.00', '8000.00', '56.60', '5008.08', '10000.00', '10000.00', '20000.00', '10000.00', '10.00', '10000.00', '100000.00', 6, b'1'),
(9, 13, 4, '5.56', '5.00', '5.00', '6.00', '900.72', '5008.00', '1.00', '2.00', '3.00', '4.00', '5.00', '8.00', '23.00', 1, b'1'),
(10, 14, 4, '3.08', '50.00', '1.00', '5000.00', '0.97', '3.00', '10000.00', '1.00', '1.00', '1.00', '1.00', '1.00', '10005.00', 1, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `estimate_status`
--

CREATE TABLE `estimate_status` (
  `Estimate_Status_Id` int(250) NOT NULL,
  `Est_Status` varchar(100) NOT NULL,
  `Est_Status_Status` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `estimate_status`
--

INSERT INTO `estimate_status` (`Estimate_Status_Id`, `Est_Status`, `Est_Status_Status`) VALUES
(1, 'Draft', b'1'),
(2, 'Shortlisted', b'1'),
(3, 'Negotiations', b'1'),
(4, 'Quoted', b'1'),
(5, 'Revised', b'1'),
(6, 'Won', b'1'),
(7, 'Lost', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `expected_expense`
--

CREATE TABLE `expected_expense` (
  `Expense_Id` int(250) NOT NULL,
  `Exp_Desc` varchar(250) NOT NULL,
  `Exp_Amount` decimal(60,2) NOT NULL,
  `Exp_Date` date NOT NULL,
  `Transaction_Category_Id` int(250) NOT NULL,
  `Transaction_Id` int(250) DEFAULT NULL,
  `Plan_Id` int(250) DEFAULT NULL,
  `Prj_Id` int(250) DEFAULT NULL,
  `Exp_Paid_Status` bit(1) DEFAULT NULL,
  `Exp_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `expected_income`
--

CREATE TABLE `expected_income` (
  `Income_Id` int(250) NOT NULL,
  `Income_Desc` varchar(100) NOT NULL,
  `Income_Amount` decimal(60,2) NOT NULL,
  `Income_Date` date NOT NULL,
  `Transaction_Category_Id` int(250) NOT NULL,
  `Transaction_Id` int(250) NOT NULL,
  `Prj_Id` int(250) NOT NULL,
  `Income_Receive_Status` bit(1) NOT NULL,
  `Income_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `expense_division`
--

CREATE TABLE `expense_division` (
  `Exp_Div_Id` int(250) NOT NULL,
  `ED_Desc` varchar(200) NOT NULL,
  `ED_Amount` decimal(60,2) NOT NULL,
  `Expense_Id` int(250) NOT NULL,
  `ED_Paid_Status` bit(1) NOT NULL,
  `Transaction_Id` int(250) DEFAULT NULL,
  `ED_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `File_Id` int(250) NOT NULL,
  `File_Desc` varchar(50) NOT NULL,
  `File_Type` varchar(50) NOT NULL,
  `Emp_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `flat`
--

CREATE TABLE `flat` (
  `Flat_Id` int(250) NOT NULL,
  `Flat_Code` varchar(50) NOT NULL,
  `Flat_Name` varchar(50) NOT NULL,
  `Flat_Status` bit(1) NOT NULL,
  `Lvl_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `flat_asgn_to_type`
--

CREATE TABLE `flat_asgn_to_type` (
  `Flat_Assigned_Id` int(250) NOT NULL,
  `Flat_Asgd_Status` bit(1) NOT NULL,
  `Flat_Id` int(250) NOT NULL,
  `Flat_Type_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `flat_type`
--

CREATE TABLE `flat_type` (
  `Flat_Type_Id` int(250) NOT NULL,
  `Flat_Type_Code` varchar(50) NOT NULL,
  `Flat_Type_Name` varchar(60) NOT NULL,
  `Flat_Type_Status` bit(1) NOT NULL,
  `Prj_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `flat_type_asgn_act`
--

CREATE TABLE `flat_type_asgn_act` (
  `Flt_Asgn_Act_Id` int(250) NOT NULL,
  `Flat_Bgt_Manpower` decimal(50,2) NOT NULL,
  `Flt_Asgn_Act_Status` bit(1) NOT NULL,
  `Act_Id` int(250) NOT NULL,
  `Flat_Type_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `full_allowance`
--

CREATE TABLE `full_allowance` (
  `FULL_ALW_ID` int(250) NOT NULL,
  `FULL_ALW_NAME` varchar(50) NOT NULL,
  `FULL_ALW_AMT` decimal(30,3) NOT NULL,
  `PAYSLIP_ID` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `increment_mgs`
--

CREATE TABLE `increment_mgs` (
  `Inc_Msg_Id` int(250) NOT NULL,
  `Old_Designation` varchar(70) NOT NULL,
  `Old_Salary` int(50) NOT NULL,
  `Increment` int(50) NOT NULL,
  `New_Designation` varchar(70) NOT NULL,
  `New_Salary` int(50) NOT NULL,
  `Emp_Status` varchar(70) DEFAULT NULL,
  `Inc_Msg_Status` bit(1) NOT NULL,
  `Emp_Id` int(250) NOT NULL,
  `Catergory` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `Lvl_Id` int(250) NOT NULL,
  `Lvl_No` decimal(50,0) DEFAULT NULL,
  `Lvl_Code` varchar(40) NOT NULL,
  `Lvl_Name` varchar(60) DEFAULT NULL,
  `Lvl_Status` bit(1) NOT NULL,
  `Blg_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `main_contractor`
--

CREATE TABLE `main_contractor` (
  `Main_Contractor_Id` int(250) NOT NULL,
  `Main_Contractor_Name` varchar(100) NOT NULL,
  `Main_Contractor_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `main_contractor`
--

INSERT INTO `main_contractor` (`Main_Contractor_Id`, `Main_Contractor_Name`, `Main_Contractor_Status`) VALUES
(4, 'M/s. GINCO General  Contracting LLC ', b'1'),
(5, 'Bin Shafar  Contracting', b'1'),
(6, 'Eagle Building  Contracting LLC', b'1'),
(7, 'Al Habbai  Contracting LLC', b'1'),
(8, 'MBCC', b'1'),
(9, '7525', b'1'),
(10, '7525', b'1'),
(11, '7525', b'1'),
(12, 'mcmc1', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `manpower`
--

CREATE TABLE `manpower` (
  `MP_Id` int(250) NOT NULL,
  `MP_Name` varchar(70) NOT NULL,
  `MP_Status` bit(1) NOT NULL,
  `Comp_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `manpower_post`
--

CREATE TABLE `manpower_post` (
  `MP_Post_Id` int(250) NOT NULL,
  `Dept_Id` int(250) NOT NULL,
  `Post_Id` int(250) NOT NULL,
  `MP_Post_Desc` varchar(70) NOT NULL,
  `MP_Post_Qty` int(11) DEFAULT NULL,
  `MP_Post_Unit` varchar(30) NOT NULL,
  `MP_Post_Rate` int(200) NOT NULL,
  `MP_Post_Status` bit(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `Mat_Id` int(250) NOT NULL,
  `Mat_Code` varchar(50) NOT NULL,
  `Mat_Desc` varchar(250) NOT NULL,
  `Mat_Qty` decimal(65,0) NOT NULL,
  `Mat_Unit` varchar(20) NOT NULL,
  `Mat_Status` bit(1) NOT NULL,
  `Dept_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `material_post`
--

CREATE TABLE `material_post` (
  `Mat_Post_Id` int(250) NOT NULL,
  `Mat_Id` varchar(250) DEFAULT NULL,
  `Mat_Post_Ref_Code` varchar(60) DEFAULT NULL,
  `Mat_Post_Unit` varchar(50) DEFAULT NULL,
  `Mat_Post_Capacity` varchar(70) DEFAULT NULL,
  `Mat_Post_Esp` decimal(50,2) DEFAULT NULL,
  `Mat_Post_Location` varchar(250) DEFAULT NULL,
  `Mat_Post_Qty` int(250) NOT NULL,
  `Mat_Post_Brand` varchar(70) DEFAULT NULL,
  `Mat_Post_Status` bit(2) NOT NULL,
  `Post_Id` int(250) NOT NULL,
  `MP_Grp_Id` int(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `material_post_group`
--

CREATE TABLE `material_post_group` (
  `MP_Grp_Id` int(250) NOT NULL,
  `MP_Grp_Name` varchar(100) NOT NULL,
  `MP_Grp_Location` varchar(250) DEFAULT NULL,
  `MP_Grp_Status` bit(1) NOT NULL,
  `Post_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mat_qty`
--

CREATE TABLE `mat_qty` (
  `Mat_Qty_Id` int(250) NOT NULL,
  `Mat_Q_Qty` decimal(65,0) NOT NULL,
  `Prj_Id` int(250) NOT NULL,
  `Mat_Id` int(250) NOT NULL,
  `Mat_Qty_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `Notif_Id` int(250) NOT NULL,
  `Not_Type` varchar(50) NOT NULL,
  `Not_Status` bit(2) NOT NULL,
  `User_Id` int(250) NOT NULL,
  `Not_Date` datetime NOT NULL DEFAULT current_timestamp(),
  `Comp_Id` int(250) DEFAULT NULL,
  `Post_Id` int(250) DEFAULT NULL,
  `Quote_Id` int(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment_plan`
--

CREATE TABLE `payment_plan` (
  `Plan_Id` int(250) NOT NULL,
  `Plan_Desc` varchar(200) NOT NULL,
  `Transaction_Category_Id` int(250) NOT NULL,
  `Frequency` varchar(70) NOT NULL,
  `Plan_Amount` decimal(60,2) NOT NULL,
  `Y_Date` date DEFAULT NULL,
  `Q1_Date` date DEFAULT NULL,
  `Q2_Date` date DEFAULT NULL,
  `Q3_Date` date DEFAULT NULL,
  `Q4_Date` date DEFAULT NULL,
  `Plan_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payslip`
--

CREATE TABLE `payslip` (
  `PAYSLIP_ID` int(250) NOT NULL,
  `P_DATE` date NOT NULL,
  `P_FULL_BASIC` decimal(30,3) NOT NULL,
  `P_BASIC_SALARY` decimal(30,3) NOT NULL,
  `P_NORM_OTHRS` decimal(10,2) DEFAULT NULL,
  `P_NORM_OTAMT` decimal(30,3) DEFAULT NULL,
  `P_HOL_OTHRS` decimal(10,2) DEFAULT NULL,
  `P_HOL_OTAMT` decimal(30,3) DEFAULT NULL,
  `P_SP_HRS` decimal(10,3) DEFAULT NULL,
  `P_SP_AMT` decimal(30,3) DEFAULT NULL,
  `P_BNS_HR` decimal(30,4) DEFAULT NULL,
  `P_BNS_AMT` decimal(30,4) DEFAULT NULL,
  `P_ABDAYS` decimal(10,2) DEFAULT NULL,
  `P_LDAYS` decimal(10,2) DEFAULT NULL,
  `P_STATUS` bit(1) NOT NULL,
  `EMP_ID` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `plex`
--

CREATE TABLE `plex` (
  `Plx_Id` int(250) NOT NULL,
  `Plx_Code` varchar(40) NOT NULL,
  `Plx_Name` varchar(50) NOT NULL,
  `Plx_Status` bit(1) NOT NULL,
  `Villa_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `Post_Id` int(250) NOT NULL,
  `Post_Name` varchar(70) NOT NULL,
  `Post_Desc` varchar(250) NOT NULL,
  `Post_Type` varchar(50) NOT NULL,
  `Post_Date` date NOT NULL,
  `Post_Status` varchar(2) NOT NULL,
  `Prj_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `Product_Id` int(250) NOT NULL,
  `Prod_Desc` varchar(80) NOT NULL,
  `Prod_Brand` varchar(80) DEFAULT NULL,
  `Prod_Country` varchar(50) DEFAULT NULL,
  `Prod_Status` bit(1) NOT NULL,
  `Dept_Id` int(250) NOT NULL,
  `Comp_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `Prj_Id` int(250) NOT NULL,
  `Prj_Code` varchar(40) NOT NULL,
  `Prj_Name` varchar(60) NOT NULL,
  `Prj_Category` varchar(40) NOT NULL,
  `Prj_Type` varchar(40) NOT NULL,
  `Prj_Start_Date` date NOT NULL,
  `Prj_End_Date` date NOT NULL,
  `Prj_Emirate_Location` varchar(50) NOT NULL,
  `Prj_Location_Desc` varchar(50) NOT NULL,
  `Prj_Client_Name` varchar(50) NOT NULL,
  `Prj_Main_Contractor` varchar(50) NOT NULL,
  `Prj_Consultant` varchar(50) NOT NULL,
  `Prj_State` varchar(50) DEFAULT NULL,
  `Prj_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `project_estimation`
--

CREATE TABLE `project_estimation` (
  `Prj_Est_Id` int(250) NOT NULL,
  `PE_Code` varchar(50) DEFAULT NULL,
  `PE_Name` varchar(150) NOT NULL,
  `PE_Category` varchar(50) DEFAULT NULL,
  `PE_Type` varchar(50) NOT NULL,
  `PE_Date` date NOT NULL,
  `PE_Emirate_Location` varchar(50) NOT NULL,
  `Client_Id` int(250) DEFAULT NULL,
  `Main_Contractor_Id` int(250) DEFAULT NULL,
  `Consultant_Id` int(250) DEFAULT NULL,
  `PE_Status` bit(1) NOT NULL,
  `PE_Timestamp` int(11) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `project_estimation`
--

INSERT INTO `project_estimation` (`Prj_Est_Id`, `PE_Code`, `PE_Name`, `PE_Category`, `PE_Type`, `PE_Date`, `PE_Emirate_Location`, `Client_Id`, `Main_Contractor_Id`, `Consultant_Id`, `PE_Status`, `PE_Timestamp`) VALUES
(3, 'P-1', 'G + 3P + 16Typ + 17th Floor + Roof Building Al Nahda First Dubai-UAE', 'Villa', 'Residential', '2023-12-26', 'Dubai', 2, 4, 2, b'1', 2147483647),
(4, '20231226070132', '', '', '', '0000-00-00', '', NULL, NULL, NULL, b'0', 2147483647),
(5, 'P-2', 'G+7+R Labour Camp Building Jabal Ali Dubai-UAE', 'Building', 'Labour Camp', '2023-11-07', 'Fujairah', 3, 5, 5, b'1', 2147483647),
(6, 'P-3', '2B + G + 8 Typical + 9 th Floor (Penthouse + Gym) Hotel Apartment Palm Jumeirah, Dubai-UAE', 'Building', 'Hotel', '2023-12-12', 'Fujairah', 7, 4, 4, b'1', 2147483647),
(7, 'P-4', 'Nadd Al Hamar Mosque, Dubai-UAE ', 'Building', 'Mosque', '2023-12-30', 'Fujairah', 8, 6, 5, b'0', 2147483647),
(9, 'P-5', 'a5 4532', '', 'Hotel', '2024-01-18', 'Sharjah', 3, 5, 6, b'0', 2147483647),
(12, 'P-5', 'Dubai Villa', '', 'Mosque', '2024-01-02', 'Ras al Khaimah', 2, 6, 2, b'1', 2147483647),
(13, 'P-6', 'testing edit', '', 'Hotel', '2024-03-18', 'Dubai', 3, 6, 4, b'1', 2147483647),
(14, 'P-7', 'testing edit', '', 'Hotel', '2024-03-25', 'Dubai', 4, 6, 4, b'0', 2147483647),
(15, '', 'test', '', '', '2024-04-10', 'Ajman', 2, 5, 2, b'1', 2147483647),
(18, 'P-7', 'test', '', 'Villa', '2024-05-01', 'Dubai', 3, 12, 10, b'1', 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `project_system`
--

CREATE TABLE `project_system` (
  `Prj_Sys_Id` int(250) NOT NULL,
  `Prj_Sys_Desc` varchar(150) NOT NULL,
  `Dept_Id` int(250) NOT NULL,
  `Prj_Sys_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `project_system`
--

INSERT INTO `project_system` (`Prj_Sys_Id`, `Prj_Sys_Desc`, `Dept_Id`, `Prj_Sys_Status`) VALUES
(2, 'VRF', 3, b'1'),
(3, 'DX', 3, b'1'),
(4, 'Chilled Air Cooled', 3, b'1'),
(5, 'Chilled Water Cooled', 3, b'1'),
(6, 'District Cooling', 3, b'1');

-- --------------------------------------------------------

--
-- Table structure for table `quote`
--

CREATE TABLE `quote` (
  `Quote_Id` int(250) NOT NULL,
  `Quote_Submitted` datetime NOT NULL DEFAULT current_timestamp(),
  `Quote_Status` bit(1) NOT NULL,
  `Quote_Message` varchar(1000) DEFAULT NULL,
  `Quote_T&C` varchar(5000) DEFAULT NULL,
  `Quote_Discount` decimal(50,0) DEFAULT NULL,
  `Quote_Approval` int(2) NOT NULL,
  `Post_Id` int(250) NOT NULL,
  `Comp_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `quote_detail`
--

CREATE TABLE `quote_detail` (
  `Quote_Detail_Id` int(250) NOT NULL,
  `Quote_Detail_Status` bit(2) NOT NULL,
  `Quote_Detail_Approval` varchar(2) NOT NULL,
  `Quote_Detail_Value` decimal(65,2) NOT NULL,
  `Quote_Remarks` varchar(200) DEFAULT NULL,
  `Quote_Detail_Disc` decimal(20,2) DEFAULT NULL,
  `MP_Post_Id` int(250) DEFAULT NULL,
  `Mat_Post_Id` int(250) DEFAULT NULL,
  `Quote_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `Service_Id` int(250) NOT NULL,
  `Serve_Desc` varchar(80) NOT NULL,
  `Serve_Unit` varchar(70) NOT NULL,
  `Serve_Rate` int(200) NOT NULL,
  `Serve_Status` bit(1) NOT NULL,
  `Dept_Id` int(250) NOT NULL,
  `Comp_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subcontractor`
--

CREATE TABLE `subcontractor` (
  `SB_Id` int(250) NOT NULL,
  `SB_Name` varchar(70) NOT NULL,
  `SB_Status` bit(1) NOT NULL,
  `Comp_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `target`
--

CREATE TABLE `target` (
  `Target_Id` int(250) NOT NULL,
  `Target_Prj_No` int(100) NOT NULL,
  `Target_Date` date NOT NULL,
  `Target_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `target`
--

INSERT INTO `target` (`Target_Id`, `Target_Prj_No`, `Target_Date`, `Target_Status`) VALUES
(1, 15, '2024-01-01', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `time_sheet`
--

CREATE TABLE `time_sheet` (
  `TS_ID` int(250) NOT NULL,
  `TS_DATE` date NOT NULL,
  `TS_DAY_STATUS` varchar(50) NOT NULL,
  `TS_M_IN` time NOT NULL,
  `TS_EVE_OUT` time NOT NULL,
  `TS_RG_HRS` decimal(30,2) NOT NULL,
  `TS_OT_HRS` decimal(30,2) DEFAULT NULL,
  `TS_HOL_OT_HRS` decimal(30,2) NOT NULL,
  `TS_B_HRS` decimal(30,2) DEFAULT NULL,
  `TS_SP_HRS` decimal(30,2) DEFAULT NULL,
  `TS_JB_NAME` varchar(80) DEFAULT NULL,
  `EMP_ID` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `Transaction_Id` int(250) NOT NULL,
  `Transaction_Date` date NOT NULL,
  `Transaction_Type_Id` int(250) DEFAULT NULL,
  `Transaction_Cheque_No` int(15) NOT NULL,
  `Transaction_Amount` decimal(60,2) NOT NULL,
  `Account_Id` int(250) NOT NULL,
  `Transaction_Details` varchar(1000) NOT NULL,
  `Transaction_Status_Id` int(250) NOT NULL,
  `Transaction_Remarks` varchar(1000) DEFAULT NULL,
  `User_Id` int(250) NOT NULL,
  `Prj_Id` int(250) DEFAULT NULL,
  `Transaction_Cancel_Status` bit(1) NOT NULL,
  `Transaction_Category_Id` int(250) DEFAULT NULL,
  `Transaction_Date_Insert` timestamp NOT NULL DEFAULT current_timestamp(),
  `Transaction_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_category`
--

CREATE TABLE `transaction_category` (
  `Transaction_Category_Id` int(250) NOT NULL,
  `Transaction_Cat_Code` varchar(40) NOT NULL,
  `Transaction_Category_Description` varchar(60) NOT NULL,
  `Prj_Id` int(250) DEFAULT NULL,
  `Transaction_Category_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_status`
--

CREATE TABLE `transaction_status` (
  `Transaction_Status_Id` int(250) NOT NULL,
  `Transaction_Status_Description` varchar(100) NOT NULL,
  `Transaction_Status_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_type`
--

CREATE TABLE `transaction_type` (
  `Transaction_Type_Id` int(250) NOT NULL,
  `Transaction_Type_Code` varchar(50) NOT NULL,
  `Transaction_Type_Name` varchar(70) NOT NULL,
  `Transaction_Sign` varchar(30) DEFAULT NULL,
  `Transaction_Type_Status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE `userlog` (
  `Userlog_Id` int(250) NOT NULL,
  `Login_Time` timestamp NOT NULL DEFAULT current_timestamp(),
  `Logout_Time` timestamp NULL DEFAULT NULL,
  `User_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `userlog`
--

INSERT INTO `userlog` (`Userlog_Id`, `Login_Time`, `Logout_Time`, `User_Id`) VALUES
(1, '2024-03-19 09:18:19', '2024-03-19 09:18:24', 1775);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `USER_ID` int(250) NOT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `USER_EMAIL` varchar(50) DEFAULT NULL,
  `USER_PASSWORD` varchar(50) NOT NULL,
  `USERTYPE` varchar(50) NOT NULL,
  `Dept_Id` int(250) DEFAULT NULL,
  `USER_STATUS` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`USER_ID`, `USERNAME`, `USER_EMAIL`, `USER_PASSWORD`, `USERTYPE`, `Dept_Id`, `USER_STATUS`) VALUES
(1, 'jeanette', 'admin@email.com', 'c8b6664921a91e0266faa476dac34f75', 'admin', NULL, b'1'),
(38, '00021', 'null', '5d100a2cf660559b1f36379eab837401', 'user', NULL, b'0'),
(39, '00023', '23@emt', '392d0b740cd20ec5bf3b56f6e399f39f', 'user', NULL, b'1'),
(40, '00024', '24@EMT', 'ad87fc49f9f3974ca3a3fc3a709e5501', 'user', NULL, b'1'),
(41, '00025', '25@emt', 'c11d83f03c8838c0db7227854412fad1', 'user', NULL, b'1'),
(42, '00026', 'null', '60a659ed94994302d595d30bbd624184', 'user', NULL, b'1'),
(43, '00028', 'null', 'ec416ed15593b3e92dd0c5b587069bbd', 'user', NULL, b'1'),
(44, '00029', 'null', 'e3ec75287ec7038112faf69748a79a02', 'user', NULL, b'1'),
(45, '00030', 'null', 'e3ec75287ec7038112faf69748a79a02', 'user', NULL, b'1'),
(46, '00031', 'null', 'f0474bd4a05b699155d0d1bff6ad3117', 'user', NULL, b'1'),
(47, '00032', '32@emt', 'f0474bd4a05b699155d0d1bff6ad3117', 'user', NULL, b'1'),
(48, '00035', 'null', 'f0474bd4a05b699155d0d1bff6ad3117', 'user', NULL, b'1'),
(49, '00036', 'null', 'f0474bd4a05b699155d0d1bff6ad3117', 'user', NULL, b'1'),
(50, '00038', 'null', '9f9c423716c1a2c73a1cb4281928bd72', 'user', NULL, b'1'),
(51, '00039', '39@emt', '4cdd445cb814539dba5c2315d89a3f63', 'user', NULL, b'1'),
(52, '00040', '40@emt', '4cdd445cb814539dba5c2315d89a3f63', 'user', NULL, b'1'),
(53, '00041', '41@emt', 'ccafa28b8874185077f18e4f62334726', 'user', NULL, b'1'),
(54, '00042', 'null', 'f080880dbb587889c4ba30a9651b7fa7', 'user', NULL, b'1'),
(55, '00043', 'null', '4cdd445cb814539dba5c2315d89a3f63', 'user', NULL, b'1'),
(56, '00045', '45@emt', '6cbf38c6d768673efc8e728545a3b02c', 'user', NULL, b'1'),
(57, '00046', 'emt@123', 'b60bda1d46fc1ab1abc38128cafe13fb', 'user', NULL, b'1'),
(58, '00048', 'null', '86b8de80439625719d7e5c019fa4e7d9', 'user', NULL, b'1'),
(59, '00050', '50@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(60, '00052', '52@emt', '2641129d1203141fa655b16d1b86ff63', 'user', NULL, b'1'),
(61, '00053', 'null', '9751aca754aba89d6b0eb6aa0549afb4', 'user', NULL, b'1'),
(62, '00057', 'null', '618f77b5315b8dbc6586e2ed410309cd', 'user', NULL, b'1'),
(63, '00060', '60@emt', 'a4ccd9cfb0090c12bd37f8be20c88775', 'user', NULL, b'1'),
(64, '00062', '62@emt', 'e2407e2b2ebb8ff6fc4196d0c1b66d27', 'user', NULL, b'1'),
(65, '00064', 'null', 'f02172786af753eaf696d6b725855525', 'user', NULL, b'1'),
(66, '00065', '65@EMT', 'aa620a12c3e75b52a63d741d9c484578', 'user', NULL, b'1'),
(67, '00066', 'null', 'a6ec1e328534da335a0d969ceda07fce', 'user', NULL, b'1'),
(68, '00067', 'null', 'f02172786af753eaf696d6b725855525', 'user', NULL, b'1'),
(69, '00068', 'null', '0cebf2dc5d015e6a2b0c9c9915facb54', 'user', NULL, b'1'),
(70, '00070', 'null', 'a4ee8d8dd964b9f58e4b2ac0e1803e8f', 'user', NULL, b'1'),
(71, '00071', 'null', 'b01e09d1459cc391ac935fb33a75bfd4', 'user', NULL, b'1'),
(72, '00072', 'null', 'b01e09d1459cc391ac935fb33a75bfd4', 'user', NULL, b'1'),
(73, '00074', '74@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(74, '00075', '75@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(75, '00076', '76@EMT', 'f96ff709629c4e820a958561a81d1573', 'user', NULL, b'1'),
(76, '00077', 'null', '467e985836d2e1d83a455dd16c60db4b', 'user', NULL, b'1'),
(77, '00078', 'null', '322929511c4c69c7dce1609d33973c8e', 'user', NULL, b'1'),
(78, '00080', 'null', '5dbb48200de4222b38f137a3cd3040e1', 'user', NULL, b'1'),
(79, '00081', 'null', '03b4caf1ee2447b151154d06116ae6f9', 'user', NULL, b'1'),
(80, '00083', '83@emt', '3fcc6843212cfe56959dd387eeab1d2b', 'user', NULL, b'0'),
(81, '00084', '84@emt', 'b371c89a1c1aec70329aaa8d3715fbe1', 'user', NULL, b'1'),
(82, '00085', 'null', 'b371c89a1c1aec70329aaa8d3715fbe1', 'user', NULL, b'1'),
(83, '00087', 'null', 'f12c9601b577d2785fe3f6471ef987c9', 'user', NULL, b'1'),
(84, '00088', 'null', '0aebb1a3197bbe015c7d632d168dd532', 'user', NULL, b'1'),
(85, '00089', 'null', '27c60c588abe47848ee554d9fe17556e', 'user', NULL, b'1'),
(86, '00090', 'null', '83fdd9cf3f1caea725b8dfa70b0cf1f4', 'user', NULL, b'1'),
(87, '00091', 'null', 'd7f6acd9cfd6e904bbf12c8eda8a9d80', 'user', NULL, b'1'),
(88, '00093', 'null', '2f056c940e7aeb6fa138fa0ce8899057', 'user', NULL, b'1'),
(89, '00099', 'null', '6b4b5902e1c68c218e6445add30abc72', 'user', NULL, b'1'),
(90, '00100', '100@emt', '60c134380bf42b7de10710ff6a8389bd', 'user', NULL, b'1'),
(91, '00101', '101@emt', '7ad7c372e715663609d3295c8d3246d0', 'user', NULL, b'1'),
(92, '00102', 'null', '7ad7c372e715663609d3295c8d3246d0', 'user', NULL, b'1'),
(93, '00103', 'null', '7ad7c372e715663609d3295c8d3246d0', 'user', NULL, b'1'),
(94, '00110', 'null', '82ba3ff65d9f7d33e7b87c22c1711e15', 'user', NULL, b'1'),
(95, '00115', 'null', 'f3de68b01fcee99019ef660c9b494c9f', 'user', NULL, b'1'),
(96, '00121', 'null', '87d3c950c394300a18acd67ae1888c54', 'user', NULL, b'1'),
(97, '00122', '122@emt', '87d3c950c394300a18acd67ae1888c54', 'user', NULL, b'1'),
(98, '00125', 'null', '7e6e19b2dda1832ff1530c626c66bbb1', 'user', NULL, b'1'),
(99, '00131', 'null', '0584fc9708e4cb7af8128b19089ba513', 'user', NULL, b'0'),
(100, '00132', '132@emt', '936e47a5827b34890cdf8161439f083e', 'user', NULL, b'1'),
(101, '00135', 'null', '80aa6fe82c364b9db6f8cd2e12411637', 'user', NULL, b'1'),
(102, '00136', 'null', '2ff4cfb569ddc70e070d059555a7f35e', 'user', NULL, b'1'),
(103, '00138', 'null', '5b67a32d1137fa2c87bee5962ae772d7', 'user', NULL, b'0'),
(104, '00139', '139@emt', '20e77df72d65347b7f5ec1f844cbf2fa', 'user', NULL, b'1'),
(105, '00142', '142@emt', '4ad217de694211fdd2108ca1674e4dae', 'user', NULL, b'1'),
(106, '00145', 'null', '98f65bac97828fa8a48a50364e4fd50c', 'user', NULL, b'1'),
(107, '00146', 'null', '165e2360e354382c1edccdcbcddbcce8', 'user', NULL, b'1'),
(108, '00147', '147@emt', '0ab00c48e8a47ba04d4d70f2faebd366', 'user', NULL, b'1'),
(109, '00149', 'null', '98b7d58e30db566f18024e4a6bda271e', 'user', NULL, b'1'),
(110, '00150', 'null', '9e5829a6bf0488326c441827d00255b2', 'user', NULL, b'1'),
(111, '00151', 'null', 'b7df01b99f371c1e04c83592bd80e21a', 'user', NULL, b'1'),
(112, '00152', 'null', 'd89e5aaffcac9ea8031badbdfa75d9fb', 'user', NULL, b'1'),
(113, '00153', '153@emt', 'a7ba8a4f4455b15ec0e58bb00e8ed639', 'user', NULL, b'1'),
(114, '00154', '154@emt', '2998b4b7eb018f483570f103d5038656', 'user', NULL, b'1'),
(115, '00155', 'null', '2998b4b7eb018f483570f103d5038656', 'user', NULL, b'1'),
(116, '00156', '156@emt', '2998b4b7eb018f483570f103d5038656', 'user', NULL, b'1'),
(117, '00157', '157@emt', '2998b4b7eb018f483570f103d5038656', 'user', NULL, b'1'),
(118, '00160', '160@emt', '2998b4b7eb018f483570f103d5038656', 'user', NULL, b'1'),
(119, '00161', '161@emt', 'efd2bedfaf1296bd7c46c5c5805668e1', 'user', NULL, b'1'),
(120, '00163', '163@emt', '05623c9819b494421892868dbc03df67', 'user', NULL, b'1'),
(121, '00164', 'null', '05623c9819b494421892868dbc03df67', 'user', NULL, b'1'),
(122, '00169', '169@emt', '1d545590b13cf783a17f1cb76c66d826', 'user', NULL, b'1'),
(123, '00170', '170@emt', 'c9979fcc9107662db6895eabbdbfb218', 'user', NULL, b'1'),
(124, '00172', 'null', '317d6608146a3c8ac9fae3e678f0b5e2', 'user', NULL, b'1'),
(125, '00173', 'null', '33a67619bcf712f04de5e24b885cd734', 'user', NULL, b'1'),
(126, '00175', '175@emt', 'f9819e4d963f46cbc169f56bea1f2cc7', 'user', NULL, b'1'),
(127, '00180', '180@emt', 'd3607f4e8b34170cde94c98dbb5bbac3', 'user', NULL, b'1'),
(128, '00181', 'null', 'd3607f4e8b34170cde94c98dbb5bbac3', 'user', NULL, b'1'),
(129, '00182', 'null', 'd3607f4e8b34170cde94c98dbb5bbac3', 'user', NULL, b'1'),
(130, '00183', 'null', '9c885884c8cfec91f31e29b585b9ccea', 'user', NULL, b'1'),
(131, '00185', 'null', '98774a8a4155aeacb02fecfeab41f0ad', 'user', NULL, b'1'),
(132, '00187', '187@emt', '59430c3933a1990d1c6dab2987b05ada', 'user', NULL, b'1'),
(133, '00188', '188@emt', '59430c3933a1990d1c6dab2987b05ada', 'user', NULL, b'1'),
(134, '00190', 'null', '99b5eea03e12a629487420773cc54942', 'user', NULL, b'1'),
(135, '00192', 'null', '8184cb641566cca42194a5f43efd0bd0', 'user', NULL, b'1'),
(136, '00193', 'null', '26a107a4ef18ecb41012296132f1ace8', 'user', NULL, b'1'),
(137, '00194', '194@emt', 'bb9f1550d0d1bcd929a03e325de44a57', 'user', NULL, b'1'),
(138, '00197', 'null', 'bf6e3dd2ad4af462476be6eff049ec98', 'user', NULL, b'1'),
(139, '00198', 'null', 'bbe2993f720f1ad44b2598578f9f6961', 'user', NULL, b'1'),
(140, '00200', 'null', '8fea80c99795bd8fb84c4b1530ecaacb', 'user', NULL, b'1'),
(141, '00201', 'null', '7483825c101fdc0d7ea5ad03622570df', 'user', NULL, b'1'),
(142, '00202', '202@emt', '8fea80c99795bd8fb84c4b1530ecaacb', 'user', NULL, b'1'),
(143, '00203', '203@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(144, '00205', '205@emt', '82542239cf099d3a18df195a93ec539f', 'user', NULL, b'1'),
(145, '00207', '207@EMT', '9cd3c4ab94b79b0786966f708883d255', 'user', NULL, b'1'),
(146, '00213', '213@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(147, '00214', '214@emt', '246084d6906d53cb8f2a6231948963c6', 'user', NULL, b'1'),
(148, '00218', 'null', '114844afec4031b2f137f50b4da2b7db', 'user', NULL, b'1'),
(149, '00219', 'null', '87ba650ec7cd93e29655601e03b8ff8f', 'user', NULL, b'1'),
(150, '00220', 'null', '862a6115ff8b204688ef223654e40417', 'user', NULL, b'1'),
(151, '00221', 'null', '862a6115ff8b204688ef223654e40417', 'user', NULL, b'1'),
(152, '00223', '223@emt', '862a6115ff8b204688ef223654e40417', 'user', NULL, b'1'),
(153, '00224', '224@emt', '862a6115ff8b204688ef223654e40417', 'user', NULL, b'1'),
(154, '00225', '225@emt', '82542239cf099d3a18df195a93ec539f', 'user', NULL, b'1'),
(155, '00227', '227@emt', '862a6115ff8b204688ef223654e40417', 'user', NULL, b'1'),
(156, '00229', 'null', '2b472bfb2e9482834b24a30d001f5229', 'user', NULL, b'1'),
(157, '00230', '230@emt', 'aa254034539c7926dc15942412f780a1', 'user', NULL, b'1'),
(158, '00231', 'null', 'a9bd0eeb3ce6858df275497bb2089ec4', 'user', NULL, b'1'),
(159, '00232', 'null', 'aa254034539c7926dc15942412f780a1', 'user', NULL, b'1'),
(160, '00234', 'null', '4f824abe1e4ef45079e1521fde970d7f', 'user', NULL, b'1'),
(161, '00237', '237@emt', '23ee9d40865bc3c1a564a5b04479a4c5', 'user', NULL, b'1'),
(162, '00238', '238@emt', '23ee9d40865bc3c1a564a5b04479a4c5', 'user', NULL, b'1'),
(163, '00241', '241@emt', 'cec61cdd83d03722542730dbd5821401', 'user', NULL, b'1'),
(164, '00244', '244@emt', 'd255eb7d069bf75697b307ee7bd7f5c3', 'user', NULL, b'1'),
(165, '00245', '245@emt', '38b3eff8baf56627478ec76a704e9b52', 'user', NULL, b'1'),
(166, '00246', '246@emt', '088039ac08248bf706c8ccfb977d53ba', 'user', NULL, b'1'),
(167, '00247', 'null', 'b41b054cc39b50a44f128620f3df1363', 'user', NULL, b'1'),
(168, '00249', 'null', '7199ee0dccd1c866824874009b6219e1', 'user', NULL, b'1'),
(169, '00251', 'null', '7199ee0dccd1c866824874009b6219e1', 'user', NULL, b'1'),
(170, '00255', 'null', '50c73ebe3a416f613dc1e3161715cb0b', 'user', NULL, b'1'),
(171, '00256', '256@emt', 'b5a4ec1027e592ae8edcbba3d4584575', 'user', NULL, b'1'),
(172, '00260', '260@emt', '093ce6d17c2f6d1d30a6ade9a665a398', 'user', NULL, b'1'),
(173, '00261', '261@emt', '04b09eb283d03b7282bf5f022a510db6', 'user', NULL, b'1'),
(174, '00263', '263@emt', '04b09eb283d03b7282bf5f022a510db6', 'user', NULL, b'1'),
(175, '00264', '264@emt', '027208f60b7f7571cc002366e2198bc9', 'user', NULL, b'1'),
(176, '00265', '265@emt', '9f3b82b28e74056e473df9ae63dd4e66', 'user', NULL, b'1'),
(177, '00267', '267@emt', '5d64810dcea31b0fc0984f6d8ea44bef', 'user', NULL, b'1'),
(178, '00268', '268@emt', '68b3f5ca4d138b977d7f7720bd180889', 'user', NULL, b'1'),
(179, '00269', 'null', 'abc392b5724b753b798f4d4462658d06', 'user', NULL, b'1'),
(180, '00271', '271@emt', '68b3f5ca4d138b977d7f7720bd180889', 'user', NULL, b'1'),
(181, '00272', 'null', 'cc33c56f369a7a488096d9e41602817f', 'user', NULL, b'1'),
(182, '00275', 'null', '7ae09330eb48c38ed7888cc415481237', 'user', NULL, b'1'),
(183, '00276', '276@emt', 'cb9eee749799ceca7e957c9b4693e7cf', 'user', NULL, b'1'),
(184, '00277', 'null', 'cb9eee749799ceca7e957c9b4693e7cf', 'user', NULL, b'1'),
(185, '00278', 'null', '7b2b8655f0621289b34a836710d47deb', 'user', NULL, b'1'),
(186, '00279', '279@emt', '673d4cfd0645080dd9ab828f9fd1d097', 'user', NULL, b'1'),
(187, '00280', '280@emt', 'b6092727278491956ee6d5c46e8ffe7a', 'user', NULL, b'1'),
(188, '00282', 'null', '4324bd1f796c3b08814de18116cae91c', 'user', NULL, b'1'),
(189, '00283', 'null', '84e7cb0c60077d4d61d16135ca895b11', 'user', NULL, b'1'),
(190, '00284', '284@emt', '964997a9e131b5aa6fee87db0943f6b3', 'user', NULL, b'1'),
(191, '00285', 'null', '4324bd1f796c3b08814de18116cae91c', 'user', NULL, b'1'),
(192, '00289', 'null', 'aa687cb0cb1b9c77091eb3aa9c7b5285', 'user', NULL, b'1'),
(193, '00292', 'null', '7e8a51c56d67e966bfd260ffd37a977a', 'user', NULL, b'1'),
(194, '00294', '294@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(195, '00295', 'null', '9390327b00b48e95699bb35546081489', 'user', NULL, b'1'),
(196, '00298', 'null', '26b1c787cda107b3c6098e428c0533de', 'user', NULL, b'1'),
(197, '00299', '299@emt', '26b1c787cda107b3c6098e428c0533de', 'user', NULL, b'1'),
(198, '00300', 'null', '07f1b7658ae9800fbb3c51d82eff4bf1', 'user', NULL, b'1'),
(199, '00302', 'null', 'f49d4a25ee16228836e247e62feef005', 'user', NULL, b'1'),
(200, '00303', '303@emt', 'a89eb5ed9b48e5b66d8903dc2926ec73', 'user', NULL, b'1'),
(201, '00305', 'null', '197fe47dd004d02eb29828bf948602e1', 'user', NULL, b'1'),
(202, '00309', 'null', 'e2d66135b2fa5e522dd40d9f6225ab06', 'user', NULL, b'1'),
(203, '00313', 'null', 'ac31472462894c99d09ad9f5a5f3bc21', 'user', NULL, b'1'),
(204, '00314', 'null', '4c1b37c38e68ff260b94848fabacc25f', 'user', NULL, b'1'),
(205, '00321', '321@emt', '35b6176b3c79a180781b7cd791d2ba6a', 'user', NULL, b'1'),
(206, '00327', '327@emt', 'd931ec52f808bfdcdcc88a00071328f3', 'user', NULL, b'1'),
(207, '00329', 'null', '10d769377105c2d0689bbd134b6e2f34', 'user', NULL, b'1'),
(208, '00330', '330@emt', '51245522b36c2d417c81f49ea59e46d5', 'user', NULL, b'1'),
(209, '00332', '332@emt', '1b1f6c3fe603a82d7dc757faecb6249c', 'user', NULL, b'1'),
(210, '00333', 'null', 'b4ee6c235c0715c655642760a5c744c2', 'user', NULL, b'1'),
(211, '00337', 'null', '0363580e03e2ad40ba62f0ef270f15c6', 'user', NULL, b'1'),
(212, '00338', 'null', '09028044225aad29bcb77ef88eed4d82', 'user', NULL, b'1'),
(213, '00339', 'null', '09028044225aad29bcb77ef88eed4d82', 'user', NULL, b'1'),
(214, '00343', 'null', '09028044225aad29bcb77ef88eed4d82', 'user', NULL, b'1'),
(215, '00345', '345@emt', 'ed488a91fac95a5b9c2e0fc85ac72a3a', 'user', NULL, b'1'),
(216, '00348', '348@emt', '0a5e5fdd3a41b0c4beb804c6927f9a3f', 'user', NULL, b'1'),
(217, '00349', '349@emt', '0a5e5fdd3a41b0c4beb804c6927f9a3f', 'user', NULL, b'1'),
(218, '00350', 'null', 'c25b8361aa04f5a8a9f141eb66645758', 'user', NULL, b'1'),
(219, '00351', 'null', 'c25b8361aa04f5a8a9f141eb66645758', 'user', NULL, b'1'),
(220, '00352', '352@emt', 'c25b8361aa04f5a8a9f141eb66645758', 'user', NULL, b'1'),
(221, '00353', '353@emt', 'c25b8361aa04f5a8a9f141eb66645758', 'user', NULL, b'1'),
(222, '00357', '357@emt', '52fa3d6e6bd64d81a7bd633748abc57d', 'user', NULL, b'1'),
(223, '00359', '359@emt', '52fa3d6e6bd64d81a7bd633748abc57d', 'user', NULL, b'1'),
(224, '00366', '366@emt', '8af0e21b5d7da94e46ff2bc9a67f43e5', 'user', NULL, b'1'),
(225, '00367', '367@emt', '8af0e21b5d7da94e46ff2bc9a67f43e5', 'user', NULL, b'1'),
(226, '00369', 'null', '8af0e21b5d7da94e46ff2bc9a67f43e5', 'user', NULL, b'1'),
(227, '00370', 'null', '8af0e21b5d7da94e46ff2bc9a67f43e5', 'user', NULL, b'1'),
(228, '00371', 'null', 'ddc105ffbc6ecf1c41f20267b0dac1dc', 'user', NULL, b'1'),
(229, '00372', '372@emt', '8af0e21b5d7da94e46ff2bc9a67f43e5', 'user', NULL, b'1'),
(230, '00375', '375@emt', '6bbf12392c15e96cbaf530b770da25cd', 'user', NULL, b'1'),
(231, '00376', '376@emt', '6bbf12392c15e96cbaf530b770da25cd', 'user', NULL, b'1'),
(232, '00377', '377@emt', '6bbf12392c15e96cbaf530b770da25cd', 'user', NULL, b'1'),
(233, '00378', '378@emt', '6bbf12392c15e96cbaf530b770da25cd', 'user', NULL, b'1'),
(234, '00379', 'null', '6bbf12392c15e96cbaf530b770da25cd', 'user', NULL, b'1'),
(235, '00380', 'null', '6bbf12392c15e96cbaf530b770da25cd', 'user', NULL, b'1'),
(236, '00381', 'null', '64a43b6ca15d128ac6a0679b39bc9c07', 'user', NULL, b'1'),
(237, '00383', 'null', '93adf910f49b87b2e383c01e2adc9ef6', 'user', NULL, b'1'),
(238, '00384', '384@emt', '93adf910f49b87b2e383c01e2adc9ef6', 'user', NULL, b'1'),
(239, '00385', 'null', '061c08d0512ebde97d26b561d7b34d32', 'user', NULL, b'1'),
(240, '00386', 'null', '93adf910f49b87b2e383c01e2adc9ef6', 'user', NULL, b'1'),
(241, '00389', '389@emt', '93adf910f49b87b2e383c01e2adc9ef6', 'user', NULL, b'1'),
(242, '00392', '392@emt', 'e5d4b3e0817dfde786db2959db15e4b8', 'user', NULL, b'1'),
(243, '00394', 'null', 'ba83a72905ebf7e377544d9bdf8810bd', 'user', NULL, b'1'),
(244, '00396', 'null', 'c161b8ca87c45f2160ee552a8db8011f', 'user', NULL, b'1'),
(245, '00399', 'null', '9d56d2b671e2f3c54a12e7bb120cea43', 'user', NULL, b'1'),
(246, '00400', 'null', '2b7264509679bdc8d5e43a0b669ee1b9', 'user', NULL, b'1'),
(247, '00402', '402@EMT', '2b7264509679bdc8d5e43a0b669ee1b9', 'user', NULL, b'1'),
(248, '00403', '403@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(249, '00407', '407@emt', '319118d996abf05c2e367148a2ebe3a7', 'user', NULL, b'1'),
(250, '00409', 'null', 'f051664421e2d60546f0966bff5f307f', 'user', NULL, b'1'),
(251, '00410', 'null', 'f051664421e2d60546f0966bff5f307f', 'user', NULL, b'1'),
(252, '00414', '414@emt', '1ec1adf3079245bbd7908aa761c7dc2b', 'user', NULL, b'1'),
(253, '00417', '417@emt', 'f5423459fa860f99677f467e5123af54', 'user', NULL, b'1'),
(254, '00419', 'null', 'f5423459fa860f99677f467e5123af54', 'user', NULL, b'1'),
(255, '00425', '425@emt', '1ec1adf3079245bbd7908aa761c7dc2b', 'user', NULL, b'1'),
(256, '00427', '427@emt', 'b3592b8c45b9fba3a921c3f50e71b78b', 'user', NULL, b'1'),
(257, '00428', 'null', 'b4d59de35996b4b22d7494e10656d5dd', 'user', NULL, b'1'),
(258, '00429', '429@emt', 'b4d59de35996b4b22d7494e10656d5dd', 'user', NULL, b'1'),
(259, '00430', 'null', 'b7bb51562d9502e1c7abb00cb228921c', 'user', NULL, b'1'),
(260, '00431', 'null', '4e9ca91ef0427a45f3e5d4f4ee476077', 'user', NULL, b'1'),
(261, '00433', '433@emt', '19e1d9e87be519da78887ac1fd151c83', 'user', NULL, b'1'),
(262, '00435', 'null', 'add10d6d13e6526ebf9ce8e0e2e563e9', 'user', NULL, b'1'),
(263, '00436', '436@emt', 'a3435387778dc3e793a6961a09486c8d', 'user', NULL, b'1'),
(264, '00437', '437@emt', '19e1d9e87be519da78887ac1fd151c83', 'user', NULL, b'1'),
(265, '00439', '439@emt', '1359374df90ad7a6f284c9d5d0b778aa', 'user', NULL, b'1'),
(266, '00440', 'null', 'b916b65514648a7fd455be065e3de908', 'user', NULL, b'1'),
(267, '00441', '441@emt', '7f8eb3f213ce3cd5aa6f4c8b93d6844f', 'user', NULL, b'1'),
(268, '00442', 'null', '8c91aad67ac0db41a85bc8f64f8cec7b', 'user', NULL, b'1'),
(269, '00444', '444@emt', 'e8963bb0134e552e492d023ae5da853d', 'user', NULL, b'1'),
(270, '00445', 'null', 'e8963bb0134e552e492d023ae5da853d', 'user', NULL, b'1'),
(271, '00448', '448@emt', 'd295768cf579d2fadaf0f9a5cfb7a3be', 'user', NULL, b'1'),
(272, '00449', '449@EMT', 'd295768cf579d2fadaf0f9a5cfb7a3be', 'user', NULL, b'1'),
(273, '00450', 'null', 'd295768cf579d2fadaf0f9a5cfb7a3be', 'user', NULL, b'1'),
(274, '00451', 'null', 'd295768cf579d2fadaf0f9a5cfb7a3be', 'user', NULL, b'1'),
(275, '00452', '452@emt', 'd295768cf579d2fadaf0f9a5cfb7a3be', 'user', NULL, b'1'),
(276, '00453', 'null', 'd295768cf579d2fadaf0f9a5cfb7a3be', 'user', NULL, b'1'),
(277, '00454', '454@emt', 'd295768cf579d2fadaf0f9a5cfb7a3be', 'user', NULL, b'1'),
(278, '00455', 'null', 'd295768cf579d2fadaf0f9a5cfb7a3be', 'user', NULL, b'1'),
(279, '00457', 'null', 'd295768cf579d2fadaf0f9a5cfb7a3be', 'user', NULL, b'1'),
(280, '00458', 'null', '44766fa8cbfe8618ec8ead86cc053d60', 'user', NULL, b'1'),
(281, '00459', 'null', 'ff08715be1cb672a76214bfe9cfbf0e8', 'user', NULL, b'1'),
(282, '00460', 'null', '6c00632d19f8302d3ed7559a2a25521e', 'user', NULL, b'1'),
(283, '00461', 'null', 'd157411906c7db6df22ef6add78d5cc7', 'user', NULL, b'1'),
(284, '00462', 'null', '85ea2c4e83ba1ee630cbb2f9c6e4eaee', 'user', NULL, b'1'),
(285, '00463', '463@emt', 'd295768cf579d2fadaf0f9a5cfb7a3be', 'user', NULL, b'1'),
(286, '00464', '464@emt', 'd295768cf579d2fadaf0f9a5cfb7a3be', 'user', NULL, b'1'),
(287, '00465', 'null', 'd295768cf579d2fadaf0f9a5cfb7a3be', 'user', NULL, b'1'),
(288, '00466', 'null', 'aa5d2800297a182cd072cb41fa83a8ba', 'user', NULL, b'1'),
(289, '00467', 'null', 'aa5d2800297a182cd072cb41fa83a8ba', 'user', NULL, b'1'),
(290, '00468', 'null', 'aa5d2800297a182cd072cb41fa83a8ba', 'user', NULL, b'1'),
(291, '00471', 'null', 'faa8eca8d84433241885234de724e088', 'user', NULL, b'1'),
(292, '00473', '473@emt', 'aa5d2800297a182cd072cb41fa83a8ba', 'user', NULL, b'1'),
(293, '00474', '474@emt', 'baa2e81760b56ebd2595355bbc4625ef', 'user', NULL, b'1'),
(294, '00475', 'null', 'aa5d2800297a182cd072cb41fa83a8ba', 'user', NULL, b'1'),
(295, '00476', 'null', '69b4fa3be19bdf400df34e41b93636a4', 'user', NULL, b'1'),
(296, '00477', 'null', 'a1c3adedac1486915384bc66b0387521', 'user', NULL, b'1'),
(297, '00479', 'null', '95f37f2c88006d8da15f475f3fe3170b', 'user', NULL, b'1'),
(298, '00480', 'null', '6d3317279d140a0550e32ab763605eba', 'user', NULL, b'1'),
(299, '00481', 'null', 'd19755c3fcf9e6c44c17d2c68dd561f6', 'user', NULL, b'1'),
(300, '00483', 'null', 'c1d03bed30a371e343dbe79cf064a910', 'user', NULL, b'1'),
(301, '00485', 'null', '75daa1ad653bedcff0f4dd95faa4466e', 'user', NULL, b'1'),
(302, '00486', 'null', 'a42b7216da89b751b768b381115b12ec', 'user', NULL, b'1'),
(303, '00489', 'null', '039e072fea62a95e80ae1b26bcf075a0', 'user', NULL, b'1'),
(304, '00490', 'null', 'fc19956b086575a6b8e6453d4546371a', 'user', NULL, b'1'),
(305, '00491', 'null', '0ad1a739d37ce103cb6f88e1351c9910', 'user', NULL, b'1'),
(306, '00492', 'null', '9a43ec4dfd6c270e0b005440743118b6', 'user', NULL, b'1'),
(307, '00493', 'null', 'c9a0885e67f57a8e24ec7e3d8d66251a', 'user', NULL, b'1'),
(308, '00495', 'null', 'cc015eb6480f6e57bd3fb85f6f5b92e1', 'user', NULL, b'1'),
(309, '00496', 'null', 'cc015eb6480f6e57bd3fb85f6f5b92e1', 'user', NULL, b'1'),
(310, '00497', '497@emt', 'cc015eb6480f6e57bd3fb85f6f5b92e1', 'user', NULL, b'1'),
(311, '00498', 'null', 'cc015eb6480f6e57bd3fb85f6f5b92e1', 'user', NULL, b'1'),
(312, '00499', 'null', 'e87a535112080f1925a95651ab0aba9b', 'user', NULL, b'1'),
(313, '00506', 'null', 'cc015eb6480f6e57bd3fb85f6f5b92e1', 'user', NULL, b'1'),
(314, '00507', '507@emt', 'cc015eb6480f6e57bd3fb85f6f5b92e1', 'user', NULL, b'1'),
(315, '00510', '510@EMT', 'b67e4b2e2d6a062e55416750010be098', 'user', NULL, b'1'),
(316, '00511', '511@emt', '25202f8681e246f0ee0a7154c537e6d8', 'user', NULL, b'1'),
(317, '00512', '512@emt', '67046763505c60c4abcda5df9e2c155a', 'user', NULL, b'1'),
(318, '00514', 'null', '67046763505c60c4abcda5df9e2c155a', 'user', NULL, b'1'),
(319, '00515', 'null', 'e8a740052617cb6a48b4e2505bc585e4', 'user', NULL, b'1'),
(320, '00516', '516@emt', '25202f8681e246f0ee0a7154c537e6d8', 'user', NULL, b'1'),
(321, '00517', 'null', '8f844f21b40670778f605a6fb132150c', 'user', NULL, b'1'),
(322, '00518', 'null', '3b7bc0a525d15edb06a560077b55642e', 'user', NULL, b'1'),
(323, '00519', 'null', 'a1e2ade7fcc3d9aafac775369a3f17c0', 'user', NULL, b'1'),
(324, '00520', 'null', 'd7db5a1f32d65c5e13f8a6e762c7d2cd', 'user', NULL, b'1'),
(325, '00521', 'null', 'c9e476ebd4c0f44266f3549cdb41fb1b', 'user', NULL, b'1'),
(326, '00522', 'null', 'dab78334791647a26552de4d836c8e6f', 'user', NULL, b'1'),
(327, '00523', 'null', 'dab78334791647a26552de4d836c8e6f', 'user', NULL, b'1'),
(328, '00524', 'null', 'dab78334791647a26552de4d836c8e6f', 'user', NULL, b'1'),
(329, '00527', '527@emt', '2cab3d1176be7edaff5f739e9513767a', 'user', NULL, b'1'),
(330, '00528', '528@emt', '932cfe73fc1385c7e1dfb5cd5c6ba6d9', 'user', NULL, b'1'),
(331, '00530', 'null', '8f08bdac335ca0e2e55874965e6471f3', 'user', NULL, b'1'),
(332, '00531', 'null', '401dc27e9077dda727a29c9d5a06200f', 'user', NULL, b'1'),
(333, '00532', 'null', '0866897d7c3ffefbf3f6691e61b56efe', 'user', NULL, b'1'),
(334, '00536', 'null', '074aac502aff7ca77b8b75972a5de48a', 'user', NULL, b'1'),
(335, '00537', 'null', '88d59101746460a254c97da58f19875a', 'user', NULL, b'1'),
(336, '00539', '539@emt', '3f2cca655a8df690754cb3ccf4a7b0ba', 'user', NULL, b'1'),
(337, '00541', '541@emt', '3f2cca655a8df690754cb3ccf4a7b0ba', 'user', NULL, b'1'),
(338, '00542', '542@emt', '3f2cca655a8df690754cb3ccf4a7b0ba', 'user', NULL, b'1'),
(339, '00543', '543@emt', '3f2cca655a8df690754cb3ccf4a7b0ba', 'user', NULL, b'1'),
(340, '00544', 'null', '14257a6fdf6821be00d678977ba9085d', 'user', NULL, b'1'),
(341, '00546', 'null', '4c56edb1cdf5eafb136f34224ecf1ad2', 'user', NULL, b'1'),
(342, '00547', 'null', 'c40488efd57eac00b23aafccaf183b00', 'user', NULL, b'1'),
(343, '00548', 'null', '1ebccf4bca1ec7b2ba99db2d319c05a6', 'user', NULL, b'1'),
(344, '00550', 'null', 'c2c1da2c42c5744328df722765690caa', 'user', NULL, b'1'),
(345, '00551', '551@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(346, '00553', '553@emt', '3142645bc4210a6cdc534d766eb4e145', 'user', NULL, b'1'),
(347, '00554', 'null', 'c7b1beaf19152a3942b28c0a71c2feb3', 'user', NULL, b'1'),
(348, '00555', '555@emt', '1c9b5f99f45df0e5b38df9833d662c55', 'user', NULL, b'1'),
(349, '00557', 'null', '841a3a5c7e5ba699fcc52bab2b669fbe', 'user', NULL, b'1'),
(350, '00559', '559@emt', '1dad44bb9bbffc84d1d168c251c230b9', 'user', NULL, b'1'),
(351, '00561', 'null', '77d82dd676cc353260ac72a736c1d838', 'user', NULL, b'1'),
(352, '00562', '562@emt', '133430645f7aed981d8ac84c4e6dba6a', 'user', NULL, b'1'),
(353, '00564', 'null', '133430645f7aed981d8ac84c4e6dba6a', 'user', NULL, b'1'),
(354, '00565', '565@emt', '09fffc11277404441a578bbec1b5aba2', 'user', NULL, b'1'),
(355, '00567', '567@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(356, '00568', 'null', '6875aa44fc56587bdcd8e98bfa3cf5bb', 'user', NULL, b'1'),
(357, '00569', '569@emt', '42d63ab98ccb2fa9a34a132799ef4c4b', 'user', NULL, b'1'),
(358, '00570', 'null', '0698602d9602c08d26b2b51ebd332aa5', 'user', NULL, b'0'),
(359, '00572', '572@emt', 'c3ed739557a032519a5e613ad84fac5e', 'user', NULL, b'1'),
(360, '00574', 'null', '3592649c0d8a4b8b4ddf6333c4fa30e0', 'user', NULL, b'1'),
(361, '00575', 'null', 'd2361c273b1879a96ed4dd6beb4d7124', 'user', NULL, b'1'),
(362, '00576', 'null', '1e9843735635fcf60da1d7f2bb0c43a3', 'user', NULL, b'1'),
(363, '00578', 'null', 'd7f875e0c9accf7bcc31f532f77c274f', 'user', NULL, b'1'),
(364, '00580', 'null', 'c8f0f0214a8d5c17d02c3767d46b3469', 'user', NULL, b'1'),
(365, '00581', 'null', 'c8f0f0214a8d5c17d02c3767d46b3469', 'user', NULL, b'1'),
(366, '00583', 'null', 'f90b0a71d0650a8c3153c13822095636', 'user', NULL, b'1'),
(367, '00584', '584@emt', '2626f37be6d36acfb013d0981a33f525', 'user', NULL, b'1'),
(368, '00585', '585@emt', 'a65e7e37407097de2194e4cc087477b4', 'user', NULL, b'1'),
(369, '00586', '586@emt', '0c9dd606a2b1f2aadd1257a0596d0784', 'user', NULL, b'0'),
(370, '00587', 'null', 'e9e1c3339b50255fc0e01fe4994aaa43', 'user', NULL, b'1'),
(371, '00588', 'null', 'aaaa022a118467dc2a2a527a36600f22', 'user', NULL, b'1'),
(372, '00589', '589@emt', 'b1a2a61e72eeedf92bd556659538404a', 'user', NULL, b'1'),
(373, '00590', 'null', '164e23b916c1c7fc74bb677b50ade0c8', 'user', NULL, b'1'),
(374, '00592', 'null', 'f842db31f21b3921171a68d3884ee770', 'user', NULL, b'1'),
(375, '00593', 'null', '19fe183ba2754206430f6064f94dc6d5', 'user', NULL, b'1'),
(376, '00594', 'null', '41e29b656167193faca638ab51508ba4', 'user', NULL, b'1'),
(377, '00595', 'null', 'b1a2a61e72eeedf92bd556659538404a', 'user', NULL, b'1'),
(378, '00596', 'null', '91476f1f077d21c052e2b89da8ea0b3b', 'user', NULL, b'1'),
(379, '00597', '597@emt', 'e5d4c52163996c78135c0582572f4a49', 'user', NULL, b'1'),
(380, '00598', '598@emt', 'e5d4c52163996c78135c0582572f4a49', 'user', NULL, b'1'),
(381, '00600', 'null', 'c14e2b49253a78b06a10a493d9cc78fb', 'user', NULL, b'1'),
(382, '00601', 'null', '9280a0ac45248173ebc7bf8d876dfd3a', 'user', NULL, b'1'),
(383, '00602', 'null', 'a321b875a145f6810330d2c910e8a4af', 'user', NULL, b'1'),
(384, '00603', 'null', '1ae8a1c38a7f379b618719b202a8a01c', 'user', NULL, b'1'),
(385, '00604', 'null', '973c5281270043b7253b844044302b7e', 'user', NULL, b'1'),
(386, '00605', '605@emt', '713100809187bdfaf16ec315e8559fa8', 'user', NULL, b'1'),
(387, '00607', 'null', 'a4b4796a444aaae0f6c3823ba4fdd71f', 'user', NULL, b'1'),
(388, '00608', 'null', '56f1833bd279060eb1a4331206ca6454', 'user', NULL, b'1'),
(389, '00611', 'null', 'ab75a95f09859b09d6ecabcf6fd895b2', 'user', NULL, b'1'),
(390, '00612', 'null', 'c36081a30d813240b98c8cfd3f601e1d', 'user', NULL, b'1'),
(391, '00613', 'null', 'a75a8b3d995dccfed7ecc56934561b60', 'user', NULL, b'1'),
(392, '00614', '614@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(393, '00615', 'null', 'e0933d02044232b627919782d39d78cd', 'user', NULL, b'1'),
(394, '00616', 'null', 'a75a8b3d995dccfed7ecc56934561b60', 'user', NULL, b'1'),
(395, '00617', 'null', '15b99e312a8ef4c197aa9bc19b364022', 'user', NULL, b'1'),
(396, '00618', 'null', 'aa78c3db4fc4a1a343183d6113ec46ba', 'user', NULL, b'1'),
(397, '00619', 'null', '646d450fbe1c6d9c833e311586cbff00', 'user', NULL, b'1'),
(398, '00620', '620@emt', '646d450fbe1c6d9c833e311586cbff00', 'user', NULL, b'1'),
(399, '00623', 'null', '4e0d999b56cd65dc01404f169ec6d6ab', 'user', NULL, b'1'),
(400, '00624', 'null', '03991fd5f3b1ad5fd97939082bd9b4fe', 'user', NULL, b'1'),
(401, '00625', 'null', 'b1580779672e4f19b39e94e03dd3ca30', 'user', NULL, b'1'),
(402, '00626', 'null', '1f22049d3dd7c25d1eb1603bf17ce689', 'user', NULL, b'1'),
(403, '00628', 'null', 'eebc1ebd2f1eb693c7348e7ca5d3a31c', 'user', NULL, b'1'),
(404, '00629', '629@emt', 'd84b0d1046127299f23064ed99b06321', 'user', NULL, b'1'),
(405, '00630', 'null', '860038d609aaa61dd8e860e9ec19067a', 'user', NULL, b'1'),
(406, '00631', 'null', '7d12b66d3df6af8d429c1a357d8b9e1a', 'user', NULL, b'1'),
(407, '00632', '632@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(408, '00633', 'null', '82574cd0329150ef9e71c57a480ba111', 'user', NULL, b'1'),
(409, '00634', 'null', 'd84b0d1046127299f23064ed99b06321', 'user', NULL, b'1'),
(410, '00635', 'null', 'dfaf6f6c12e6d8926fe7d4d7ea3f68d5', 'user', NULL, b'1'),
(411, '00636', 'null', '140b13c7475e3e2f6d7da253d2ba5dbe', 'user', NULL, b'1'),
(412, '00637', 'null', '9717e2c7a63d8da2116eca42703e02f9', 'user', NULL, b'1'),
(413, '00638', 'null', 'a04383f6c5ceedb1c057f3110bd2c8a8', 'user', NULL, b'1'),
(414, '00640', 'null', '9717e2c7a63d8da2116eca42703e02f9', 'user', NULL, b'1'),
(415, '00641', 'null', '9717e2c7a63d8da2116eca42703e02f9', 'user', NULL, b'1'),
(416, '00643', '643@emt', '9717e2c7a63d8da2116eca42703e02f9', 'user', NULL, b'1'),
(417, '00644', 'null', '608fadbe910c8c0135dc05fbf8340ecd', 'user', NULL, b'1'),
(418, '00645', 'null', '6091ce3f0e402f9b423d0a4b929f1f64', 'user', NULL, b'1'),
(419, '00646', 'null', '674a0041d44033b912b7d68e659f43e4', 'user', NULL, b'1'),
(420, '00647', 'null', '20ec1bbe6eebce138227b3c2d6fc9305', 'user', NULL, b'1'),
(421, '00648', 'null', 'eb8407df64027dcf5f878774f505ad84', 'user', NULL, b'1'),
(422, '00649', 'null', '40627d411fda3311d69d7a7ef13a4370', 'user', NULL, b'1'),
(423, '00651', 'null', '443832a8f2fcae58456a2096302fbb8b', 'user', NULL, b'1'),
(424, '00652', 'null', '5f8f7f1c1a3c46a5286fde91c2f4391e', 'user', NULL, b'1'),
(425, '00653', 'null', 'fd7588ac3a2a0e39d8281ce29df92972', 'user', NULL, b'1'),
(426, '00654', '654@emt', 'fd7588ac3a2a0e39d8281ce29df92972', 'user', NULL, b'1'),
(427, '00656', 'null', 'fd7588ac3a2a0e39d8281ce29df92972', 'user', NULL, b'1'),
(428, '00657', '657@emt', 'fd7588ac3a2a0e39d8281ce29df92972', 'user', NULL, b'1'),
(429, '00658', 'null', 'fd7588ac3a2a0e39d8281ce29df92972', 'user', NULL, b'1'),
(430, '00659', 'null', 'fd7588ac3a2a0e39d8281ce29df92972', 'user', NULL, b'1'),
(431, '00660', '660@emt', '4066039c623ae8fc363503ecdcc11522', 'user', NULL, b'1'),
(432, '00661', '661@emt', '4066039c623ae8fc363503ecdcc11522', 'user', NULL, b'1'),
(433, '00662', 'null', 'ebb2a86bf32831cae5c438103fcb3e5d', 'user', NULL, b'1'),
(434, '00664', 'null', '4066039c623ae8fc363503ecdcc11522', 'user', NULL, b'1'),
(435, '00665', '665@emt', '4066039c623ae8fc363503ecdcc11522', 'user', NULL, b'1'),
(436, '00666', 'null', '4066039c623ae8fc363503ecdcc11522', 'user', NULL, b'1'),
(437, '00667', 'null', '3f0e43a47bfec9a473ef3a1871c85330', 'user', NULL, b'1'),
(438, '00668', 'null', '6a5314d8b0cbd81c992dbfcf4a066d26', 'user', NULL, b'1'),
(439, '00669', 'null', '4066039c623ae8fc363503ecdcc11522', 'user', NULL, b'1'),
(440, '00670', '670@EMT', '4066039c623ae8fc363503ecdcc11522', 'user', NULL, b'0'),
(441, '00671', 'null', '4066039c623ae8fc363503ecdcc11522', 'user', NULL, b'1'),
(442, '00673', '673@emt', '285b56ac85ba5a6bd7811dc15996bc71', 'user', NULL, b'1'),
(443, '00674', 'null', 'f99095a4554873dbafbb555b3b72e83f', 'user', NULL, b'1'),
(444, '00675', '675@emt', 'e4391c44598da5b810697f46a02e3add', 'user', NULL, b'1'),
(445, '00676', 'null', 'e4391c44598da5b810697f46a02e3add', 'user', NULL, b'1'),
(446, '00677', '677@emt', 'e4391c44598da5b810697f46a02e3add', 'user', NULL, b'1'),
(447, '00678', '678@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(448, '00681', '681@emt', '2b84d97abd6d4c4142ec545d551bbfa1', 'user', NULL, b'1'),
(449, '00682', 'null', 'e7c8aad91900433b1c1ea1c8070dfa34', 'user', NULL, b'1'),
(450, '00683', 'null', 'e7c8aad91900433b1c1ea1c8070dfa34', 'user', NULL, b'1'),
(451, '00684', 'null', 'e7c8aad91900433b1c1ea1c8070dfa34', 'user', NULL, b'1'),
(452, '00685', 'null', 'e7c8aad91900433b1c1ea1c8070dfa34', 'user', NULL, b'1'),
(453, '00686', 'null', 'e7c8aad91900433b1c1ea1c8070dfa34', 'user', NULL, b'1'),
(454, '00687', 'null', '6c1322905ead636eaefd1684d65c34f4', 'user', NULL, b'1'),
(455, '00688', 'null', 'c8a0086a4d783232bcb1389e556f0877', 'user', NULL, b'1'),
(456, '00689', 'null', '3fd4afdec6cd9d2809b16b07d4608b90', 'user', NULL, b'1'),
(457, '00690', 'null', '9dffe8b0aced187ba80ee963450aabe0', 'user', NULL, b'1'),
(458, '00691', 'null', 'e65693e0c70ce56de2b75a6d3d8af50c', 'user', NULL, b'1'),
(459, '00692', '692@emt', 'e56984485d709f7dc260aaf510e872bd', 'user', NULL, b'1'),
(460, '00694', 'null', '44c048a25b5af5d0d0d4069ad5266f4e', 'user', NULL, b'1'),
(461, '00695', '695@emt', '687fb13304d6610f3206b42350f997c6', 'user', NULL, b'1'),
(462, '00697', 'null', '28d942c92d781b12ac119b977ccbfcc8', 'user', NULL, b'1'),
(463, '00699', 'null', '1be4d3128481155f6fcd443171c14a94', 'user', NULL, b'1'),
(464, '00700', 'null', '1be4d3128481155f6fcd443171c14a94', 'user', NULL, b'1'),
(465, '00702', 'null', '6f21dacacfe7c09917be7fa2e76906cd', 'user', NULL, b'1'),
(466, '00703', 'null', 'f2763c0d38fc34d064765e5561d2d653', 'user', NULL, b'1'),
(467, '00707', '707@emt', '198eeba56b6dbb15f07391563e983504', 'user', NULL, b'1'),
(468, '00709', 'null', 'dba08e9b841951c4462f82a78c99ab88', 'user', NULL, b'1'),
(469, '00710', 'null', 'd72751dae1835013d16161d3fd90a606', 'user', NULL, b'1'),
(470, '00711', 'null', 'd72751dae1835013d16161d3fd90a606', 'user', NULL, b'1'),
(471, '00712', '712@emt', 'd72751dae1835013d16161d3fd90a606', 'user', NULL, b'1'),
(472, '00713', 'null', '2bf0f92dff5772a1793d04dbbbdde53d', 'user', NULL, b'1'),
(473, '00714', 'null', 'c2e563d562c249b94c6de9f5aa476988', 'user', NULL, b'1'),
(474, '00715', 'null', '615971bc5d19eb66eaa322d85eca1aa9', 'user', NULL, b'1'),
(475, '00716', '716@emt', '6fe540f841171a6533a089f575f6cfd5', 'user', NULL, b'1'),
(476, '00717', 'null', '6fe540f841171a6533a089f575f6cfd5', 'user', NULL, b'1'),
(477, '00718', 'null', '29d977f813a1e0b143dee00e8b80b280', 'user', NULL, b'1'),
(478, '00721', 'null', '8013c5c766baeaf79202080a9e9e89ce', 'user', NULL, b'1'),
(479, '00725', 'null', 'abd00a3f844f71b50534c8581598e0e3', 'user', NULL, b'1'),
(480, '00730', 'null', '774d7d07edabe54581d4ff19434eed28', 'user', NULL, b'1'),
(481, '00732', 'null', '05b91eee134a2e64695c79bba8f71c96', 'user', NULL, b'1'),
(482, '00735', 'null', '5a0dae78baf817130e34dc54393cba85', 'user', NULL, b'1'),
(483, '00736', 'null', 'b3cbfdd223eb34b23fc6f75714dae280', 'user', NULL, b'1'),
(484, '00737', 'null', '94b8de26e70619c8f0a51908ee5592bc', 'user', NULL, b'1'),
(485, '00738', 'null', '48b5d20e3715321cd6ceb98fe9406237', 'user', NULL, b'1'),
(486, '00741', '741@emt', '5cf15eb8c3b4d911af3b17f63d4bd16f', 'user', NULL, b'1'),
(487, '00743', '743@emt', '5cf15eb8c3b4d911af3b17f63d4bd16f', 'user', NULL, b'1'),
(488, '00745', 'null', '39d28e55099bb4d7460c309d0d71e6e8', 'user', NULL, b'1'),
(489, '00746', 'null', '33a9a8e3063f90d911070d1fdb05d939', 'user', NULL, b'1'),
(490, '00747', 'null', 'af73f2bd89b0f6a843d570b101245f8e', 'user', NULL, b'1'),
(491, '00748', 'null', '33a9a8e3063f90d911070d1fdb05d939', 'user', NULL, b'1'),
(492, '00749', 'null', '33a9a8e3063f90d911070d1fdb05d939', 'user', NULL, b'1'),
(493, '00750', '750@emt', '33a9a8e3063f90d911070d1fdb05d939', 'user', NULL, b'1'),
(494, '00751', 'null', '33a9a8e3063f90d911070d1fdb05d939', 'user', NULL, b'1'),
(495, '00752', 'null', '33a9a8e3063f90d911070d1fdb05d939', 'user', NULL, b'1'),
(496, '00753', 'null', '33a9a8e3063f90d911070d1fdb05d939', 'user', NULL, b'1'),
(497, '00754', '754@EMT', '33a9a8e3063f90d911070d1fdb05d939', 'user', NULL, b'1'),
(498, '00755', 'null', '33a9a8e3063f90d911070d1fdb05d939', 'user', NULL, b'1'),
(499, '00757', 'null', '41b9a1763a0865e5773ca6517c650a8f', 'user', NULL, b'1'),
(500, '00758', 'null', '4a4adfa5716d5c705eaf7a24b6f2159c', 'user', NULL, b'1'),
(501, '00760', 'null', 'c2a8ff55c34c1642f02da2a88b462bf4', 'user', NULL, b'1'),
(502, '00761', '761@emt', '2bc9aa8a1aee15665ec6274f1f45763b', 'user', NULL, b'1'),
(503, '00762', '762@emt', '2bc9aa8a1aee15665ec6274f1f45763b', 'user', NULL, b'1'),
(504, '00763', 'null', '2bc9aa8a1aee15665ec6274f1f45763b', 'user', NULL, b'1'),
(505, '00765', '765@emt', '0a8f3c2c96d850f9e1038aa66c34645d', 'user', NULL, b'1'),
(506, '00766', 'null', '768cd7775d287ded5979461490464a8b', 'user', NULL, b'1'),
(507, '00767', 'null', '768cd7775d287ded5979461490464a8b', 'user', NULL, b'1'),
(508, '00768', 'null', '768cd7775d287ded5979461490464a8b', 'user', NULL, b'1'),
(509, '00769', 'null', '6a8dcb866995e7a44bbf6b9d11a460b6', 'user', NULL, b'1'),
(510, '00771', '771@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(511, '00772', 'null', '151b10250739db6614086ae9830ddd06', 'user', NULL, b'1'),
(512, '00773', '773@emt', '768cd7775d287ded5979461490464a8b', 'user', NULL, b'1'),
(513, '00774', 'null', 'bd4502a7c8d103836743f5e114cf2848', 'user', NULL, b'1'),
(514, '00775', '775@emt', '84d9ee44e457ddef7f2c4f25dc8fa865', 'user', NULL, b'1'),
(515, '00776', '776@emt', '3e97fe870ce835c8402b27883b8dfdf8', 'user', NULL, b'1'),
(516, '00778', '778@emt', '3e97fe870ce835c8402b27883b8dfdf8', 'user', NULL, b'1'),
(517, '00779', 'null', '3e97fe870ce835c8402b27883b8dfdf8', 'user', NULL, b'1'),
(518, '00780', 'null', 'b184772aab12875dbf418d8212de5eb2', 'user', NULL, b'1'),
(519, '00781', 'null', '975590429674e4f05b0c255c10ed49c1', 'user', NULL, b'1'),
(520, '00782', '782@emt', 'e54871cb5c01d8ee43dc8a76b25b80dd', 'user', NULL, b'1'),
(521, '00783', '783@emt', '00e97a54a1e64df1996aeced49b70ebb', 'user', NULL, b'1'),
(522, '00784', 'null', '00e97a54a1e64df1996aeced49b70ebb', 'user', NULL, b'1'),
(523, '00785', 'null', '173290c4b38c87d00d1b9200992a103f', 'user', NULL, b'1'),
(524, '00786', 'null', '33a9a8e3063f90d911070d1fdb05d939', 'user', NULL, b'1'),
(525, '00788', '788@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(526, '00791', 'null', '9f4256d8b02816b3aeafcceeb4c59551', 'user', NULL, b'1'),
(527, '00792', 'null', 'cfd09063654a5dcfdb5eba864e6900b5', 'user', NULL, b'1'),
(528, '00793', 'null', '744d7b870b505100e4d25b860e79b76b', 'user', NULL, b'1'),
(529, '00794', '794@emt', '9f4256d8b02816b3aeafcceeb4c59551', 'user', NULL, b'1'),
(530, '00795', 'null', '81577654a3dc70afe5daa8733456090e', 'user', NULL, b'1'),
(531, '00796', 'null', '9f4256d8b02816b3aeafcceeb4c59551', 'user', NULL, b'1'),
(532, '00798', 'null', '9f4256d8b02816b3aeafcceeb4c59551', 'user', NULL, b'1'),
(533, '00799', '799@emt', '9f4256d8b02816b3aeafcceeb4c59551', 'user', NULL, b'1'),
(534, '00800', 'null', '8d6510fcd1cfd69b19580c759827e960', 'user', NULL, b'1'),
(535, '00801', 'null', '9f4256d8b02816b3aeafcceeb4c59551', 'user', NULL, b'1'),
(536, '00802', 'null', '9f4256d8b02816b3aeafcceeb4c59551', 'user', NULL, b'1'),
(537, '00804', 'null', 'ded7c3ab257c6d9e0f8a97d66c959f1c', 'user', NULL, b'1'),
(538, '00805', 'null', '36c60e6652036a8dea95eda58750d9d7', 'user', NULL, b'1'),
(539, '00806', 'null', 'd804feead2892ba581b4d03b3aa604cf', 'user', NULL, b'1'),
(540, '00807', 'null', '43a1ae8756dcffdba9cd96f376a7f3eb', 'user', NULL, b'1'),
(541, '00808', 'null', '9f4256d8b02816b3aeafcceeb4c59551', 'user', NULL, b'1'),
(542, '00809', 'null', '9f4256d8b02816b3aeafcceeb4c59551', 'user', NULL, b'1'),
(543, '00813', 'null', 'c2c4340b1efc3ab659d65d0b30c9806d', 'user', NULL, b'1'),
(544, '00814', 'null', '2d2ebdc7b567f996a85be4bf713df397', 'user', NULL, b'1'),
(545, '00818', 'null', '8dc6a4fca3f222009eaaba0288247407', 'user', NULL, b'1'),
(546, '00819', 'null', 'bbf89f596b5c33d30597ac2a52488229', 'user', NULL, b'1'),
(547, '00820', 'null', 'bbf89f596b5c33d30597ac2a52488229', 'user', NULL, b'1'),
(548, '00821', 'null', '4e724568c3d5cf43fbca8e8a4771fff1', 'user', NULL, b'1'),
(549, '00822', 'null', 'bbf89f596b5c33d30597ac2a52488229', 'user', NULL, b'1'),
(550, '00824', 'null', '3851da9088a057bc735f681cd1e80e70', 'user', NULL, b'1'),
(551, '00825', 'null', '199a81edbf074f8a34fbd7cc8b066804', 'user', NULL, b'1'),
(552, '00826', 'null', '24f4021c41545a4dc7301cf7430faf56', 'user', NULL, b'1'),
(553, '00827', 'null', 'a68cfebbdef8978750b71bb4ea9ae6a4', 'user', NULL, b'1'),
(554, '00828', 'null', 'b551c4a1ed0826db642ad4fdf1186d73', 'user', NULL, b'1'),
(555, '00829', 'null', '6ef28747b9663e104be9417eb19b03a3', 'user', NULL, b'1'),
(556, '00830', '830@emt', 'bbf89f596b5c33d30597ac2a52488229', 'user', NULL, b'1'),
(557, '00831', '831@EMT', 'bbf89f596b5c33d30597ac2a52488229', 'user', NULL, b'1'),
(558, '00833', 'null', '3d2203aa0945e59e710e3729363dfb32', 'user', NULL, b'1'),
(559, '00834', 'null', 'bbf89f596b5c33d30597ac2a52488229', 'user', NULL, b'1'),
(560, '00835', 'null', 'bbf89f596b5c33d30597ac2a52488229', 'user', NULL, b'1'),
(561, '00837', 'null', '3253114e005b8c3bd00cc0f01df0af41', 'user', NULL, b'1'),
(562, '00838', 'null', 'b62f93fe58007d7f2082118ef69f2468', 'user', NULL, b'1'),
(563, '00839', 'null', '45bfe9a5303459a2e5cc2c698e4f9bce', 'user', NULL, b'1'),
(564, '00840', 'null', 'd9e02130b3017e18fec9f09fb3db73a1', 'user', NULL, b'0'),
(565, '00841', 'null', 'a1ac9fc13806d304bc0d3cb45a14511f', 'user', NULL, b'1'),
(566, '00842', '842@emt', 'd9e02130b3017e18fec9f09fb3db73a1', 'user', NULL, b'1'),
(567, '00843', '843@emt', 'd9e02130b3017e18fec9f09fb3db73a1', 'user', NULL, b'1'),
(568, '00845', 'null', 'd9e02130b3017e18fec9f09fb3db73a1', 'user', NULL, b'1'),
(569, '00846', 'null', 'd9e02130b3017e18fec9f09fb3db73a1', 'user', NULL, b'1'),
(570, '00847', 'null', '089eb0916b0789de30ef2216f001d34c', 'user', NULL, b'1'),
(571, '00849', 'null', 'd9e02130b3017e18fec9f09fb3db73a1', 'user', NULL, b'1'),
(572, '00850', '850@emt', 'd9e02130b3017e18fec9f09fb3db73a1', 'user', NULL, b'1'),
(573, '00851', 'null', 'd9e02130b3017e18fec9f09fb3db73a1', 'user', NULL, b'1'),
(574, '00852', 'null', '374c18adc03c5dca2bcd65bff414f437', 'user', NULL, b'1'),
(575, '00853', 'null', '412b3cffda315c85d623ecebebf7a21e', 'user', NULL, b'1'),
(576, '00854', 'null', 'd9e02130b3017e18fec9f09fb3db73a1', 'user', NULL, b'1'),
(577, '00855', 'null', 'd9e02130b3017e18fec9f09fb3db73a1', 'user', NULL, b'1'),
(578, '00856', 'null', '98afdf1f1c88b81118020d30ba64bbc2', 'user', NULL, b'1'),
(579, '00857', 'null', 'd9e02130b3017e18fec9f09fb3db73a1', 'user', NULL, b'1'),
(580, '00859', 'null', '3315a5eda9cb31f4fcfd47fd45203758', 'user', NULL, b'1'),
(581, '00860', 'null', '3315a5eda9cb31f4fcfd47fd45203758', 'user', NULL, b'1'),
(582, '00861', 'null', '7e958327edc290c8f8da48312d4f2921', 'user', NULL, b'1'),
(583, '00862', 'null', '02c1e460ea54dbc3ec28fc2f8e8b5be4', 'user', NULL, b'1'),
(584, '00863', 'null', '02c1e460ea54dbc3ec28fc2f8e8b5be4', 'user', NULL, b'1'),
(585, '00864', 'null', '02c1e460ea54dbc3ec28fc2f8e8b5be4', 'user', NULL, b'1'),
(586, '00865', 'null', '02c1e460ea54dbc3ec28fc2f8e8b5be4', 'user', NULL, b'1'),
(587, '00866', 'null', '4ee762c600eb2bb1398d68028e4e89e6', 'user', NULL, b'1'),
(588, '00867', 'null', '9453585ce694cdb104efacedcc43280e', 'user', NULL, b'1'),
(589, '00868', 'null', '6da1d9dd9720973f9b74cf098f68b70e', 'user', NULL, b'1'),
(590, '00869', 'null', '77277e0f859be176cc9876c6345d0eff', 'user', NULL, b'1'),
(591, '00870', 'null', '19f4dce0e7776e51c566bf3a346d2358', 'user', NULL, b'1'),
(592, '00871', 'null', '02c1e460ea54dbc3ec28fc2f8e8b5be4', 'user', NULL, b'1'),
(593, '00872', 'null', '538b43a47d46736491825bf6e40bd09f', 'user', NULL, b'1'),
(594, '00873', 'null', '2c64d5116ba5c39b28e9d3e8a7153e67', 'user', NULL, b'1'),
(595, '00874', 'null', '271650d2be19cb60912f30f76595398e', 'user', NULL, b'1'),
(596, '00875', 'null', '538b43a47d46736491825bf6e40bd09f', 'user', NULL, b'1'),
(597, '00876', 'null', '8d80e30cab446c4fb788721761ecdde4', 'user', NULL, b'1'),
(598, '00877', 'null', '538b43a47d46736491825bf6e40bd09f', 'user', NULL, b'1'),
(599, '00878', '878@emt', '99706e218a24fc774ac52efe140b10f6', 'user', NULL, b'1'),
(600, '00879', 'null', '99706e218a24fc774ac52efe140b10f6', 'user', NULL, b'1'),
(601, '00880', 'null', '99706e218a24fc774ac52efe140b10f6', 'user', NULL, b'1'),
(602, '00886', 'null', '24924691f41a1e07eb359699d97f3892', 'user', NULL, b'1'),
(603, '00887', 'null', '2a15d14d96e755fcd9de1122f42703e4', 'user', NULL, b'1'),
(604, '00888', 'null', '24924691f41a1e07eb359699d97f3892', 'user', NULL, b'1'),
(605, '00889', '889@emt', 'd9fdba5b8b70524add6681b0a3159371', 'user', NULL, b'1'),
(606, '00890', '890@emt', '300cc6a6661ca30cfc7cac2c1cab7e38', 'user', NULL, b'1'),
(607, '00891', 'null', '026f254239a09446b55c7b9a2ff69696', 'user', NULL, b'1'),
(608, '00892', '892@emt', 'a8485173705691334e9cf7f772b01b95', 'user', NULL, b'0'),
(609, '00893', 'null', '24924691f41a1e07eb359699d97f3892', 'user', NULL, b'1'),
(610, '00895', 'null', 'a0a2956d5189ff32d3b5b39df700c4ed', 'user', NULL, b'1'),
(611, '00896', '896@emt', '24924691f41a1e07eb359699d97f3892', 'user', NULL, b'1'),
(612, '00897', '897@emt', '24924691f41a1e07eb359699d97f3892', 'user', NULL, b'1'),
(613, '00899', '899@emt', '24924691f41a1e07eb359699d97f3892', 'user', NULL, b'1'),
(614, '00900', '900@emt', '24924691f41a1e07eb359699d97f3892', 'user', NULL, b'1'),
(615, '00901', 'null', '41ccca3afb91f24ab33d9ed79f1ac2c7', 'user', NULL, b'1'),
(616, '00902', 'null', '43a0fd80292f8b1f46f0dcaee7b26bbe', 'user', NULL, b'1'),
(617, '00903', 'null', 'bed9591fa8d9e5a61b8441bab5c05599', 'user', NULL, b'1'),
(618, '00904', 'null', '24924691f41a1e07eb359699d97f3892', 'user', NULL, b'1'),
(619, '00908', 'null', '8202ab9c332c85397ed09f3f7e675484', 'user', NULL, b'1'),
(620, '00910', 'null', '8202ab9c332c85397ed09f3f7e675484', 'user', NULL, b'1'),
(621, '00911', '911@emt', '8202ab9c332c85397ed09f3f7e675484', 'user', NULL, b'1'),
(622, '00912', 'null', '64df619fa3ba6080618a6d007af1406a', 'user', NULL, b'1'),
(623, '00913', '913@emt', '8202ab9c332c85397ed09f3f7e675484', 'user', NULL, b'1'),
(624, '00914', 'null', 'cca678df8d7dc659b80ac7d3b29787b8', 'user', NULL, b'1'),
(625, '00915', '915@emt', '8202ab9c332c85397ed09f3f7e675484', 'user', NULL, b'1'),
(626, '00916', '916@emt', '8202ab9c332c85397ed09f3f7e675484', 'user', NULL, b'1'),
(627, '00921', 'null', 'fd482805763f0fd0fd5261e750ca2e57', 'user', NULL, b'1'),
(628, '00922', 'null', 'd7c210cc0b732f7541c9faa66413b32d', 'user', NULL, b'1'),
(629, '00923', 'null', '699f99bfbc0293edd08d41b917511565', 'user', NULL, b'1'),
(630, '00924', 'null', 'd7c210cc0b732f7541c9faa66413b32d', 'user', NULL, b'1'),
(631, '00925', 'null', '5adc2dd24bed82d8cb8ab5a40ef063b4', 'user', NULL, b'1'),
(632, '00926', '926@EMT', 'ec379c845118b1c961c9be0bbc5fe601', 'user', NULL, b'1'),
(633, '00927', '927@emt', 'ec379c845118b1c961c9be0bbc5fe601', 'user', NULL, b'1'),
(634, '00928', '928@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(635, '00930', 'null', '751b086ddb0973c2aae060c7aa78c3ed', 'user', NULL, b'0'),
(636, '00931', '931@emt', '751b086ddb0973c2aae060c7aa78c3ed', 'user', NULL, b'1'),
(637, '00932', 'null', '751b086ddb0973c2aae060c7aa78c3ed', 'user', NULL, b'1'),
(638, '00933', '933@emt', '045117b0e0a11a242b9765e79cbf113f', 'user', NULL, b'1'),
(639, '00934', 'null', '0831c3a258c70fe29075bea91b35354e', 'user', NULL, b'1'),
(640, '00935', '935@emt', '0831c3a258c70fe29075bea91b35354e', 'user', NULL, b'0'),
(641, '00937', '937@emt', 'ea63a89008b0c0dcafa3d1888db48efa', 'user', NULL, b'1'),
(642, '00940', 'null', '7b2bd34d82807b29ccb1b64030495a2e', 'user', NULL, b'1'),
(643, '00941', 'null', '7b2bd34d82807b29ccb1b64030495a2e', 'user', NULL, b'1'),
(644, '00942', 'null', '7eeb13dcd84b40d5e7b88d9a3db04242', 'user', NULL, b'1'),
(645, '00943', 'null', '7eeb13dcd84b40d5e7b88d9a3db04242', 'user', NULL, b'1'),
(646, '00944', 'null', '7eeb13dcd84b40d5e7b88d9a3db04242', 'user', NULL, b'1'),
(647, '00945', '945@emt', '5c0030fb1e095bcea347e30191e7336e', 'user', NULL, b'1'),
(648, '00946', '946@EMT', '8173fb18c76f9015f67adbe8a1b3a1f8', 'user', NULL, b'1'),
(649, '00947', 'null', '8d2eb15984c6769f52106b508a6229f6', 'user', NULL, b'1'),
(650, '00948', 'null', '32440e583f6092af7a080942f88040d6', 'user', NULL, b'1'),
(651, '00949', 'null', 'f004357feeec65b3b32fbae5921a6429', 'user', NULL, b'1'),
(652, '00950', 'null', 'b22d4ec35e5947e58ade8e34fb325ee5', 'user', NULL, b'1'),
(653, '00951', 'null', 'f004357feeec65b3b32fbae5921a6429', 'user', NULL, b'1'),
(654, '00953', 'null', 'b711098ebab026e785a635ea23db8af2', 'user', NULL, b'1'),
(655, '00954', 'null', 'ec8e0a97ff1d1ca74f59fb4f16185b55', 'user', NULL, b'1'),
(656, '00955', '955@emt', '7bdea22f14b921d1192e31ad9079d03d', 'user', NULL, b'1'),
(657, '00956', '956@emt', 'a8befc7d5fb0a59b86b1b7f744d77ce1', 'user', NULL, b'1'),
(658, '00957', '957@emt', '960d6c16d04865c3d8885bac7e9dfa3d', 'user', NULL, b'1'),
(659, '00958', 'null', 'f004357feeec65b3b32fbae5921a6429', 'user', NULL, b'1'),
(660, '00961', '961@emt', 'a8befc7d5fb0a59b86b1b7f744d77ce1', 'user', NULL, b'1'),
(661, '00963', 'null', '0a2264dacdce48260f0fab4c027894eb', 'user', NULL, b'1'),
(662, '00964', 'null', '16069d4fa85f5b09454273143ae1c3ac', 'user', NULL, b'1'),
(663, '00965', 'null', 'e1b4bef5d45d919b22a2990b49527de2', 'user', NULL, b'1'),
(664, '00966', '966@emt', 'ebb4162365ce5b1e6f176e5f44a9b569', 'user', NULL, b'1'),
(665, '00967', 'null', '699803e5f40e92c1a705a0c4564d4a63', 'user', NULL, b'1'),
(666, '00968', 'null', '5f1d9e01a624a941fe2caa2f7c0b02a1', 'user', NULL, b'1'),
(667, '00969', '969@emt', '37f52f21aca33bea799fced6636c9cc7', 'user', NULL, b'1'),
(668, '00970', 'null', 'f2bf0b24a6d1f1c4fe172236e5a8eda9', 'user', NULL, b'1');
INSERT INTO `users` (`USER_ID`, `USERNAME`, `USER_EMAIL`, `USER_PASSWORD`, `USERTYPE`, `Dept_Id`, `USER_STATUS`) VALUES
(669, '00971', 'null', 'f2bf0b24a6d1f1c4fe172236e5a8eda9', 'user', NULL, b'1'),
(670, '00972', 'null', '37775d06efc99eed52189b35cd322401', 'user', NULL, b'1'),
(671, '00973', '973@emt', '626c24ed906a5e437dc68dbcd7df8a20', 'user', NULL, b'0'),
(672, '00976', 'null', 'df39be3da44856c48393fd1a4b580199', 'user', NULL, b'1'),
(673, '00977', '977@emt', '4dce32076fa8eb92d5d18987660f85bb', 'user', NULL, b'1'),
(674, '00979', '979@emt', '3e1d7a0f0b79a03731a6d5dea9e4e59d', 'user', NULL, b'1'),
(675, '00980', 'null', '3e1d7a0f0b79a03731a6d5dea9e4e59d', 'user', NULL, b'1'),
(676, '00981', '981@emt', 'd088f32ed0c133d82ad63a12f0b92988', 'user', NULL, b'1'),
(677, '00983', 'null', '317da2bfa74064caa7a009560be4afc3', 'user', NULL, b'1'),
(678, '00986', '986@EMT', 'cd170007bd2b2c6a3a86799fb2588950', 'user', NULL, b'1'),
(679, '00987', '987@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(680, '00989', '989@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(681, '00990', 'null', '8de819c47b59dbbf6311bcbcf232f0e8', 'user', NULL, b'1'),
(682, '00991', 'null', '0075a38d550e1f0da99004da5d08f5e9', 'user', NULL, b'1'),
(683, '00992', 'null', '133f58ee2a12f28dd1f8af7382b473b4', 'user', NULL, b'1'),
(684, '00993', 'null', 'e496e19b90a82d1c6f3658953f70fec7', 'user', NULL, b'1'),
(685, '00994', 'null', '1cf4b1a35037824b670da72cf189d8fe', 'user', NULL, b'1'),
(686, '00995', 'null', '288267648d031733a54cd2168539d01b', 'user', NULL, b'1'),
(687, '00998', 'null', '92fa6ab5a666f38b2b1418328a3a8ba9', 'user', NULL, b'0'),
(688, '01000', 'null', '773fa3cdc5cd6729e94fc5536da8536a', 'user', NULL, b'1'),
(689, '01001', 'null', '041341678cf1e69e31b7123f5144e8ca', 'user', NULL, b'1'),
(690, '01002', '1002@EMT', '9ad89c0d942446a12d16da4df7b233d9', 'user', NULL, b'1'),
(691, '01004', '1004@emt', '84181d075c919177dc396ca2d483def3', 'user', NULL, b'1'),
(692, '01005', '1005@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(693, '01006', '1006@emt', 'c1184edeb88248dd9c8aed753577674e', 'user', NULL, b'1'),
(694, '01008', '1008@emt', '7b7a51c88807097b07d512b4e9b24ac8', 'user', NULL, b'1'),
(695, '01009', '1009@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(696, '01010', 'null', '417aa8d22619e06e7d5c9fe155863a55', 'user', NULL, b'1'),
(697, '01011', '1011@emt', '42cc558b774a5e08a63b0d20492e4df7', 'user', NULL, b'1'),
(698, '01012', 'null', '5fe95bdabcd33eebf329b7cb9272ee31', 'user', NULL, b'1'),
(699, '01013', 'null', '4258070696a6ceaad85949c4c9ce4abf', 'user', NULL, b'1'),
(700, '01016', 'user@email.com', '10aac92862b95706e1411c4efa531c53', 'admin', NULL, b'1'),
(701, '01017', '1017@emt', '6c4b761a28b734fe93831e3fb400ce87', 'user', NULL, b'1'),
(702, '01018', 'null', 'bdfffbdc25f9eca35db0625734840e31', 'user', NULL, b'1'),
(703, '01019', 'null', 'af2141245234e097024730bbe9bc1535', 'user', NULL, b'1'),
(704, '01020', '1020@emt', '69eecea759374e24fabd73e98b3eab0e', 'user', NULL, b'1'),
(705, '01021', 'null', 'f0f25dc4eaf0f01627bc626f51869843', 'user', NULL, b'1'),
(706, '01023', 'null', 'a5c797321aec35fa3bee86e70e1e6991', 'user', NULL, b'1'),
(707, '01024', 'null', '33a50ad1f60e900eb5ec43dcf7ac8c0a', 'user', NULL, b'1'),
(708, '01025', 'null', 'a5c797321aec35fa3bee86e70e1e6991', 'user', NULL, b'1'),
(709, '01027', 'null', '58b24193b9924320a8af661fda6f1952', 'user', NULL, b'1'),
(710, '01028', '1028@emt', 'a5c797321aec35fa3bee86e70e1e6991', 'user', NULL, b'1'),
(711, '01029', '1029@emt', 'c62dfbba5ed8e972953b1a175e4f4b45', 'user', NULL, b'1'),
(712, '01031', 'null', '79b4928ce35860aafc79b4849c0c4f58', 'user', NULL, b'1'),
(713, '01032', '1032@emt', 'f51fbeb80db1ab596219ece815f31aba', 'user', NULL, b'1'),
(714, '01033', '1033@emt', '58daf01bf616370befe2ad36e8391dfa', 'user', NULL, b'1'),
(715, '01034', '1034@emt', '28ad3909b85d9b69396bf450024367a0', 'user', NULL, b'1'),
(716, '01035', 'null', '74489aec460db8ef7366c70305f291e8', 'user', NULL, b'1'),
(717, '01036', '1036@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(718, '01037', 'null', '28ad3909b85d9b69396bf450024367a0', 'user', NULL, b'1'),
(719, '01038', '1038@emt', '5870dd88e833841ec1ed6f7121d4c113', 'user', NULL, b'1'),
(720, '01039', 'null', 'c7a371383eb9c31282ff7692a1c1afa1', 'user', NULL, b'1'),
(721, '01041', 'null', '1fa7a8396114b32f45646642c930b85e', 'user', NULL, b'1'),
(722, '01042', '1042@emt', '9c0a76b09a09525862b0ff17f194928b', 'user', NULL, b'1'),
(723, '01043', 'null', '1a96adafff5700c95707891178a38a24', 'user', NULL, b'1'),
(724, '01044', 'null', '5768f6b22f73753567e0564d1012f561', 'user', NULL, b'1'),
(725, '01045', 'null', '1a96adafff5700c95707891178a38a24', 'user', NULL, b'1'),
(726, '01046', 'null', 'a3bdd69bfc7daa9368c7d65046d33714', 'user', NULL, b'1'),
(727, '01049', 'null', '6fe0e994ed6b04f68a4c514ddace47d7', 'user', NULL, b'1'),
(728, '01050', 'null', '751f61680dbf243766ae508200cbe8b4', 'user', NULL, b'1'),
(729, '01051', 'null', '95da48ae7db12c387753796ee9fde79d', 'user', NULL, b'1'),
(730, '01052', 'null', 'aa20a187e7228654b99ed805925102d0', 'user', NULL, b'1'),
(731, '01053', 'null', 'c487bc56ca42cab32c2910e9ddc29a4a', 'user', NULL, b'1'),
(732, '01054', 'null', '7289e7c7717d22707c58e1425fe7b7ee', 'user', NULL, b'1'),
(733, '01055', 'null', '818e68bd8d96ea788948dfa7366c83b4', 'user', NULL, b'1'),
(734, '01056', 'null', 'a3bdd69bfc7daa9368c7d65046d33714', 'user', NULL, b'1'),
(735, '01057', 'null', 'a04d88ba447e495015a69126f0d5fc0e', 'user', NULL, b'0'),
(736, '01060', 'null', '37854f0754bdf3df146d421946e692ac', 'user', NULL, b'1'),
(737, '01061', 'iqbal@emtdubai', '1a96adafff5700c95707891178a38a24', 'user', NULL, b'0'),
(738, '01062', 'null', '2e4d5c0a17b6a1d8aa23b90091c85166', 'user', NULL, b'1'),
(739, '01064', 'null', '881c861686e80c338ffcea845a8b6e0c', 'user', NULL, b'1'),
(740, '01065', '1065@EMT', 'eadd23f2ae26cbcb10d037bc8fec348a', 'user', NULL, b'1'),
(741, '01066', '1066@emt', 'f979fade640e6863dce924dc54b5e9d8', 'user', NULL, b'1'),
(742, '01067', 'null', '8b4801d4ef847e2b39e17e4de607fed0', 'user', NULL, b'1'),
(743, '01069', 'null', 'f979fade640e6863dce924dc54b5e9d8', 'user', NULL, b'1'),
(744, '01070', 'null', '3d87d1d3c8ec076b9f30eb1992c24d75', 'user', NULL, b'1'),
(745, '01071', 'null', 'f979fade640e6863dce924dc54b5e9d8', 'user', NULL, b'1'),
(746, '01072', 'null', '8137f0baf6fccb636d532364be7b4297', 'user', NULL, b'1'),
(747, '01073', 'null', 'f979fade640e6863dce924dc54b5e9d8', 'user', NULL, b'1'),
(748, '01074', 'null', '712c38de3566e1cc86b10267b1d1dfab', 'user', NULL, b'1'),
(749, '01075', '1075@emt', 'f979fade640e6863dce924dc54b5e9d8', 'user', NULL, b'1'),
(750, '01076', '1076@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(751, '01077', 'null', '97b0d669804a10a24d93fd71ee8fa796', 'user', NULL, b'1'),
(752, '01079', 'null', 'b3e49bf619e95519c3a94c55d61c8afb', 'user', NULL, b'1'),
(753, '01083', 'null', '631692a43db773629699172d48da9cca', 'user', NULL, b'1'),
(754, '01084', 'null', 'c1c03033aeae75d24dba267108e8381a', 'user', NULL, b'1'),
(755, '01085', 'null', 'ee587e35691e7510ba383c040d80a91b', 'user', NULL, b'1'),
(756, '01087', '1087@emt', '6a2e97ed3ad57e2e07b1fac2954652db', 'user', NULL, b'1'),
(757, '01088', 'null', '8d24ae7ae68ab9f124838e0e5505de42', 'user', NULL, b'1'),
(758, '01090', 'null', '0f15e431e23efab7663bd7650de3c597', 'user', NULL, b'1'),
(759, '01095', '1095@emt', '85ce6b4cfcc35cb3d757965aa33a4922', 'user', NULL, b'1'),
(760, '01096', 'null', '90d26f1f0701f7e69a5d7468cf070d89', 'user', NULL, b'1'),
(761, '01097', '1097@emt', '85ce6b4cfcc35cb3d757965aa33a4922', 'user', NULL, b'1'),
(762, '01098', '1098@emt', '85ce6b4cfcc35cb3d757965aa33a4922', 'user', NULL, b'1'),
(763, '01099', 'null', 'e1211f37c5fb8bc61e46269a4c405a04', 'user', NULL, b'1'),
(764, '01100', '1100@emt', 'ae3725a6d7b063cc27fc93dbe9ab9beb', 'user', NULL, b'1'),
(765, '01101', 'null', '85ce6b4cfcc35cb3d757965aa33a4922', 'user', NULL, b'1'),
(766, '01102', 'null', '85ce6b4cfcc35cb3d757965aa33a4922', 'user', NULL, b'1'),
(767, '01103', 'null', '85ce6b4cfcc35cb3d757965aa33a4922', 'user', NULL, b'1'),
(768, '01104', 'null', '85ce6b4cfcc35cb3d757965aa33a4922', 'user', NULL, b'1'),
(769, '01106', 'null', '85ce6b4cfcc35cb3d757965aa33a4922', 'user', NULL, b'1'),
(770, '01107', 'null', '85ce6b4cfcc35cb3d757965aa33a4922', 'user', NULL, b'1'),
(771, '01108', 'null', '85ce6b4cfcc35cb3d757965aa33a4922', 'user', NULL, b'1'),
(772, '01109', 'null', '85ce6b4cfcc35cb3d757965aa33a4922', 'user', NULL, b'1'),
(773, '01110', 'null', '0efa877356d9fe4e893896f2c2dd10da', 'user', NULL, b'1'),
(774, '01111', 'null', '85ce6b4cfcc35cb3d757965aa33a4922', 'user', NULL, b'1'),
(775, '01112', '1112@emt', '85ce6b4cfcc35cb3d757965aa33a4922', 'user', NULL, b'1'),
(776, '01113', 'null', '0d79edddca44327cc8a6b572d9b6de9d', 'user', NULL, b'1'),
(777, '01115', '1115@emt', 'e80558389052d9c52c15a3961a26c765', 'user', NULL, b'1'),
(778, '01116', 'null', '6074c6aa3488f3c2dddff2a7ca821aab', 'user', NULL, b'1'),
(779, '01117', '117@emt', '1fd149840c5f8bbfa36eceb4eacef4cc', 'user', NULL, b'1'),
(780, '01118', 'null', 'e80558389052d9c52c15a3961a26c765', 'user', NULL, b'1'),
(781, '01119', 'null', 'e80558389052d9c52c15a3961a26c765', 'user', NULL, b'1'),
(782, '01123', 'null', 'e80558389052d9c52c15a3961a26c765', 'user', NULL, b'1'),
(783, '01124', 'null', '02cbf44835d591042ccefbfe01553fc1', 'user', NULL, b'1'),
(784, '01125', 'null', 'e80558389052d9c52c15a3961a26c765', 'user', NULL, b'1'),
(785, '01126', '1126@emt', 'e80558389052d9c52c15a3961a26c765', 'user', NULL, b'1'),
(786, '01127', 'null', 'e80558389052d9c52c15a3961a26c765', 'user', NULL, b'1'),
(787, '01128', 'null', 'e80558389052d9c52c15a3961a26c765', 'user', NULL, b'1'),
(788, '01129', '1129@emt', 'e80558389052d9c52c15a3961a26c765', 'user', NULL, b'1'),
(789, '01130', 'null', '9e8a0dbf913731b6ac802ed183faafc0', 'user', NULL, b'1'),
(790, '01131', 'null', 'e80558389052d9c52c15a3961a26c765', 'user', NULL, b'1'),
(791, '01132', 'null', 'e80558389052d9c52c15a3961a26c765', 'user', NULL, b'1'),
(792, '01133', 'null', 'e798f21ac76a780caff3bf007e8c44a9', 'user', NULL, b'1'),
(793, '01134', 'null', 'e80558389052d9c52c15a3961a26c765', 'user', NULL, b'1'),
(794, '01135', 'null', 'e80558389052d9c52c15a3961a26c765', 'user', NULL, b'1'),
(795, '01136', 'null', 'dc90e362f4c8448619dc572f5702d64a', 'user', NULL, b'1'),
(796, '01137', 'null', '5fc489f4c29d2ade17da95c91f3f6a89', 'user', NULL, b'1'),
(797, '01138', 'null', 'c48f2c4dc571957bfb6df30d4a8e53a4', 'user', NULL, b'1'),
(798, '01139', 'null', 'e80558389052d9c52c15a3961a26c765', 'user', NULL, b'1'),
(799, '01141', 'null', 'bb0586f86ef886379707cf35bba5ed52', 'user', NULL, b'1'),
(800, '01142', '1142@emt', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(801, '01143', 'null', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(802, '01144', '1144@emt', 'cf719d0e860dba7e30152a5ba4cf85dd', 'user', NULL, b'1'),
(803, '01145', 'null', 'e80558389052d9c52c15a3961a26c765', 'user', NULL, b'1'),
(804, '01146', '1146@emt', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(805, '01147', 'null', 'f21e811baab4a1e9a799aba2a72ef34f', 'user', NULL, b'1'),
(806, '01148', 'null', '3d1d3fe00aef295e7c217bbafb2265ab', 'user', NULL, b'1'),
(807, '01149', 'null', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(808, '01150', 'null', '7a5480d86e9b3573aeeaf724424677f7', 'user', NULL, b'1'),
(809, '01151', 'null', '45d2f4c79500535f697f118cf6012779', 'user', NULL, b'1'),
(810, '01152', 'null', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(811, '01154', 'null', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(812, '01155', '1155@emt', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(813, '01157', 'null', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(814, '01158', 'null', '0696bba1cd2a4b972c374c324fc32be1', 'user', NULL, b'1'),
(815, '01159', 'null', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(816, '01161', 'null', '7da33d5cd8382baac4969adce3280088', 'user', NULL, b'1'),
(817, '01162', '1162@EMT', '04809295fc6b9c47fa9ca4a974cac914', 'user', NULL, b'1'),
(818, '01163', 'null', '59e77d4a0b8dfb0c73d0148d2e7a6519', 'user', NULL, b'1'),
(819, '01164', '1164@EMT', 'a60a6c50712303b2744f57aa9ca4cf41', 'user', NULL, b'1'),
(820, '01165', '1165@emt', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(821, '01166', 'null', '0f2c9a93eea6f38fabb3acb1c31488c6', 'user', NULL, b'1'),
(822, '01167', 'null', 'de6625fb32a16dd9535be6c0a0681418', 'user', NULL, b'1'),
(823, '01168', '1168@emt', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(824, '01169', '1169@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(825, '01170', '1170@emt', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(826, '01171', 'null', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(827, '01172', 'null', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(828, '01173', 'null', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(829, '01174', 'null', '6403e022e3f64e96bc44c4a7d3ed29f6', 'user', NULL, b'1'),
(830, '01175', '1175@emt', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(831, '01176', 'null', 'f0a4b3a38200fe7657fcbbca4ded1426', 'user', NULL, b'1'),
(832, '01177', 'null', 'd8f4280cf01872f33bce12d141be5b8e', 'user', NULL, b'1'),
(833, '01178', 'null', '10821dfb40b1ae780818b6e77fd52b8e', 'user', NULL, b'1'),
(834, '01179', '1179@emt', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'0'),
(835, '01180', 'null', 'a8e6af10bc51ec1cee894f7dc278eb90', 'user', NULL, b'1'),
(836, '01181', 'null', '9567d425b5338add1b91e90bd7b6a45e', 'user', NULL, b'1'),
(837, '01183', 'null', 'd59569a853864a73caf9be6385ce4685', 'user', NULL, b'1'),
(838, '01184', 'null', 'e0af382b11a8fa8fa6b1bd6d78acaf13', 'user', NULL, b'1'),
(839, '01186', 'null', '8491a9311b5cbcd0daf837ff7fe8a8a9', 'user', NULL, b'1'),
(840, '01187', 'null', 'd59569a853864a73caf9be6385ce4685', 'user', NULL, b'1'),
(841, '01188', 'null', '2a7c9a939320e9e7327f50c18bfbd909', 'user', NULL, b'1'),
(842, '01189', 'null', '9a426de9e210e8389e02802bb1cd2327', 'user', NULL, b'1'),
(843, '01190', 'null', 'be6c6c2f1d5b0dd953947e1d607b68dd', 'user', NULL, b'1'),
(844, '01191', 'null', 'd59569a853864a73caf9be6385ce4685', 'user', NULL, b'1'),
(845, '01192', '1192@emt', 'e80558389052d9c52c15a3961a26c765', 'user', NULL, b'1'),
(846, '01195', 'null', 'ddfb772f9d2680822f508dd821030b96', 'user', NULL, b'1'),
(847, '01196', 'null', 'cd47dccad86d43a436c3d996801d4ca0', 'user', NULL, b'1'),
(848, '01197', 'null', '41a35e4b27a89a475129a41f8149058f', 'user', NULL, b'1'),
(849, '01198', 'null', '4ecb614c698bee3738e11d2f143fb31f', 'user', NULL, b'1'),
(850, '01200', 'null', '1fc66bdd7c376bb330672f78e684fb57', 'user', NULL, b'1'),
(851, '01201', 'null', 'b18078040eae5ac25dcf59511d9f943e', 'user', NULL, b'1'),
(852, '01202', 'null', '16ac4d10c2f7107943959d275bcfb86f', 'user', NULL, b'1'),
(853, '01203', 'null', 'b18078040eae5ac25dcf59511d9f943e', 'user', NULL, b'1'),
(854, '01204', '1204@emt', 'b18078040eae5ac25dcf59511d9f943e', 'user', NULL, b'1'),
(855, '01205', 'null', 'b18078040eae5ac25dcf59511d9f943e', 'user', NULL, b'1'),
(856, '01206', 'null', 'b18078040eae5ac25dcf59511d9f943e', 'user', NULL, b'1'),
(857, '01207', 'null', 'd54144aa57efb96e317a1649b10331d8', 'user', NULL, b'1'),
(858, '01208', 'null', '41b403a12d01c42df516402ca0f84eca', 'user', NULL, b'1'),
(859, '01209', 'null', '022cb5d8a2b32dd6819d26eafcbaa504', 'user', NULL, b'1'),
(860, '01210', 'null', '4a03fab89db926be119455bcaec0a981', 'user', NULL, b'1'),
(861, '01211', 'null', 'a8bea9859c3c8b1b2795535a183188b6', 'user', NULL, b'1'),
(862, '01212', 'null', '7cb5a7391983fdbac04205af3deddf6c', 'user', NULL, b'1'),
(863, '01213', 'null', 'bd7f858ffe2bf4e84d81b8d72e22f085', 'user', NULL, b'1'),
(864, '01214', 'null', 'bd7f858ffe2bf4e84d81b8d72e22f085', 'user', NULL, b'1'),
(865, '01215', 'null', '53b51cec24fc5b45e400dc0b56fcae56', 'user', NULL, b'1'),
(866, '01217', 'null', 'f8ec77cd1a9262783ee74c16428d6d0c', 'user', NULL, b'1'),
(867, '01218', '1218@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(868, '01219', 'null', '37810a3cc48dcfd8b83256313ce12453', 'user', NULL, b'1'),
(869, '01220', 'null', '71b514171c0209eec887b82bcd5b1743', 'user', NULL, b'1'),
(870, '01221', 'null', '97ecfbe13dd639b79de4559c0a9e14db', 'user', NULL, b'1'),
(871, '01222', '1222@emt', '14063f5e8731bf52d14cd7a253ccf183', 'user', NULL, b'1'),
(872, '01223', 'null', '8bd7f218dc433a1af4d7e2b550d5cc22', 'user', NULL, b'1'),
(873, '01224', '1224@emt', '8b76c98ba1aaf498a8e8a56b608b7f6c', 'user', NULL, b'1'),
(874, '01225', 'null', '8b76c98ba1aaf498a8e8a56b608b7f6c', 'user', NULL, b'1'),
(875, '01226', 'null', '8b76c98ba1aaf498a8e8a56b608b7f6c', 'user', NULL, b'1'),
(876, '01227', '1227@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(877, '01228', 'null', '62e80ea3489c8446a6dc6caa8ed6a793', 'user', NULL, b'1'),
(878, '01229', 'null', '62e80ea3489c8446a6dc6caa8ed6a793', 'user', NULL, b'1'),
(879, '01230', '1230@emt', '8914696db367fbd368b5f0e7cdebde7e', 'user', NULL, b'0'),
(880, '01231', 'null', 'c70b92973e8b4b1b10f0909cbd7af94b', 'user', NULL, b'0'),
(881, '01232', 'null', 'ee606f649873eac17bebf9e91a9415cd', 'user', NULL, b'1'),
(882, '01233', '1233@emt', '1122f19f72162b66b2341377071edc1b', 'user', NULL, b'1'),
(883, '01234', 'null', '7de982a6e17c1005c2a0257c80e3597e', 'user', NULL, b'1'),
(884, '01236', 'null', '4d990d90e75385e4c6862b51a3b2b9ce', 'user', NULL, b'1'),
(885, '01237', '1237@emt', '2aa73fc7b272a33ec70ab98c3a525549', 'user', NULL, b'1'),
(886, '01238', 'null', '990a5146dca24160ad03f27d375607f7', 'user', NULL, b'1'),
(887, '01239', 'null', 'ac3f0d6941587f3ed4800f2ed7bd3d04', 'user', NULL, b'1'),
(888, '01240', 'null', 'f9a1174acf70d09c99b35df67275302a', 'user', NULL, b'1'),
(889, '01241', 'null', '4f9408eb4c132f0caff36ba0c0506679', 'user', NULL, b'1'),
(890, '01242', 'null', '9d7311ba459f9e45ed746755a32dcd11', 'user', NULL, b'1'),
(891, '01243', '1243@emt', '7d45fd75325a102f8769201e0566d516', 'user', NULL, b'0'),
(892, '01245', '1245@emt', 'aed1777188da58fb19d17b9d9b0032ed', 'user', NULL, b'1'),
(893, '01246', 'null', '8b76c98ba1aaf498a8e8a56b608b7f6c', 'user', NULL, b'1'),
(894, '01247', '1247@emt', 'aa48b1e041d598782fbc1dcbcc8b4253', 'user', NULL, b'1'),
(895, '01248', 'null', '995ae561eb3d7ccdded2521e3696171e', 'user', NULL, b'1'),
(896, '01250', 'null', '7fe49395b0a5e4d2ae81cf488c2c2e85', 'user', NULL, b'1'),
(897, '01251', '1251@EMT', '6a2e97ed3ad57e2e07b1fac2954652db', 'user', NULL, b'1'),
(898, '01252', 'null', 'd59569a853864a73caf9be6385ce4685', 'user', NULL, b'1'),
(899, '01253', '1253@emt', '6e400ecebdd1154f334f54a072371a26', 'user', NULL, b'1'),
(900, '01254', 'null', '8a0ee247d293889f7223112f28a345e2', 'user', NULL, b'1'),
(901, '01255', '1255@emt', '6e400ecebdd1154f334f54a072371a26', 'user', NULL, b'1'),
(902, '01256', 'null', 'f90364967669c76ff416f3c8655b62c0', 'user', NULL, b'1'),
(903, '01257', 'null', 'a3978081ad7f6273eb4a283ae8078432', 'user', NULL, b'1'),
(904, '01258', '1258@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(905, '01259', 'null', '6e400ecebdd1154f334f54a072371a26', 'user', NULL, b'1'),
(906, '01260', '1260@EMT', '65ddeb1702accd1128d088e518ff5236', 'user', NULL, b'1'),
(907, '01262', '1262@emt', 'd7d3f44944951d678e9baa0da2d5b70d', 'user', NULL, b'1'),
(908, '01263', '1263@emt', 'd7d3f44944951d678e9baa0da2d5b70d', 'user', NULL, b'1'),
(909, '01264', 'null', '1bfe80db33cddb78dd5bb12e9292cf14', 'user', NULL, b'1'),
(910, '01265', 'null', '511c733415d1a0a3f68447b03291fec6', 'user', NULL, b'1'),
(911, '01266', 'null', '7a6e74af52eaf4724772ae6fe1435cee', 'user', NULL, b'0'),
(912, '01267', '1267@EMT', '4cb567011086b7b97da74253010fa7a2', 'user', NULL, b'1'),
(913, '01269', '1269@emt', 'e00406144c1e7e35240afed70f34166a', 'user', NULL, b'1'),
(914, '01270', '1270@emt', 'b89a88bb2e1c3e7c6a20d9502a83c093', 'user', NULL, b'1'),
(915, '01272', 'null', '7b1c4147235d0f642217f314c522e41b', 'user', NULL, b'1'),
(916, '01274', 'null', 'c5589e1db937f5468d8c37974a28efd5', 'user', NULL, b'1'),
(917, '01275', 'null', '39f68dcdf7a08f97956b2a39683ec896', 'user', NULL, b'1'),
(918, '01276', '1276@EMT', '4ee16b31334b16e9f286f46500623f95', 'user', NULL, b'1'),
(919, '01281', '1281@emt', '04158015485d0ba3d71a26df310cfa5a', 'user', NULL, b'1'),
(920, '01282', '1282@emt', '04158015485d0ba3d71a26df310cfa5a', 'user', NULL, b'1'),
(921, '01284', 'null', '145300b45eb2ecf796c08f12e87c26f0', 'user', NULL, b'1'),
(922, '01285', '1285@emt', '40f80d6a700e5acad89a1d2c7aa2fa7a', 'user', NULL, b'1'),
(923, '01286', 'null', 'c06bb9716306e5233a0ab8f536a27371', 'user', NULL, b'1'),
(924, '01287', '1287@emt', 'db4c38bb9aee2e8d58d76b8e3003cb31', 'user', NULL, b'1'),
(925, '01288', 'null', 'db4c38bb9aee2e8d58d76b8e3003cb31', 'user', NULL, b'1'),
(926, '01289', 'null', '408b758df4ad9f95ba94e4dc6ed19c4d', 'user', NULL, b'1'),
(927, '01290', '1290@emt', '82a1c898cd356aeef3c2ab1c9f949f07', 'user', NULL, b'1'),
(928, '01292', '1292@emt', '41ae4eae496bc2f770ea6d5a8c95d307', 'user', NULL, b'1'),
(929, '01293', 'null', '757e259e5c95665eba8684b93516703e', 'user', NULL, b'1'),
(930, '01294', '1294@EMT', '03f8e573598454e3b1c1ec2c8aed63c1', 'user', NULL, b'1'),
(931, '01296', 'null', 'a71311b1c2c8738b803f80e076eb062e', 'user', NULL, b'1'),
(932, '01299', '1299@emt', '76aac3c5c487d3f0a8293da5a9156ce0', 'user', NULL, b'1'),
(933, '01300', '1300@emt', '190eaf6d29942fde5ca46ab96670da1c', 'user', NULL, b'1'),
(934, '01309', '1309@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(935, '01311', '1311@emt', 'c42af2fa7356818e0389593714f59b52', 'user', NULL, b'1'),
(936, '01312', '1312@emt', '4181a3689da83111af6bc7d6cbb67197', 'user', NULL, b'1'),
(937, '01313', 'null', 'd7fb823a63380c384bd919e53d642c87', 'user', NULL, b'1'),
(938, '01314', '1314@emt', 'b54f5cfb37a6e4a3619b566be2d33776', 'user', NULL, b'1'),
(939, '01315', '1315@emt', 'fa6121ee67e0d305e6514034adcf5685', 'user', NULL, b'1'),
(940, '01317', '1317@emt', 'df5030a57197a9db5b6ec72541db92d0', 'user', NULL, b'1'),
(941, '01318', 'null', 'e74bd4a97a2b229e7ff02a5cf0b8d1ad', 'user', NULL, b'1'),
(942, '01319', 'null', 'be4fbd420c788144c8c0f8ffac3c94f0', 'user', NULL, b'1'),
(943, '01320', '1320@emt', '69d37a4169d7355cedc0c76371341916', 'user', NULL, b'1'),
(944, '01321', 'null', '9fd15fdfad51a5a0c82e22036fb8c7fd', 'user', NULL, b'1'),
(945, '01323', '1323@emt', 'e4d194b24c52bd58bab30c1671cfd4c3', 'user', NULL, b'1'),
(946, '01325', '1325@emt', 'a356197f0881b9982e3dafb183e646fa', 'user', NULL, b'1'),
(947, '01326', 'null', 'c9a4329e06348b592d1840e522ea63d7', 'user', NULL, b'1'),
(948, '01327', 'null', 'c9a4329e06348b592d1840e522ea63d7', 'user', NULL, b'1'),
(949, '01328', '1328@emt', 'dc833441c1cb0918c7dbf138d203646e', 'user', NULL, b'1'),
(950, '01329', '1329@emt', '3e4fbca64b66a0309f8d715ea5a65a6f', 'user', NULL, b'1'),
(951, '01331', '1331@emt', '238ed07756505446a2c10c2ed9ed5c28', 'user', NULL, b'1'),
(952, '01332', '1332@emt', 'c35a29d6af37df6fbe549d3e66e092d3', 'user', NULL, b'1'),
(953, '01333', '1333@emt', '34e293976a84314be25412b3253d06be', 'user', NULL, b'1'),
(954, '01334', '1334@emt', '0e2a8e22ca477e413d8b839a81fa7e43', 'user', NULL, b'0'),
(955, '01335', 'null', 'c9a4329e06348b592d1840e522ea63d7', 'user', NULL, b'1'),
(956, '00003', 'null', '446dffd4b53bdbf90091d7c883708a6d', 'user', NULL, b'1'),
(957, '00005', '5@emt', '403ee82f300ebf823da0556e132c5728', 'user', NULL, b'1'),
(958, '00006', '6@emt', '06122a5e52344c0059bbe9e210874c82', 'user', NULL, b'1'),
(959, '00007', 'null', 'c7ceacea9b9d25ef8de077d5267ee899', 'user', NULL, b'1'),
(960, '00008', '8@emt', '552964ce8239a796e207c3600fef1586', 'user', NULL, b'1'),
(961, '00010', 'null', '5fd27329b82634168ea65c99e4b01d12', 'user', NULL, b'1'),
(962, '00011', '11@emt', '70c42a25ad84a151b263c32e446601db', 'user', NULL, b'1'),
(963, '00012', '12@emt', 'a04f8754a702d598ba267532c7229e9d', 'user', NULL, b'1'),
(964, '00013', '13@EMT', '643cae30898fb2d50ba01ba83485a3b4', 'user', NULL, b'1'),
(965, '00016', 'user@email.com', 'e83b7989a4c93a01dbdfd7126095c2d4', 'admin', NULL, b'0'),
(966, '00049', 'null', 'a564a848cdd0cffaf2bbfd4bb4b77780', 'user', NULL, b'1'),
(967, '00094', 'null', 'a20bc717dc651ef1bf07781ff80c3832', 'user', NULL, b'1'),
(968, '00096', '96@emt', '20f877299d608dcb563e680a57c09bb6', 'user', NULL, b'1'),
(969, '00109', '109@emt', 'c68325ee302e8bcf7fe40fa42ec3c411', 'user', NULL, b'1'),
(970, '00112', 'null', '24dc91bbdd4349ab6224771087b9198f', 'user', NULL, b'1'),
(971, '00113', 'null', '53884f706fc7be72ed2889e54fb53b7a', 'user', NULL, b'1'),
(972, '00117', 'null', '2f89808ff30ead4d7fad1190bbff8261', 'user', NULL, b'1'),
(973, '00128', 'null', '417ddb02bed45f0e78b3ae90088b8c03', 'user', NULL, b'1'),
(974, '00133', 'null', 'a6ba4b6ec78529d5f90cc62dd49d252d', 'user', NULL, b'1'),
(975, '00134', '134@emt', 'e5d580e08ba2a5e3afd1f5cdfb0a427f', 'user', NULL, b'1'),
(976, '00143', '143@emt', 'e5d672dceffc01b7230c3dcbdd597409', 'user', NULL, b'1'),
(977, '00144', 'null', 'c63cdfc6e97dc164eff89de45eb6a9e7', 'user', NULL, b'1'),
(978, '00148', 'null', 'd7b95c1969f30a1931e919c6bb1577e6', 'user', NULL, b'1'),
(979, '00167', 'abhilash@emt', 'd5ffa37d3045d6334a681767dc20f011', 'user', NULL, b'1'),
(980, '00168', 'null', 'ce8cd23074b86910ce96a0549a994e6a', 'user', NULL, b'0'),
(981, '00281', 'null', 'a73edfd070aa45cc036e0c9dad73c72f', 'user', NULL, b'1'),
(982, '00306', 'null', '1ca967d0f28b254e2954523419afad12', 'user', NULL, b'1'),
(983, '00307', 'null', '1ed29932f08214509ee792b0de98f53a', 'user', NULL, b'0'),
(984, '00310', 'null', '55cc3ea036a2f998e318984773e93f91', 'user', NULL, b'1'),
(985, '00315', 'null', '9377aeb0b8d941f81f2ee28423c06a1c', 'user', NULL, b'1'),
(986, '00316', 'null', '377d8787b1e267510d2b45835b90d718', 'user', NULL, b'1'),
(987, '00317', 'null', '72dc99fb1d53a74757c7e1b937b5c161', 'user', NULL, b'1'),
(988, '00318', '318@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(989, '00323', '323@emt', '25a6beff7446bb4afd34fc4f49d790e9', 'user', NULL, b'1'),
(990, '00324', 'null', '15b3191e0bc977abec7ef37302dd3761', 'user', NULL, b'1'),
(991, '00328', 'null', '45f58f2722a90226c263d093edc7d7d6', 'user', NULL, b'0'),
(992, '00334', 'null', 'd45d79f0cd366891bde6153798e84759', 'user', NULL, b'1'),
(993, '00355', 'null', '1729be47f81b9867ce79a323e911c129', 'user', NULL, b'1'),
(994, '00364', '364@EMT', 'b753c09c72c648e1a6122a1bb08c2ae8', 'user', NULL, b'1'),
(995, '00365', 'null', 'a7fe41338c6063fa714425389a9dead8', 'user', NULL, b'1'),
(996, '00393', '393@emt', 'fecd084ed9ff831eeec392ebb6668355', 'user', NULL, b'1'),
(997, '00446', 'emt@123', '4ab9d26a0c7c2a62c3a2573bc48d9105', 'user', NULL, b'1'),
(998, '00500', '500@emt', 'a2b69f67fed7a539fda899e8ddef7fd2', 'user', NULL, b'1'),
(999, '00504', 'null', '2c86348f33a2c3b785400de4cb6f79c5', 'user', NULL, b'1'),
(1000, '00505', 'null', '6e19e11c6f7396ad8de8bf4c383aea85', 'user', NULL, b'1'),
(1001, '00525', '525@emt', '7c4ead60f164cfec3b908c72cea9c9fe', 'user', NULL, b'1'),
(1002, '00526', 'null', '3431d1b3583a70d4d303855e01f95b76', 'user', NULL, b'1'),
(1003, '00549', 'null', '17497d0f7a4ccacbe5ad8873c1b449c3', 'user', NULL, b'1'),
(1004, '00599', '599@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(1005, '00621', 'null', '148abb88d7e2c69eeb67e1d83c060c4f', 'user', NULL, b'1'),
(1006, '00627', 'null', '3a8b5a12e0face94145623e42045f514', 'user', NULL, b'1'),
(1007, '00679', 'null', '2052797df02a26de24e87f9b6c72cdff', 'user', NULL, b'1'),
(1008, '00680', 'null', '35523bb262236e61157dd0fa1343f181', 'user', NULL, b'1'),
(1009, '00693', '693@emt', '623a5b43bce4b2de48f751073e7abbb9', 'user', NULL, b'1'),
(1010, '00698', '698@emt', 'a770de0608194e3b24252cd6a6a2767f', 'user', NULL, b'1'),
(1011, '00701', '701@emt', 'd71827030029f390f9c7bb382b68d499', 'user', NULL, b'1'),
(1012, '00704', 'null', 'f013d1697c4b2c1d46d1879b4f2e3fe9', 'user', NULL, b'1'),
(1013, '00705', 'null', '72b322d63daaa70e7bbb16bfab83a6ca', 'user', NULL, b'1'),
(1014, '00706', '706@emt', '7e11230542fbee1ba9e61dd052518279', 'user', NULL, b'1'),
(1015, '00708', '708@emt', 'd4618b99b2481d99bfbf60b6296dad6d', 'user', NULL, b'1'),
(1016, '00733', 'null', '487c52a0db58c90c9eb2db54635817af', 'user', NULL, b'1'),
(1017, '00744', '744@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'1'),
(1018, '00789', 'null', '5a0ef707214f974c97836959152a6897', 'user', NULL, b'1'),
(1019, '00790', 'null', 'a44bd39d22947c3ec07702e0cdc82501', 'user', NULL, b'1'),
(1020, '00810', 'null', '1f1bb1cfd906b2828a1e4b9a039fd347', 'user', NULL, b'1'),
(1021, '00815', 'null', 'd96b4ef35f8d142a57baec63071e5afa', 'user', NULL, b'1'),
(1022, '00836', 'null', '543822b9f581ba42c95d98817425335f', 'user', NULL, b'1'),
(1023, '00858', '858@emt', 'cd5ae965a099c62c6c70ffe18ff4e5a6', 'user', NULL, b'1'),
(1024, '00881', 'jebin@emt', '978926ca03bc579df556667fdcac2c24', 'user', NULL, b'1'),
(1025, '00882', '882@emt', '827ccb0eea8a706c4c34a16891f84e7b', 'user', NULL, b'0'),
(1026, '00883', 'null', 'f7e254bb3c0dd87c7b37583fef541c48', 'user', NULL, b'1'),
(1027, '00884', '884@emt', 'fbc93049cd9030feca8f6e628ff674d9', 'user', NULL, b'1'),
(1028, '00885', 'null', '82904779a2c6334e7b720a6cb723bb99', 'user', NULL, b'1'),
(1029, '00917', 'null', '097311281705542999c0c41e580bf7aa', 'user', NULL, b'1'),
(1030, '00918', 'null', 'cc54855f1837fae857da921a1cade486', 'user', NULL, b'1'),
(1031, '00929', 'null', '87ac9c6a4ea27630f1e85387da720841', 'user', NULL, b'1'),
(1032, '00952', '952@emt', '56103516f2672b47411a0c3c72971cb0', 'user', NULL, b'0'),
(1033, '00962', '962@emt', '3ca4de4eefacc274cb36cea243f1badf', 'user', NULL, b'1'),
(1034, '00974', 'null', '1568d5ef58159d99c07e059e17911505', 'user', NULL, b'1'),
(1035, '00975', '975@emt', '72b23493aeac9d24fc385579b743eb26', 'user', NULL, b'1'),
(1036, '00982', 'null', '0dbf74465406089ab78d2172be1653da', 'user', NULL, b'1'),
(1037, '00984', 'null', 'afa3cf8cb3325cd742221b5f6672eff9', 'user', NULL, b'1'),
(1038, '00996', 'null', '49c8a97c70bb8d70bcbf3b60faf8ba2f', 'user', NULL, b'1'),
(1039, '00997', 'null', '69aeefcc12491a48375b591ad2cb8f18', 'user', NULL, b'1'),
(1040, '01007', 'null', '3f5d76a5cedc1aa0af2ef5c3b1173efd', 'user', NULL, b'1'),
(1041, '01015', '1015@emt', '32d516f659fb3da2607fb55f8757e98c', 'user', NULL, b'1'),
(1042, '01022', 'null', '5763c89bae8ad9c859e6fa81e9befed4', 'user', NULL, b'1'),
(1043, '01030', 'null', 'a1159ff349c6b555cefc4b96484d1e60', 'user', NULL, b'1'),
(1044, '01058', 'null', '2f37ebd7df2b513c33877ac49cb63c07', 'user', NULL, b'1'),
(1045, '01078', '1078@emt', 'd0cb2c294c8d2e330ded2318da7ceceb', 'user', NULL, b'1'),
(1046, '01080', 'null', 'f9d93798ff9191b7a109a6e31c4a49b7', 'user', NULL, b'1'),
(1047, '01081', 'null', 'd5077dc37532a024afcec683d3e46fbd', 'user', NULL, b'1'),
(1048, '01092', 'null', '27a3497299b6968e48574c322044a266', 'user', NULL, b'1'),
(1049, '01094', 'null', 'd737b91b3576b1d2067d9a0287579073', 'user', NULL, b'1'),
(1050, '01182', 'null', '7ee93e088315e6a7ecf59963498c78d9', 'user', NULL, b'1'),
(1051, '01199', '1199@emt', '4ba665061a2434b475a2b2a0f25405eb', 'user', NULL, b'1'),
(1052, '01249', 'null', '1730572bdeb300487db02ee0ba85173d', 'user', NULL, b'1'),
(1053, '01261', 'null', '153b762d146c8ee6645d7ef817048597', 'user', NULL, b'1'),
(1054, '01268', 'null', 'c57ccb6f7cdd31ab7107b594fab3d076', 'user', NULL, b'1'),
(1055, '01271', 'null', '5c88d2de0cb3d5656b82af152f9ba53e', 'user', NULL, b'1'),
(1056, '01273', 'null', 'f4e8eca1d00f6d704efee6fbf4f1c8bd', 'user', NULL, b'1'),
(1057, '01277', 'sample@f.co', 'c8b6664921a91e0266faa476dac34f75', 'user', NULL, b'1'),
(1058, '01278', 'user@email.com', 'abcd52bfdb7b2a663be7c7d6da7ae0d6', 'user', NULL, b'1'),
(1059, '01280', 'null', 'ccc2b919dc3b2a2a5b6639db4aa8fa15', 'user', NULL, b'1'),
(1060, '01283', 'null', '9656c9b5170c373ca2cc36c9e8651748', 'user', NULL, b'1'),
(1061, '01297', 'null', '009606d76945292c239af520d28caf2b', 'user', NULL, b'1'),
(1062, '01298', 'null', 'f46a96ed18f8e73ba922e0bda604c655', 'user', NULL, b'1'),
(1063, '01301', '1301@emt', 'c7c52ae0a073e78d44236242647dd299', 'user', NULL, b'1'),
(1064, '01302', 'umar@emt', '4d72c3a17f5f2c4189590a598d43efda', 'user', NULL, b'1'),
(1065, '01303', 'null', 'e9a42a61cbd1436e415b484721b071dd', 'user', NULL, b'1'),
(1066, '01304', 'null', 'e851bfe33d1cb20fe290c41737ad12e1', 'user', NULL, b'1'),
(1067, '01305', 'null', '1168abfdcb526a5031a20f8b6c9aa77c', 'user', NULL, b'1'),
(1068, '01307', 'null', '11fc151ce936f6086924c52dd365361a', 'user', NULL, b'1'),
(1069, '01322', 'null', 'adfe1eeee8992f7ca50c77b989231c28', 'user', NULL, b'1'),
(1070, '01324', 'null', 'ef3cf7ac69a3be59b391e925b36d6cf8', 'user', NULL, b'1'),
(1071, '01330', '1330@EMT', '02e3243e6d293cbfb8eb2da63878693b', 'user', NULL, b'1'),
(1072, '01336', 'null', 'a6de05385567780400ff86482ecdc2eb', 'user', NULL, b'1'),
(1073, '01337', 'gigin@emtdubai.ae', '090f130803e2cdf388aedfca5d63942b', 'user', NULL, b'1'),
(1074, '01350', '1350@emt', 'becb7480751ed29fe364cddf8482ad33', 'user', NULL, b'1'),
(1075, '01340', NULL, 'e3bba3f0b06792a23936d1065b653466', 'user', NULL, b'0'),
(1076, '01341', NULL, '136206cc99cf10d97ab495885e3f697a', 'user', NULL, b'1'),
(1077, '01342', NULL, '136206cc99cf10d97ab495885e3f697a', 'user', NULL, b'1'),
(1078, '01344', NULL, '186cbcdd1fdf9a03fe4780243d07d587', 'user', NULL, b'1'),
(1079, '01345', NULL, '38d11b1ab4fe2d4c4a704a22a822f841', 'user', NULL, b'0'),
(1080, '01346', NULL, '0f260570401f3f9c50d0a129291a40f1', 'user', NULL, b'1'),
(1081, '01349', NULL, '506d1066283f2d98b0e80524bafaf9d3', 'user', NULL, b'1'),
(1082, '01354', NULL, '94cd21ad8f59ccb393930ab4bac9922d', 'user', NULL, b'1'),
(1083, '01355', NULL, '94cd21ad8f59ccb393930ab4bac9922d', 'user', NULL, b'0'),
(1084, '01356', NULL, '94cd21ad8f59ccb393930ab4bac9922d', 'user', NULL, b'1'),
(1085, '01357', NULL, '94cd21ad8f59ccb393930ab4bac9922d', 'user', NULL, b'1'),
(1086, '01358', '1358@emt', '94cd21ad8f59ccb393930ab4bac9922d', 'user', NULL, b'1'),
(1087, '01359', NULL, '94cd21ad8f59ccb393930ab4bac9922d', 'user', NULL, b'1'),
(1088, '01360', NULL, '94cd21ad8f59ccb393930ab4bac9922d', 'user', NULL, b'1'),
(1089, '01361', NULL, '94cd21ad8f59ccb393930ab4bac9922d', 'user', NULL, b'1'),
(1090, '01362', NULL, '06c32d9dd15f4c3d5297b9791ef554ff', 'user', NULL, b'1'),
(1091, '01369', NULL, 'dc3e90ac05dea39cb4e34c63684132e7', 'user', NULL, b'1'),
(1092, '01370', NULL, '360ac1b6ac25cd438083fa5fbfef105d', 'user', NULL, b'1'),
(1093, '01374', NULL, '1fdacf3d5f39773a926c188d073e43df', 'user', NULL, b'1'),
(1094, '01375', NULL, '6c86afc5ffba6b50fcf64e773cdc5505', 'user', NULL, b'1'),
(1095, '01377', NULL, '94cd21ad8f59ccb393930ab4bac9922d', 'user', NULL, b'1'),
(1096, '01380', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1097, '01381', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1098, '01382', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1099, '01383', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1100, '01384', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1101, '01385', NULL, '707eb72f50fd334a872e10ef07a165dc', 'user', NULL, b'1'),
(1102, '01387', NULL, '7c6da90c9e04df5992457f1b0493d3ca', 'user', NULL, b'0'),
(1103, '01388', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'0'),
(1104, '01389', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1105, '01391', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1106, '01392', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1107, '01393', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1108, '01394', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1109, '01395', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1110, '01396', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1111, '01397', NULL, 'ccc224ab7ddd09eadbb26d9d834c9533', 'user', NULL, b'1'),
(1112, '01398', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1113, '01399', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1114, '01400', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1115, '01401', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1116, '01406', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1117, '01407', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'1'),
(1118, '01408', NULL, 'ff03a96748f34f3b5ed557dc23a40062', 'user', NULL, b'0'),
(1119, '01409', NULL, '46821cf7ac3e3a60d8ebbf34318bfd2f', 'user', NULL, b'1'),
(1120, '01412', NULL, '3dd07402504f6132731746a954bf06fe', 'user', NULL, b'1'),
(1121, '01413', NULL, '510b51e6c534a308a13f2bffd01108e3', 'user', NULL, b'0'),
(1122, '01417', NULL, 'c3cf1796949750a49682cb0a2d848009', 'user', NULL, b'1'),
(1123, '01419', NULL, '14da9d11c1b9cd00aa9f886133104ba5', 'user', NULL, b'1'),
(1124, '01420', NULL, '529fbf87acfa18c1df625b5a8fbc5fda', 'user', NULL, b'1'),
(1125, '01423', NULL, 'a4bb4ffcccdbde9a733e33521a35084c', 'user', NULL, b'1'),
(1126, '01424', NULL, 'c218cac787436482bb2f851dfed96920', 'user', NULL, b'1'),
(1127, '01425', NULL, 'c3cf1796949750a49682cb0a2d848009', 'user', NULL, b'1'),
(1128, '01431', NULL, '6c043aa2bb63d25775a69c4be98eda2f', 'user', NULL, b'1'),
(1129, '01438', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'0'),
(1130, '01439', NULL, '3608d154ae575951a571dce724a88de5', 'user', NULL, b'1'),
(1131, '01440', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1132, '01441', NULL, '5466ab00c92847d6d188b82d10c7a1a0', 'user', NULL, b'1'),
(1133, '01442', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'0'),
(1134, '01443', NULL, '5466ab00c92847d6d188b82d10c7a1a0', 'user', NULL, b'1'),
(1135, '01444', NULL, '5466ab00c92847d6d188b82d10c7a1a0', 'user', NULL, b'1'),
(1136, '01445', NULL, '4fd2d5f79d7761ffc748241eea66da50', 'user', NULL, b'1'),
(1137, '01446', NULL, '9078f89a73b257a1ee14795e138a5f84', 'user', NULL, b'1'),
(1138, '01448', NULL, '9a612ea41de1eff99b91d02a9fb3ba0b', 'user', NULL, b'0'),
(1139, '01449', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1140, '01450', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1141, '01453', NULL, '0c92dbc645554a5d3bdf9717dfe3e8fc', 'user', NULL, b'1'),
(1142, '01454', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1143, '01455', NULL, '7bbcf24149d5995392449d4fa8406f91', 'user', NULL, b'0'),
(1144, '01456', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1145, '01457', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'0'),
(1146, '01458', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1147, '01459', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1148, '01460', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1149, '01461', NULL, '99d6e83190718918d518a39186287ad1', 'user', NULL, b'1'),
(1150, '01462', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1151, '01463', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1152, '01464', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1153, '01465', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1154, '01466', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1155, '01467', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1156, '01468', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1157, '01469', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1158, '01470', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1159, '01471', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1160, '01472', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1161, '01473', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1162, '01474', NULL, 'f02601f0d43aaa5b42e53bfd7635ec32', 'user', NULL, b'1'),
(1163, '01475', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1164, '01476', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1165, '01477', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1166, '01478', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1167, '01479', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1168, '01480', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1169, '01481', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1170, '01482', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1171, '01483', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1172, '01484', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1173, '01485', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1174, '01486', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1175, '01487', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1176, '01488', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1177, '01489', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1178, '01490', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1179, '01491', NULL, '01eaefaa395385347c15af62505d8302', 'user', NULL, b'1'),
(1180, '01492', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1181, '01493', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1182, '01494', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1183, '01495', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1184, '01497', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1185, '01498', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1186, '01499', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1187, '01500', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1188, '01501', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1189, '01502', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1190, '01503', NULL, '213fa0b010d2b70a0d8d0a27e42e2886', 'user', NULL, b'1'),
(1191, '01504', NULL, '95004f8fd7e0fcfeae63cd4988cedb16', 'user', NULL, b'1'),
(1192, '01505', NULL, '93121d36d512a2b923377a63a4ab8ccb', 'user', NULL, b'1'),
(1193, '01506', NULL, 'a4a71553f32a01ca3e220069002a91a0', 'user', NULL, b'1'),
(1194, '01507', NULL, '0b14de11d4590755574dd51a2572d274', 'user', NULL, b'1'),
(1195, '01508', NULL, 'b7b17c7232f336dd4cace53d435a4372', 'user', NULL, b'1'),
(1196, '01509', NULL, '95b8f48dfce2480fb38b3ca4cbde29b5', 'user', NULL, b'1'),
(1197, '01510', NULL, 'a4a71553f32a01ca3e220069002a91a0', 'user', NULL, b'1'),
(1198, '01511', NULL, 'a4a71553f32a01ca3e220069002a91a0', 'user', NULL, b'1'),
(1199, '01512', NULL, 'a4a71553f32a01ca3e220069002a91a0', 'user', NULL, b'1'),
(1200, '01513', NULL, '918c25b0faca4cac38e5aa8fd826e79b', 'user', NULL, b'1'),
(1201, '01514', NULL, '95b8f48dfce2480fb38b3ca4cbde29b5', 'user', NULL, b'1'),
(1202, '01515', NULL, '5466ab00c92847d6d188b82d10c7a1a0', 'user', NULL, b'1'),
(1203, '01516', NULL, 'a4a71553f32a01ca3e220069002a91a0', 'user', NULL, b'1'),
(1204, '01517', NULL, 'a4a71553f32a01ca3e220069002a91a0', 'user', NULL, b'1'),
(1205, '01518', NULL, '95b8f48dfce2480fb38b3ca4cbde29b5', 'user', NULL, b'1'),
(1206, '01519', NULL, '95b8f48dfce2480fb38b3ca4cbde29b5', 'user', NULL, b'1'),
(1207, '01520', NULL, '95b8f48dfce2480fb38b3ca4cbde29b5', 'user', NULL, b'0'),
(1208, '01521', NULL, '5466ab00c92847d6d188b82d10c7a1a0', 'user', NULL, b'1'),
(1209, '01522', NULL, 'a4a71553f32a01ca3e220069002a91a0', 'user', NULL, b'0'),
(1210, '01523', NULL, 'a4a71553f32a01ca3e220069002a91a0', 'user', NULL, b'1'),
(1211, '01524', NULL, '95b8f48dfce2480fb38b3ca4cbde29b5', 'user', NULL, b'1'),
(1212, '01525', NULL, '95b8f48dfce2480fb38b3ca4cbde29b5', 'user', NULL, b'1'),
(1213, '01526', NULL, 'a4a71553f32a01ca3e220069002a91a0', 'user', NULL, b'0'),
(1214, '01527', NULL, '95b8f48dfce2480fb38b3ca4cbde29b5', 'user', NULL, b'1'),
(1215, '01528', NULL, '95b8f48dfce2480fb38b3ca4cbde29b5', 'user', NULL, b'1'),
(1216, '01529', NULL, '6b9ffbbfadb4666a13fe170fb309b74a', 'user', NULL, b'1'),
(1217, '01530', NULL, 'a4beafcfd5691b54397c9f35755c5742', 'user', NULL, b'1'),
(1218, '01531', NULL, '5466ab00c92847d6d188b82d10c7a1a0', 'user', NULL, b'0'),
(1219, '01533', NULL, 'e123f540f185a0b806c8ea7bde39f7b6', 'user', NULL, b'0'),
(1220, '01534', NULL, '5466ab00c92847d6d188b82d10c7a1a0', 'user', NULL, b'1'),
(1221, '01535', NULL, '5466ab00c92847d6d188b82d10c7a1a0', 'user', NULL, b'1'),
(1222, '01536', NULL, '95b8f48dfce2480fb38b3ca4cbde29b5', 'user', NULL, b'1'),
(1223, '01537', NULL, 'a4a71553f32a01ca3e220069002a91a0', 'user', NULL, b'1'),
(1224, '01538', NULL, '72d20156f6b2225fa3f9110443d2a720', 'user', NULL, b'1'),
(1225, '01539', NULL, '72d20156f6b2225fa3f9110443d2a720', 'user', NULL, b'1'),
(1226, '01540', NULL, '72d20156f6b2225fa3f9110443d2a720', 'user', NULL, b'1'),
(1227, '01541', NULL, '72d20156f6b2225fa3f9110443d2a720', 'user', NULL, b'1'),
(1228, '01542', NULL, '72d20156f6b2225fa3f9110443d2a720', 'user', NULL, b'1'),
(1229, '01543', NULL, '9bc77cb275e2754a2c0dbc5677680179', 'user', NULL, b'0'),
(1230, '01544', NULL, '72d20156f6b2225fa3f9110443d2a720', 'user', NULL, b'1'),
(1231, '01545', NULL, '72d20156f6b2225fa3f9110443d2a720', 'user', NULL, b'1'),
(1232, '01546', NULL, '72d20156f6b2225fa3f9110443d2a720', 'user', NULL, b'1'),
(1233, '01547', NULL, '72d20156f6b2225fa3f9110443d2a720', 'user', NULL, b'0'),
(1234, '01548', NULL, '72d20156f6b2225fa3f9110443d2a720', 'user', NULL, b'1'),
(1235, '01549', NULL, '72d20156f6b2225fa3f9110443d2a720', 'user', NULL, b'1'),
(1236, '01550', NULL, '1e30a52e864d9c879fe64055ea79fcf2', 'user', NULL, b'1'),
(1237, '01551', NULL, '72d20156f6b2225fa3f9110443d2a720', 'user', NULL, b'1'),
(1238, '01552', NULL, 'e5cd7051b1a8dcfe704a0ecf9b400992', 'user', NULL, b'1'),
(1239, '01554', NULL, 'ab33828cbc02168c501b4011325edf7e', 'user', NULL, b'1'),
(1240, '01555', NULL, 'ab33828cbc02168c501b4011325edf7e', 'user', NULL, b'1'),
(1241, '01556', NULL, '95b8f48dfce2480fb38b3ca4cbde29b5', 'user', NULL, b'1'),
(1242, '01557', NULL, 'ab33828cbc02168c501b4011325edf7e', 'user', NULL, b'1'),
(1243, '01558', NULL, 'd7e0ed01e6da0dcacfabb7077e3d9ae3', 'user', NULL, b'1'),
(1244, '01561', NULL, 'd7e0ed01e6da0dcacfabb7077e3d9ae3', 'user', NULL, b'1'),
(1245, '01563', NULL, 'd7e0ed01e6da0dcacfabb7077e3d9ae3', 'user', NULL, b'1'),
(1246, '01567', NULL, 'd7e0ed01e6da0dcacfabb7077e3d9ae3', 'user', NULL, b'1'),
(1247, '01569', NULL, '72d20156f6b2225fa3f9110443d2a720', 'user', NULL, b'1'),
(1248, '01572', '1572@emt', 'ab33828cbc02168c501b4011325edf7e', 'user', NULL, b'0'),
(1249, '01573', NULL, 'ab33828cbc02168c501b4011325edf7e', 'user', NULL, b'1'),
(1250, '01574', NULL, 'ab33828cbc02168c501b4011325edf7e', 'user', NULL, b'1'),
(1251, '01575', NULL, 'ab33828cbc02168c501b4011325edf7e', 'user', NULL, b'1'),
(1252, '01576', NULL, 'ab33828cbc02168c501b4011325edf7e', 'user', NULL, b'1'),
(1253, '01577', NULL, 'ab33828cbc02168c501b4011325edf7e', 'user', NULL, b'1'),
(1254, '01578', NULL, 'ab33828cbc02168c501b4011325edf7e', 'user', NULL, b'1'),
(1255, '01579', NULL, '0c92dbc645554a5d3bdf9717dfe3e8fc', 'user', NULL, b'1'),
(1256, '01580', NULL, 'ab33828cbc02168c501b4011325edf7e', 'user', NULL, b'1'),
(1257, '01581', NULL, '8039bcfb3f4227ed71cbce9d763d58de', 'user', NULL, b'1'),
(1258, '01582', NULL, 'ab33828cbc02168c501b4011325edf7e', 'user', NULL, b'1'),
(1259, '01583', NULL, 'ab33828cbc02168c501b4011325edf7e', 'user', NULL, b'1'),
(1260, '01584', NULL, '72d20156f6b2225fa3f9110443d2a720', 'user', NULL, b'1'),
(1261, '01585', NULL, 'ab33828cbc02168c501b4011325edf7e', 'user', NULL, b'1'),
(1262, '01586', NULL, 'f03dced6c3901c56f8ce7947798e57c9', 'user', NULL, b'1'),
(1263, '01587', NULL, '8bb87bba36906420522426e5b9acedfb', 'user', NULL, b'1'),
(1264, '01588', NULL, '8bb87bba36906420522426e5b9acedfb', 'user', NULL, b'1'),
(1265, '01589', NULL, 'ab33828cbc02168c501b4011325edf7e', 'user', NULL, b'1'),
(1266, '01590', NULL, '72d20156f6b2225fa3f9110443d2a720', 'user', NULL, b'0'),
(1267, '01591', NULL, '58519edafffac13aede5002d0e344c37', 'user', NULL, b'1'),
(1268, '01592', NULL, 'f4a4ea7d1621cd1cbd45fe7ceb0dc407', 'user', NULL, b'1'),
(1269, '01593', NULL, 'f03dced6c3901c56f8ce7947798e57c9', 'user', NULL, b'1'),
(1270, '01594', NULL, 'f03dced6c3901c56f8ce7947798e57c9', 'user', NULL, b'1'),
(1271, '01595', NULL, 'f03dced6c3901c56f8ce7947798e57c9', 'user', NULL, b'1'),
(1272, '01596', NULL, 'f03dced6c3901c56f8ce7947798e57c9', 'user', NULL, b'1'),
(1273, '01597', NULL, 'f03dced6c3901c56f8ce7947798e57c9', 'user', NULL, b'1'),
(1274, '01598', NULL, 'a04b735477317b8795b3218a9256d202', 'user', NULL, b'1'),
(1275, '01599', NULL, '58519edafffac13aede5002d0e344c37', 'user', NULL, b'1'),
(1276, '01600', NULL, 'a07faafc4b6c1e54f361f592f1c6efcc', 'user', NULL, b'1'),
(1277, '01601', NULL, 'f03dced6c3901c56f8ce7947798e57c9', 'user', NULL, b'1'),
(1278, '01602', NULL, '72d20156f6b2225fa3f9110443d2a720', 'user', NULL, b'1'),
(1279, '01603', NULL, 'f03dced6c3901c56f8ce7947798e57c9', 'user', NULL, b'1'),
(1280, '01604', NULL, '2b3bffb263ec0707281adf300024d787', 'user', NULL, b'1'),
(1281, '01605', NULL, 'f03dced6c3901c56f8ce7947798e57c9', 'user', NULL, b'1'),
(1282, '01606', NULL, 'd7e0ed01e6da0dcacfabb7077e3d9ae3', 'user', NULL, b'1'),
(1283, '01607', NULL, 'd7e0ed01e6da0dcacfabb7077e3d9ae3', 'user', NULL, b'1'),
(1284, '01609', NULL, '0f58404a743d3c43fa807e9e90a2ad05', 'user', NULL, b'1'),
(1285, '01610', NULL, '58519edafffac13aede5002d0e344c37', 'user', NULL, b'1'),
(1286, '01611', NULL, 'f03dced6c3901c56f8ce7947798e57c9', 'user', NULL, b'1'),
(1287, '01612', NULL, 'f03dced6c3901c56f8ce7947798e57c9', 'user', NULL, b'1'),
(1288, '01613', NULL, '8bb87bba36906420522426e5b9acedfb', 'user', NULL, b'1'),
(1289, '01614', NULL, '8bb87bba36906420522426e5b9acedfb', 'user', NULL, b'1'),
(1290, '01615', NULL, '8bb87bba36906420522426e5b9acedfb', 'user', NULL, b'1'),
(1291, '01616', NULL, '8bb87bba36906420522426e5b9acedfb', 'user', NULL, b'1'),
(1292, '01617', NULL, '13c38410b128b945211077601175b1aa', 'user', NULL, b'1'),
(1293, '01618', NULL, '8bb87bba36906420522426e5b9acedfb', 'user', NULL, b'1'),
(1294, '01619', NULL, '8bb87bba36906420522426e5b9acedfb', 'user', NULL, b'1'),
(1295, '01620', NULL, '8bb87bba36906420522426e5b9acedfb', 'user', NULL, b'1'),
(1296, '01621', NULL, '8bb87bba36906420522426e5b9acedfb', 'user', NULL, b'1'),
(1297, '01622', NULL, 'f03dced6c3901c56f8ce7947798e57c9', 'user', NULL, b'1'),
(1298, '01623', NULL, '75d48091a0a47872b500d945dc977ae2', 'user', NULL, b'1'),
(1299, '01624', NULL, '75d48091a0a47872b500d945dc977ae2', 'user', NULL, b'0'),
(1300, '01625', NULL, '75d48091a0a47872b500d945dc977ae2', 'user', NULL, b'1'),
(1301, '01626', NULL, 'd7e0ed01e6da0dcacfabb7077e3d9ae3', 'user', NULL, b'1'),
(1302, '01627', NULL, '75d48091a0a47872b500d945dc977ae2', 'user', NULL, b'1');
INSERT INTO `users` (`USER_ID`, `USERNAME`, `USER_EMAIL`, `USER_PASSWORD`, `USERTYPE`, `Dept_Id`, `USER_STATUS`) VALUES
(1775, 'jeanette', NULL, '1f3870be274f6c49b3e31a0c6728957f', 'planning_eng', NULL, b'1'),
(1776, 'storemgr', NULL, 'a87732e5975ee4ebea47bd6406719dbd', 'storemgr', NULL, b'1'),
(1777, 'projectmgr', NULL, '45268901fecc3bf1e1fb9dcedb1c1ba7', 'proj_mgr', NULL, b'1'),
(1783, 'P0050PME', '', '5cca2c3d5a6a8d5179f400e8c54398e0', 'foreman', 1, b'1'),
(1784, 'P0050PMP', '', '89d351f6ca702073e9b907f81fe80708', 'foreman', 2, b'1'),
(1785, 'P0050PMHVAC', '', 'a1ec1270046dd6efddd18afb9bfe1489', 'foreman', 3, b'1'),
(1840, 'P0054-ELEC', '', 'c952e1fa034f46d27de217f123aecea3', 'foreman', 1, b'1'),
(1841, 'P0054-PLUM', '', 'af0a2616ce6d697f1fb408ddc8a20d94', 'foreman', 2, b'1'),
(1842, 'P0054-HVAC', '', 'b146090a9239b2c5843eeec62d1ca888', 'foreman', 3, b'1'),
(1861, 'P0055-PLUM', '', '42192ac2fa7b76f29ef430ba52a72329', 'foreman', 2, b'1'),
(1862, 'P0055-ELEC', '', '7e58b46de63e4efb26ec14fdc19cb1ae', 'foreman', 1, b'1'),
(1863, 'P0055-HVAC', '', '59e6c0a48e147df1288c2de589b91091', 'foreman', 3, b'1'),
(1967, 'jeanettep', 'jeanette@emtdubai.a e', '9d454810433d0f80f574e0f35ded4d30', 'purchase', NULL, b'1'),
(1980, 'jeanette@emtdubai.ae', '', '9ed2d376e04915d313411a67fa4c0129', 'company', NULL, b'1'),
(1985, 'salman@prernaconsultancy.com', NULL, 'ff0e7f1ac8cc4ac01f0cf1c0b3ca3bda', 'company', NULL, b'1'),
(1988, 'juzar@tajint.ae', NULL, '2723f18c0b0be47cc59ae911f117899a', 'company', NULL, b'1'),
(1989, 'office@pipelinktank.com', NULL, '6a72e4c8f31cf4aca6e330f32a0d93ed', 'company', NULL, b'1'),
(1996, 'shiv@lustre.ae', NULL, 'd1d7f67636de1417f47540ff28f97985', 'company', NULL, b'1'),
(1997, 'sales@pipecogroup.com', NULL, '03b98bbe9d9339d889cdf1872861277b', 'company', NULL, b'1'),
(1998, 'Sales1.babalmadina@gmail.com', NULL, 'a5b59e3e3474b86d7786eb92bd313a29', 'company', NULL, b'1'),
(1999, 'sales@mhifire.com', NULL, '7ad1c4ff6f04d8d9b729fa65ab4adf9a', 'company', NULL, b'1'),
(2001, 'saji@phoenixtrio.ae', NULL, 'da0ded6203a7fec9ecd658b0dea74143', 'company', NULL, b'1'),
(2002, 'mae@aswar-me.com', NULL, '2a7fb0e100344f78b748aa48e79ccaae', 'company', NULL, b'1'),
(2004, 'farahintldxb@gmail.com', NULL, '66f0bb8c043d4d6e2856edb7d390ce39', 'company', NULL, b'1'),
(2005, 'rakesh@edge-ts.net', NULL, 'd230207fdfdff708ac2e4c28220275ab', 'company', NULL, b'1'),
(2006, 'info@simtechgulf.ae', NULL, '6548c4f177f540d8e59a1b6a1f3f4232', 'company', NULL, b'1'),
(2009, 'shebisheiref@gmail.com', NULL, '53f918fd698c818d8274ef11bb47b2ad', 'company', NULL, b'1'),
(2010, 'h.abbas@royalgas.ae', NULL, '42c4a0522d8dd37532bffa049d4f4763', 'company', NULL, b'1'),
(2011, 'Sales@smartgasuae.com', NULL, 'dc82412b166bced324e5f31bf6f09686', 'company', NULL, b'1'),
(2012, 'fvmc.sales1@gmail.com', NULL, '0ba36f417d94a579d47a8ed2fb03fd98', 'company', NULL, b'1'),
(2013, 'saftywld@emirates.net.ae', NULL, '6c4ae80649e88e8c614444cf8fe3f6fa', 'company', NULL, b'1'),
(2015, 'sheen@gulfirevision.com', NULL, '393bda2cabd19b9d173f9cba39bfff2d', 'company', NULL, b'1'),
(2016, 'info@sensortechuae.com', NULL, '883667a9f1d5c2e112f4d0e3c1c2e854', 'company', NULL, b'1'),
(2017, 'shirin@tsmart.ae', NULL, 'fc083c1dc774a2a54fe58ee184a60c38', 'company', NULL, b'1'),
(2018, 'shahbazkhan@rakbmcs.com', NULL, '', 'company', NULL, b'1'),
(2019, 'camyin@tychegulf.com', NULL, 'b275f39d5ce33e3377e84258af91624b', 'company', NULL, b'1'),
(2020, 'mbmt56452@hotmail.com', NULL, 'c1d950d9c82ea6b766211739a2bec8f2', 'company', NULL, b'1'),
(2023, 'hafeez@shoebgroup.com', NULL, '048ac592b118b63017198cfba645eb88', 'company', NULL, b'1'),
(2024, 'arun@arabfalconuae.com', NULL, '0e0a0a4bb73040aed20d4c026cff5a59', 'company', NULL, b'1'),
(2025, 'salescoordinator@mafsafety.ae', NULL, '7d59992cb2198ff95310130af8620fd4', 'company', NULL, b'1'),
(2026, 'furqan@pioneertravels.net', NULL, '4ae7f24b953230ab3da1cb6a1ce68040', 'company', NULL, b'1'),
(2027, 'ashik.m@fosterintl.com', NULL, 'fc8a9c6f6dd5cb3ec1c0b65094dd35dd', 'company', NULL, b'1'),
(2028, 'info@petrosafeme.com', NULL, '77456cd3c47636b4fd5e6493e3e83e61', 'company', NULL, b'1'),
(2029, 'marco@jballosewage.com', NULL, '9de68b84ff36a93108a3a20f27f74ed0', 'company', NULL, b'1'),
(2030, 'majd@taqweem.ae', NULL, 'f4fd3bb352e340c61f52a724a2f08d9a', 'company', NULL, b'1'),
(2031, 'pankaj@dezireconsultants.com', NULL, 'ae8155d0bc6ea5132ad7acb0d46f61ff', 'company', NULL, b'1'),
(2033, 'crm@solarhrm.com', NULL, 'e18f8b565e79db5dba9be57168085639', 'company', NULL, b'1'),
(2034, 'navin@aldawamllc.com', NULL, '52217e655135de2d9966789d82faea86', 'company', NULL, b'1'),
(2035, 'shajaratzaytoon@gmail.com', NULL, '9c4d34c36876b5ba4c0102c602e0da20', 'company', NULL, b'1'),
(2036, 'shajaratzaytoon@gmail.com', NULL, '9c4d34c36876b5ba4c0102c602e0da20', 'company', NULL, b'1'),
(2037, 'shajaratzaytoon@gmail.com', NULL, '9c4d34c36876b5ba4c0102c602e0da20', 'company', NULL, b'1'),
(2038, 'waqas@elroytravels.com', NULL, 'c19407b30e9ec1677d3655b051b53199', 'company', NULL, b'1'),
(2039, 'babfranc@yahoo.com', NULL, '305679f718225a2aac5d74bdd39e2aa0', 'company', NULL, b'1'),
(2040, 'lobo@lmltechnicalservices.com', NULL, '38c86c6b48efece5903a891e0a18b6d3', 'company', NULL, b'1'),
(2041, 'dreambridgetech@gmail.com', NULL, 'eed891d425c4ed61c39a4920ffb410d5', 'company', NULL, b'1'),
(2042, 'vikas@mastersinternation.com', NULL, '3347d2dbd76d33765aa790c9ec5bd45e', 'company', NULL, b'1'),
(2043, 'najah', 'najah @emtdubai.ae', '57028c63d4c2cd9cc47422e051cd1c19', 'purchase', NULL, b'1'),
(2044, 'info@qusoorbeaut.com', NULL, '34c264e1048fa5cd45836ca55636f3d2', 'company', NULL, b'1'),
(2045, 'danish.rabbani@stallion-intl.com', NULL, 'be38b100b4c0c2178c92f46ce662bc91', 'company', NULL, b'1'),
(2046, 'marketing@kannanenterprises.com', NULL, 'd34ef8e78380aaf8a6bee19a05d67855', 'company', NULL, b'1'),
(2047, 'thehubgroup1@gmail.com', NULL, '888ec581a23289a971511c803908e0c0', 'company', NULL, b'1'),
(2048, 'manpower.reliance@gmail.com', NULL, 'b6648a3ecf653ee49a2251cec1f9d95a', 'company', NULL, b'1'),
(2049, 'mainstarllc@gmail.com', NULL, 'b6fa61150d773a3b72a83e9a242b543c', 'company', NULL, b'1'),
(2050, 'fssales5@triolight.ae', NULL, '21a4468d30ea0e4cf1acd1348230f88f', 'company', NULL, b'1'),
(2051, 'nadim@rafcouae.com', NULL, '97d8236763c82481037d1960cb602d1e', 'company', NULL, b'1'),
(2052, 'arif@nojoom.ae', NULL, 'b18c676495a0d995d4387d2702251ddf', 'company', NULL, b'1'),
(2053, 'safeer@cmatuae.com', NULL, '4315af5208cea3f1db99b670d1600250', 'company', NULL, b'1'),
(2054, 'sales@vitsllc.com', NULL, '928a69b2fb3fef3a33099edec8419fd6', 'company', NULL, b'1'),
(2056, 'aladantechnical388@gmail.com', NULL, 'ae691be6e8b92219c38a73a6d26f67db', 'company', NULL, b'1'),
(2057, 'rahuldak23@gmail.com', NULL, '5f10e799c24f734b0e01f2da45800b9b', 'company', NULL, b'1'),
(2058, 'adeel@candorz.com', NULL, '6db9c7c2b9eca64704c83b2c0b514a15', 'company', NULL, b'1'),
(2063, 'midwaystarsales@gmail.com', NULL, '25d55ad283aa400af464c76d713c07ad', 'company', NULL, b'1'),
(2064, 'adars.k@wintec-is.com', NULL, '0c53183d8bab500fe57144432b9e6389', 'company', NULL, b'1'),
(2065, 'rafique.sons@gmail.com', NULL, 'fd663b8b08b461adb8d06525b395b2a0', 'company', NULL, b'1'),
(2066, 'rafique.sons@gmail.com', NULL, 'fd663b8b08b461adb8d06525b395b2a0', 'company', NULL, b'1'),
(2067, 'madinatjalfar@gmail.com', NULL, '6ee6d36c52e3b07e8140c9dce5ddd86e', 'company', NULL, b'1'),
(2068, 'wardatalsabah149406@gmail.com', NULL, 'c8f79568caa3c5809e6992d37cf136a7', 'company', NULL, b'1'),
(2069, 'jasauae23@gmail.com', NULL, 'e3e12ef5bae0e8a114ddccf5ad991133', 'company', NULL, b'1'),
(2070, 'meeran@snges.com', NULL, '9d680baa8789144e2c96bff4da7a0f7f', 'company', NULL, b'1'),
(2071, 'info@technorb.com', NULL, '7e2ecdf6efd52f954e6b21996de8576d', 'company', NULL, b'1'),
(2072, 'hatim987@gmail.com', NULL, '2a5fb2d904f2de4091249a4d912b6dcd', 'company', NULL, b'1'),
(2073, 'alnehdaelcetro23@gmail.com', NULL, '8d5d8a192656e75f4f3a34eb39bc399d', 'company', NULL, b'1'),
(2074, 'sajeesh@fmbtrading.com', NULL, '90433a7870737838b6838ba45a5a6ca0', 'company', NULL, b'1'),
(2075, 'sales@alsumaa.com', NULL, 'ab075f6205f5627abfd03fa7f3fc86a5', 'company', NULL, b'1'),
(2076, 'thahir.m.@pixel-org.com', NULL, '50837f09a1b7f14f2c6b7fd398d414a4', 'company', NULL, b'1'),
(2078, 'mohamed.thahir@pixel-digital.com', NULL, '50837f09a1b7f14f2c6b7fd398d414a4', 'company', NULL, b'1'),
(2079, 'salesteam@rototechrms.com', NULL, 'd8f401bc84d76923650fa273168419c8', 'company', NULL, b'1'),
(2080, 'imec@emirates.net.ae', NULL, '07e149c648a463e105d84ab6b9d2eb57', 'company', NULL, b'1'),
(2081, 'elias@alfuad.ae', NULL, '683dc798a3989aec889f8bcb99e8d76c', 'company', NULL, b'1'),
(2082, 'yusuf@arcade1.ae', NULL, '18fb165d55bb3be27f9a792a38d0a4d2', 'company', NULL, b'1'),
(2083, 'lloyd.tom@ae.centiel.com', NULL, '59818c3de5d0485a572c8200df3fae65', 'company', NULL, b'1'),
(2086, 'syedniyas@tcstech.me', NULL, 'e90f14bb081cc39f7c6ffe36b6252959', 'company', NULL, b'1'),
(2089, 'info@cloudfiveme.com', NULL, '11062f57bfa1fb58d6ceb93e107607b3', 'company', NULL, b'1'),
(2091, 'ratheesh.valayanad@deltaww.com', NULL, '3572f5c4ab35bef7e6320295bee975eb', 'company', NULL, b'1'),
(2092, 'sales6@aitsgulf.com', NULL, 'f207eed6d0b0073410a3b87063de6b7b', 'company', NULL, b'1'),
(2093, 'abdullah.raihan@energia.ae', NULL, '50f8a85f2fb876f138b09e95dd88fb49', 'company', NULL, b'1'),
(2094, 'alsaqar.alramadi@gmail.com', NULL, '', 'company', NULL, b'1'),
(2095, 'aljabalelectromechanical@gmail.com', NULL, '79053915c59417933701a3cd0205307c', 'company', NULL, b'1'),
(2096, 'ta_5522@hotmail.com', NULL, 'f229b89ce246852060b50b6805ea3219', 'company', NULL, b'1'),
(2097, 'ta_5522@hotmail.com', NULL, 'f229b89ce246852060b50b6805ea3219', 'company', NULL, b'1'),
(2098, 'samson@avalonnetworks.com', NULL, 'b384b6c8ec5bb2ea781986ed28f063e4', 'company', NULL, b'1'),
(2100, 'bdstartech@gmail.com', NULL, 'b8ff5574e900a2302b3a39138e6dd2e7', 'company', NULL, b'1'),
(2101, 'standardllc@gmail.com', NULL, '09da4807d902e139e89b80e6cd3e8fd5', 'company', NULL, b'1'),
(2102, 'enquiry.zaltec@gmail.com', NULL, 'e7896ed26cd975d21f380fed08072af3', 'company', NULL, b'1'),
(2103, 'info@htcuae.com', NULL, '27a9b0225019840f917d97b74c1f8a76', 'company', NULL, b'1'),
(2104, 'neeraj.albateel@gmail.com', NULL, 'e73efee274e35cd0f133624774d16006', 'company', NULL, b'1'),
(2108, 'anjie', 'angielyn @emtdubai.ae', '35196d33e815007121dc161e9563803e', 'purchase', NULL, b'1'),
(2110, 'sales@saig.me', NULL, 'b51b6a64020a185b18b164cafe2649d3', 'company', NULL, b'1'),
(2111, 'SHAHBAZACCC@GMAIL.COM', NULL, '8d0eaddc7e0cdf7dca0f715981591516', 'company', NULL, b'1'),
(2112, 'mahmoud.saad@jedi-me.com', NULL, '83aa8482d33baa622820d8c2fd0949a4', 'company', NULL, b'1'),
(2113, 'sales@altawareqt.com', NULL, 'd72488e600ea5b4ca133a97e1fe4cdc3', 'company', NULL, b'1'),
(2114, 'alanwarelec53@gmail.com', NULL, '87f7b1dbf47456f573c0ae754eafb377', 'company', NULL, b'1'),
(2115, 'maliksaeed66@yahoo.com', NULL, '0a4f736c798abded9bba6d163373f02a', 'company', NULL, b'1'),
(2116, 'stop_fire_uae@yahoo.com', NULL, 'b554129d7d715518145ee03e501195e9', 'company', NULL, b'1'),
(2117, 'sales01@agdubai.com', NULL, '7ff409c1b334749c550e9ab7324b4eb4', 'company', NULL, b'1'),
(2118, 'shahul.modernvision@gmail.com', NULL, 'f2c266e90605f5366c6178647aa1b183', 'company', NULL, b'1'),
(2119, 'tanveer.hasan@awrostamani.com', NULL, '6bc29c0f1bc52db8e099a5e0a4af7288', 'company', NULL, b'1'),
(2120, 'raja@evergreenegi.com', NULL, 'c77b14308da147eb0a468871b27e1c60', 'company', NULL, b'1'),
(2122, 'amit@smartsensesupplies.me', NULL, 'e10adc3949ba59abbe56e057f20f883e', 'company', NULL, b'1'),
(2123, 'quality@genuinesafety.net', NULL, 'c993ca895619ad6960fc2d3d3abb6b67', 'company', NULL, b'1'),
(2124, 'friendme@emirates.net.ae', NULL, '857e3d16438fcc29bce20207e7295a77', 'company', NULL, b'1'),
(2125, 'arun@spectrawll.com', NULL, 'e8aff9406f85e7908f6f61d86eeadff0', 'company', NULL, b'1'),
(2126, 'sadaf@derbygroup.ae', NULL, '706302fe13dad5b75d110b015dd52a71', 'company', NULL, b'1'),
(2127, 'ask@august75.com', NULL, '751478a2caf2267f7abcb00f8b12d21e', 'company', NULL, b'1'),
(2128, 'info@regeny.ae', NULL, '10c481517e78c236bf4c86525f7e9dfd', 'company', NULL, b'1'),
(2129, 'inquiry@tradealdhabi.com', NULL, 'f206060250b59b6e8d5007e8307329f0', 'company', NULL, b'1'),
(2132, 'atlantis.roadtransport@gmail.com', NULL, 'b19763c28bc01665d50ee024d8744933', 'company', NULL, b'1'),
(2133, 'clientservice@euroemirates.com', NULL, 'b59f34586139cb1d3a1559d6618e5ffc', 'company', NULL, b'1'),
(2134, 'info@c4s.ae', NULL, '057f31156d47822b139bf84b0085a0e2', 'company', NULL, b'1'),
(2135, 'jose.p@cdtme.com', NULL, 'd1f6e44dc744b4216744da3391747ff3', 'company', NULL, b'1'),
(2136, 'sales2@gelectricme.com', NULL, '0b0823c7fcbd4d9f7a50a70dac42070b', 'company', NULL, b'1'),
(2140, 'izaz@ultimixbuildingmaterials.com', NULL, '9af1a1cce34592d4746b5a38cb343445', 'company', NULL, b'1'),
(2141, 'sales.rmm@live.com', NULL, '94f53fed1e22ab8808c1d1736e66cc8e', 'company', NULL, b'1'),
(2144, 'Jamsheed@mechoninternational.com', NULL, '302d2e3d2246197dccbd79038a4b62dc', 'company', NULL, b'1'),
(2145, 'admin@seis.ae', NULL, 'e3e9e75d7d12566b6fe217b97e5c1d37', 'company', NULL, b'1'),
(2146, 'info@watertecs.com', NULL, '39264f787cb933091ee354f547a74182', 'company', NULL, b'1'),
(2147, 'helpdesk@rakbmcs.com', NULL, 'aee73fbcb434f2bc157f7c2d5939bcfc', 'company', NULL, b'1'),
(2150, 'cbm@greenwts.com', NULL, 'b66b9ca9ee6efd503735e7a4870278e7', 'company', NULL, b'1'),
(2152, 'sales4@grit-overseas.com', NULL, '0047ed7daf52bdf942167b7fa1fe67ff', 'company', NULL, b'1'),
(2153, 'saqafalmadina@gmail.com', NULL, '8819b149fde9ec2309fa9156fdf9db6a', 'company', NULL, b'1'),
(2154, 'fvmc.gm@gmail.com', NULL, '34ef853c13e6c57a647ddc5d34b59af0', 'company', NULL, b'1'),
(2155, 'kashif@aim-asia.net', NULL, 'f2fb81dd36917affd2608021b5c2f73c', 'company', NULL, b'1'),
(2156, 'dgm@gammaff.com', NULL, '03ef244b335d0a4204c2a0f1cd6942f1', 'company', NULL, b'1'),
(2157, 'sales@dec-equipment.com', NULL, 'ab5f2a29f0035d0e05910e2264e330df', 'company', NULL, b'1'),
(2191, 'royalsonsae@gmail.com', NULL, '987000c32c11a3c26b46a37f27fbada0', 'company', NULL, b'1'),
(2192, 'P0062', '', '47f23de496f151f5a6cfb37d3f38a1d2', 'foreman', 1, b'1'),
(2193, '00083', NULL, '164da1c26da4ad8540904eb37363b13e', 'user', NULL, b'1'),
(2194, '00083', NULL, 'a0d83b7fc398a706b9825e8111640137', 'user', NULL, b'1'),
(2195, '00967', NULL, '6f474845a53e90bf22e6da8cbc841b76', 'user', NULL, b'1'),
(2196, '01226', NULL, '2e6f7c5f1c72c544940d9b9f7eb7004b', 'user', NULL, b'1'),
(2197, '01266', NULL, '612dca34aae4b7e736682670bc179bb8', 'user', NULL, b'1'),
(2198, '01312', NULL, '27beee71c53b2825d9afb4653fea0e96', 'user', NULL, b'1'),
(2199, '01361', NULL, 'b2eb350bb29f7a215a83a742a4ac4fb3', 'user', NULL, b'1'),
(2200, '01388', NULL, 'e478a74d695731e3bed43cbf49a61f69', 'user', NULL, b'1'),
(2201, '01408', NULL, '8430011945072b80c277f328b2292513', 'user', NULL, b'1'),
(2202, '01438', NULL, '8faee7aaf2fd395f04fb8c59fdc91a74', 'user', NULL, b'1'),
(2203, '01609', NULL, 'd933502164fb5555bb9d392403995377', 'user', NULL, b'1'),
(2204, '01789', NULL, '03b5dd8ba38739d007ddf7c747180bd3', 'user', NULL, b'1'),
(2205, '01802', NULL, '27ebc8d7c30d3212013e05957d949b88', 'user', NULL, b'1'),
(2206, '01912', NULL, '76f380af9589acb9b206cfc5b4fb331b', 'user', NULL, b'1'),
(2207, '01914', NULL, '956a760b0a828aa69cdbd94732360a14', 'user', NULL, b'1'),
(2208, '02150', NULL, '3864a3c944ed91043744fc2d77721304', 'user', NULL, b'1'),
(2209, '02171', NULL, '06c32d9dd15f4c3d5297b9791ef554ff', 'user', NULL, b'1'),
(2210, '02174', NULL, '297c132fb5ba090ec81c12e8badf0928', 'user', NULL, b'1'),
(2211, '02175', NULL, 'dc4a9b87d0a57a80661d5f250516d132', 'user', NULL, b'1'),
(2212, '02176', NULL, '7f698fcf686e9ec2fb9289d665b9a130', 'user', NULL, b'1'),
(2213, '02186', NULL, '17cc4b52e17883a3e144376141452590', 'user', NULL, b'1'),
(2214, '02190', NULL, '85abb2461b8b3d9eca1546a46754a491', 'user', NULL, b'1'),
(2215, '02194', NULL, 'fa0163eb704e8017ea895a92e1b22bf7', 'user', NULL, b'1'),
(2216, '02198', NULL, '1069cc42224575eb837263e564c929f9', 'user', NULL, b'1'),
(2217, '02200', NULL, 'd8e059b823e071a489c6ef21005676d8', 'user', NULL, b'1'),
(2218, '02202', NULL, '7310e24f2da3fbc3362f7d39fd573e51', 'user', NULL, b'1'),
(2219, '02203', NULL, 'f6d9255f32bcb58c7755894a7c8548d2', 'user', NULL, b'1'),
(2220, '02205', NULL, '707eb72f50fd334a872e10ef07a165dc', 'user', NULL, b'1'),
(2221, '02207', NULL, '7c6da90c9e04df5992457f1b0493d3ca', 'user', NULL, b'1'),
(2222, '02208', NULL, '0a2eb763812f0e3def809212a41f60e7', 'user', NULL, b'1'),
(2223, '02209', NULL, 'fde4893107e370e5fe24d8fbd2c8d6f2', 'user', NULL, b'1'),
(2224, '02212', NULL, 'd3558653759acb8d539617c99527feff', 'user', NULL, b'1'),
(2225, '02213', NULL, 'c08744e28fe4cd120117167df046e63f', 'user', NULL, b'1'),
(2226, '02215', NULL, '43f314ece91736bf9a13312924d6204e', 'user', NULL, b'1'),
(2227, '02216', NULL, '3b67465742e35c707a463bbe57078ec4', 'user', NULL, b'1'),
(2228, '02217', NULL, 'a7a3ce8bb9eaeaabdccc2d68de7ee1ec', 'user', NULL, b'1'),
(2229, '02218', NULL, 'a6f552ff54e5dfad8e4536d24370576f', 'user', NULL, b'1'),
(2230, '02220', NULL, 'ccc224ab7ddd09eadbb26d9d834c9533', 'user', NULL, b'1'),
(2231, '02221', NULL, '7708b607df5cc124e9057138063f4b5d', 'user', NULL, b'1'),
(2232, '02222', NULL, '3297118a2ac8c9e2bb169c94b7f07d40', 'user', NULL, b'1'),
(2233, '02223', NULL, 'b13f23fe3ec0faa34bd29e627d2b56dd', 'user', NULL, b'1'),
(2234, '02235', NULL, '1d135ae9f5256599db55f57944085e5d', 'user', NULL, b'1'),
(2235, '02245', NULL, 'bcd4b35779cad664d1cdd298a706ac0e', 'user', NULL, b'1'),
(2236, '02246', NULL, '5c2d63a9e486db11e6c4ee2fb4e3a187', 'user', NULL, b'1'),
(2237, '02247', NULL, 'c8a2c6e313f52408492713c5f4eb0c2f', 'user', NULL, b'1'),
(2238, '02248', NULL, '46821cf7ac3e3a60d8ebbf34318bfd2f', 'user', NULL, b'1'),
(2239, '02251', NULL, '5f13a0084b5c25747b343f73157e226f', 'user', NULL, b'1'),
(2240, '02252', NULL, '510b51e6c534a308a13f2bffd01108e3', 'user', NULL, b'1'),
(2241, '02253', NULL, '7314a3a82fa3c06a3c9306a932026d44', 'user', NULL, b'1'),
(2242, '02256', NULL, 'da7c9a11dff2895396aa6ce100f79f15', 'user', NULL, b'1'),
(2243, '02257', NULL, '82de66cb56ad6f30733437fb64906f83', 'user', NULL, b'1'),
(2244, '02258', NULL, 'a4bb4ffcccdbde9a733e33521a35084c', 'user', NULL, b'1'),
(2245, '02260', NULL, 'c218cac787436482bb2f851dfed96920', 'user', NULL, b'1'),
(2246, '02261', NULL, '1ae891ae0840f330ed74f3c4fb0f942a', 'user', NULL, b'1'),
(2247, '02264', NULL, '454b87b5162614bcc7819e5fb8945906', 'user', NULL, b'1'),
(2248, '02265', NULL, 'ddea1ed9cf0fadc060efcf045e221053', 'user', NULL, b'1'),
(2249, '02266', NULL, '3608d154ae575951a571dce724a88de5', 'user', NULL, b'1'),
(2250, '02267', NULL, '4d07f0d92183e3ce4656c4ffe1010ec4', 'user', NULL, b'1'),
(2251, '02268', NULL, '77eaa6017c33c73d9112fe1895c07241', 'user', NULL, b'1'),
(2252, '02270', NULL, '07b36c1d03faf1b82628c05f2d111430', 'user', NULL, b'1'),
(2253, '02271', NULL, '0bf76615dc31e5be88871b2cfbe1fcf8', 'user', NULL, b'1'),
(2254, '02272', NULL, 'd05ecf5c01503e0e6d14425ac90338b5', 'user', NULL, b'1'),
(2255, '02274', NULL, '2ffda92265a238ae57ff29822a2052a5', 'user', NULL, b'1'),
(2256, '02275', NULL, '87dfb10d1ae3074f839766141df984a1', 'user', NULL, b'1'),
(2257, '02276', NULL, 'fdd8121bc177760c5e77578de66503ab', 'user', NULL, b'1'),
(2258, '02277', NULL, '9d36dc324665969f11e8ebfebd0cdf8e', 'user', NULL, b'1'),
(2259, '02279', NULL, '31565e3f47f236fe6eaf37e5dc2966bb', 'user', NULL, b'1'),
(2260, '02280', NULL, '40ef927d38ee8a8f4ae7431c6018d342', 'user', NULL, b'1'),
(2261, '02281', NULL, '2375f5ab808e896a0bc78c5c85663645', 'user', NULL, b'1'),
(2262, '02282', NULL, '7bbcf24149d5995392449d4fa8406f91', 'user', NULL, b'1'),
(2263, '02283', NULL, 'a87e33202baa524952ba901089245f2f', 'user', NULL, b'1'),
(2264, '02284', NULL, 'd9bd79223a2017d3017ad1c344488d62', 'user', NULL, b'1'),
(2265, '02285', NULL, 'c10788506533c007f88a3239ace206e7', 'user', NULL, b'1'),
(2266, '02286', NULL, 'b1a3d15600fc2b8ca1db13ab9058abc4', 'user', NULL, b'1'),
(2267, '02287', NULL, 'eae74ff39cdf54f4f68631892a4e10d0', 'user', NULL, b'1'),
(2268, '02291', NULL, '99d6e83190718918d518a39186287ad1', 'user', NULL, b'1'),
(2269, '02292', NULL, '795df73d85d9a97e3cf2ac05ae55d40a', 'user', NULL, b'1'),
(2270, '02293', NULL, '2b4db2da24f726aafcd35ef7a40e25e6', 'user', NULL, b'1'),
(2271, '02294', NULL, '9b273f1dece83556d62de7998ea81c60', 'user', NULL, b'1'),
(2272, '02295', NULL, '004f1702e4282eb0be0c4581f8474b24', 'user', NULL, b'1'),
(2273, '02296', NULL, '197121215947ba775524ff80f83a3192', 'user', NULL, b'1'),
(2274, '02297', NULL, '2349b9a167a1dcae0fd2d092e5087c38', 'user', NULL, b'1'),
(2275, '02298', NULL, '7a7d8c007a938196b703bb173cbc989d', 'user', NULL, b'1'),
(2276, '02299', NULL, '2420adcd7cb15af113d6ed6517ecaa02', 'user', NULL, b'1'),
(2277, '02300', NULL, '3e71834af57fdfa60809f69e60ce7c96', 'user', NULL, b'1'),
(2278, '02301', NULL, '350e793965da206539cefc3e231e8280', 'user', NULL, b'1'),
(2279, '02302', NULL, '2444a7b00b6521892b36cbd6fd2a44e4', 'user', NULL, b'1'),
(2280, '02303', NULL, '591441a06c551a275b07b842b38b4dd9', 'user', NULL, b'1'),
(2281, '02304', NULL, 'f02601f0d43aaa5b42e53bfd7635ec32', 'user', NULL, b'1'),
(2282, '02305', NULL, '71e0905aade37758aa2b92dbdcf8fdc2', 'user', NULL, b'1'),
(2283, '02306', NULL, '71b72ccf195672f8b503f2dced8c96d0', 'user', NULL, b'1'),
(2284, '02307', NULL, '3dfa1a609e40e0362fab020807ab322f', 'user', NULL, b'1'),
(2285, '02308', NULL, '2537542cbefda98b0e1fb0936dcd898e', 'user', NULL, b'1'),
(2286, '02309', NULL, 'b753a93c53ca57bccc2af7e6c17e266d', 'user', NULL, b'1'),
(2287, '02310', NULL, '7a0ba27f267804704e940e02e568b176', 'user', NULL, b'1'),
(2288, '02311', NULL, 'ba6dc7e1279423cabc85f858185ce2ea', 'user', NULL, b'1'),
(2289, '02312', NULL, 'bdc965a11878bc88db05174e6c6c5252', 'user', NULL, b'1'),
(2290, '02313', NULL, '6da9e0a106a3e82a83b22a0b6370d27b', 'user', NULL, b'1'),
(2291, '02314', NULL, 'ab9fc8bd0cc24ce1f6ddeabc1edd62cd', 'user', NULL, b'1'),
(2292, '02315', NULL, '42afac68aaea1dcd28970bd5d8f6b261', 'user', NULL, b'1'),
(2293, '02316', NULL, '1f3ddcda962e308b0ae0763d8e919b6e', 'user', NULL, b'1'),
(2294, '02317', NULL, '168235185e0bca5a76b219a40229bc76', 'user', NULL, b'1'),
(2295, '02318', NULL, '36151b04d3d2298a4caf5decf65e5667', 'user', NULL, b'1'),
(2296, '02319', NULL, '57a6471596a487b3cb2b1da58b7bcc9d', 'user', NULL, b'1'),
(2297, '02320', NULL, '0c65f5392ff8c7bd7fce6b0ba9dc7c77', 'user', NULL, b'1'),
(2298, '02321', NULL, '01eaefaa395385347c15af62505d8302', 'user', NULL, b'1'),
(2299, '02322', NULL, '61eb525dfe0fe61df2acad488aa7a7f2', 'user', NULL, b'1'),
(2300, '02324', NULL, 'b288fba8a311accd03460a2c222c69df', 'user', NULL, b'1'),
(2301, '00001', NULL, 'add87595a9e3b406f49f291f04eec357', 'user', NULL, b'1'),
(2302, '00016', NULL, 'e74b0b8f7321ad1d6caefe8c6c124270', 'user', NULL, b'1'),
(2303, '00138', NULL, '75bbc189c5e6176cf2a671be9cf27e2d', 'user', NULL, b'1'),
(2304, '00168', NULL, 'dd3ba09b226ebf1010c12e5997afb44d', 'user', NULL, b'1'),
(2305, '02007', NULL, 'd3c8b2e701a91ceeedcc53e0191715ad', 'user', NULL, b'1'),
(2306, '02177', NULL, '45ed6b1294edccfbd6b7b505aa8a12f8', 'user', NULL, b'1'),
(2307, '02181', NULL, '3093ec529c0949339814ceda77e72129', 'user', NULL, b'1'),
(2308, '02182', NULL, '69c570dde4d8b9e7c81bd3eaf9a48e7a', 'user', NULL, b'1'),
(2309, '02184', NULL, '9be2a3f4ec9e2843b462dac65fe7e523', 'user', NULL, b'1'),
(2310, '02189', NULL, 'e7d316bf56efc239e5a48113535db793', 'user', NULL, b'1'),
(2311, '02193', NULL, '93121d36d512a2b923377a63a4ab8ccb', 'user', NULL, b'1'),
(2312, '02227', NULL, '41814ebc76a38db3c97fb870124b22ca', 'user', NULL, b'1'),
(2313, '02228', NULL, '0b14de11d4590755574dd51a2572d274', 'user', NULL, b'1'),
(2314, '02230', NULL, '1ec3de8584c42dc747b418c05bb4e1be', 'user', NULL, b'1'),
(2315, '02231', NULL, '4819ea90978e7b5de2979e894c75c411', 'user', NULL, b'1'),
(2316, '02237', NULL, 'a4640587e82266a25c2b107dbc90a7e7', 'user', NULL, b'1'),
(2317, '02238', NULL, '750e5b551a9088d59c82f184d13ef321', 'user', NULL, b'1'),
(2318, '02241', NULL, 'bd83d857c1b76cb8e5e595b17afb9510', 'user', NULL, b'1'),
(2319, '02243', NULL, '918c25b0faca4cac38e5aa8fd826e79b', 'user', NULL, b'1'),
(2320, '02249', NULL, '2f7ad86c89509489d4f1686c1986988d', 'user', NULL, b'1'),
(2321, '02250', NULL, 'c401eeaa24b606635ef5bdda365d551e', 'user', NULL, b'1'),
(2322, '02254', NULL, '63f160e112b8f9685fac8c3ee26c0054', 'user', NULL, b'1'),
(2323, '02255', NULL, '7cfd68ccbba4e65b684e92b2c37f1fb2', 'user', NULL, b'1'),
(2324, '02259', NULL, '245246bc5a973c58bc7fac24cabb8e31', 'user', NULL, b'1'),
(2325, '02262', NULL, '814f712831f05a53f0d9a0c8313a3e5b', 'user', NULL, b'1'),
(2326, '02263', NULL, 'fb9b62c3b018ee75d0540bbe6d7a67c7', 'user', NULL, b'1'),
(2327, '02269', NULL, 'f17f7a946561b8c9032524ef6d21ba63', 'user', NULL, b'1'),
(2328, '02273', NULL, '4987eb58c0e4e24e1dcf45085603f49f', 'user', NULL, b'1'),
(2329, '02278', NULL, '84136a1b802e75cbad20ac00db61b6b5', 'user', NULL, b'1'),
(2330, '02288', NULL, '11d3b6e1b7b5712ecabf5462fd19d7f4', 'user', NULL, b'1'),
(2331, '02289', NULL, '8e1ac202045a5cec17c4ae982a0c82e2', 'user', NULL, b'1'),
(2332, '02290', NULL, '1e5a9568dd17923cfc08dad3a1a3a422', 'user', NULL, b'1'),
(2333, '02323', NULL, '4eefd8fe27d478b562fc8216f31a42dd', 'user', NULL, b'1'),
(2334, '02325', NULL, 'fd3929140720e13d5a035101f486393f', 'user', NULL, b'1'),
(2335, 'P0062-HVAC', '', '0ef85c5820ca966a8939f9840153cd96', 'foreman', 3, b'1'),
(2336, 'P0028', '', 'f620c42b5970406e1b9ce5b2251bf139', 'foreman', 1, b'1'),
(2337, 'accounts', NULL, 'e268443e43d93dab7ebef303bbe9642f', 'account', NULL, b'1'),
(2338, 'acc1', NULL, 'b71985397688d6f1820685dde534981b', 'account', NULL, b'0'),
(2339, 'acc_ast', NULL, '4297f44b13955235245b2497399d7a93', 'account_asst', NULL, b'1'),
(2340, 'omara', NULL, 'e10adc3949ba59abbe56e057f20f883e', 'account', NULL, b'1'),
(2341, 'bernaljeanette28@gmail.com', NULL, '07d57736e9bb03e5b28d5aa774c70985', 'company', NULL, b'1'),
(2342, 'estimation', NULL, '94e7e210543475d2dc1ee610e7b4af1d', 'estimation', NULL, b'1'),
(2343, 'test', NULL, 'efa1487ad42d0d6988708d077ea7a02a', 'estimation', NULL, b'1'),
(2344, 'est2', NULL, 'e10adc3949ba59abbe56e057f20f883e', 'estimation', NULL, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `villa`
--

CREATE TABLE `villa` (
  `Villa_Id` int(250) NOT NULL,
  `Villa_Code` varchar(50) NOT NULL,
  `Villa_Name` varchar(50) NOT NULL,
  `Villa_Status` bit(1) NOT NULL,
  `Prj_Id` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`Account_Id`),
  ADD KEY `FK_Acc_Type` (`Account_Type_Id`),
  ADD KEY `FK_Bank_Id` (`Bank_Id`);

--
-- Indexes for table `account_type`
--
ALTER TABLE `account_type`
  ADD PRIMARY KEY (`Account_Type_Id`);

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`Act_Id`),
  ADD KEY `fk_Act_Cat_Id` (`Act_Cat_Id`),
  ADD KEY `fk_Dept_Id_Act` (`Dept_Id`);

--
-- Indexes for table `activity_category`
--
ALTER TABLE `activity_category`
  ADD PRIMARY KEY (`Act_Cat_Id`),
  ADD KEY `fk_department` (`Dept_Id`);

--
-- Indexes for table `activity_standard`
--
ALTER TABLE `activity_standard`
  ADD PRIMARY KEY (`Act_Standard_Id`),
  ADD KEY `S_Prj_FK` (`Prj_Id`),
  ADD KEY `S_Act_FK` (`Act_Id`);

--
-- Indexes for table `additional`
--
ALTER TABLE `additional`
  ADD PRIMARY KEY (`ADD_ID`),
  ADD KEY `fk_add_payslip` (`PAYSLIP_ID`);

--
-- Indexes for table `allowance`
--
ALTER TABLE `allowance`
  ADD PRIMARY KEY (`ALW_ID`),
  ADD KEY `fk_payslip` (`PAYSLIP_ID`),
  ADD KEY `fk_emp` (`EMP_ID`);

--
-- Indexes for table `asgn_emp_to_prj`
--
ALTER TABLE `asgn_emp_to_prj`
  ADD PRIMARY KEY (`Asgd_Emp_to_Prj`),
  ADD KEY `fk_Prj_Id` (`Prj_Id`),
  ADD KEY `fk_User_Id` (`User_Id`),
  ADD KEY `fk_Emp_Id` (`Emp_Id`);

--
-- Indexes for table `asgn_mat_to_act`
--
ALTER TABLE `asgn_mat_to_act`
  ADD PRIMARY KEY (`Asgd_Mat_to_Act_Id`),
  ADD KEY `fk_Asgd_Act_Id` (`Asgd_Act_Id`),
  ADD KEY `fk_Assigned_Mat` (`Asgd_Mat_Id`);

--
-- Indexes for table `asgn_mp`
--
ALTER TABLE `asgn_mp`
  ADD PRIMARY KEY (`Asgn_MP_Id`),
  ADD KEY `FK_DE_Id_MP` (`DE_Id`),
  ADD KEY `FK_MP_Id` (`MP_Id`);

--
-- Indexes for table `asgn_subcon`
--
ALTER TABLE `asgn_subcon`
  ADD PRIMARY KEY (`Asgn_SB_Id`),
  ADD KEY `FK_DE_Id` (`DE_Id`),
  ADD KEY `FK_SB_Id` (`SB_Id`);

--
-- Indexes for table `asgn_worker`
--
ALTER TABLE `asgn_worker`
  ADD PRIMARY KEY (`Asgd_Worker_Id`),
  ADD KEY `fk_Emp_Id_AW` (`Emp_Id`),
  ADD KEY `fk_DE` (`DE_Id`);

--
-- Indexes for table `assigned_activity`
--
ALTER TABLE `assigned_activity`
  ADD PRIMARY KEY (`Asgd_Act_Id`),
  ADD KEY `fk_flat_id` (`Flat_Id`),
  ADD KEY `fk_act_id` (`Act_Id`),
  ADD KEY `fk_Act_Cat_Id_asgnact` (`Act_Cat_Id`);

--
-- Indexes for table `assigned_material`
--
ALTER TABLE `assigned_material`
  ADD PRIMARY KEY (`Asgd_Mat_Id`),
  ADD KEY `m_FK_Act_Id` (`Act_Id`),
  ADD KEY `m_FK_Mat_Id` (`Mat_Id`);

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`Bank_Id`);

--
-- Indexes for table `building`
--
ALTER TABLE `building`
  ADD PRIMARY KEY (`Blg_Id`),
  ADD KEY `fk_Plx_Id` (`Plx_Id`),
  ADD KEY `fk_Prj_Id_P` (`Prj_Id`);

--
-- Indexes for table `chq_code`
--
ALTER TABLE `chq_code`
  ADD PRIMARY KEY (`Chq_Code_Id`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`Client_Id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`Comp_Id`),
  ADD KEY `Comp_User_Id` (`User_Id`);

--
-- Indexes for table `comp_department`
--
ALTER TABLE `comp_department`
  ADD PRIMARY KEY (`Comp_Dept_Id`);

--
-- Indexes for table `consultant`
--
ALTER TABLE `consultant`
  ADD PRIMARY KEY (`Consultant_Id`);

--
-- Indexes for table `daily_entry`
--
ALTER TABLE `daily_entry`
  ADD PRIMARY KEY (`DE_Id`),
  ADD KEY `fk_Asgn_Act_DE` (`Asgd_Act_Id`),
  ADD KEY `fk_User_Id_DE` (`User_Id`);

--
-- Indexes for table `daily_entry2`
--
ALTER TABLE `daily_entry2`
  ADD PRIMARY KEY (`DE_Id2`),
  ADD KEY `User_Id_DE2_Fk` (`User_Id`),
  ADD KEY `Act_Id_DE2_Fk` (`Act_Id`),
  ADD KEY `Prj_Id_DE2_Fk` (`Prj_Id`);

--
-- Indexes for table `deduction`
--
ALTER TABLE `deduction`
  ADD PRIMARY KEY (`DEDUC_ID`),
  ADD KEY `fk_payslipIDd` (`PAYSLIP_ID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`Dept_Id`);

--
-- Indexes for table `email`
--
ALTER TABLE `email`
  ADD PRIMARY KEY (`Email_Id`),
  ADD KEY `Email_Grp_FK` (`Email_Grp_Id`);

--
-- Indexes for table `email_group`
--
ALTER TABLE `email_group`
  ADD PRIMARY KEY (`Email_Grp_Id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`EMP_ID`),
  ADD KEY `fk_users` (`USER_ID`);

--
-- Indexes for table `estimate`
--
ALTER TABLE `estimate`
  ADD PRIMARY KEY (`Estimate_Id`),
  ADD KEY `FK_Status_Id` (`Estimate_Status_Id`),
  ADD KEY `FK_PE_Id` (`Prj_Est_Id`),
  ADD KEY `FK_Prj_Sys_Id` (`Prj_Sys_Id`);

--
-- Indexes for table `estimate_status`
--
ALTER TABLE `estimate_status`
  ADD PRIMARY KEY (`Estimate_Status_Id`);

--
-- Indexes for table `expected_expense`
--
ALTER TABLE `expected_expense`
  ADD PRIMARY KEY (`Expense_Id`),
  ADD KEY `FK_Trans_Cat_EE` (`Transaction_Category_Id`),
  ADD KEY `FK_Plan_Id_EE` (`Plan_Id`),
  ADD KEY `FK_Prj_Id_EE` (`Prj_Id`),
  ADD KEY `FK_Trans_Id` (`Transaction_Id`);

--
-- Indexes for table `expected_income`
--
ALTER TABLE `expected_income`
  ADD PRIMARY KEY (`Income_Id`);

--
-- Indexes for table `expense_division`
--
ALTER TABLE `expense_division`
  ADD PRIMARY KEY (`Exp_Div_Id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`File_Id`),
  ADD KEY `File_Emp_FK` (`Emp_Id`);

--
-- Indexes for table `flat`
--
ALTER TABLE `flat`
  ADD PRIMARY KEY (`Flat_Id`),
  ADD KEY `fk_lvl` (`Lvl_Id`);

--
-- Indexes for table `flat_asgn_to_type`
--
ALTER TABLE `flat_asgn_to_type`
  ADD PRIMARY KEY (`Flat_Assigned_Id`),
  ADD KEY `FK_Flt_Type_Asgd` (`Flat_Type_Id`),
  ADD KEY `FK_Flat_Id_` (`Flat_Id`);

--
-- Indexes for table `flat_type`
--
ALTER TABLE `flat_type`
  ADD PRIMARY KEY (`Flat_Type_Id`),
  ADD KEY `FK_Prj_Flt_Type` (`Prj_Id`);

--
-- Indexes for table `flat_type_asgn_act`
--
ALTER TABLE `flat_type_asgn_act`
  ADD PRIMARY KEY (`Flt_Asgn_Act_Id`),
  ADD KEY `FK_Flt_Type` (`Flat_Type_Id`),
  ADD KEY `FK_Flt_Type_Act_Id` (`Act_Id`);

--
-- Indexes for table `full_allowance`
--
ALTER TABLE `full_allowance`
  ADD PRIMARY KEY (`FULL_ALW_ID`),
  ADD KEY `PAYSLIP_ID` (`PAYSLIP_ID`);

--
-- Indexes for table `increment_mgs`
--
ALTER TABLE `increment_mgs`
  ADD PRIMARY KEY (`Inc_Msg_Id`),
  ADD KEY `msg_emp_fk` (`Emp_Id`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`Lvl_Id`),
  ADD KEY `fk_building` (`Blg_Id`);

--
-- Indexes for table `main_contractor`
--
ALTER TABLE `main_contractor`
  ADD PRIMARY KEY (`Main_Contractor_Id`);

--
-- Indexes for table `manpower`
--
ALTER TABLE `manpower`
  ADD PRIMARY KEY (`MP_Id`);

--
-- Indexes for table `manpower_post`
--
ALTER TABLE `manpower_post`
  ADD PRIMARY KEY (`MP_Post_Id`),
  ADD KEY `FK_MP_Dept_Id` (`Dept_Id`),
  ADD KEY `FK_MP_Post_Id` (`Post_Id`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`Mat_Id`),
  ADD KEY `fk_Dept_Id_M` (`Dept_Id`);

--
-- Indexes for table `material_post`
--
ALTER TABLE `material_post`
  ADD PRIMARY KEY (`Mat_Post_Id`),
  ADD KEY `Mat_Post_Mat_Id_FK` (`Mat_Id`),
  ADD KEY `Post_Id_FK` (`Post_Id`);

--
-- Indexes for table `material_post_group`
--
ALTER TABLE `material_post_group`
  ADD PRIMARY KEY (`MP_Grp_Id`),
  ADD KEY `FK_Grp_Post_Id` (`Post_Id`);

--
-- Indexes for table `mat_qty`
--
ALTER TABLE `mat_qty`
  ADD PRIMARY KEY (`Mat_Qty_Id`),
  ADD KEY `Prj_Id_Qty` (`Prj_Id`),
  ADD KEY `fk_Mat_Qty` (`Mat_Id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`Notif_Id`),
  ADD KEY `not_user_fk` (`User_Id`),
  ADD KEY `not_comp_fk` (`Comp_Id`),
  ADD KEY `not_post_fk` (`Post_Id`);

--
-- Indexes for table `payment_plan`
--
ALTER TABLE `payment_plan`
  ADD PRIMARY KEY (`Plan_Id`),
  ADD KEY `FK_Trans_Cat_Plan` (`Transaction_Category_Id`);

--
-- Indexes for table `payslip`
--
ALTER TABLE `payslip`
  ADD PRIMARY KEY (`PAYSLIP_ID`),
  ADD KEY `fk_employee` (`EMP_ID`);

--
-- Indexes for table `plex`
--
ALTER TABLE `plex`
  ADD PRIMARY KEY (`Plx_Id`),
  ADD KEY `fk_villa` (`Villa_Id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`Post_Id`),
  ADD KEY `FK_post_prj` (`Prj_Id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`Product_Id`),
  ADD KEY `Comp_Prod_Id` (`Comp_Id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`Prj_Id`);

--
-- Indexes for table `project_estimation`
--
ALTER TABLE `project_estimation`
  ADD PRIMARY KEY (`Prj_Est_Id`),
  ADD KEY `FK_Client_Id` (`Client_Id`),
  ADD KEY `FK_Consultant_Id` (`Consultant_Id`),
  ADD KEY `FK_MC` (`Main_Contractor_Id`);

--
-- Indexes for table `project_system`
--
ALTER TABLE `project_system`
  ADD PRIMARY KEY (`Prj_Sys_Id`);

--
-- Indexes for table `quote`
--
ALTER TABLE `quote`
  ADD PRIMARY KEY (`Quote_Id`),
  ADD KEY `Quote_comp_fk` (`Comp_Id`),
  ADD KEY `Quote_post_fk` (`Post_Id`);

--
-- Indexes for table `quote_detail`
--
ALTER TABLE `quote_detail`
  ADD PRIMARY KEY (`Quote_Detail_Id`),
  ADD KEY `Quote_Id_FK` (`Quote_Id`),
  ADD KEY `MP_Post_Id_Fk` (`MP_Post_Id`),
  ADD KEY `Mat_Post_Id_Fk` (`Mat_Post_Id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`Service_Id`),
  ADD KEY `Comp_Service_Id` (`Comp_Id`);

--
-- Indexes for table `subcontractor`
--
ALTER TABLE `subcontractor`
  ADD PRIMARY KEY (`SB_Id`);

--
-- Indexes for table `target`
--
ALTER TABLE `target`
  ADD PRIMARY KEY (`Target_Id`);

--
-- Indexes for table `time_sheet`
--
ALTER TABLE `time_sheet`
  ADD PRIMARY KEY (`TS_ID`),
  ADD KEY `fk_employee_ts` (`EMP_ID`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`Transaction_Id`),
  ADD KEY `FK_Trans_Type` (`Transaction_Type_Id`),
  ADD KEY `FK_Acc_Id` (`Account_Id`),
  ADD KEY `FK_Trans_Stat` (`Transaction_Status_Id`),
  ADD KEY `FK_Trans_User_Id` (`User_Id`),
  ADD KEY `FK_Trans_Prj_Id` (`Prj_Id`),
  ADD KEY `FK_Trans_Cat` (`Transaction_Category_Id`);

--
-- Indexes for table `transaction_category`
--
ALTER TABLE `transaction_category`
  ADD PRIMARY KEY (`Transaction_Category_Id`);

--
-- Indexes for table `transaction_status`
--
ALTER TABLE `transaction_status`
  ADD PRIMARY KEY (`Transaction_Status_Id`);

--
-- Indexes for table `transaction_type`
--
ALTER TABLE `transaction_type`
  ADD PRIMARY KEY (`Transaction_Type_Id`);

--
-- Indexes for table `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`Userlog_Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`USER_ID`),
  ADD KEY `FK_Dept_Id_user` (`Dept_Id`);

--
-- Indexes for table `villa`
--
ALTER TABLE `villa`
  ADD PRIMARY KEY (`Villa_Id`),
  ADD KEY `fk_Prj_Id_V` (`Prj_Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `Account_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_type`
--
ALTER TABLE `account_type`
  MODIFY `Account_Type_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `Act_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_category`
--
ALTER TABLE `activity_category`
  MODIFY `Act_Cat_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_standard`
--
ALTER TABLE `activity_standard`
  MODIFY `Act_Standard_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `additional`
--
ALTER TABLE `additional`
  MODIFY `ADD_ID` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allowance`
--
ALTER TABLE `allowance`
  MODIFY `ALW_ID` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asgn_emp_to_prj`
--
ALTER TABLE `asgn_emp_to_prj`
  MODIFY `Asgd_Emp_to_Prj` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asgn_mat_to_act`
--
ALTER TABLE `asgn_mat_to_act`
  MODIFY `Asgd_Mat_to_Act_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asgn_mp`
--
ALTER TABLE `asgn_mp`
  MODIFY `Asgn_MP_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asgn_subcon`
--
ALTER TABLE `asgn_subcon`
  MODIFY `Asgn_SB_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asgn_worker`
--
ALTER TABLE `asgn_worker`
  MODIFY `Asgd_Worker_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assigned_activity`
--
ALTER TABLE `assigned_activity`
  MODIFY `Asgd_Act_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assigned_material`
--
ALTER TABLE `assigned_material`
  MODIFY `Asgd_Mat_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
  MODIFY `Bank_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `building`
--
ALTER TABLE `building`
  MODIFY `Blg_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chq_code`
--
ALTER TABLE `chq_code`
  MODIFY `Chq_Code_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `Client_Id` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `Comp_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comp_department`
--
ALTER TABLE `comp_department`
  MODIFY `Comp_Dept_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `consultant`
--
ALTER TABLE `consultant`
  MODIFY `Consultant_Id` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `daily_entry`
--
ALTER TABLE `daily_entry`
  MODIFY `DE_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_entry2`
--
ALTER TABLE `daily_entry2`
  MODIFY `DE_Id2` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deduction`
--
ALTER TABLE `deduction`
  MODIFY `DEDUC_ID` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `Dept_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email`
--
ALTER TABLE `email`
  MODIFY `Email_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_group`
--
ALTER TABLE `email_group`
  MODIFY `Email_Grp_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `EMP_ID` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estimate`
--
ALTER TABLE `estimate`
  MODIFY `Estimate_Id` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `estimate_status`
--
ALTER TABLE `estimate_status`
  MODIFY `Estimate_Status_Id` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `expected_expense`
--
ALTER TABLE `expected_expense`
  MODIFY `Expense_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expected_income`
--
ALTER TABLE `expected_income`
  MODIFY `Income_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_division`
--
ALTER TABLE `expense_division`
  MODIFY `Exp_Div_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `File_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flat`
--
ALTER TABLE `flat`
  MODIFY `Flat_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flat_asgn_to_type`
--
ALTER TABLE `flat_asgn_to_type`
  MODIFY `Flat_Assigned_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flat_type`
--
ALTER TABLE `flat_type`
  MODIFY `Flat_Type_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flat_type_asgn_act`
--
ALTER TABLE `flat_type_asgn_act`
  MODIFY `Flt_Asgn_Act_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `full_allowance`
--
ALTER TABLE `full_allowance`
  MODIFY `FULL_ALW_ID` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `increment_mgs`
--
ALTER TABLE `increment_mgs`
  MODIFY `Inc_Msg_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `Lvl_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `main_contractor`
--
ALTER TABLE `main_contractor`
  MODIFY `Main_Contractor_Id` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `manpower`
--
ALTER TABLE `manpower`
  MODIFY `MP_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manpower_post`
--
ALTER TABLE `manpower_post`
  MODIFY `MP_Post_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `Mat_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_post`
--
ALTER TABLE `material_post`
  MODIFY `Mat_Post_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_post_group`
--
ALTER TABLE `material_post_group`
  MODIFY `MP_Grp_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mat_qty`
--
ALTER TABLE `mat_qty`
  MODIFY `Mat_Qty_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `Notif_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_plan`
--
ALTER TABLE `payment_plan`
  MODIFY `Plan_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payslip`
--
ALTER TABLE `payslip`
  MODIFY `PAYSLIP_ID` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plex`
--
ALTER TABLE `plex`
  MODIFY `Plx_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `Post_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `Product_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `Prj_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_estimation`
--
ALTER TABLE `project_estimation`
  MODIFY `Prj_Est_Id` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `project_system`
--
ALTER TABLE `project_system`
  MODIFY `Prj_Sys_Id` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `quote`
--
ALTER TABLE `quote`
  MODIFY `Quote_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quote_detail`
--
ALTER TABLE `quote_detail`
  MODIFY `Quote_Detail_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `Service_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcontractor`
--
ALTER TABLE `subcontractor`
  MODIFY `SB_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `target`
--
ALTER TABLE `target`
  MODIFY `Target_Id` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `time_sheet`
--
ALTER TABLE `time_sheet`
  MODIFY `TS_ID` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `Transaction_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_category`
--
ALTER TABLE `transaction_category`
  MODIFY `Transaction_Category_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_status`
--
ALTER TABLE `transaction_status`
  MODIFY `Transaction_Status_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_type`
--
ALTER TABLE `transaction_type`
  MODIFY `Transaction_Type_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `Userlog_Id` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `USER_ID` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2345;

--
-- AUTO_INCREMENT for table `villa`
--
ALTER TABLE `villa`
  MODIFY `Villa_Id` int(250) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `FK_Acc_Type` FOREIGN KEY (`Account_Type_Id`) REFERENCES `account_type` (`Account_Type_Id`),
  ADD CONSTRAINT `FK_Bank_Id` FOREIGN KEY (`Bank_Id`) REFERENCES `bank` (`Bank_Id`);

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `fk_Act_Cat_Id` FOREIGN KEY (`Act_Cat_Id`) REFERENCES `activity_category` (`Act_Cat_Id`),
  ADD CONSTRAINT `fk_Dept_Id_Act` FOREIGN KEY (`Dept_Id`) REFERENCES `department` (`Dept_Id`);

--
-- Constraints for table `activity_category`
--
ALTER TABLE `activity_category`
  ADD CONSTRAINT `fk_department` FOREIGN KEY (`Dept_Id`) REFERENCES `department` (`Dept_Id`);

--
-- Constraints for table `activity_standard`
--
ALTER TABLE `activity_standard`
  ADD CONSTRAINT `S_Act_FK` FOREIGN KEY (`Act_Id`) REFERENCES `activity` (`Act_Id`),
  ADD CONSTRAINT `S_Prj_FK` FOREIGN KEY (`Prj_Id`) REFERENCES `project` (`Prj_Id`);

--
-- Constraints for table `additional`
--
ALTER TABLE `additional`
  ADD CONSTRAINT `fk_add_payslip` FOREIGN KEY (`PAYSLIP_ID`) REFERENCES `payslip` (`PAYSLIP_ID`);

--
-- Constraints for table `allowance`
--
ALTER TABLE `allowance`
  ADD CONSTRAINT `fk_emp` FOREIGN KEY (`EMP_ID`) REFERENCES `employee` (`EMP_ID`),
  ADD CONSTRAINT `fk_payslip` FOREIGN KEY (`PAYSLIP_ID`) REFERENCES `payslip` (`PAYSLIP_ID`);

--
-- Constraints for table `asgn_emp_to_prj`
--
ALTER TABLE `asgn_emp_to_prj`
  ADD CONSTRAINT `fk_Emp_Id` FOREIGN KEY (`Emp_Id`) REFERENCES `employee` (`EMP_ID`),
  ADD CONSTRAINT `fk_Prj_Id` FOREIGN KEY (`Prj_Id`) REFERENCES `project` (`Prj_Id`),
  ADD CONSTRAINT `fk_User_Id` FOREIGN KEY (`User_Id`) REFERENCES `users` (`USER_ID`);

--
-- Constraints for table `asgn_mat_to_act`
--
ALTER TABLE `asgn_mat_to_act`
  ADD CONSTRAINT `fk_Asgd_Act_Id` FOREIGN KEY (`Asgd_Act_Id`) REFERENCES `assigned_activity` (`Asgd_Act_Id`),
  ADD CONSTRAINT `fk_Assigned_Mat` FOREIGN KEY (`Asgd_Mat_Id`) REFERENCES `assigned_material` (`Asgd_Mat_Id`);

--
-- Constraints for table `asgn_mp`
--
ALTER TABLE `asgn_mp`
  ADD CONSTRAINT `FK_DE_Id_MP` FOREIGN KEY (`DE_Id`) REFERENCES `daily_entry` (`DE_Id`),
  ADD CONSTRAINT `FK_MP_Id` FOREIGN KEY (`MP_Id`) REFERENCES `manpower` (`MP_Id`);

--
-- Constraints for table `asgn_subcon`
--
ALTER TABLE `asgn_subcon`
  ADD CONSTRAINT `FK_DE_Id` FOREIGN KEY (`DE_Id`) REFERENCES `daily_entry` (`DE_Id`),
  ADD CONSTRAINT `FK_SB_Id` FOREIGN KEY (`SB_Id`) REFERENCES `subcontractor` (`SB_Id`);

--
-- Constraints for table `asgn_worker`
--
ALTER TABLE `asgn_worker`
  ADD CONSTRAINT `fk_DE` FOREIGN KEY (`DE_Id`) REFERENCES `daily_entry` (`DE_Id`),
  ADD CONSTRAINT `fk_Emp_Id_AW` FOREIGN KEY (`Emp_Id`) REFERENCES `employee` (`EMP_ID`);

--
-- Constraints for table `assigned_activity`
--
ALTER TABLE `assigned_activity`
  ADD CONSTRAINT `fk_Act_Cat_Id_asgnact` FOREIGN KEY (`Act_Cat_Id`) REFERENCES `activity_category` (`Act_Cat_Id`),
  ADD CONSTRAINT `fk_act_id` FOREIGN KEY (`Act_Id`) REFERENCES `activity` (`Act_Id`),
  ADD CONSTRAINT `fk_flat_id` FOREIGN KEY (`Flat_Id`) REFERENCES `flat` (`Flat_Id`);

--
-- Constraints for table `assigned_material`
--
ALTER TABLE `assigned_material`
  ADD CONSTRAINT `m_FK_Act_Id` FOREIGN KEY (`Act_Id`) REFERENCES `activity` (`Act_Id`),
  ADD CONSTRAINT `m_FK_Mat_Id` FOREIGN KEY (`Mat_Id`) REFERENCES `material` (`Mat_Id`);

--
-- Constraints for table `building`
--
ALTER TABLE `building`
  ADD CONSTRAINT `fk_Plx_Id` FOREIGN KEY (`Plx_Id`) REFERENCES `plex` (`Plx_Id`),
  ADD CONSTRAINT `fk_Prj_Id_P` FOREIGN KEY (`Prj_Id`) REFERENCES `project` (`Prj_Id`);

--
-- Constraints for table `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `Comp_User_Id` FOREIGN KEY (`User_Id`) REFERENCES `users` (`USER_ID`);

--
-- Constraints for table `daily_entry`
--
ALTER TABLE `daily_entry`
  ADD CONSTRAINT `fk_Asgn_Act_DE` FOREIGN KEY (`Asgd_Act_Id`) REFERENCES `assigned_activity` (`Asgd_Act_Id`),
  ADD CONSTRAINT `fk_User_Id_DE` FOREIGN KEY (`User_Id`) REFERENCES `users` (`USER_ID`);

--
-- Constraints for table `daily_entry2`
--
ALTER TABLE `daily_entry2`
  ADD CONSTRAINT `Act_Id_DE2_Fk` FOREIGN KEY (`Act_Id`) REFERENCES `activity` (`Act_Id`),
  ADD CONSTRAINT `Prj_Id_DE2_Fk` FOREIGN KEY (`Prj_Id`) REFERENCES `project` (`Prj_Id`),
  ADD CONSTRAINT `User_Id_DE2_Fk` FOREIGN KEY (`User_Id`) REFERENCES `users` (`USER_ID`);

--
-- Constraints for table `deduction`
--
ALTER TABLE `deduction`
  ADD CONSTRAINT `fk_payslipIDd` FOREIGN KEY (`PAYSLIP_ID`) REFERENCES `payslip` (`PAYSLIP_ID`);

--
-- Constraints for table `email`
--
ALTER TABLE `email`
  ADD CONSTRAINT `Email_Grp_FK` FOREIGN KEY (`Email_Grp_Id`) REFERENCES `email_group` (`Email_Grp_Id`);

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `fk_users` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`);

--
-- Constraints for table `estimate`
--
ALTER TABLE `estimate`
  ADD CONSTRAINT `FK_PE_Id` FOREIGN KEY (`Prj_Est_Id`) REFERENCES `project_estimation` (`Prj_Est_Id`),
  ADD CONSTRAINT `FK_Prj_Sys_Id` FOREIGN KEY (`Prj_Sys_Id`) REFERENCES `project_system` (`Prj_Sys_Id`),
  ADD CONSTRAINT `FK_Status_Id` FOREIGN KEY (`Estimate_Status_Id`) REFERENCES `estimate_status` (`Estimate_Status_Id`);

--
-- Constraints for table `expected_expense`
--
ALTER TABLE `expected_expense`
  ADD CONSTRAINT `FK_Plan_Id_EE` FOREIGN KEY (`Plan_Id`) REFERENCES `payment_plan` (`Plan_Id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `FK_Prj_Id_EE` FOREIGN KEY (`Prj_Id`) REFERENCES `project` (`Prj_Id`),
  ADD CONSTRAINT `FK_Trans_Cat_EE` FOREIGN KEY (`Transaction_Category_Id`) REFERENCES `transaction_category` (`Transaction_Category_Id`),
  ADD CONSTRAINT `FK_Trans_Id` FOREIGN KEY (`Transaction_Id`) REFERENCES `transaction` (`Transaction_Id`);

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `File_Emp_FK` FOREIGN KEY (`Emp_Id`) REFERENCES `employee` (`EMP_ID`);

--
-- Constraints for table `flat`
--
ALTER TABLE `flat`
  ADD CONSTRAINT `fk_lvl` FOREIGN KEY (`Lvl_Id`) REFERENCES `level` (`Lvl_Id`);

--
-- Constraints for table `flat_asgn_to_type`
--
ALTER TABLE `flat_asgn_to_type`
  ADD CONSTRAINT `FK_Flat_Id_` FOREIGN KEY (`Flat_Id`) REFERENCES `flat` (`Flat_Id`),
  ADD CONSTRAINT `FK_Flt_Type_Asgd` FOREIGN KEY (`Flat_Type_Id`) REFERENCES `flat_type` (`Flat_Type_Id`);

--
-- Constraints for table `flat_type`
--
ALTER TABLE `flat_type`
  ADD CONSTRAINT `FK_Prj_Flt_Type` FOREIGN KEY (`Prj_Id`) REFERENCES `project` (`Prj_Id`);

--
-- Constraints for table `flat_type_asgn_act`
--
ALTER TABLE `flat_type_asgn_act`
  ADD CONSTRAINT `FK_Flt_Type` FOREIGN KEY (`Flat_Type_Id`) REFERENCES `flat_type` (`Flat_Type_Id`),
  ADD CONSTRAINT `FK_Flt_Type_Act_Id` FOREIGN KEY (`Act_Id`) REFERENCES `activity` (`Act_Id`);

--
-- Constraints for table `full_allowance`
--
ALTER TABLE `full_allowance`
  ADD CONSTRAINT `full_allowance_ibfk_1` FOREIGN KEY (`PAYSLIP_ID`) REFERENCES `payslip` (`PAYSLIP_ID`);

--
-- Constraints for table `increment_mgs`
--
ALTER TABLE `increment_mgs`
  ADD CONSTRAINT `msg_emp_fk` FOREIGN KEY (`Emp_Id`) REFERENCES `employee` (`EMP_ID`);

--
-- Constraints for table `level`
--
ALTER TABLE `level`
  ADD CONSTRAINT `fk_building` FOREIGN KEY (`Blg_Id`) REFERENCES `building` (`Blg_Id`);

--
-- Constraints for table `manpower_post`
--
ALTER TABLE `manpower_post`
  ADD CONSTRAINT `FK_MP_Dept_Id` FOREIGN KEY (`Dept_Id`) REFERENCES `department` (`Dept_Id`),
  ADD CONSTRAINT `FK_MP_Post_Id` FOREIGN KEY (`Post_Id`) REFERENCES `post` (`Post_Id`);

--
-- Constraints for table `material`
--
ALTER TABLE `material`
  ADD CONSTRAINT `fk_Dept_Id_M` FOREIGN KEY (`Dept_Id`) REFERENCES `department` (`Dept_Id`);

--
-- Constraints for table `material_post`
--
ALTER TABLE `material_post`
  ADD CONSTRAINT `Post_Id_FK` FOREIGN KEY (`Post_Id`) REFERENCES `post` (`Post_Id`);

--
-- Constraints for table `material_post_group`
--
ALTER TABLE `material_post_group`
  ADD CONSTRAINT `FK_Grp_Post_Id` FOREIGN KEY (`Post_Id`) REFERENCES `post` (`Post_Id`);

--
-- Constraints for table `mat_qty`
--
ALTER TABLE `mat_qty`
  ADD CONSTRAINT `Prj_Id_Qty` FOREIGN KEY (`Prj_Id`) REFERENCES `project` (`Prj_Id`),
  ADD CONSTRAINT `fk_Mat_Qty` FOREIGN KEY (`Mat_Id`) REFERENCES `material` (`Mat_Id`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `not_comp_fk` FOREIGN KEY (`Comp_Id`) REFERENCES `company` (`Comp_Id`),
  ADD CONSTRAINT `not_post_fk` FOREIGN KEY (`Post_Id`) REFERENCES `post` (`Post_Id`),
  ADD CONSTRAINT `not_user_fk` FOREIGN KEY (`User_Id`) REFERENCES `users` (`USER_ID`);

--
-- Constraints for table `payment_plan`
--
ALTER TABLE `payment_plan`
  ADD CONSTRAINT `FK_Trans_Cat_Plan` FOREIGN KEY (`Transaction_Category_Id`) REFERENCES `transaction_category` (`Transaction_Category_Id`);

--
-- Constraints for table `payslip`
--
ALTER TABLE `payslip`
  ADD CONSTRAINT `fk_employee` FOREIGN KEY (`EMP_ID`) REFERENCES `employee` (`EMP_ID`);

--
-- Constraints for table `plex`
--
ALTER TABLE `plex`
  ADD CONSTRAINT `fk_villa` FOREIGN KEY (`Villa_Id`) REFERENCES `villa` (`Villa_Id`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `FK_post_prj` FOREIGN KEY (`Prj_Id`) REFERENCES `project` (`Prj_Id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `Comp_Prod_Id` FOREIGN KEY (`Comp_Id`) REFERENCES `company` (`Comp_Id`);

--
-- Constraints for table `project_estimation`
--
ALTER TABLE `project_estimation`
  ADD CONSTRAINT `FK_Client_Id` FOREIGN KEY (`Client_Id`) REFERENCES `client` (`Client_Id`),
  ADD CONSTRAINT `FK_Consultant_Id` FOREIGN KEY (`Consultant_Id`) REFERENCES `consultant` (`Consultant_Id`),
  ADD CONSTRAINT `FK_MC` FOREIGN KEY (`Main_Contractor_Id`) REFERENCES `main_contractor` (`Main_Contractor_Id`);

--
-- Constraints for table `quote`
--
ALTER TABLE `quote`
  ADD CONSTRAINT `Quote_comp_fk` FOREIGN KEY (`Comp_Id`) REFERENCES `company` (`Comp_Id`);

--
-- Constraints for table `quote_detail`
--
ALTER TABLE `quote_detail`
  ADD CONSTRAINT `MP_Post_Id_Fk` FOREIGN KEY (`MP_Post_Id`) REFERENCES `manpower_post` (`MP_Post_Id`),
  ADD CONSTRAINT `Mat_Post_Id_Fk` FOREIGN KEY (`Mat_Post_Id`) REFERENCES `material_post` (`Mat_Post_Id`),
  ADD CONSTRAINT `Quote_Id_FK` FOREIGN KEY (`Quote_Id`) REFERENCES `quote` (`Quote_Id`);

--
-- Constraints for table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `Comp_Service_Id` FOREIGN KEY (`Comp_Id`) REFERENCES `company` (`Comp_Id`);

--
-- Constraints for table `time_sheet`
--
ALTER TABLE `time_sheet`
  ADD CONSTRAINT `fk_employee_ts` FOREIGN KEY (`EMP_ID`) REFERENCES `employee` (`EMP_ID`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `FK_Acc_Id` FOREIGN KEY (`Account_Id`) REFERENCES `account` (`Account_Id`),
  ADD CONSTRAINT `FK_Trans_Cat` FOREIGN KEY (`Transaction_Category_Id`) REFERENCES `transaction_category` (`Transaction_Category_Id`),
  ADD CONSTRAINT `FK_Trans_Prj_Id` FOREIGN KEY (`Prj_Id`) REFERENCES `project` (`Prj_Id`),
  ADD CONSTRAINT `FK_Trans_Stat` FOREIGN KEY (`Transaction_Status_Id`) REFERENCES `transaction_status` (`Transaction_Status_Id`),
  ADD CONSTRAINT `FK_Trans_Type` FOREIGN KEY (`Transaction_Type_Id`) REFERENCES `transaction_type` (`Transaction_Type_Id`),
  ADD CONSTRAINT `FK_Trans_User_Id` FOREIGN KEY (`User_Id`) REFERENCES `users` (`USER_ID`);

--
-- Constraints for table `villa`
--
ALTER TABLE `villa`
  ADD CONSTRAINT `fk_Prj_Id_V` FOREIGN KEY (`Prj_Id`) REFERENCES `project` (`Prj_Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
