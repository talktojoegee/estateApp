<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\PaymentDefinition;
use App\Models\SalaryAllowance;
use App\Models\SalaryStructure;
use App\Models\SalaryStructurePersonalized;
use App\Models\User;
use Illuminate\Http\Request;

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
}
