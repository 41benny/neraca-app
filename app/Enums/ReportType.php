<?php

namespace App\Enums;

enum ReportType: string
{
    case BalanceSheet = 'balance_sheet';
    case IncomeStatement = 'income_statement';
    case CashFlow = 'cash_flow';
}
