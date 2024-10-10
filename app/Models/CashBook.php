<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashBook extends Model
{
    use HasFactory;
    protected $primaryKey = 'cashbook_id';


    public function getAccount(){
        return $this->belongsTo(CashBookAccount::class, 'cashbook_account_id');
    }

    public function getBranch(){
        return $this->belongsTo(ChurchBranch::class, 'cashbook_branch_id');
    }


    public function getEntryBy(){
        return $this->belongsTo(User::class, 'cashbook_user_id');
    }
    public function getCategory(){
        return $this->belongsTo(TransactionCategory::class, 'cashbook_category_id');
    }

    public function getCurrency(){
        return $this->belongsTo(Currency::class, 'cashbook_currency_id');
    }


    public function getAccountCashbookRecords(){
        return $this->hasMany(CashBookAccount::class, 'cashbook_account_id', 'cba_id');
    }

    public function addCashBook($branchId, $categoryId, $accountId, $currency, $paymentMethod, $level,
                                $transactionType, $transactionDate, $description, $narration = null,
                                $debit = 0, $credit = 0, $refCode, $month, $year){
        $category = $this->getTransactionCategory($categoryId);
        $cashbook = new CashBook();
        $cashbook->cashbook_branch_id = $branchId;
        $cashbook->cashbook_category_id = $categoryId;
        $cashbook->cashbook_account_id = $accountId;
        $cashbook->cashbook_currency_id = $currency;
        $cashbook->cashbook_payment_method = $paymentMethod;
        $cashbook->cashbook_level = $level;
        $cashbook->cashbook_user_id = Auth::user()->id;
        $cashbook->cashbook_transaction_type = $transactionType;
        $cashbook->cashbook_transaction_date = $transactionDate;
        $cashbook->cashbook_description = $description;
        $cashbook->cashbook_narration = $narration;
        $cashbook->cashbook_month = $month ?? date('m', strtotime($transactionDate));
        $cashbook->cashbook_year = $year ?? date('Y', strtotime($transactionDate));
        $cashbook->cashbook_debit = $debit;
        $cashbook->cashbook_credit = $credit;
        $cashbook->cashbook_ref_code = $refCode;
        $cashbook->cashbook_remit_table = $category->tc_remittable ?? 0;
        $cashbook->save();
        return $cashbook;
    }


    public function actionCashBookEntry($refCode, $status, $actionedBy, $comment){
        $entry = CashBook::where('cashbook_ref_code', $refCode)->first();
        $entry->cashbook_actioned_by = $actionedBy;
        $entry->cashbook_date_actioned = now();
        $entry->cashbook_actioned_comment = $comment;
        $entry->save();
    }


    public function getCashBookEntryByRefCode($refCode){
        return CashBook::where('cashbook_ref_code', $refCode)->first();
    }


    public function getAllCashBookEntriesByBranch($branchId){
        return CashBook::where('cashbook_branch_id', $branchId)->get();
    }



    public function getAllCashBookEntriesByUser($userId){
        return CashBook::where('cashbook_user_id', $userId)->get();
    }

    public function getTransactionCategory($catId){
        return TransactionCategory::find($catId);
    }



    public function generateReferenceCode(){
        return substr(sha1(time()), 29,40);
    }

    public function getAllBranchLocalTransactions($type){
        $defaultCurrency = $this->getDefaultCurrency();
        return CashBook::where('cashbook_branch_id', Auth::user()->branch)
            ->where('cashbook_transaction_type', $type)
            ->where('cashbook_currency_id', $defaultCurrency->id)->orderBy('cashbook_id', 'DESC')->get();
    }
    public function getAllBranchFxTransactions($type){
        $defaultCurrency = $this->getDefaultCurrency();
        return CashBook::where('cashbook_branch_id', Auth::user()->branch)
            ->where('cashbook_transaction_type', $type)
            ->whereNot('cashbook_currency_id', $defaultCurrency->id)->orderBy('cashbook_id', 'DESC')->get();
    }
    public function getAllBranchIncomeByUser($userId){
        return CashBook::where('cashbook_branch_id', Auth::user()->branch)->where('cashbook_user_id', $userId)->orderBy('cashbook_id', 'DESC')->get();
    }

    public function getAllBranchIncomeByUserByDateRange($userId, $from, $to){
        return CashBook::where('cashbook_branch_id', Auth::user()->branch)->where('cashbook_user_id', $userId)->whereBetween('cashbook_transaction_date', [$from, $to])->orderBy('cashbook_id', 'DESC')->get();
    }

    public function getRangeBranchIncomeReport($from, $to){
        return CashBook::where('cashbook_branch_id', Auth::user()->branch)->whereBetween('cashbook_transaction_date', [$from, $to])->get();
    }

    public function getBranchYesterdays($branchId){
        $yesterday = date("Y-m-d", strtotime( '-1 days' ) );
        $defaultCurrency = $this->getDefaultCurrency();
        return CashBook::where('cashbook_branch_id', $branchId)
            ->whereDate('cashbook_transaction_date', $yesterday)
            ->where('cashbook_currency_id', $defaultCurrency->id)->orderBy('cashbook_id', 'DESC')->get();
    }

    public function getBranchTodays($branchId){
        $defaultCurrency = $this->getDefaultCurrency();
        return CashBook::where('cashbook_branch_id', $branchId)
            ->where('cashbook_currency_id', $defaultCurrency->id)
            ->whereDate('cashbook_transaction_date', now())->orderBy('cashbook_id', 'DESC')->get();
    }

    public function getBranchMonths($branchId){
        $defaultCurrency = $this->getDefaultCurrency();
        return CashBook::where('cashbook_branch_id', $branchId)
            ->where('cashbook_currency_id', $defaultCurrency->id)
            ->whereMonth('cashbook_transaction_date', date('m'))
            ->whereYear('cashbook_transaction_date', date('Y'))->orderBy('cashbook_id', 'DESC')->get();
    }

    public function getBranchLastMonths($branchId){
        $currentMonth = date('m');
        $lastMonth = $currentMonth - 1;
        if($lastMonth == 0){
            $lastMonth = 12;
        }
        $defaultCurrency = $this->getDefaultCurrency();
        return CashBook::where('cashbook_branch_id', $branchId)
            ->where('cashbook_currency_id', $defaultCurrency->id)
            ->whereMonth('cashbook_transaction_date', $lastMonth)
            ->whereYear('cashbook_transaction_date', date('Y'))->orderBy('cashbook_id', 'DESC')->get();
    }
    public function getBranchThisWeek($branchId){
        $defaultCurrency = $this->getDefaultCurrency();
        return CashBook::where('cashbook_branch_id', $branchId)
            ->where('cashbook_currency_id', $defaultCurrency->id)
            ->whereBetween('cashbook_transaction_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek() ])
            ->orderBy('cashbook_id', 'DESC')->get();
    }

    public function getThisYearIncomeStat(){
        return CashBook::select(
            DB::raw("DATE_FORMAT(cashbook_transaction_date, '%m-%Y') monthYear"),
            DB::raw("YEAR(cashbook_transaction_date) year, MONTH(cashbook_transaction_date) month"),
            DB::raw("SUM(cashbook_credit) amount"),
            'cashbook_transaction_date',
        )->whereYear('cashbook_transaction_date', date('Y'))
            ->where('cashbook_branch_id', Auth::user()->branch)
            ->orderBy('cashbook_month', 'ASC')
            ->groupby('cashbook_year','cashbook_month')
            ->get();
    }
    public function getIncomeStatRange($from, $to){
        return CashBook::select(
            DB::raw("DATE_FORMAT(cashbook_transaction_date, '%m-%Y') monthYear"),
            DB::raw("YEAR(cashbook_transaction_date) year, MONTH(cashbook_transaction_date) month"),
            DB::raw("SUM(cashbook_credit) amount"),
            'cashbook_transaction_date',
        )->whereBetween('cashbook_transaction_date', [$from, $to])
            ->where('cashbook_branch_id', Auth::user()->branch)
            ->orderBy('cashbook_month', 'ASC')
            ->groupby('cashbook_year','cashbook_month')
            ->get();
    }


    public function getDefaultCurrency(){
        $symbol = env('APP_CURRENCY');
        return Currency::where('symbol', $symbol)->first();
    }


    public function getTransactionAttachment($casbookId){
        return CashBookAttachment::where('cba_cashbook_id', $casbookId)->first();
    }

    public function getBranchMonthsUnpaidRemittance($from, $to, $branchId){
        $defaultCurrency = $this->getDefaultCurrency();
        return CashBook::select(
            DB::raw("SUM(cashbook_credit) cashbook_credit"),
            'cashbook_transaction_date', 'cashbook_branch_id', 'cashbook_currency_id','cashbook_remittance_paid',
            'cashbook_transaction_type', 'cashbook_transaction_date', 'cashbook_category_id', 'cashbook_id'
        )->whereBetween('cashbook_transaction_date', [$from, $to])
            ->where('cashbook_branch_id', $branchId)
            ->where('cashbook_currency_id', $defaultCurrency->id)
            ->where('cashbook_remittance_paid', 2)
            ->where('cashbook_remit_table', 1) //remittance is to be paid
            ->where('cashbook_transaction_type', 1)
            ->whereMonth('cashbook_transaction_date', date('m'))
            ->whereYear('cashbook_transaction_date', date('Y'))
            ->groupBy('cashbook_category_id')
            ->orderBy('cashbook_id', 'DESC')
            ->get();
    }
    public function getCashbookTransactionsByDateRange($from, $to, $branchId, $account){
        $defaultCurrency = $this->getDefaultCurrency();
        return CashBook::whereBetween('cashbook_transaction_date', [$from, $to])
            //->where('cashbook_branch_id', $branchId)
            ->where('cashbook_account_id', $account)
            //->where('cashbook_currency_id', $defaultCurrency->id)
            //->whereMonth('cashbook_transaction_date', date('m'))
            //->whereYear('cashbook_transaction_date', date('Y'))
            //->groupBy('cashbook_category_id')
            ->orderBy('cashbook_id', 'ASC')
            ->get();
    }
    public function getCashbookFXTransactionsByDateRange($from, $to, $branchId){
        $defaultCurrency = $this->getDefaultCurrency();
        return CashBook::whereBetween('cashbook_transaction_date', [$from, $to])
            ->where('cashbook_branch_id', $branchId)
            ->whereNot('cashbook_currency_id', $defaultCurrency->id)
            ->whereMonth('cashbook_transaction_date', date('m'))
            ->whereYear('cashbook_transaction_date', date('Y'))
            ->groupBy('cashbook_currency_id')
            ->orderBy('cashbook_id', 'ASC')
            ->get();
    }

    public function pluckCashbookIdsByDateRange($from, $to, $branchId){
        $defaultCurrency = $this->getDefaultCurrency();
        return CashBook::whereBetween('cashbook_transaction_date', [$from, $to])
            ->where('cashbook_currency_id', $defaultCurrency->id)
            ->where('cashbook_credit', ">", 0)
            ->where('cashbook_branch_id', $branchId)
            ->pluck('cashbook_id');
    }

    public function getBulkTransactionsByCategoryIds($ids, $branchId){
        return CashBook::whereIn('cashbook_id', $ids)->where('cashbook_branch_id', $branchId)->get();
    }
    public function getCashbookByCategoryBranchId($id, $catId, $branchId){
        return CashBook::where('cashbook_id', $id)
            ->where('cashbook_category_id', $catId)
            ->where('cashbook_branch_id', $branchId)->first();
    }

    public function updateCashbookRemittance($cashbookId, $refCode){
        $cashbook = CashBook::find($cashbookId);
        if(!empty($cashbook)){
            $cashbook->cashbook_remittance_ref_no = $refCode;
            $cashbook->cashbook_remittance_paid = 1;//paid
            $cashbook->save();
        }
    }
}
