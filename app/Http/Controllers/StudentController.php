<?php

namespace App\Http\Controllers;

use App\Http\Requests\Students\StudentPostRequest;
use App\Models\Student;
use Illuminate\Foundation\Exceptions\Renderer\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StudentController extends Controller
{
    private $authUser;
    public function __construct()
    {
        $this->authUser = Auth::user();
    }
public function test(){
    
}
    public function index(Request $request){
        $perPage = $request->input('per_page', 10);
        $search = $request->input('query');
        $searchTerm = strtolower(trim($search));

        $students = Student::where('user_id', $this->authUser->id)
        ->when($searchTerm, function ($query, $searchTerm) {
            return $query->where('name', 'like', '%' . $searchTerm . '%')
            ->orWhere('subject', 'like', '%' . $searchTerm . '%')
            ->orWhere('marks', 'like', '%' . $searchTerm . '%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate($perPage)
        ->withQueryString();

        return Inertia::render('Students/List', [
            'students' => $students,
            'query' => $search
        ]);
    }

    public function store(StudentPostRequest $request){
        $validated = $request->validated();
        try{
            if($validated){ 
                $name = strtolower($request->name);
                $subject = strtolower($request->subject);
                $studentById  = Student::find($request->id);
                $stuByNameAndSub = Student::where('name', $name)->where('subject', $subject)->first();
                if($studentById)
                    $studentById->update(['name'=> $name, 'subject'=> $subject, 'marks'=> $request->marks]);
                elseif($stuByNameAndSub)
                    $stuByNameAndSub->update(['name'=> $name, 'subject'=> $subject, 'marks'=> $request->marks]);
                else
                   Student::create(['name'=>$name, 'subject'=>$subject, 'marks'=>$request->marks, 'user_id'=>$this->authUser->id]);
        
                return back()->with('success', 'Student saved or updated successfully!');
            }
        }catch(Exception $e){
            info($e);
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function destroy(Request $request){
        $student = Student::find($request->id);
        $student->delete();
        return back()->with('error', 'Deleted successfully!');
    }

    public function bulkDestroy(Request $request){
        $students = Student::whereIn('id', $request->ids); 
        $students->delete();
        return back()->with('error', 'Students deleted successfully!');
    }
}
