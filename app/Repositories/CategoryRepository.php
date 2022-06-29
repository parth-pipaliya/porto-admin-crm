<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryRepository extends Repository
{
    protected $model_name = 'App\Models\Category';
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
    public function getByParentId($parent_id)
    {
        $query = $this->model->where('parent_id',$parent_id)->get();
        return $query;
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByMultipleParentIds($parent_ids)
    {
        $query = $this->model->whereIn('parent_id',$parent_ids)->get();
        return $query;
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWithRelationship($request)
    {
        $query = $this->model->select('categories.*')->with(['categoryParent']);
        
        if(!empty($request->parent_id)){
            $query = $query->where('categories.parent_id', $request->parent_id);
        }

        $query = $query->leftJoin('categories as category_parent', 'categories.parent_id', '=', 'category_parent.id');
        
        // $query = $query->orderBy('id','desc')->get();
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
                ->addColumn('action',function($selected)
                {
                    $data = '';

                    if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('category-edit') || Auth::user()->is_superadmin == 1)){
                        $data .= '<a href="'.route("admin.category.edit", ["category"=> $selected->id]).'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    }

                    if(!empty(Auth::user()) && (Auth::user()->hasPermissionTo('category-delete') || Auth::user()->is_superadmin == 1)){
                        $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    }
                    
                    return $data;
                })
                ->editColumn('categoryParent',function($selected){
                    if(!empty($selected->categoryParent)){
                        return $selected->categoryParent->name;
                    }                            
                })
                ->filterColumn('categoryParent', function ($query, $keyword) {
                    $query->whereRaw("category_parent.name like ?", ["%$keyword%"]);
                })
                ->orderColumn('categoryParent', function ($query, $order) {
                    $query->orderBy('category_parent.name', $order);
                })

                ->rawColumns(['action','categoryParent'])
                ->make(true);
    }
}