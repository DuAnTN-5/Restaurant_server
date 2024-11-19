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
        $search = $request->input('search');
        $status = $request->input('status');
        $parentId = $request->input('parent_id');
        $createdAt = $request->input('created_at');

        $query = ProductCategory::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('slug', 'LIKE', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($createdAt) {
            $query->whereDate('created_at', $createdAt);
        }

        if ($parentId) {
            $query->where('parent_id', $parentId);
        }

        $productCategories = $query->paginate(5)->appends($request->all());
        $productCategoriesParent = ProductCategory::whereNull('parent_id')->get();

        return view('admin.productcategories.index', compact('productCategories', 'productCategoriesParent'));
    }

    // Hiển thị form tạo mới danh mục
    public function create()
    {
        $productCategories = ProductCategory::all();
        return view('admin.productcategories.create', compact('productCategories'));
    }

    // Lưu danh mục mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        $slug = $request->input('slug') ?: Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        while (ProductCategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->storeAs('productfiles', time() . '.' . $image->getClientOriginalExtension(), 'public');
        }

        ProductCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => 'active',
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('product-categories.index')->with('success', 'Danh mục sản phẩm đã được thêm thành công!');
    }

    // Hiển thị form chỉnh sửa danh mục
    public function edit($id)
    {
        $productCategory = ProductCategory::findOrFail($id);
        $productCategories = ProductCategory::all();
        return view('admin.productcategories.edit', compact('productCategory', 'productCategories'));
    }

    // Cập nhật danh mục sản phẩm
    public function update(Request $request, $id)
    {
        $productCategory = ProductCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        $slug = $request->input('slug') ?: Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        while (ProductCategory::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        if ($request->hasFile('image')) {
            if ($productCategory->image && Storage::disk('public')->exists($productCategory->image)) {
                Storage::disk('public')->delete($productCategory->image);
            }
            $image = $request->file('image');
            $imagePath = $image->storeAs('productfiles', time() . '.' . $image->getClientOriginalExtension(), 'public');
            $productCategory->image = $imagePath;
        }

        $productCategory->update([
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
        $productCategory = ProductCategory::findOrFail($id);

        if ($productCategory->image && Storage::disk('public')->exists($productCategory->image)) {
            Storage::disk('public')->delete($productCategory->image);
        }

        $productCategory->delete();

        return redirect()->route('product-categories.index')->with('success', 'Danh mục sản phẩm đã được xóa!');
    }

    // Cập nhật trạng thái danh mục sản phẩm (active/inactive)
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:product_categories,id',
            'status' => 'required|in:active,inactive',
        ]);

        $productCategory = ProductCategory::findOrFail($request->id);
        $productCategory->status = $request->status;
        $productCategory->save();

        $message = $productCategory->status === 'active'
            ? 'Danh mục sản phẩm đã được kích hoạt.'
            : 'Danh mục sản phẩm đã bị vô hiệu hóa.';

        return response()->json(['success' => true, 'message' => $message]);
    }
    public function trashed(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $parentId = $request->input('parent_id');
        $createdAt = $request->input('created_at');

        $query = ProductCategory::onlyTrashed();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('slug', 'LIKE', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($createdAt) {
            $query->whereDate('created_at', $createdAt);
        }

        if ($parentId) {
            $query->where('parent_id', $parentId);
        }

        $productCategories = $query->paginate(5)->appends($request->all());
        $productCategoriesParent = ProductCategory::whereNull('parent_id')->get();

        return view('admin.productcategories.trashed', compact('productCategories', 'productCategoriesParent'));
    }

    // Khôi phục một danh mục đã bị xóa mềm
    public function restore($id)
    {
        $productCategory = ProductCategory::onlyTrashed()->findOrFail($id);
        $productCategory->restore();

        return redirect()->route('product-categories.trashed')->with('success', 'Danh mục sản phẩm đã được khôi phục!');
    }

    // Xóa vĩnh viễn một danh mục
    public function forceDelete($id)
    {
        $productCategory = ProductCategory::onlyTrashed()->findOrFail($id);

        if ($productCategory->image && Storage::disk('public')->exists($productCategory->image)) {
            Storage::disk('public')->delete($productCategory->image);
        }

        $productCategory->forceDelete();

        return redirect()->route('product-categories.trashed')->with('success', 'Danh mục sản phẩm đã được xóa vĩnh viễn!');
    }
}

