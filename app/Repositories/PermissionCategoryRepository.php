<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\PermissionCategory;
use Illuminate\Support\Str;


class PermissionCategoryRepository extends Repository
{
    protected $model_name = 'App\Models\PermissionCategory';
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
        $query = $this->model->with('getPermissions');
        $query = $query->get();
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
                    if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('permission_category-edit') || Auth::user()->is_superadmin == 1)){
                        $data .= '<a href="javascript:void(0)" onclick="editRow('.$selected->id.')" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    }

                    if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('permission_category-delete') || Auth::user()->is_superadmin == 1)){
                        $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    }
                    return $data;
                })
                ->rawColumns(['action'])
                ->make(true);
    }
}