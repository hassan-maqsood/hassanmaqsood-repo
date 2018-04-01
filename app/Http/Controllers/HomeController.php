<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Storage;


class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
        $user = new User();
        $users = $user->getUsersList();

        $project = new Project();
        $userId = Auth::user()->id;
        $projects = $project->getUserProjects($userId);
		return view('home',['users' => count($users), 'projects' => count($projects)]);
	}

    /**
     * @return View
     */
    public function getCreateAdminUser()
    {
        //$user_id = Auth::user()->id;
        $user = new User();
        return view('layouts.create-user');
    }

    public function getListUsers()
    {
        $user = new User();
        $users = $user->getUsersList();
        return view('layouts.list-users', ['users' => $users]);
    }

    public function getListProjects()
    {
        $project = new Project();
        $userId = Auth::user()->id;
        $projects = $project->getUserProjects($userId);
        $status = ['unapproved' => 'unapproved', 'approved' => 'approved', 'inprogress' => 'inprogress', 'inreview' => 'inreview', 'published' => 'published' ,'rejected' => 'rejected'];
        return view('layouts.list-projects', ['projects' => $projects, 'status' => $status]);
    }

    public function getListProjectsForAdmin()
    {
        $project = new Project();
        $projects = $project->getAdminProjects();
        $status = ['unapproved' => 'unapproved', 'approved' => 'approved', 'inprogress' => 'inprogress', 'inreview' => 'inreview', 'published' => 'published' ,'rejected' => 'rejected'];
        return view('layouts.list-projects', ['projects' => $projects, 'status' => $status]);
    }

    public function getEditProject($project_id)
    {
        $project = Project::find($project_id);
        $status = ['unapproved' => 'unapproved', 'approved' => 'approved', 'inprogress' => 'inprogress', 'inreview' => 'inreview', 'published' => 'published' ,'rejected' => 'rejected'];
        return view('layouts.edit-project', ['project' => $project, 'status' => $status]);
    }

    public function getEditUser($user_id)
    {
        $user = User::find($user_id);
        return view('layouts.edit-user', ['user' => $user]);
    }

    /**
     * @return View
     */
    public function getCreateProject()
    {
        //$user_id = Auth::user()->id;
        $user = new User();
        return view('layouts.create-project');
    }

    public function getLogout()
    {
        Auth::logout();
        return redirect()->to('auth/login');
    }

    public function getApprovalRequest($user_id)
    {
        $user_obj = new User();
        $user = User::find($user_id);
        $password = $user_obj->getRandomPassword();
        $hashed_password = $user_obj->getHashedPassword($password);
        $user->password = $hashed_password;

        if ($user_obj->approveUserAccount($user->id)) {
            if ($user->save()) {

                $count = $user_obj->sendApprovalRequestEmail($user->email, $user->name, $password);
                if ($count) {
                    return redirect()->to('list-users')->with('global', 'Approval email has been sent to the user ' . $user->name. ' .');
                } else {
                    return redirect()->to('list-users')->with('global', 'Email sending failed for ' . $user->email . ' .Try Again');
                }
            } else {
                return redirect()->to('list-users')->with('global', 'Something went wrong');
            }
        } else {
            return redirect()->to('list-users')->with('global', 'Something went wrong');
        }
    }

    public function getRejectionRequest($user_id)
    {
        $user_obj = new User();
        $user = User::find($user_id);
        $user->status = 'rejected'; // remove this later

        if ($user_obj->rejectUserAccount($user->id)) {
            if ($user->save()) {

                $count = $user_obj->sendRejectionRequestEmail($user);
                if ($count) {
                    return redirect()->to('list-users')->with('global', 'Rejection email has been sent to the user ' . $user->name. ' .');
                } else {
                    return redirect()->to('list-users')->with('global', 'Email sending failed for ' . $user->email . ' .Try Again');
                }
            } else {
                return redirect()->to('list-users')->with('global-error', 'Something went wrong');
            }
        } else {
            return redirect()->to('list-users')->with('global-error', 'Something went wrong');
        }
    }

    public function postCreateNewProject(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'supervisor' => 'required',
                'lead_member' => 'required',
                'duration' => 'required|integer',
                'description' => 'required',
                "pdf_link" => 'required'
            ],
            [
                'name.required' => 'Project Title is required',
                'supervisor.required' => 'Project Supervisor is required',
                'lead_member.required' => 'Project Lead member\'s name is required',
                'description.required' => 'Project Description is required',
                'duration.required' => 'Project Duraiton is required in months',
                'duration.integer' => 'Project Duraiton should be in integer months',
                "pdf_link.required" => 'Document in .pdf is required'
            ]
        );

        if ($validator->fails()) {
            return redirect()->to('/create-project')->withInput()->withErrors($validator->errors());
        } else {

            $project = new Project();

            if ($request->hasFile('pdf_link')) {
                $extension = $input['pdf_link']->getClientOriginalExtension();
                $nameOfFile = md5(uniqid()) . '-' . strtotime("now") . '.' . $extension;
                Storage::put('project-uploads/'.$nameOfFile,file_get_contents($request->file('pdf_link')->getRealPath(),'public'));
                $project->pdf_link = $nameOfFile;
            }

            //create project
            $project->name = $input['name'];
            $project->supervisor = $input['supervisor'];
            $project->lead_member = $input['lead_member'];
            $project->duration = $input['duration'];
            $project->description = $input['description'];

            $userId = Auth::user()->id;
            $project->user_id = $userId;
        }

        if ($project->save()) {
            return redirect()->to('/list-projects')->with('global', 'Project submitted Successfully');
        } else {
            return redirect()->to('/create-project')->with('global-error', 'Random problem');
        }
    }

    public function postEditNewProject(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'supervisor' => 'required',
                'lead_member' => 'required',
                'duration' => 'required|integer',
                'description' => 'required',
            ],
            [
                'name.required' => 'Project Title is required',
                'supervisor.required' => 'Project Supervisor is required',
                'lead_member.required' => 'Project Lead member\'s name is required',
                'description.required' => 'Project Description is required',
                'duration.required' => 'Project Duraiton is required in months',
                'duration.integer' => 'Project Duraiton should be in integer months',
                "pdf_link.required" => 'Document in .pdf is required'
            ]
        );

        if ($validator->fails()) {
            return redirect()->to('/edit-project/'. $input['project_id'])->withInput()->withErrors($validator->errors());
        } else {
            $projectId = $input['project_id'];
            $project = Project::find($projectId);

            if ($request->hasFile('pdf_link')) {
                $extension = $input['pdf_link']->getClientOriginalExtension();
                $nameOfFile = md5(uniqid()) . '-' . strtotime("now") . '.' . $extension;
                Storage::put('project-uploads/'.$nameOfFile,file_get_contents($request->file('pdf_link')->getRealPath(),'public'));
                $project->pdf_link = $nameOfFile;
            }

            //create project
            $project->name = $input['name'];
            $project->supervisor = $input['supervisor'];
            $project->lead_member = $input['lead_member'];
            $project->duration = $input['duration'];
            $project->description = $input['description'];
            if(isset($input['status'])){
                $project->status = $input['status'];
            }
        }

        if ($project->save()) {
            return redirect()->to('/list-projects')->with('global', 'Project changed Successfully');
        } else {
            return redirect()->to('/edit-project')->with('global-error', 'Random problem');
        }
    }


    public function getDownloadDocument($pdfLinkName){

        $dir = storage_path('app/project-uploads/'.$pdfLinkName);
        $file = $dir;
        header('Content-Description: File Transfer');
        header("Content-Type: application/pdf");
        header('Content-Disposition: attachment; filename="'.$pdfLinkName.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        ob_clean();
        flush();
        readfile($file);
        exit;
    }

    public function postCreateNewUserByAdmin(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|unique:users',
                "name" => 'required',
                'school_name' => 'required|regex:/[A-Za-z0-9]/',
                'address' => 'required|min:5regex:/[A-Za-z0-9]/',
            ],
            [
                'name.required' => 'Name is required',
                'school_name.required' => 'School name is required',
                'email.required' => 'Email is required',
                "email.email" => 'Enter valid email',
                'address.required' => 'Please enter Address',
            ]
        );

        if ($validator->fails()) {
            return redirect()->to('/')->withInput()->withErrors($validator->errors());
        } else {

            $user = new User();

            //create user
            $user->name = $input['name'];
            $user->email = $input['email'];
            $user->address = $input['address'];
            $user->school_name = $input['school_name'];
            $user->status = 'approved';
            $role = $user->getUserRoleId();
            $user->role_id = $role->id;

            if ($user->save()) {

                $user_obj = new User();
                $user = User::find($user->id);
                $password = $user_obj->getRandomPassword();
                $hashed_password = $user_obj->getHashedPassword($password);
                $user->password = $hashed_password;
                $user->save();

                $count = $user_obj->sendApprovalRequestEmail($user->email, $user->name, $password);
                if ($count) {
                    return redirect()->to('/')->with('global', 'User created Successfully');
                } else {
                    return redirect()->to('/')->with('global-error', 'Some random problem occurred');
                }
            } else {
                return redirect()->to('/')->with('global-error', 'User Creation failed. Try again');
            }
        }
    }

    public function postEditNewUser(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(),
            [
                "name" => 'required',
                'school_name' => 'required|regex:/[A-Za-z0-9]/',
                'address' => 'required|min:5regex:/[A-Za-z0-9]/',
            ],
            [
                'name.required' => 'Name is required',
                'school_name.required' => 'School name is required',
                'address.required' => 'Please enter Address',
            ]
        );

        if ($validator->fails()) {
            return redirect()->to('/edit-user/'.$input['user_id'])->withInput()->withErrors($validator->errors());
        } else {

            $user = User::find($input['user_id']);

            //create user
            $user->name = $input['name'];
            $user->address = $input['address'];
            $user->school_name = $input['school_name'];

            if ($user->save()) {
                return redirect()->to('/')->with('global', 'User changes saved Successfully');
            } else {
                return redirect()->to('/edit-user/'.$input['user_id'])->with('global-error', 'Some random problem occurred');
            }
        }
    }









}
