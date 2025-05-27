<?php
session_start();
header('Content-Type: application/json');
include 'function.php';

$incomes_data = load_user_incomes_fromDB_to_pichart();
$expenses_data = load_user_expenses_fromDB_to_pichart();
$balance_data = load_user_financial_balance_fromDB_to_pichart();

echo json_encode([
    "incomes" => $incomes_data, 
    "expenses" => $expenses_data,
    "balance" => $balance_data
]);