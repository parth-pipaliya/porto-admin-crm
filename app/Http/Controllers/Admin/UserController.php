<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserTransactionRepository;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\CategoryRepository;
use Auth;
use Carbon\Carbon;
use DB;
use Helper;

class UserController extends Controller
{

    private $user_repo, $category_repo;

    public function __construct(
        UserRepository $user_repo, 
        CategoryRepository  $category_repo
        )
    {
        $this->user_repo = $user_repo;
        $this->category_repo = $category_repo;
    }
     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {           
        $categories = [];
        $categories = $this->category_repo->getAll();
        if($request->all()){
            return $this->user_repo->getDatatable($request);
         }
        return view('admin.user.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if(!empty($request->file('profile_image'))) {          
            $file = $request->file('profile_image');
            $storagePath = 'upload/images/profile';            
            $file_name = $this->user_repo->uploadFolderWiseFile($file, $storagePath);
            $data['profile_image'] =  $file_name;
        }
        $this->user_repo->dataCrudUsingData($data, $request->id); 
                  
        return redirect()->route('admin.user.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = $this->category_repo->get();
        $data = $this->user_repo->getbyIdedit($id);
        return view('admin.user.add',compact('data','categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categories = $this->category_repo->get();
        $data = $this->user_repo->getbyIdedit($id);
        return view('admin.user.add',compact('data','categories'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        $data = $this->user_repo->getById($id);
        try{
            DB::beginTransaction();
            if(!empty($data)){
                $this->user_repo->removeOauthAccessTokens($id); 
                $this->user_repo->forceDelete($id); 
                DB::commit();
                return response()->json(['msg'=>'Deleted success'], 200);
            }
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['msg'=>'Can not delete this user'], 500);
        }  
        return response()->json(['msg'=>'Data Not success'], 500);
    }

    /**
     * editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $data = $this->user_repo->getById($request->user_id);
        if(!empty($data)){
            if(isset($request->status) && $data->status == '1' && $request->status == '0'){
                if($data->profile_completed_progress == '100'){
                    $data = ['status' => $request->status, 'approved_date' => $this->user_repo->getCurrentDateTime()];
                    $send_notification = [
                                       'sender_id' => NULL,
                                       'receiver_id' => $request->user_id,
                                       'title' => 'Profile',
                                       'message' => 'Congratulations! Your profile has been approved, you can now accept appointments on Ezzycare',
                                       'parameter' => '',
                                       'msg_type' => '7',
                                   ];  
                   $this->notification_repo->sendingWithoutSenderNotification($send_notification);       
                }else{
                    return response()->json(['msg'=>'Please fill required details before approval.'], 500);
                }
            }else{
                 $data = ['status' => $request->status];
            }
            $this->user_repo->update($data, $request->user_id); 
            return response()->json(['msg'=>'Status Change success'], 200);
        }
        
        return response()->json(['msg'=>'Please fill all required details.'], 500);
    }

}
