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
        $parent_id = $request->query('parent_id');
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

        if ($parent_id) {
            $query->whereHas('category', function ($q) use ($parent_id) {
                $q->where('parent_id', $parent_id);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $products = $query->paginate(10)->appends($request->all());
        $categories = ProductCategory::all();

        return view('admin.products.index', compact('products', 'categories', 'search', 'created_at', 'parent_id', 'status'));
    }

    // Hiển thị form tạo sản phẩm mới
    public function create()
    {
        $categories = ProductCategory::all();
        return view('admin.products.create', compact('categories'));
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
        $imageUrl = 'productfiles/' . $fileName; // Lưu đường dẫn tương đối vào cơ sở dữ liệu
    }

    Product::create([
        'name' => $request->name,
        'slug' => $slug,
        'description' => $request->description,
        'summary' => $request->summary,
        'price' => $request->price,
        'discount_price' => $request->discount_price,
        'stock_quantity' => $request->stock_quantity,
        'image_url' => $imageUrl,
        'category_id' => $request->category_id,
        'ingredients' => $request->ingredients,
        'tags' => $request->tags,
        'status' => $request->status ?? 'available',
        'product_code' => $request->product_code,
        'position' => $request->position,
    ]);

    $flasher->addSuccess('Sản phẩm đã được thêm thành công!');
    return redirect()->route('products.index');
}

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
        // Xóa ảnh cũ nếu tồn tại
        if ($product->image_url && file_exists(public_path($product->image_url))) {
            unlink(public_path($product->image_url));
        }

        // Lưu ảnh mới
        $file = $request->file('image_url');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('productfiles'), $fileName);
        $product->image_url = 'productfiles/' . $fileName;
    }

    $product->update([
        'name' => $request->name,
        'slug' => $slug,
        'description' => $request->description,
        'summary' => $request->summary,
        'price' => $request->price,
        'discount_price' => $request->discount_price,
        'stock_quantity' => $request->stock_quantity,
        'category_id' => $request->category_id,
        'ingredients' => $request->ingredients,
        'tags' => $request->tags,
        'status' => $request->status ?? 'available',
        'product_code' => $request->product_code,
        'position' => $request->position,
    ]);

    $flasher->addSuccess('Sản phẩm đã được cập nhật!');
    return redirect()->route('products.index');
}

    // Hiển thị form chỉnh sửa sản phẩm
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = ProductCategory::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Cập nhật sản phẩm trong cơ sở dữ liệu
    

    // Xóa sản phẩm khỏi cơ sở dữ liệu
    public function destroy($id, FlasherInterface $flasher)
    {
        $product = Product::findOrFail($id);

        if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
            Storage::disk('public')->delete($product->image_url);
        }

        $product->delete();
        $flasher->addSuccess('Sản phẩm đã được xóa!');
        return redirect()->route('products.index');
    }

    // Cập nhật trạng thái sản phẩm (available/unavailable)
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
