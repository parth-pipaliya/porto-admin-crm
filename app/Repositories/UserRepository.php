<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\User;
use Illuminate\Support\Str;
use Validator;
use DB;

class UserRepository extends Repository
{
    protected $model_name = 'App\Models\User';
    protected $model;

    public function __construct()
    {
        parent::__construct();
    }

    public function getStatusValue()
    {
        return $this->model->status_value;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function registerWithMobileno($request)
    {   
        
        // $card_number = $this->genrateCardNumber();
        // $mobile_code = $this->generateOTPCode();
        // $message = 'The OTP is '.$mobile_code.' to verify '.config('app.name').' Account.';
        // $this->sendMessage($mobile_code, $request->country_code.$request->mobile_no);

        $this->model->withTrashed()->updateOrCreate(['mobile_no' => $request->mobile_no,'country_code' => $request->country_code], [
                'otp_code' => $request->otp_code,
                'status' => $request->status,
                'deleted_at' => NULL
            ])->restore();    
    
        return $this->model->where('mobile_no', $request->mobile_no)->where('country_code', $request->country_code)->first();
        // if(!empty($user)){
        //     $this->model->where('mobile_no', $request->mobile_no)->update(['crm_card'=> $card_number]);
        // }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function registerWithRestore($request)
    {   
       
        $card_number = $this->genrateCardNumber();
        $this->model->withTrashed()->updateOrCreate(['mobile_no' => $request->mobile_no,'country_code' => $request->country_code], [
                'first_name' => !empty($request->first_name) ? $request->first_name : NULL,
                'last_name' => !empty($request->last_name) ? $request->last_name : NULL,    
                'email' => isset($request->email) ? $request->email : NULL,
                'password' => isset($request->password) ? Hash::make($request->password) : NULL,
                'category_id' => isset($request->category_id) ? $request->category_id : NULL,
                'subcategory_id' => isset($request->subcategory_id) ? $request->subcategory_id : NULL,
                'status' => !empty($request->category_id) ? 1 : 0,
                'device_type' => isset($request->device_type) ? $request->device_type : NULL,
                'device_token' => !empty($request->device_token) ? $request->device_token : NULL,
                'social_type'=> isset($request->social_type) ? $request->social_type : NULL,
                'facebook_id'=> !empty($request->facebook_id) ? $request->facebook_id : NULL,
                'google_id'=>!empty($request->google_id) ? $request->google_id : NULL,
                'apple_id'=> !empty($request->apple_id) ? $request->apple_id : NULL,
                'user_timezone'=> !empty(request()->header('X-TimeZone')) ? request()->header('X-TimeZone') : '',
                'deleted_at' => NULL
            ])->restore();    
    
        $user = $this->model->where('mobile_no', $request->mobile_no)->where('country_code', $request->country_code)->whereNull('crm_card');
        if(!empty($user)){
            $this->model->where('mobile_no', $request->mobile_no)->where('country_code', $request->country_code)->update(['crm_card'=> $card_number]);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function dataCrudUsingData($data, $id = '')
    {   
        if(!empty($id)){
            return $this->update($data, $id);
        } else {
            return $this->store($data);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function dataCrud($request, $id = '')
    {   $data = array();
        if(!empty($request)){
            $filter = $request->all();
            foreach ($filter as $key => $value) {
                $data[$key] = $value;
            }
        }
        if(!empty($id)){
            return $this->update($data, $id);
        } else {
            return $this->store($data);
        }
    }

    /**
     * remove oauth access tokens in db.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function removeOauthAccessTokens($user_id)
    {  
        DB::table('oauth_access_tokens')->where('user_id', $user_id)->delete();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWithRelationship($request)
    {
        DB::enableQueryLog();
        $query = $this->model->select('users.*')->with(['categoryChild','categoryParent']);    

        if(!empty($request->category_id)){
            $query = $query->whereHas('categoryParent', function ($query) use ($request) {
                $query->where('parent_id', $request->category_id);
            });
            
            if(!empty($request->subcategory_id)){
                $query = $query->where('users.category_id', $request->subcategory_id);
            }
            
            if(is_array($request->status)){
                $query = $query->whereIn('users.status', $request->status);
            }else{
                $query = $query->where('users.status', $request->status);
            }
        }
        

        if(!empty($request->filter_status) || $request->filter_status == '0'){
            $query = $query->where('users.status', $request->filter_status);
        } 

        if(!empty($request->start_date) && !empty($request->end_date)){
            $query = $query->whereDate('users.created_at', '>=',$request->start_date)->whereDate('users.created_at' , '<=',$request->end_date);
        }

        $query = $query->leftJoin('categories as categoryParent', 'users.category_id', '=', 'categoryParent.id')
                        ->leftJoin('categories as categoryChild', 'users.subcategory_id', '=', 'categoryChild.id');
        // $query = $query->orderBy('id','desc')->get();
        // print_r(DB::getQueryLog());
        // die;
        return $query;
    }
    
    /**
     * Display a listing of the Datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDatatable($request)
    {   
        $data = $this->getWithRelationship($request);
        return Datatables::of($data)
                ->addColumn('action',function($selected) use ($request)
                {
                    $data = '';
                    if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('user-edit') || Auth::user()->is_superadmin == 1)){
                        $data .= '<a href="'.route("admin.user.edit", ["user"=> $selected->id]).'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;&nbsp';
                    }

                    if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('user-view') || Auth::user()->is_superadmin == 1)){
                        $data .= '<a href="'.route("admin.user.show", ["user"=> $selected->id]).'" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp';
                    }

                    if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('user-delete') || Auth::user()->is_superadmin == 1)){
                        $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;';
                    }

                    return $data;
                })
                ->editColumn('status',function($selected)
                {
                    $data = '';
                    if($selected->status == '2'){
                        $data .= '<div class="badge badge-danger">'.$selected->status_name.'</div>';
                    }else if($selected->status == '1'){
                        $data .= '<div class="badge badge-danger">'.$selected->status_name.'</div>';
                    }else{                        
                        $data .= '<div class="badge badge-success">'.$selected->status_name.'</div>';
                    }
          
                    return $data;
                })
                ->filterColumn('status', function ($query, $keyword) use ($request) {
                    if (in_array($request->search['value'], $this->getStatusValue())){
                        $user_status = array_search($request->search['value'], $this->getStatusValue());
                        $query->where("users.status", $user_status);                       
                    }
                })


                ->addColumn('user_name',function($selected)
                {
                     return $selected->user_name;
                })                
                ->filterColumn('user_name', function ($query, $keyword) {
                    $query->whereRaw("concat(users.first_name, ' ', users.last_name) like ?", ["%$keyword%"]);
                })
                ->orderColumn('user_name', function ($query, $order) {
                    $query->orderBy('users.first_name', $order)->orderBy('users.last_name', $order);
                })

                ->addColumn('mobile_no',function($selected)
                {
                     return $selected->mobile_no_country_code;
                })
                ->filterColumn('mobile_no', function ($query, $keyword) {
                    $query->whereRaw("concat(users.country_code, ' ', users.mobile_no) like ?", ["%$keyword%"]);
                })
                ->orderColumn('mobile_no', function ($query, $order) {
                    $query->orderBy('users.mobile_no', $order);
                })

                ->editColumn('category_type',function($selected){
                    $data = '';
                    if(!empty($selected->categoryParent)){
                        $data .='<div class="text-success"><strong>'. $selected->categoryParent->name.'</strong></div>';
                    }                            
                    if(!empty($selected->categoryChild)){
                        $data .='<div class="text-success"><strong>'. $selected->categoryChild->name.'</strong></div>';
                    }  
                    return $data;                          
                })
                ->filterColumn('category_type', function ($query, $keyword) {
                    $query->whereRaw("concat(categoryParent.name, ' ', categoryChild.name) like ?", ["%$keyword%"]);
                })
                ->orderColumn('category_type', function ($query, $order) {
                    $query->orderBy('categoryParent.name', $order);
                })

                ->editColumn('created_at',function($selected){
                    return !empty($selected->created_at) ? $this->getDateTimeFormate($selected->created_at) : '-';
                })
                
                
                ->rawColumns(['action','categoryParent','status','category_type'])
                ->make(true);
    }
    
     /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdedit($id)
    {   
        return $this->model->with(['categoryParent','categoryChild'])->find($id);

    }

    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserbyCardNumber($card_number)
    {   
        return $this->model->where('crm_card',$card_number)->first();

    }
   
   
    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkbyMobileNo($request)
    {   
        return $this->model->where('mobile_no',$request->mobile_no)->where('country_code',$request->country_code)->whereIn('status',['0','1','2'])->first();
    }
   
   
    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkbyEmailId($request)
    {   
        return $this->model->where('email',$request->email)->first();
    }
 
    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkbyMobileNoAndEmail($request)
    {   
        return $this->model->where(function($query) use ($request){
                        $query->orWhere('mobile_no',$request->mobile_no)->orWhere('email',$request->email);
                    })->first();
    }

    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkbyMobileNoVerify($request)
    {   
        return $this->model->where('mobile_no',$request->mobile_no)->where('country_code',$request->country_code)->where('status','3')->whereNotNull('mobile_verified_at')->first();
    }

    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyMobileNo($request)
    {   
        return $this->model->where('mobile_no',$request->mobile_no)->where('country_code',$request->country_code)->first();
    }
    
    
    /**
     * generate card no for ezzy care card.
     *
     * @return \Illuminate\Http\Response
     */   
    function genrateCardNumber() 
    {     
        $length = 10;
        $str_result = '0123456789'; 
        $card_number = 'CRM_'.substr(str_shuffle($str_result), 0, $length);
        $validator = Validator::make(
            [
                'crm_card' => $card_number
            ],
            [
                'crm_card' => 'required|unique:users',
            ]
        );
        if ($validator->fails()) {
            self::genrateCardNumber();
        }
        return $card_number; 
    } 



}