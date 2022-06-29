<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use App\Models\Admin;
use Illuminate\Support\Str;

class AdminRepository extends Repository
{
    protected $model_name = 'App\Models\Admin';
    protected $model;

    public function __construct()
    {
        parent::__construct();
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWithRelationship()
    {
        $query = $this->model->orderBy('id','desc')->get();
        return $query;
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
                    if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('admin-edit') || Auth::user()->is_superadmin == 1)){
                        $data .= '<a href="'.route("admin.admin_user.edit", ["user"=> $selected->id]).'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    
                    }

                    if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('admin-view') || Auth::user()->is_superadmin == 1)){
                        $data .= '<a href="'.route("admin.admin_user.show", ["user"=> $selected->id]).'" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp';
                    }

                    if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('admin-delete') || Auth::user()->is_superadmin == 1)){
                        if (Auth::guard('admin')->user()->id != $selected->id) {
                            $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                        }
                    }
                    
                    return $data;
                })
                ->rawColumns(['action'])
                ->make(true);
    }
}