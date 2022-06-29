<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SupportChatRepository;
use App\Repositories\UserRepository;
use App\Repositories\SupportRequestRepository;
use App\Http\Requests\Admin\SupportChatRequest;

class SupportChatController extends Controller
{
    private $user_repo, $support_request_repo, $support_chat_repo;

    public function __construct(UserRepository $user_repo, SupportRequestRepository $support_request_repo, SupportChatRepository $support_chat_repo)
    {
        $this->support_request_repo = $support_request_repo;
        $this->support_chat_repo = $support_chat_repo;
        $this->user_repo = $user_repo;
    }

    public function edit($id)
    {
        $data = $this->support_chat_repo->getById($id);
        return response()->json(['status'=>true,'data'=>$data], 200);
    }

    public function store(SupportChatRequest $request)
    {
        if(!empty($request->chat_id)){
            $data = [
                'message' => json_encode($request->message),
            ];
            $chat_add = $this->support_chat_repo->dataCrud($data, $request->chat_id);
            return response()->json(['status'=>true,'msg'=>'Message is update successfully'], 200);
        }else{
            $data = [
                'support_request_id' => $request->support_id,
                'admin_id' => $request->user()->id,
                'message' => json_encode($request->message),
            ];
            $chat_add = $this->support_chat_repo->dataCrud($data);
            return response()->json(['msg'=>'Send New Message'], 200);
        }

    }

    
    public function destroy($id)
    {
        $data = $this->support_chat_repo->getById($id);
        try{
            if(!empty($data)){
                $this->support_chat_repo->forceDelete($id); 
                return response()->json(['msg'=>'Deleted success'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['msg'=>'Can not delete this support chat msg request'], 500);
        }  

        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
