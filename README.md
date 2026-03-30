# 🛒 Walmart Sales Analysis (SQL + PHP)

## 📌 Project Overview

This project analyzes Walmart sales data using SQL and displays insights through a PHP-based dashboard. It helps understand sales trends, store performance, and the impact of holidays on sales.

## 🛠 Technologies Used

- MySQL (phpMyAdmin - XAMPP)
- PHP
- HTML & CSS

## 📊 Features

- Total Sales by Store
- Average Weekly Sales
- Monthly Sales Trends
- Holiday vs Non-Holiday Analysis
- Best and Worst Sales Weeks

## 📊 Sample SQL Queries

```sql
SELECT Store, SUM(Weekly_Sales) AS Total_Sales
FROM walmart_sales
GROUP BY Store;

SELECT MONTH(Date), SUM(Weekly_Sales)
FROM walmart_sales
GROUP BY MONTH(Date);
```

## ▶️ How to Run

1. Start XAMPP (Apache & MySQL)
2. Import dataset into phpMyAdmin
3. Place project folder in `htdocs`
4. Open: http://localhost/salesanalysis/dashboard.php
