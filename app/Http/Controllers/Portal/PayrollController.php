<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\PaymentDefinition;
use App\Models\PayrollMonthYear;
use App\Models\Salary;
use App\Models\SalaryAllowance;
use App\Models\SalaryStructure;
use App\Models\SalaryStructurePersonalized;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{

    public function __construct()
    {
        $this->paymentdefinition = new PaymentDefinition();
        $this->salarystructure = new SalaryStructure();
        $this->salaryallowance = new SalaryAllowance();
        $this->user = new User();
        $this->salarystructurepersonalized = new SalaryStructurePersonalized();

    }

    public function handlePaymentDefinition(Request $request){

        $method = $request->getMethod();
        switch ($method){
            case 'GET':
                return view('payroll.payment-definition',[
                    'definitions'=>$this->paymentdefinition->getPaymentDefinitionList()
                ]);
            case 'POST':
                $this->validate($request,[
                    "payCode"=>'required|unique:payment_definitions,pay_code',
                    "paymentName"=>"required",
                ],[
                    "payCode.required"=>"Enter pay code",
                    "payCode.unique"=>"A payment definition with this pay code already exist. Choose a another one",
                    "paymentName.required"=>"Enter payment name"
                ]);
                $this->paymentdefinition->addPaymentDefinition($request);
                session()->flash("success", "Success! New payment definition added.");
                return back();
            case 'PUT':
                $this->validate($request,[
                    "payCode"=>'required',
                    "paymentName"=>"required",
                    "definition"=>"required"
                ],[
                    "payCode.required"=>"Enter pay code",
                    "payCode.unique"=>"A payment definition with this pay code already exist. Choose a another one",
                    "paymentName.required"=>"Enter payment name",
                    "definition.required"=>""
                ]);
                $this->paymentdefinition->updatePaymentDefinition($request);
                session()->flash("success", "Success! Changes saved.");
                return back();
        }
    }


    public function handleSalaryStructure(Request $request){
        $method = $request->getMethod();
        switch ($method){
            case 'GET':
                return view('payroll.salary-structure',
                ['records'=>$this->salarystructure->getSalaryStructures()]);

            case 'POST':
                $this->validate($request,[
                    "name"=>"required",
                ],[
                    "name.required"=>"Enter salary structure name"
                ]);
                $this->salarystructure->addSalaryStructure($request);
                session()->flash("success", "Action successful");
                return back();
            case 'PUT':
                $this->validate($request,[
                    "name"=>"required",
                    "structure"=>"required"
                ],[
                    "name.required"=>"Enter salary structure name",
                    "structure.required"=>""
                ]);
                $this->salarystructure->updateSalaryStructure($request);
                session()->flash("success", "Changes saved");
                return back();

        }
    }

    public function showNewSalaryAllowanceForm(){

            return view('payroll.new-salary-allowance',
                [
                    'definitions'=>$this->paymentdefinition->getPaymentDefinitionListByVariance(1),
                    'salaryCategories'=>$this->salarystructure->getSalaryStructures()
                ]);


    }

    public function handleSalaryAllowances(Request $request){
        $method = $request->getMethod();
        switch ($method){
            case 'GET':
                return view('payroll.salary-allowances',
                [
                    'allowances'=>$this->salaryallowance->getAllowances(),
                    'definitions'=>$this->paymentdefinition->getPaymentDefinitionListByVariance(1),
                    'salaryCategories'=>$this->salarystructure->getSalaryStructures()
                ]);

            case 'POST':
                $this->validate($request,[
                    "category"=>"required",
                    "paymentDefinition"=>"required|array",
                    "paymentDefinition.*"=>"required",
                    "amount"=>"required|array",
                    "amount.*"=>"required",
                ],[
                    "category.required"=>"Choose salary structure",
                    "paymentDefinition.required"=>"Choose payment definition",
                    "paymentDefinition.array"=>"Choose payment definition from the list",
                    "amount.required"=>"Enter an amount",
                    "amount.*"=>"Enter an amount",
                ]);
                foreach($request->paymentDefinition as $key => $def){
                    $this->salaryallowance->addSalaryAllowance($request->category, $def, $request->amount[$key]);
                }
                session()->flash("success", "Action successful");
                return back();
            case 'PUT':
                $this->validate($request,[
                    "category"=>"required",
                    "paymentDefinition"=>"required",
                    "amount"=>"required",
                    "allowance"=>"required"
                ],[
                    "category.required"=>"Select salary structure category",
                    "paymentDefinition.required"=>"Choose payment definition",
                    "amount.required"=>"Enter an amount",
                    "allowance.required"=>"",
                ]);
                $this->salaryallowance->updateSalaryAllowance($request->allowance, $request->category,
                    $request->paymentDefinition, $request->amount);
                session()->flash("success", "Changes saved");
                return back();

        }
    }

    public function showSalaryStructures(){
        return view('payroll.process.salary-structures',[
            'users'=>$this->user->getAllUsers()
        ]);
    }
    public function showSalarySetupForm($slug){
        $user = $this->user->getUserBySlug($slug);
        if(empty($user)){
            session()->flash("error", "Whoops! Something went wrong.");
            return back();
        }
        return view('payroll.process.salary-structure-setup',[
            'user'=>$user,
            'definitions'=>$this->paymentdefinition->getPaymentDefinitionListByVariance(1),
            'categories'=>$this->salarystructure->getSalaryStructures()
        ]);
    }
    public function showEmployeeSalaryStructure($slug){
        $user = $this->user->getUserBySlug($slug);
        if(empty($user)){
            session()->flash("error", "Whoops! Something went wrong.");
            return back();
        }
        $salarySetup = $user->salary_structure_setup;
        $salaryStructure = null;
        $personalized = null;
        if($salarySetup == 1){ //setup done
            if($user->salary_structure_category == 0){ //personalized
                //get personalized structure
                $personalized = SalaryStructurePersonalized::getEmployeePersonalizedStructure($user->id);
            }else{
                $salaryStructure = $this->salarystructure->getSalaryStructureById($user->salary_structure_category);
            }
        }
        return view('payroll.process.employee-salary-structure',[
            'user'=>$user,
            'personalized'=>$personalized,
            'salaryStructure'=>$salaryStructure,
        ]);
    }

    public function editEmployeeSalaryStructure($slug){
        $user = $this->user->getUserBySlug($slug);
        if(empty($user)){
            session()->flash("error", "Whoops! Something went wrong.");
            return back();
        }
        $salarySetup = $user->salary_structure_setup;
        $salaryStructure = null;
        $personalized = null;
        if($salarySetup == 1){ //setup done
            if($user->salary_structure_category == 0){ //personalized
                //get personalized structure
                $personalized = SalaryStructurePersonalized::getEmployeePersonalizedStructure($user->id);
            }else{
                $salaryStructure = $this->salarystructure->getSalaryStructureById($user->salary_structure_category);
            }
        }
        return view('payroll.process.edit-salary-structure-setup',[
            'user'=>$user,
            'personalized'=>$personalized,
            'salaryStructure'=>$salaryStructure,
            'categories'=>$this->salarystructure->getSalaryStructures(),
            'definitions'=>$this->paymentdefinition->getPaymentDefinitionListByVariance(1),
        ]);
    }

    public function setupSalaryStructure(Request $request){
        $this->validate($request,[
            "employee"=>"required",
            "salaryStructureType"=>"required",
        ],[
            "employee.required"=>"",
            "salaryStructureType.required"=>"Choose salary structure type",
        ]);

        $type = $request->salaryStructureType;
        $employee = $this->user->getUserById($request->employee);
        if(empty($employee)){
            session()->flash("error", "Whoops! No record found for this user");
            return back();
        }
        if($type == 0){ //personalized
            $this->validate($request,[
                "paymentDefinition"=>"required|array",
                "paymentDefinition.*"=>"required",
                "amount"=>"required|array",
                "amount.*"=>"required",
            ],[
                "paymentDefinition.required"=>"Choose payment definition",
                "paymentDefinition.array"=>"Choose payment definition from the list",
                "amount.required"=>"Enter an amount",
                "amount.*"=>"Enter an amount",
            ]);
            foreach($request->paymentDefinition as $key => $def){
                $this->salarystructurepersonalized->addSalaryAllowance($request->employee, $def, $request->amount[$key]);
            }
            $employee->salary_structure_setup = 1; //done
            $employee->salary_structure_category = 0; //personalized
            $employee->save();
            session()->flash("success", "Action successful!");
            return back();
        }else{

            $this->validate($request,[
                "category"=>"required",
            ],[
                "category.required"=>"Choose salary structure category",
            ]);
            $employee->salary_structure_setup = 1; //done
            $employee->salary_structure_category = $request->category; //personalized
            $employee->save();
            session()->flash("success", "Action successful!");
            return back();
        }
    }


    public function updateSalaryStructure(Request $request){
        $this->validate($request,[
            "employee"=>"required",
            "salaryStructureType"=>"required",
        ],[
            "employee.required"=>"",
            "salaryStructureType.required"=>"Choose salary structure type",
        ]);

        $type = $request->salaryStructureType;
        $employee = $this->user->getUserById($request->employee);
        if(empty($employee)){
            session()->flash("error", "Whoops! No record found for this user");
            return back();
        }
        if($type == 0){ //personalized
            $this->validate($request,[
                "paymentDefinition"=>"required|array",
                "paymentDefinition.*"=>"required",
                "amount"=>"required|array",
                "amount.*"=>"required",
            ],[
                "paymentDefinition.required"=>"Choose payment definition",
                "paymentDefinition.array"=>"Choose payment definition from the list",
                "amount.required"=>"Enter an amount",
                "amount.*"=>"Enter an amount",
            ]);
            //clear previous record
            $previousStructure = SalaryStructurePersonalized::getEmployeePersonalizedStructure($request->employee);
            if(count($previousStructure) > 0){
                foreach ($previousStructure as $prev){
                    $prev->delete();
                }
            }
            foreach($request->paymentDefinition as $key => $def){
                $this->salarystructurepersonalized->addSalaryAllowance($request->employee, $def, $request->amount[$key]);
            }
            $employee->salary_structure_setup = 1; //done
            $employee->salary_structure_category = 0; //personalized
            $employee->save();
            session()->flash("success", "Your changes were saved!");
            return back();
        }else{

            $this->validate($request,[
                "category"=>"required",
            ],[
                "category.required"=>"Choose salary structure category",
            ]);
            $employee->salary_structure_setup = 1; //done
            $employee->salary_structure_category = $request->category; //personalized
            $employee->save();
            session()->flash("success", "Your changes were saved!");
            return back();
        }
    }

    public function showSalaryAllowances($slug){
        $structure = $this->salarystructure->getSalaryStructureBySlug($slug);
        if(empty($structure)){
            session()->flash("error", "Whoops! No record found.");
            return back();
        }
        return view('payroll.view-salary-structure-allowances',[
            'structure'=>$structure,
            'definitions'=>$this->paymentdefinition->getPaymentDefinitionListByVariance(1)
        ]);
    }

    public function showPayrollMonthYear(Request $request){

        $method = $request->getMethod();
        switch ($method){
            case 'GET':
                return view('payroll.payroll-month-year',[
                    'record'=> PayrollMonthYear::getActivePayrollMonthYear(),
                ]);
            case 'POST':
                $this->validate($request,[
                    "monthYear"=>"required",

                ],[
                    "monthYear.required"=>"Choose payroll month/year"
                ]);
                $month = date('m', strtotime($request->monthYear));
                $year = date('Y', strtotime($request->monthYear));
                $records = PayrollMonthYear::getPayrollMonthYear();
                foreach($records as $record){
                    $record->payroll_status = 0;
                    $record->save();
                }
                PayrollMonthYear::addPayrollMonthYear($month, $year);
                session()->flash("success", "Action successful");
                return back();

        }
    }

    public function showPayrollRoutine(Request $request){

        $method = $request->getMethod();
        switch ($method){
            case 'GET':
                return view('payroll.process.payroll-routine',[
                    'record'=> PayrollMonthYear::getActivePayrollMonthYear(),
                ]);
            case 'POST':
                $this->validate($request,[
                    "monthYear"=>"required",

                ],[
                    "monthYear.required"=>"Choose payroll month/year"
                ]);
                $month = date('m', strtotime($request->monthYear));
                $year = date('Y', strtotime($request->monthYear));
                $records = PayrollMonthYear::getPayrollMonthYear();
                foreach($records as $record){
                    $record->payroll_status = 0;
                    $record->save();
                }
                PayrollMonthYear::addPayrollMonthYear($month, $year);
                session()->flash("success", "Action successful");
                return back();

        }
    }


    public function runPayrollRoutine(){
        $activePeriod = PayrollMonthYear::getActivePayrollMonthYear();
        if(empty($activePeriod)){
            session()->flash("error", "Whoops! Kindly set payroll month & year to continue");
            return back();
        }
        $pendingRoutine = Salary::getPendingSalary();

        if(count($pendingRoutine) > 0){
            session()->flash("error", "Whoops! There is a pending routine that needs to be run or undone. ");
            return back();
        }

        $activeEmployees = $this->user->getActiveUsers();
        $authUser = Auth::user();
        $batchCode = substr(sha1(time()),29,40);

        foreach($activeEmployees as $employee){
            if($employee->salary_structure_setup == 1){
                $salaryStructureCategory = $employee->salary_structure_category;
                if($salaryStructureCategory == 0){ //personalized
                    $personalizedStructure = SalaryStructurePersonalized::getEmployeePersonalizedStructure($employee->id);
                    if(count($personalizedStructure) > 0){
                        foreach($personalizedStructure as $def){
                            $pd = PaymentDefinition::find($def->payment_definition_id);
                            $data = [
                                'paid_by'=>$authUser->id,
                                'employee_id'=>$employee->id,
                                'payment_definition_id'=>$def->payment_definition_id,
                                'payroll_month'=>$activePeriod->payroll_month,
                                'payroll_year'=>$activePeriod->payroll_year,
                                'amount'=>$def->amount,
                                'status'=>0,
                                'batch_code'=>$batchCode,
                                'payment_type'=>!empty($pd) ? $pd->payment_type : 1
                            ];
                            Salary::create($data);
                        }
                    }

                }else{ //categorized structure
                    $allowances = $this->salaryallowance->getAllowancesBySalaryStructureId($employee->salary_structure_category);
                    if(count($allowances) > 0){
                        foreach($allowances as $allowance){
                            $paymentDefinition = PaymentDefinition::find($allowance->payment_definition_id);
                            $data = [
                                'paid_by'=>$authUser->id,
                                'employee_id'=>$employee->id,
                                'payment_definition_id'=>$allowance->payment_definition_id,
                                'payroll_month'=>$activePeriod->payroll_month,
                                'payroll_year'=>$activePeriod->payroll_year,
                                'amount'=>$allowance->amount,
                                'status'=>0,
                                'batch_code'=>$batchCode,
                                'payment_type'=>!empty($paymentDefinition) ? $paymentDefinition->payment_type : 1,
                            ];
                            Salary::create($data);
                        }
                    }
                }
            }

        }
        session()->flash("success", "Action successful");
        return back();
    }

    public function showApprovePayrollRoutineView(){
        return view('payroll.process.approve-payroll-routine',[
            'records'=>Salary::getDistinctPendingSalary()
        ]);
    }


    public function approveOrUndoPayrollRoutine(Request $request){
        $action = $request->action ?? 'approve';
        $batchCode = $request->batch_code ?? null;
        if(is_null($batchCode)){
            session()->flash("error", "Something is missing. Try again later or contact admin.");
            return back();
        }
        $salaries = Salary::getSalaryByBatchCode($batchCode);
        if(count($salaries) <= 0){
            session()->flash("error", "No record found.");
            return back();
        }
        switch ($action){
            case "approve":
            foreach($salaries as $salary){
                $salary->status = 1;
                $salary->date_actioned = now();
                $salary->actioned_by = Auth::user()->id;
                $salary->save();
            }
                $activePeriod = PayrollMonthYear::getActivePayrollMonthYear();
            if(!empty($activePeriod)){
                $activePeriod->payroll_status = 0;
                $activePeriod->save();
            }
            break;
            case "undo":
            foreach ($salaries as $salary){
                $salary->delete();
            }
            break;

        }
        session()->flash("success", "Action successful");
        return back();
    }

    public function showPayrollReportForm(){
        return view('payroll.process.payroll-report',[
            'search'=>0
        ]);
    }
}
