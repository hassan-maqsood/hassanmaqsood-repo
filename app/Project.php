<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    public function getUserProjects($userId){
        return DB::table('projects')->where('user_id',$userId)->get();
    }

    public function getAdminProjects(){
        return DB::table('projects')
            ->select('projects.name as project_name','projects.*','users.*','users.name as user_name')
            ->leftJoin('users', 'projects.user_id', '=', 'users.id')
            ->where('users.id','!=',1)
            ->get();
    }
}
