<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm với tìm kiếm và phân trang
    public function index(Request $request)
{
    // Lấy các tham số lọc từ request
    $search = $request->query('search');
    $created_at = $request->query('created_at');
    $parent_id = $request->query('parent_id');
    $status = $request->query('status');

    // Khởi tạo query để lọc sản phẩm
    $query = Product::query();

    // Lọc theo từ khóa tìm kiếm
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%");
        });
    }

    // Lọc theo ngày tạo nếu có
    if ($created_at) {
        $query->whereDate('created_at', $created_at);
    }

    // Lọc theo danh mục cha
    if ($parent_id) {
        // Giả sử mỗi sản phẩm có một category_id và category có mối quan hệ với parent_id
        $query->whereHas('category', function ($q) use ($parent_id) {
            $q->where('parent_id', $parent_id);
        });
    }

    // Lọc theo tình trạng (status) nếu có
    if ($status) {
        $query->where('status', $status);
    }

    // Phân trang và trả về kết quả
    $products = $query->paginate(10)->appends($request->all());

    return view('admin.Products.index', compact('products', 'search', 'created_at', 'parent_id', 'status'));
}


    // Hiển thị form tạo sản phẩm mới
    public function create()
    {
        return view('admin.Products.create');
    }

    // Lưu sản phẩm vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'stock_quantity' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Nếu có upload ảnh
        ]);

        // Tạo slug tự động từ tên và kiểm tra trùng lặp
        $slug = Str::slug($request->name);
        if (Product::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        // Xử lý upload ảnh
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageUrl = $file->store('images/products', 'public');
        }

        // Tạo sản phẩm mới
        Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'image_url' => $imageUrl,
        ]);

        // Thông báo thêm thành công
        $flasher->addSuccess('Sản phẩm đã được thêm thành công!');

        return redirect()->route('products.index');
    }

    // Hiển thị form chỉnh sửa sản phẩm
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.Products.edit', compact('product'));
    }

    // Cập nhật sản phẩm
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'stock_quantity' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Nếu có upload ảnh
        ]);

        // Tạo slug tự động từ tên nếu không cung cấp
        $slug = Str::slug($request->name);
        if (Product::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $slug . '-' . time();
        }

        // Xử lý cập nhật ảnh nếu có upload ảnh mới
        $imageUrl = $product->image_url;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageUrl = $file->store('images/products', 'public');
        }

        // Cập nhật sản phẩm
        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'image_url' => $imageUrl,
        ]);

        // Thông báo cập nhật thành công
        $flasher->addSuccess('Sản phẩm đã được cập nhật!');

        return redirect()->route('products.index');
    }

    // Xóa sản phẩm
    public function destroy($id, FlasherInterface $flasher)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        // Thông báo xóa thành công
        $flasher->addSuccess('Sản phẩm đã được xóa!');

        return redirect()->route('products.index');
    }
}
