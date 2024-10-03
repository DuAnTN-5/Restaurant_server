<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductCategoriesController extends Controller
{
    // Hiển thị danh sách các danh mục sản phẩm
    public function index(Request $request)
    {
        // Lấy các tham số từ request
        $search = $request->input('search');
        $status = $request->input('status');
        $parentId = $request->input('parent_id');
        $createdAt = $request->input('created_at');

        // Khởi tạo query cơ bản
        $query = ProductCategory::query();

        // Nếu có từ khóa tìm kiếm, tìm theo tên hoặc slug
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('slug', 'LIKE', "%{$search}%");
            });
        }

        // Nếu có tình trạng
        if ($status) {
            $query->where('status', $status);
        }

        // Nếu có ngày tạo
        if ($createdAt) {
            $query->whereDate('created_at', $createdAt);
        }

        // Nếu có lọc theo danh mục cha
        if ($parentId) {
            $query->where('parent_id', $parentId);
        }

        // Lấy danh sách danh mục với phân trang và giữ lại các tham số lọc
        $categories = $query->paginate(10)->appends($request->all());

        // Lấy danh sách danh mục cha để hiển thị trong dropdown
        $categoriesParent = ProductCategory::whereNull('parent_id')->get();

        // Trả về view với các dữ liệu cần thiết
        return view('admin.productcategories.index', compact('categories', 'categoriesParent'));
    }

    // Hiển thị form tạo mới danh mục
    public function create()
    {
        $categories = ProductCategory::all(); // Lấy danh mục cha
        return view('admin.productcategories.create', compact('categories'));
    }

    // Lưu danh mục mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        // Tạo slug tự động từ tên nếu không có slug
        $slug = $request->input('slug') ? $request->input('slug') : Str::slug($request->name);

        // Đảm bảo slug là duy nhất
        $originalSlug = $slug;
        $counter = 1;
        while (ProductCategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Xử lý file ảnh nếu có
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->storeAs('productfiles', time().'.'.$image->getClientOriginalExtension(), 'public');
        }

        // Tạo danh mục sản phẩm mới
        ProductCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => 'active', // Mặc định trạng thái là active
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('product-categories.index')->with('success', 'Danh mục sản phẩm đã được thêm thành công!');
    }

    // Hiển thị form chỉnh sửa danh mục
    public function edit($id)
    {
        $category = ProductCategory::findOrFail($id);
        $categories = ProductCategory::all(); // Lấy danh mục cha
        return view('admin.productcategories.edit', compact('category', 'categories'));
    }

    // Cập nhật danh mục sản phẩm
    public function update(Request $request, $id)
    {
        $category = ProductCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        // Tạo slug tự động từ tên nếu không có slug
        $slug = $request->input('slug') ? $request->input('slug') : Str::slug($request->name);

        // Đảm bảo slug là duy nhất và không trùng với các danh mục khác (ngoại trừ danh mục hiện tại)
        $originalSlug = $slug;
        $counter = 1;
        while (ProductCategory::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Xử lý file ảnh nếu có
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            // Lưu ảnh mới
            $image = $request->file('image');
            $imagePath = $image->storeAs('productfiles', time().'.'.$image->getClientOriginalExtension(), 'public');
            $category->image = $imagePath;
        }

        // Cập nhật danh mục sản phẩm
        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('product-categories.index')->with('success', 'Danh mục sản phẩm đã được cập nhật thành công!');
    }

    // Xóa danh mục sản phẩm
    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);

        // Xóa ảnh nếu tồn tại
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        // Xóa danh mục
        $category->delete();

        return redirect()->route('product-categories.index')->with('success', 'Danh mục sản phẩm đã được xóa!');
    }
    public function toggleStatus($id)
    {
    $category = ProductCategory::findOrFail($id);

    // Thay đổi trạng thái
    $category->status = $category->status === 'active' ? 'inactive' : 'active';
    $category->save();

    return response()->json(['status' => $category->status]);
    }

}
