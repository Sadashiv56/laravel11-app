<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use DataTables;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /* if ($request->ajax()) {
            $data = SubCategory::with('category')->get();
            return datatables()
                ->of($data)
                 ->addColumn('status', function (SubCategory $data) {
                    if ($data->status == 1) {
                       return 'Active';
                    } else {
                        return 'Inactive';
                    }
                })
                ->addColumn("action", function (SubCategory $data) {
                    $edit = route("subcategory.edit", $data->id);
                    $deleteLink = route("subcategory.destroy", $data->id);
                    $btn = '<a href="' . $edit . '" class="edit btn btn-danger btn-sm">Edit</a>';
                    $btn .= '<form action="' . $deleteLink . '" method="POST" style="display:inline-block;" class="delete-form">';
                    $btn .= csrf_field();
                    $btn .= method_field('DELETE');
                    $btn .= '<button type="submit" class="btn btn-primary btn-sm delete-button">Delete</button>';
                    $btn .= '</form>';
                    return $btn;
                })
                ->rawColumns(["action"])
                ->make(true);
                dd($data);
        } */
        return view('subcategory.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('subcategory.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'category_id' => 'required|numeric',
            'name' => 'required',
            'slug' => 'required|unique:subcategories,slug',
            'status' => 'required|in:1,0', // Validate status as 1 or 0
        ]);

        // Create the SubCategory
        SubCategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $request->slug,
             'status' => $request->status // Directly assign the status value
        ]);

        // Redirect with success message
        return redirect()->route('subcategory.index')
                         ->with('success', 'SubCategory created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
