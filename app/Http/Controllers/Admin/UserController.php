<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($cari = $request->query('cari')) {
            $query->where('name', 'like', "%{$cari}%")
                ->orWhere('email', 'like', "%{$cari}%");
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.users.index', ['users' => $users]);
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('status', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(Request $request, User $user): View
    {
        $this->authorizeManage($request, $user);

        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorizeManage($request, $user);

        $data = $request->validated();
        $actor = $request->user();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // jangan timpa sandi lama dengan null
        }

        // Hanya admin yang boleh menetapkan peran; cegah admin terakhir menurunkan diri sendiri.
        if (! $actor->isAdmin()) {
            unset($data['role']);
        } elseif (isset($data['role']) && $data['role'] !== 'admin' && $user->isAdmin() && $this->adminCount() <= 1) {
            return back()->with('error', 'Tidak dapat menurunkan admin terakhir menjadi editor.')->withInput();
        }

        $user->update($data);

        $redirect = $actor->isAdmin()
            ? redirect()->route('admin.users.index')
            : redirect()->route('admin.users.edit', $user);

        return $redirect->with('status', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Non-admin hanya boleh menyunting akunnya sendiri.
     */
    private function authorizeManage(Request $request, User $user): void
    {
        abort_unless($request->user()->isAdmin() || $request->user()->id === $user->id, 403);
    }

    private function adminCount(): int
    {
        return User::where('role', 'admin')->count();
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        // Lindungi: tidak boleh menghapus akun sendiri.
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if (Post::where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Akun ini masih memiliki tulisan. Pertahankan akun agar tulisan dan nama penulis tidak hilang.');
        }

        // Lindungi: harus selalu ada minimal satu pengguna.
        if (User::count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus pengguna terakhir.');
        }

        // Lindungi: harus selalu ada minimal satu administrator.
        if ($user->isAdmin() && $this->adminCount() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus administrator terakhir.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('status', 'Pengguna berhasil dihapus.');
    }
}
