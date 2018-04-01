<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

    public function getUserRoleId()
    {
        $role_id = DB::table('roles')
            ->select('id')
            ->where('role', 'like', 'user')
            ->first();
        return $role_id;
    }

    public function sendPendingRequestEmail($email, $name)
    {
        $data = array('email' => $email, 'name' => $name);

        $count = Mail::send('layouts.registration-pending-email', $data, function ($message) use ($data) {
            $message->from(config('mail.username'), 'PM Team');
            $message->to($data['email'])->subject('Registration-Pending');
        });
        return $count;
    }

    public function getUsersList(){

        return DB::table('users')->where('role_id','!=',1)->get();
    }

    public function getRandomPassword()
    {
        $password = str_random(8);
        return $password;
    }

    /**
     * get hashed password
     *
     * @param $password
     * @return int
     */
    public function getHashedPassword($password)
    {
        $hashed_password = Hash::make($password);
        return $hashed_password;
    }

    public function approveUserAccount($userId){

        $status = DB::table('users')
            ->where('id', $userId)
            ->update(['status' => 'approved']);
        return $status;
    }

    public function rejectUserAccount($userId){

        $status = DB::table('users')
            ->where('id', $userId)
            ->update(['status' => 'rejected']);
        return $status;
    }

    public function sendApprovalRequestEmail($email,$name,  $random_password)
    {
        $data = array('email' => $email,'name' => $name, 'password' => $random_password);

        $count = Mail::send('layouts.registration-email', $data, function ($message) use ($data) {
            $message->from(config('mail.username'), 'PM Team');
            $message->to($data['email'])->subject('Login Credentials');
        });
        return $count;
    }

    public function sendRejectionRequestEmail($user)
    {
        $data = array('email' => $user->email, 'name' => $user->name);

        $count = Mail::send('layouts.rejection-email', $data, function ($message) use ($data) {
            $message->from(config('mail.username'), 'PM Team');
            $message->to($data['email'])->subject('Registration Declined');
        });

        return $count;
    }

}
