<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('phone', 'like', "%$s%");
            });
        }

        $users = $query->orderBy('role')->orderBy('name')->paginate(20)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
            'role' => ['required', Rule::in(['admin','student'])],
            'is_active' => ['nullable','boolean'],
            'phone' => ['nullable','string','max:20'],
            'address' => ['nullable','string','max:500'],
            'bio' => ['nullable','string','max:1000'],
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_active' => (bool)($validated['is_active'] ?? true),
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'bio' => $validated['bio'] ?? null,
            ]);

            DB::commit();
            return redirect()->route('users.index')->with('success', 'Tạo tài khoản thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: '.$e->getMessage()])->withInput();
        }
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'password' => ['nullable','string','min:8','confirmed'],
            'role' => ['required', Rule::in(['admin','student'])],
            'is_active' => ['nullable','boolean'],
            'phone' => ['nullable','string','max:20'],
            'address' => ['nullable','string','max:500'],
            'bio' => ['nullable','string','max:1000'],
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'is_active' => (bool)($validated['is_active'] ?? $user->is_active),
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'bio' => $validated['bio'] ?? null,
            ];
            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }
            $user->update($data);

            DB::commit();
            return redirect()->route('users.index')->with('success', 'Cập nhật tài khoản thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: '.$e->getMessage()])->withInput();
        }
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Không thể xóa tài khoản của chính bạn.');
        }
        if ($user->student()->exists()) {
            return back()->with('error', 'Không thể xóa tài khoản gắn với hồ sơ sinh viên.');
        }
        try {
            $user->delete();
            return back()->with('success', 'Đã xóa tài khoản.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: '.$e->getMessage());
        }
    }

    public function toggleActive(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Không thể thay đổi trạng thái tài khoản của chính bạn.');
        }
        $user->is_active = !$user->is_active;
        $user->save();
        return back()->with('success', 'Đã '.($user->is_active ? 'kích hoạt' : 'vô hiệu hóa').' tài khoản.');
    }
}
