<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Teacher;
use App\Models\Calendar;
use App\Models\EducationHistory;
use App\Models\Experience;
use App\Models\User;
use App\Http\Requests\TeacherRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class TeacherController extends Controller
{
   /*public function index(Request $request)
{
    if ($request->ajax()) {
        $data = Teacher::select(['id', 'name', 'email', 'mobile', 'social_media'])->get(); 

        return datatables()->of($data)
            ->addColumn('action', function (Teacher $data) {
                $edit = route('teachers.edit', $data->id);
                $deleteLink = route('teachers.destroy', $data->id);
                $btn = '<a href="' . $edit . '" class="edit btn btn-success btn-sm">Edit</a>';
                $btn .= '<form action="' . $deleteLink . '" method="POST" style="display:inline-block;" class="delete-form">';
                $btn .= csrf_field();
                $btn .= method_field('DELETE');
                $btn .= '<button type="submit" class="btn btn-danger btn-sm delete-button">Delete</button>';
                $btn .= '</form>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    return view('teachers.index');
}*/
    public function index(Request $request)
{
    if ($request->ajax()) {
        $data = Teacher::select(['id', 'name', 'email', 'mobile', 'social_media'])->get(); 

        return datatables()->of($data)
            ->addColumn('action', function (Teacher $data) {
                $edit = route('teachers.edit', $data->id);
                $deleteLink = route('teachers.destroy', $data->id);
                $viewCalendar = route('calendar.index', $data->id); 

                $btn = '<a href="' . $viewCalendar . '" class="btn btn-info btn-sm">View Calendar</a> ';
                $btn .= '<a href="' . $edit . '" class="edit btn btn-success btn-sm">Edit</a> ';
                $btn .= '<form action="' . $deleteLink . '" method="POST" style="display:inline-block;" class="delete-form">';
                $btn .= csrf_field();
                $btn .= method_field('DELETE');
                $btn .= '<button type="submit" class="btn btn-danger btn-sm delete-button">Delete</button>';
                $btn .= '</form>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    return view('teachers.index');
}



    public function create()
    {
        $products = Product::all();
        return view('teachers.create', compact('products'));
    }
    public function store(TeacherRequest $request)
    {
        // dd($request->all());
            // dd($request->all(), $request->products, implode(',', $request->products));
        try {
            // Create the teacher
            $teacher = Teacher::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'about_teacher' => $request->about_teacher,
                'social_media' => $request->social_media,
                'user_id' => Auth::id(),
                'product_id' => $request->products ? implode(',', $request->products) : null,
            ]);
            // dd($teacher);
            // Create education histories
            if ($request->has('education.title')) {
                foreach ($request->input('education.title') as $index => $title) {
                    if ($title) {
                        EducationHistory::create([
                            'teacher_id' => $teacher->id,
                            'title' => $title,
                            'start_year' => $request->input('education.start_year')[$index],
                            'end_year' => $request->input('education.end_year')[$index],
                            'short_description' => $request->input('education.description')[$index],
                        ]);
                    }
                }
            }
            // Create experiences
            if ($request->has('experience.company_name')) {
            foreach ($request->input('experience.company_name') as $index => $company) {
                if ($company) {
                    Experience::create([
                        'teacher_id' => $teacher->id,
                        'company_name' => $company,
                        'start_year' => $request->input('experience.start_year')[$index],
                        'end_year' => $request->input('experience.end_year')[$index],
                        'description' => $request->input('experience.description')[$index],
                    ]);
                }
            }
        }
            return redirect()->route('teachers.index')->with('success', 'Teacher created successfully.');
        } 
        catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create teacher.'])->withInput();
        }
    }
    public function show(string $id)
    {
        
    }
    public function edit(string $id)
    {
        $teacher = Teacher::findOrFail($id);
        $products = Product::all();
        $selectedProducts = explode(',', $teacher->product_id); 
        $educationHistories = EducationHistory::where('teacher_id', $teacher->id)->get();
        $experiences = Experience::where('teacher_id', $teacher->id)->get();

        return view('teachers.edit', compact('teacher', 'products', 'selectedProducts', 'educationHistories', 'experiences'));
    }
                                                                                                                                                                                                                                                                                
    public function update(Request $request, string $id)
    {
        try {
            // Update the teacher
            $teacher = Teacher::findOrFail($id);
            $teacher->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'about_teacher' => $request->about_teacher,
                'social_media' => $request->social_media,
                'product_id' => $request->products ? implode(',', $request->products) : null,
            ]);

            // Update education histories
            if ($request->has('education.title')) {
                // Delete existing education histories
                EducationHistory::where('teacher_id', $teacher->id)->delete();

                foreach ($request->input('education.title') as $index => $title) {
                    if ($title) {
                        EducationHistory::create([
                            'teacher_id' => $teacher->id,
                            'title' => $title,
                            'start_year' => $request->input('education.start_year')[$index],
                            'end_year' => $request->input('education.end_year')[$index],
                            'short_description' => $request->input('education.description')[$index],
                        ]);
                    }
                }
            }

            // Update experiences
            if ($request->has('experience.company')) {
                // Delete existing experiences
                Experience::where('teacher_id', $teacher->id)->delete();

                foreach ($request->input('experience.company') as $index => $company) {
                    if ($company) {
                        Experience::create([
                            'teacher_id' => $teacher->id,
                            'company_name' => $company,
                            'start_year' => $request->input('experience.start_year')[$index],
                            'end_year' => $request->input('experience.end_year')[$index],
                            'description' => $request->input('experience.description')[$index],
                        ]);
                    }
                }
            }

            return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
        } 
        catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update teacher.'])->withInput();
        }
    }
    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                $teacher = Teacher::findOrFail($id);
                $teacher->delete();
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }
  






}
