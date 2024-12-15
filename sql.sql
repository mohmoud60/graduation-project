--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `AccountID` int(11) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `UserPassword` varchar(100) NOT NULL,
  `CreatedDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `privilege` varchar(50) NOT NULL,
  `Employee_id` int(11) NOT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `accounting`
--

CREATE TABLE `accounting` (
  `account_number` int(11) NOT NULL,
  `account_code` varchar(50) NOT NULL,
  `account_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `account_type` varchar(20) NOT NULL,
  `account_Sname` varchar(255) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT current_timestamp(),
  `currency_id` varchar(3) NOT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `bonds` (
  `id` int(11) NOT NULL,
  `bond_type` varchar(50) NOT NULL,
  `bond_number` varchar(255) NOT NULL,
  `bond_name` varchar(255) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(255) NOT NULL,
  `is_special` tinyint(1) NOT NULL DEFAULT 0,
  `Delete_Date` timestamp NULL DEFAULT NULL,
  `fund_name` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `company_info`
--

CREATE TABLE `company_info` (
  `id` int(11) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `companyName` varchar(255) NOT NULL,
  `companyAddress` varchar(255) NOT NULL,
  `mobileNumber` varchar(255) NOT NULL,
  `companyDescription` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `convert_types`
--

CREATE TABLE `convert_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `sname` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `delete_data` timestamp NULL DEFAULT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `currency_id` varchar(3) NOT NULL,
  `currency_sname` varchar(50) NOT NULL,
  `currency_symbole` varchar(3) NOT NULL,
  `ceeated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `currency_exchange`
--

CREATE TABLE `currency_exchange` (
  `id` int(11) NOT NULL,
  `order_id` varchar(11) NOT NULL,
  `type` enum('sell','buy') NOT NULL,
  `currency_ex` varchar(50) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `exchange_rate` decimal(10,5) NOT NULL,
  `total` varchar(255) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reason_delete` text DEFAULT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `customer_address` varchar(255) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `customer_phone` varchar(255) NOT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL,
  `account_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `customer_transaction`
--

CREATE TABLE `customer_transaction` (
  `id` int(11) NOT NULL,
  `tr_type` enum('deposit','withdraw') NOT NULL,
  `tr_amount` decimal(10,2) NOT NULL,
  `tr_descripcion` text NOT NULL,
  `tr_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `customer_id` int(11) NOT NULL,
  `tr_currency` varchar(3) NOT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `Employee_id` int(11) NOT NULL,
  `Employee_FullName` varchar(255) NOT NULL,
  `Employee_Email` varchar(255) NOT NULL,
  `Employee_Phone` varchar(20) NOT NULL,
  `Employee_Address` varchar(255) NOT NULL,
  `job_titel` varchar(255) NOT NULL,
  `Salary` decimal(10,0) NOT NULL,
  `loan` decimal(10,2) DEFAULT 0.00,
  `salary_paid` tinyint(1) DEFAULT 0,
  `avatar_path` varchar(255) DEFAULT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `employee_transactions`
--

CREATE TABLE `employee_transactions` (
  `transaction_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_type` enum('Advances','Salary') NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `exchange_rates`
--

CREATE TABLE `exchange_rates` (
  `id` int(11) NOT NULL,
  `currency_ex` varchar(25) NOT NULL,
  `buy_rate` decimal(10,3) NOT NULL,
  `sell_rate` decimal(10,3) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `fund_sname` varchar(255) NOT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `first_account` varchar(3) DEFAULT NULL,
  `second_account` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `income_transfer`
--

CREATE TABLE `income_transfer` (
  `id` int(11) NOT NULL,
  `ils_amount` decimal(10,2) NOT NULL,
  `usd_amount` decimal(10,2) NOT NULL,
  `CreatedDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Delete_Date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(1) NOT NULL,
  `time_zone` varchar(255) NOT NULL,
  `date_format` varchar(255) NOT NULL,
  `time_format` varchar(255) NOT NULL,
  `fiscal_year_start` int(2) NOT NULL,
  `whatsApp_logo` varchar(255) NOT NULL,
  `exchange_rate_sub` decimal(10,3) NOT NULL,
  `vodafone_cash_price` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` int(11) NOT NULL,
  `transfer_id` int(11) DEFAULT NULL,
  `from_account_id` int(11) DEFAULT NULL,
  `from_amount` decimal(10,2) DEFAULT NULL,
  `from_type` enum('deposit','withdraw') DEFAULT NULL,
  `to_account_id` int(11) DEFAULT NULL,
  `to_amount` decimal(10,2) DEFAULT NULL,
  `to_type` enum('deposit','withdraw') DEFAULT NULL,
  `income_fund` int(11) DEFAULT NULL,
  `income_amount` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `Delete_Date` timestamp NULL DEFAULT NULL,
  `from_account_type` enum('customer','funds') DEFAULT NULL,
  `to_account_type` enum('customer','funds') DEFAULT NULL,
  `tr_from_id` varchar(11) DEFAULT NULL,
  `tr_to_id` varchar(11) DEFAULT NULL,
  `cut_vodafone` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `type_id` varchar(4) NOT NULL,
  `type_name` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `type_sname` varchar(255) NOT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`AccountID`);

--
-- Indexes for table `accounting`
--
ALTER TABLE `accounting`
  ADD PRIMARY KEY (`account_number`),
  ADD KEY `fk_accounting_account_type` (`account_type`),
  ADD KEY `fk_accounting_currency` (`currency_id`);

--
-- Indexes for table `bonds`
--
ALTER TABLE `bonds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_info`
--
ALTER TABLE `company_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `convert_types`
--
ALTER TABLE `convert_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`currency_id`);

--
-- Indexes for table `currency_exchange`
--
ALTER TABLE `currency_exchange`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_traders_currency_customer` (`customer_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `customer_transaction`
--
ALTER TABLE `customer_transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_traders_transaction_customer` (`customer_id`) USING BTREE,
  ADD KEY `tr_currency` (`tr_currency`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`Employee_id`);

--
-- Indexes for table `employee_transactions`
--
ALTER TABLE `employee_transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_transfer`
--
ALTER TABLE `income_transfer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `income_fund` (`income_fund`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bonds`
--
ALTER TABLE `bonds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `company_info`
--
ALTER TABLE `company_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `convert_types`
--
ALTER TABLE `convert_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `currency_exchange`
--
ALTER TABLE `currency_exchange`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3521;

--
-- AUTO_INCREMENT for table `customer_transaction`
--
ALTER TABLE `customer_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=713;

--
-- AUTO_INCREMENT for table `employee_transactions`
--
ALTER TABLE `employee_transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `income_transfer`
--
ALTER TABLE `income_transfer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=816;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounting`
--
ALTER TABLE `accounting`
  ADD CONSTRAINT `fk_accounting_account_type` FOREIGN KEY (`account_type`) REFERENCES `type` (`type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_accounting_currency` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`currency_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `currency_exchange`
--
ALTER TABLE `currency_exchange`
  ADD CONSTRAINT `fk_traders_currency_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer_transaction`
--
ALTER TABLE `customer_transaction`
  ADD CONSTRAINT `customer_transaction_ibfk_1` FOREIGN KEY (`tr_currency`) REFERENCES `currency` (`currency_id`),
  ADD CONSTRAINT `fk_traders_currency_exchange_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_traders_transaction_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_transactions`
--
ALTER TABLE `employee_transactions`
  ADD CONSTRAINT `employee_transactions_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`Employee_id`);

--
-- Constraints for table `transfers`
--
ALTER TABLE `transfers`
  ADD CONSTRAINT `transfers_ibfk_3` FOREIGN KEY (`income_fund`) REFERENCES `accounting` (`account_number`);
COMMIT;
