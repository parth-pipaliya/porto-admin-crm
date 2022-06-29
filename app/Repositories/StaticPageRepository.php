<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\StaticPages;
use Illuminate\Support\Str;

class StaticPageRepository extends Repository
{
    protected $model_name = 'App\Models\StaticPages';
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
    public function dataCrud($data, $id = '')
    {   
        if(!empty($data)){
            if(!empty($id)){
                return $this->update($data, $id);
            } else {
                return $this->store($data);
            }
        }
    }
    
    /**
     * Display a listing of the Datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDatatable($request)
    {
        $data = $this->getAll();
        return Datatables::of($data)
                ->addColumn('action',function($selected)
                {
                    $data = '';
                    if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('static_page-edit') || Auth::user()->is_superadmin == 1)){
                        $data .= '<a href="'.route("admin.static_pages.edit", ["static_pages"=> $selected->id]).'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    }
                    if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('static_page-delete') || Auth::user()->is_superadmin == 1)){
                        $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    }
                    return $data;
                })
                ->editColumn('status',function($selected)
                {
                    //0-Active, 1-Inactive
                    $data = '';
                    if($selected->status == '0'){
                        $data .= '<div class="badge badge-info">'.$selected->status_name.'</div>';
                    }else if($selected->status == '1'){
                        $data .= '<div class="badge badge-danger">'.$selected->status_name.'</div>';
                    }
                    return $data;
                })
                ->rawColumns(['action','status'])
                ->make(true);
    }
    
}