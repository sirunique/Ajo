<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Hash;
use App\Libraries\MailHandler;
use App\User;
use App\Group;
use App\Member;
use App\Payment;


use Mail;
use App\Mail\SendMail;

use Auth;
use Illuminate\Support\Facades\DB;
// use DB;
use Log;


class UserController extends Controller
{
    

    public function login(){
        return view('auth.login');
    }
    public function register(){
        return view('auth.register');
    }
    public function postlogin(Request $request){
        //validate user
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:4',
        ]);
        if(Auth::guard('investor')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])){
            $request->session()->regenerate();
            return $this->response(true, ucwords('Login Successfull'));
        }
        return $this->response(false, ucwords('Invalid Login'));
    }
    public function postregister(Request $request){
        //validate user
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
        ]);
        $token = Hash::make($request->email);
        $token = str_replace('/', '', str_replace('$', '', $token));
        //create user
        $user = User::create([
            'email'=> $request->email,
            'password' => bcrypt($request->password),
            'email_verified_token' => $token,
        ]);

        try{
            $mail = (object) null;
            $mail->template = 'email.verify';
            $mail->from_mail = env('SENDER_EMAIL');
            $mail->from_name = env('APP_NAME');
            $mail->to_email = $user->email;
            $mail->to_name = $user->email;
            $mail->subject = 'Verify Email Address';
            $mail->verify_url = env('APP_URL') . '/email/verify/' . $user->id . '?token=' . $token;
            // $send = (new MailHandler())->sendMail($mail);

            Mail::send($mail->template, ['mail' => $mail], function ($m) use ($mail) {
                $m->from($mail->from_mail, $mail->from_name);
                $m->to($mail->to_email, $mail->to_name)->subject($mail->subject);
            });

            Log::debug('sent: ' . json_encode($send));
        }
        catch (\Exception $e){
            Log::debug($e->getMessage());
        }
        // return $this->mail($user->email,$user->email,$token,$user->id);
        if($user){
            Auth::guard('investor')->login($user);
            return $this->response(true, ucwords('Successfull'));
        }
        return $this->response(false, ucwords('Registration Failed'));
    }
    public function dashboard(){
        return view('investor.dashboard');
    }
    public function logout(Request $request){
        Auth::guard('investor')->logout();
        $request->session()->invalidate();
        return redirect()->route('login');
    }

    public function group(){
        $members = DB::table('members')
                    ->join('groups', 'members.group_id', '=', 'groups.id')
                    ->where('user_id', Auth::user()->id)
                    ->get();
        if(empty($members)) return view('investor.group');
        return view('investor.group',['members' => $members]);
    }
    public function createThrift(){
        return view('investor.createThrift');
    }
    public function postCreateThrift(Request $request){
        $request->validate([
            'groupName' => 'required|unique:groups,name',
            'amount' => 'required',
            'searchable' => 'required',
            'capacity' => 'required',
            'groupDescription' => 'required'
        ]);
        $gLink = Group::genrate();
        $group = Group::create([
            'admin_id' => Auth::user()->id,
            'name' => $request->groupName,
            'description' => $request->groupDescription,
            'max_capacity' => $request->capacity,
            'searchable' => $request->searchable,
            'amount' => $request->amount,
            'group_link' => $gLink,
        ]);
        $save_member = Member::create([
            'group_id' => $group['id'],
            'user_id' => Auth::user()->id,
            'role' => 'Administrator'
        ]);
        // return $this->group();
        return $this->response(true, ucwords('Group Created Successfullly'));
    }

    public function groupInfo($id){
        $groupInfo =  Group::findOrFail($id);
        $membersInfo = DB::table('members')
            ->join('groups','members.group_id','=','groups.id')
            ->join('users','members.user_id','=','users.id')
            ->where('group_id',$groupInfo->id)
            // if($groupInfo->status == 'running')
            ->orderBy('payout_date', 'asc')
            // ->select('members.*', )
            ->get();
        $paymentInfo = DB::table('payments')
            ->join('users','payments.member_id','=','users.id')
            ->where(['group_id' => $groupInfo->id])
            ->orderBy('week', 'asc')
            ->get();
        $countMember = Member::where(['group_id'=>$groupInfo->id])->count();
        for ($i=1; $i <= $countMember; $i++) { 
            $paymentWeeks = DB::table('payments')
                ->join('users','payments.member_id','=','users.id')
                ->join('groups','payments.group_id','=','groups.id')
                ->where(['group_id' => $groupInfo->id, 'week' => $i])
                ->orderBy('week', 'asc')
                ->select('payments.*', 'users.name', 'users.email', 'groups.amount')
                ->get();
            $data["Week ".$i] = $paymentWeeks;
        }
        // return $membersInfo;
        // return Date('Y-m-d');
        return view('investor.groupInfo', [
            'groupInfo' => $groupInfo, 
            'membersInfo' => $membersInfo,
            'countMember' => $countMember,
            'paymentInfo' => $paymentInfo,
            'paymentWeeksInfo' => $data
        ]);
    }

    private function response($status, $message){
        $res = array('res' => $status, 'message' => $message);
        return json_encode($res);
    }

    public function addMember(Request $request){
        if(!$this->IsUser($request)){
            return $this->response(false, ucwords('user does not exist'));
        } 
        return $this->GetInfo($request);
    }

    private function IsUser(Request $request){
        if(User::where('email',$request->email)->exists()) return true;
        return false;
    }
    private function GetInfo(Request $request){
       $getInfo = User::where('email', $request->email)->first();
        //check if d user is admin
        if($getInfo->id == Auth::user()->id) return $this->response(false, ucwords('Cant re add yourself'));
        // check if user exist in group
        $existInGroup = Member::where(['group_id' => $request->groupId, 'user_id' => $getInfo->id])->count();
        if($existInGroup > 0) return $this->response(false, ucwords('User already Exist'));
        // check group max
        return $this->CheckGroupMax($request,$getInfo);
    }
    private function CheckGroupMax(Request $request, $getInfo){
        $capacity = Group::where('id',$request->groupId)->first();
        $currentNo = Member::where('group_id',$request->groupId)->count();
        if($currentNo == $capacity->max_capacity) return $this->response(false, ucwords('Cant Exceed Max Capacity'));
        return $this->SaveMember($request,$getInfo->id);
    }
    private function SaveMember(Request $request,$userId){
        // save member
        $save_member = Member::create([
            'group_id' => $request->groupId,
            'user_id' => $userId,
            'role' => 'Member'
        ]);
        if($save_member){
            return $this->response(true, ucwords('Success'));
        }
        return $this->response(false, ucwords('Error Occur.. Try Again'));
    }

    public function startThrift($id){
        $groupInfo =  Group::findOrFail($id);
        if($groupInfo->status == 'running') return $this->groupInfo($groupInfo->id);
        $countMember = Member::where('group_id','=',$id)->count();
        // update start date with admin selected date then get the total num of member to cal d thrift week 
        // i.e 5 members = 5 weeks
        // end date = start date + thrift week
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime($startDate ."+". $countMember ." week"));

        // update startdate and enddate
        Group::where('id',$groupInfo->id)->update(['start_date'=>$startDate, 'end_date'=>$endDate, 'status'=>'running']);

        // Update member payoutdate with respect to groupid and userid
        $getMember = Member::where('group_id',$groupInfo->id)->get('user_id');
        for ($i=0; $i < count($getMember) ; $i++) { 
            $memberId[] = $getMember[$i]['user_id'];
        }
        // Generate payment date 
        for ($i=1; $i <= $countMember; $i++) { 
            $payOutDate[] = $getNextPayOutDate = date('Y-m-d', strtotime($startDate ."+". $i ."week" ));
        }
        shuffle($payOutDate);
        // map member id with paydate as key value pair
        if(count($memberId) != count($payOutDate)) return 'Invalid Map';
        
        for ($i= 0; $i < count($memberId); $i++) { 
            $map[] = [$memberId[$i],$payOutDate[$i]];
        }

        // update member payoutdate
        for ($i=0; $i < count($map) ; $i++) { 
            Member::where(['group_id' => $groupInfo->id, 'user_id'=> $map[$i][0]])->update(['payout_date'=>$map[$i][1]]);
        }

        for ($i=0; $i < $countMember; $i++) { 
            $wk = $i+1;
            $week = $i+1; 
            $start = $this->getNextStartWeek($wk, $startDate);
            $end = $this->getNextEndWeek($wk, $startDate, $endDate);

            for ($j=0; $j < count($memberId); $j++) { 
                $save_payments = Payment::create([
                    'group_id' => $groupInfo->id,
                    'member_id' => $memberId[$j],
                    'week' => $week,
                    'startDate' => $start,
                    'endDate' => $end,
                ]);
            }
        }
        return $this->groupInfo($groupInfo->id);

    }

    private function getNextStartWeek($wk,$startDate){
        $newwk = $wk -1;
        return $wk==1 ? date($startDate) : 
        date('Y-m-d',strtotime($startDate ."+".$newwk." week"."+ 1 day"));
    }
    private function getNextEndWeek($wk, $startDate, $endDate){
        return $wk==1 ? date('Y-m-d',strtotime($startDate ."+".$wk." week")) : 
        date('Y-m-d',strtotime($startDate ."+".$wk." week"));
    }

    public function weekPayment(Request $request){
        $getPayInfo = Payment::where('id',$request->id)->first();
        if($getPayInfo->payment_status == 'Paid') return $this->response(false, ucwords('Payment Already Made'));
        $pay= Payment::where('id',$request->id)->update(['payment_amount'=>$request->amount, 'payment_status'=>'Paid', 'date'=>date('Y-m-d')]);
        if($pay){
            return $this->response(true, ucwords('Payment Successful'));
        }
        return $this->response(false, ucwords('Error Occur.. Try Again'));
    }

    public function payout(Request $request){
        $getMemberInfo = Member::where('id',$request->id)->first();
        if($getMemberInfo->paid_status=='Paid') return $this->response(false, ucwords('Payment Already Made'));
        $pay = Member::where('id',$getMemberInfo->id)->update(['paid_status'=>'Paid', 'paid_amount'=>$request->amount, 'date'=>date('Y-m-d')]);
        if($pay){
            return $this->response(true, ucwords('Payment Successful'));
        }
        return $this->response(false, ucwords('Error Occur.. Try Again'));
    }

    private function mail($email,$name,$token,$id){
        try{
            $mail = (object) null;
            $mail->template = 'email.verify';
            $mail->from_mail = env('SENDER_EMAIL');
            $mail->from_name = env('APP_NAME');
            $mail->to_email = $email;
            $mail->to_name = $name;
            $mail->subject = 'Verify Email Address';
            $mail->verify_url = env('APP_URL') . '/email/verify/' . $id . '?token=' . $token;
            $send = (new MailHandler())->sendMail($mail);
            Log::debug('sent: ' . json_encode($send));
        }
        catch (\Exception $e){
            Log::debug($e->getMessage());
        }
    }

    public function verify(Request $request){
        $investor = User::where('id', $request->route('id'))->first();
        if($investor && ($investor->email_verified_at != null || $investor->email_verified_at != '')){
            return $this->response(false, ucwords('Email has already been verified!'));
        }
        if($request->input('token') == $investor->email_verified_token){
            // Navigate to verify page
            return view('email.verifydata',['investor' => $investor,'token' => $request->input('token')]);
        }
        return $this->response(false, ucwords('Invalid Token!'));
    }


    public function notverify(){
        return view('auth.notverify');
    }

    public function postverifydata(Request $request){
        $investor = User::where('id', $request->investorId)->first();
        if($investor && ($investor->email_verified_at != null || $investor->email_verified_at != '')){
            return $this->response(false, ucwords('Email has already been verified!'));
        }
        if($request->token == $investor->email_verified_token){
            $investor->name = $request->name;
            $investor->phone = $request->phone;
            $investor->gender = $request->gender;
            $investor->address = $request->address;
            $investor->email_verified_at = date('Y-m-d H:i:s');
            $investor->email_verified_token = null;
            $investor->verify = 1;
            $investor->save();
            Auth::guard('investor')->login($investor);
            return $this->response(true, ucwords('Your email address has beeen verified successfully!'));
        }
        return $this->response(false, ucwords('Invalid Token!'));
    }
    
}