<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SupportRequestRepository;
use App\Repositories\SupportChatRepository;
use App\Repositories\UserRepository;
use App\Http\Requests\Admin\SupportRequest;
use App\Http\Requests\Admin\SupportChatRequest;
use App\Http\Helpers\Helper;

class SupportRequestController extends Controller
{
    private $support_request_repo, $support_chat_repo, $user_repo;

    public function __construct(
        SupportRequestRepository $support_request_repo,
        SupportChatRepository $support_chat_repo,
        UserRepository $user_repo
    )
    {
        $this->support_request_repo = $support_request_repo;
        $this->support_chat_repo = $support_chat_repo;
        $this->user_repo = $user_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            return $this->support_request_repo->getDatatable($request);
        }
        return view('admin.support_request.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('admin.support_request.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupportRequest $request)
    {
        $data = [
                    'user_id' => $request->user_id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'status' => $request->status,
                    'comment' => $request->comment,
                    'admin_id' => $request->user()->id,
                    'closed_date' => ($request->status == '3') ? $this->support_request_repo->getCurrentDateTime() : NULL,
                ];
        if(!empty($request->id)){
            $support = $this->support_request_repo->getById($request->id);
            if(!empty($support)){
                $this->support_request_repo->dataCrud($data, $request->id);
            } 
        } else{
            $this->support_request_repo->dataCrud($data);
        }
        return redirect()->route('admin.support_request.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $status = $this->support_request_repo->getStatusValue();
        $data = $this->support_request_repo->getbyIdeditChat($id);
        return view('admin.support_request.add',compact('data','status'));
    }
    
    public function getChatMessages($id)
    {
        $support_chat = $this->support_chat_repo->getbySupportId($id);
        return view('admin.support_request.chat',compact('support_chat'));
    }
    
     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $status = $this->support_request_repo->getStatusValue();
        $data = $this->support_request_repo->getbyIdeditChat($id);
        return view('admin.support_request.view',compact('data','status'));
    }

    /**
     * close the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function closeSupportRequest($id)
    {
        $data = $this->support_request_repo->getById($id);
        try{
            if(!empty($data)){
                $this->support_request_repo->dataCrud(['status'=>'3', 'closed_date'=> $this->support_request_repo->getCurrentDateTime()], $id);
                return response()->json(['msg'=>'Support request close successfully'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['msg'=>'Can not close this support request'], 500);
        }  

        return response()->json(['msg'=>'Data Not success'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->support_request_repo->getById($id);
        try{
            if(!empty($data)){
                $support_chat = $this->support_chat_repo->getbySupportIdDelete($id);
                foreach($support_chat as $chat){
                     $this->support_chat_repo->forceDelete($chat->id); 
                }
                $this->support_request_repo->forceDelete($id); 
                return response()->json(['msg'=>'Deleted success'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['msg'=>'Can not delete this support request'], 500);
        }  

        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
