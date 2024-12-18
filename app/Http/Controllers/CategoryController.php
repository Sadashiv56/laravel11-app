<?php
namespace App\Http\Controllers;
use Exception;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CategoryValidationRequest;
use App\Http\Requests\CategoryUpdateValidationRequest;
use App\Http\Requests\SubCategoryValidationRequest;
use Illuminate\Http\JsonResponse;
use DataTables;
use Illuminate\Support\Facades\File;
class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::all();
            return datatables()
                ->of($data)
                ->addColumn('status', function (Category $data) {
                    if ($data->status == 1) {
                        return 'Active';
                    } else {
                        return 'Inactive';
                    }
                })
                ->addColumn('action', function (Category $data) {
                    $edit = route('categories.edit', $data->id);
                    $deleteLink = route('categories.destroy', $data->id);
                    $btn = '<a href="' . $edit . '" class="edit btn btn-success btn-sm">Edit</a>';
                    $btn .= '<form action="' . $deleteLink . '" method="POST" style="display:inline-block;" class="delete-form">';
                    $btn .= csrf_field();
                    $btn .= method_field('DELETE');
                    $btn .= '<button type="submit" class="btn btn-danger btn-sm delete-button">Delete</button>';
                    $btn .= '</form>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('category.index');
    }
    public function create()
    {
        // $categories = Category::all(); 
        $categories = Category::where('status', 1)->get(); 
        return view('category.create', compact('categories'));
    }
   public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|unique:categories,name',
            'status' => 'required|boolean',
            'parent_id' => 'nullable|integer'
        ]);
        $parent_id = $request->parent_id ?? 0;
        $category = new Category();
        $category->name = $request->name;
        $category->status = $request->status;
        $category->parent_id = $parent_id;
        $category->save();
        return response()->json([
            'success' => true,
            'message' => 'Category saved successfully!',
            'redirect_url' => route('categories.index')
        ]);
    }
    public function show(string $id)
    {
        //
    }
    public function edit($id)
    {
        $category = Category::find($id);
        $categories = Category::where('id', '!=', $id)->get();
        return view('category.edit', compact('category', 'categories'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        try {
            $category = Category::findOrFail($id);
            $category->name = $request->name;
            $category->status = $request->status;
            $category->parent_id = $request->parent_id ?? 0;
            $category->save();
            if ($category->status == 0) {
                $childCategories = Category::where('parent_id', $category->id)->get();
                foreach ($childCategories as $childCategory) {
                    $childCategory->status = 0;
                    $childCategory->save();
                }
            }
            return redirect()->route('categories.index')
                             ->with('success', 'Category updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to update category');
        }
    }
    public function destroy(string $id)
    {
        if (request()->ajax()) {
            try {
                $category = Category::findOrFail($id);
                $subcategories = Category::where('parent_id', $category->id)->get();
                if ($subcategories->isNotEmpty()) {
                    foreach ($subcategories as $subcategory) {
                        $subcategory->update(['parent_id' => $category->parent_id]);
                    }
                }
                $category->delete();
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        return redirect()->route('categories.index')
                        ->with('success', 'Category and its subcategories deleted successfully');
    }
    public function checkName(Request $request)
    {
        $name = $request->input('name');
        $exists = Category::where('name', $name)->exists();
        return response()->json(!$exists);
    }
    public function getChildren(Request $request)
    {
        $parentId = $request->input('parent_id');
        $categories = Category::where('parent_id', $parentId)->get();
        return response()->json(['categories'=>$categories]);
    }
}