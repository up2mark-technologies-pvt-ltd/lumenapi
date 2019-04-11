<?php

namespace App\Http\Controllers;

use App\Team;
use App\User;
use App\UserTeam;
use DB;
use Illuminate\Http\Request;

class TeamDataController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    // Creating user here...
    public function createTeam(Request $request)
    {

        // Validating the request parameters...
        $this->validate($request, [
            'title'      => 'required|unique:teams,title',
        ]);

        $team = Team::where('title', '=', $request->title)->first();

        // checking if team already exists...
        if ($team !== null) {
            return response()->json([
                'error' => "Team already exists...",
                'data' => $team
            ]);

        }   

        // creating new team with Team model...
         $team = new Team;

         $team->title = $request->title;

         $team->save();

         // respose for team creation...
         return response()->json([
             'success' => "Team Created Successfully...",
             'newteam' => $team,
         ]);
    }



    // showing all teams....
    public function readTeam(Request $request)
    {
         // Validating the request parameters...
        // $this->validate($request, [
        //     'email'     => 'required|email',
        // ]);

        $teams = Team::all();

        // checking if user exists...
        if ( count($teams) > 0 ) {
            return response()->json([
                'success' => "Showing all teams...",
                'data' => $teams
            ]);

        }

        // respose if user not found...
         return response()->json([
             'error' => "No team found...",
         ]);
    }


    // Update user by email only...
    public function updateTeam(Request $request)
    {
        // Validating the request parameters...
        $this->validate($request, [
            'title'     => 'required',
            'updatetitle'     => 'required',
        ]);

        $team = Team::where('title', '=', $request->title)->update(['title'=> $request->updatetitle]);

        // checking if user exists...
        if ($team) {

                return response()->json([
                    'success' => "Team updated successfully...",
                    'data' => $team
                ]);
      

        }

        // respose if user not found...
         return response()->json([
             'error' => "No team found with this title...",
             'newteam' => $request->title
         ]);
    }

    // Delete user by email only...
    public function deleteTeam(Request $request)
    {
        // Validating the request parameters...
        $this->validate($request, [
            'title'     => 'required',
        ]);

        $team = Team::where('title', '=', $request->title)->delete();

        // checking if user exists...
        if ($team) {
                return response()->json([
                    'success' => "Team deleted successfully...",
                    'data' => $team
                ]);
        }

        // respose if user not found...
         return response()->json([
             'error' => "No team found with this name...",
             'newteam' => $request->title
         ]);
    }

    // A user can be assigned to a teams and Set a user as team owner 
    public function assignUserToTeam(Request $request)
    {
        $this->validate($request, [
            'user_id'     => 'required|numeric',
            'team_id'     => 'required|numeric',
        ]);


        $team = Team::where('id', '=', $request->team_id)->first();

        if ( count($team) <= 0) {
            return response()->json([
                'success' => " No Team found with this id...",
                'data' => $request->team_id
            ]);
        }
        
        $user = User::where('id', '=', $request->user_id)->first();

        if ( count($user) <= 0) {
            return response()->json([
                'success' => " No User found with this id...",
                'data' => $request->user_id
            ]);
        }

        $already_exist = DB::table('user_team')->where('user_id', '=', $request->user_id)->where('team_id', '=', $request->team_id)->first();

        if ($already_exist) {
            return response()->json([
                'success' => " User already assigned to this team...",
                // 'data' => $request->user_id
            ]);
        }

        $inserted = DB::table('user_team')->insertGetId([
                                        'user_id' => $request->user_id,
                                        'team_id' => $request->team_id,
                                    ]);


        // make a user as team owner
        DB::table('teams')->where('id','=', $request->team_id)->update([
                                    'owner' => $user->name,
                                    ]);
        if ($inserted) {
            return response()->json([
                'success' => "User name " .$user->name. " successfully assigned to ".$team->title." team...",
                // 'data' => $request->user_id
            ]);
        }

    }

    // List what teams the users belong to
    public function userBelongToTeam(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required',
        ]);

        $users = DB::table('users')->where('name','=', $request->name)->first();

        if (!$users) {
            return response()->json([
                'error' => " User not found...",
                // 'data' => $request->user_id
            ]);
        }

        // finding teams for this user...
        $teams_name = DB::table('user_team')
                    ->where('user_team.user_id', '=', $users->id)
                    ->join('teams', 'user_team.team_id', '=', 'teams.id')
                    ->select('teams.*')
                    ->get();

        if ($teams_name->count() > 0) {
                $teamnames = [];
                foreach ($teams_name as $key => $value) {
                    $teamnames[] = $value->title;
                }

                return response()->json([
                    'data' => "user teams found",
                    'success' => $teamnames
                ]);    
        }else{
                return response()->json([
                    'message' => "No team assigned for this user",
                    'username' => $request->name
                ]);    

        }


    }

    //
}
