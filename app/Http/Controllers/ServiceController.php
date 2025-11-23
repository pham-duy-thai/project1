<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 📋 Danh sách dịch vụ
     */
    public function index()
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403, 'Bạn không có quyền truy cập.');
        }

        $services = Service::orderBy('id', 'desc')->get();

        return view('admin.services.index', compact('services'))
            ->with('layout', 'layout2.theme');
    }

    /**
     * ➕ Form thêm dịch vụ
     */
    public function create()
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        return view('admin.services.create')
            ->with('layout', 'layout2.theme');
    }

    /**
     * 💾 Lưu dịch vụ mới
     */
    public function store(Request $request)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $validated = $request->validate([
            'name'  => 'required|string|max:255|unique:services,name',
            'price' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Vui lòng nhập tên dịch vụ.',
            'name.unique'   => 'Tên dịch vụ đã tồn tại.',
            'price.required' => 'Vui lòng nhập giá dịch vụ.',
            'price.numeric'  => 'Giá dịch vụ phải là số.',
        ]);

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Thêm dịch vụ thành công!');
    }

    /**
     * ✏️ Form chỉnh sửa dịch vụ
     */
    public function edit($id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $service = Service::findOrFail($id);

        return view('admin.services.edit', compact('service'))
            ->with('layout', 'layout2.theme');
    }

    /**
     * 🔁 Cập nhật dịch vụ
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:255|unique:services,name,' . $service->id,
            'price' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Vui lòng nhập tên dịch vụ.',
            'name.unique'   => 'Tên dịch vụ đã tồn tại.',
            'price.required' => 'Vui lòng nhập giá dịch vụ.',
            'price.numeric'  => 'Giá dịch vụ phải là số.',
        ]);

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Cập nhật dịch vụ thành công!');
    }

    /**
     * 🗑️ Xóa dịch vụ
     */
    public function destroy($id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $service = Service::findOrFail($id);
        $service->delete();

        return back()->with('success', 'Xóa dịch vụ thành công!');
    }
}
