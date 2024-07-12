<?php

namespace App\Models;

use App\Models\GeneralLedger as Gl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GeneralLedger extends Model
{
    use HasFactory;


    public function getFirstGlTransaction(){
        return Gl::orderBy('id', 'ASC')->first();
    }
    public function getDrBalanceBroughtForward($start_date, $end_date){
        return Gl::whereBetween('created_at', [$start_date, $end_date])->sum('dr_amount');
    }
    public function getCrBalanceBroughtForward($start_date, $end_date){
        return Gl::whereBetween('created_at', [$start_date, $end_date])->sum('cr_amount');
    }

    public function getReports($start_date, $end_date){
        return DB::table('general_ledgers as g')
            ->join('chart_of_accounts as c', 'c.glcode', '=', 'g.glcode')
            ->select(DB::raw('sum(g.dr_amount) AS sumDebit'),DB::raw('sum(g.cr_amount) AS sumCredit'),
                'c.account_name', 'g.glcode', 'c.glcode', 'c.account_type', 'c.type')
            //->where('c.account_type', 1)
            ->where('c.type', 1)
            ->whereBetween('g.created_at', [$start_date, $end_date])
            ->orderBy('c.account_type', 'ASC')
            ->groupBy('c.account_name')
            ->get();
    }
    public function getBalanceSheetReports($date){
        $firstGl = $this->getFirstGlTransaction();
        return DB::table('general_ledgers as g')
            ->join('chart_of_accounts as c', 'c.glcode', '=', 'g.glcode')
            ->select(DB::raw('sum(g.dr_amount) AS sumDebit'),DB::raw('sum(g.cr_amount) AS sumCredit'),
                'c.account_name', 'g.glcode', 'c.glcode', 'c.account_type', 'c.type')
            //->where('c.account_type', 1)
            ->where('c.type', 1)
            ->whereBetween('g.created_at', [$firstGl->created_at, $date])
            ->orderBy('c.account_type', 'ASC')
            ->groupBy('c.account_name')
            ->get();
    }

    public function getRevenue($date){
        $firstGl = $this->getFirstGlTransaction();
        return DB::table('general_ledgers as g')
            ->join('chart_of_accounts as c', 'c.glcode', '=', 'g.glcode')
            ->where('c.type', 'Detail')
            ->whereIn('c.account_type', [4])
            ->whereBetween('g.created_at', [$firstGl->created_at,$date])
            ->get();
    }
    public function getExpenses($date){
        $firstGl = $this->getFirstGlTransaction();
        return DB::table('general_ledgers as g')
            ->join('chart_of_accounts as c', 'c.glcode', '=', 'g.glcode')
            ->where('c.type', 'Detail')
            ->whereIn('c.account_type', [5])
            ->whereBetween('g.created_at', [$firstGl->created_at,$date])
            ->get();
    }

    public function getRevenueByDateRange($start_date, $end_date){
        return DB::table('general_ledgers as g')
            ->join('chart_of_accounts as c', 'c.glcode', '=', 'g.glcode')
            ->where('c.type', 'Detail')
            ->whereIn('c.account_type', [4])
            ->whereBetween('g.created_at', [$start_date,$end_date])
            ->get();
    }
    public function getExpensesByDateRange($start_date, $end_date){
        return DB::table('general_ledgers as g')
            ->join('chart_of_accounts as c', 'c.glcode', '=', 'g.glcode')
            ->where('c.type', 'Detail')
            ->whereIn('c.account_type', [5])
            ->whereBetween('g.created_at', [$start_date,$end_date])
            ->get();
    }
}
