<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm với tìm kiếm và phân trang
    public function index(Request $request)
    {
        $search = $request->query('search');
        $created_at = $request->query('created_at');
        $category_id = $request->query('category_id');
        $status = $request->query('status');

        $query = Product::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if ($created_at) {
            $query->whereDate('created_at', $created_at);
        }

        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $products = $query->paginate(10)->appends($request->all());
        $productCategories = ProductCategory::all();

        return view('admin.products.index', compact('products', 'productCategories', 'search', 'created_at', 'category_id', 'status'));
    }

    // Hiển thị form tạo sản phẩm mới
    public function create()
    {
        $productCategories = ProductCategory::all();
        return view('admin.products.create', compact('productCategories'));
    }

    // Lưu sản phẩm mới vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
      
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'image_url' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'category_id' => 'required|exists:product_categories,id',
        ]);

        $slug = Str::slug($request->name);
        if (Product::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        $imageUrl = null;
        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('productfiles'), $fileName);
            $imageUrl = 'productfiles/' . $fileName;
        }

        Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'stock_quantity' => $request->stock_quantity,
            'image_url' => $imageUrl,
            'category_id' => $request->category_id,
            'tags' => $request->tags,
            'status' => $request->status ?? 'active',
            'product_code' => $request->product_code,
        ]);

        $flasher->addSuccess('Sản phẩm đã được thêm thành công!');
        return redirect()->route('products.index');
    }

    // Hiển thị form chỉnh sửa sản phẩm
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $productCategories = ProductCategory::all();
        return view('admin.products.edit', compact('product', 'productCategories'));
    }

    // Cập nhật sản phẩm
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'image_url' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'category_id' => 'required|exists:product_categories,id',
        ]);

        $slug = Str::slug($request->name);
        if (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
            $slug = $slug . '-' . time();
        }

        if ($request->hasFile('image_url')) {
            if ($product->image_url && file_exists(public_path($product->image_url))) {
                unlink(public_path($product->image_url));
            }

            $file = $request->file('image_url');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('productfiles'), $fileName);
            $product->image_url = 'productfiles/' . $fileName;
        }

        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
            'tags' => $request->tags,
            'status' => $request->status ?? 'active',
            'product_code' => $request->product_code,
        ]);

        $flasher->addSuccess('Sản phẩm đã được cập nhật!');
        return redirect()->route('products.index');
    }

    // Xóa sản phẩm (soft delete)
    public function destroy($id, FlasherInterface $flasher)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        $flasher->addSuccess('Sản phẩm đã được xóa tạm thời!');
        return redirect()->route('products.index');
    }

    // Hiển thị danh sách sản phẩm đã xóa
    public function trashed()
    {
        $products = Product::onlyTrashed()->paginate(10);
        return view('admin.products.restore', compact('products'));
    }

    // Khôi phục sản phẩm đã xóa
    public function restore($id, FlasherInterface $flasher)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        $flasher->addSuccess('Sản phẩm đã được khôi phục!');
        return redirect()->route('products.trashed');
    }

    // Xóa vĩnh viễn sản phẩm
    public function forceDelete($id, FlasherInterface $flasher)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        if ($product->image_url && file_exists(public_path($product->image_url))) {
            unlink(public_path($product->image_url));
        }

        $product->forceDelete();
        $flasher->addSuccess('Sản phẩm đã được xóa vĩnh viễn!');
        return redirect()->route('products.trashed');
    }

    // Cập nhật trạng thái sản phẩm (active/inactive)
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $product = Product::findOrFail($request->id);
            $product->status = $request->status;
            $product->save();

            $message = $product->status === 'active'
                ? 'Sản phẩm đã được kích hoạt.'
                : 'Sản phẩm đã bị vô hiệu hóa.';

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật trạng thái sản phẩm.']);
        }
    }
}
