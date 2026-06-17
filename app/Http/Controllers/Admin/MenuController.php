<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        return view('admin.menus.index', [
            'menus' => Menu::whereNull('parent_id')
                ->with('children.children')
                ->orderBy('urutan')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.menus.create', [
            'parents' => $this->parentOptions(),
        ]);
    }

    public function store(StoreMenuRequest $request): RedirectResponse
    {
        Menu::create($request->validated());

        return redirect()->route('admin.menus.index')->with('status', 'Menu ditambahkan.');
    }

    public function edit(Menu $menu): View
    {
        return view('admin.menus.edit', [
            'menu'    => $menu,
            'parents' => $this->parentOptions($menu),
        ]);
    }

    public function update(UpdateMenuRequest $request, Menu $menu): RedirectResponse
    {
        $data = $request->validated();

        // Cegah menu menjadi induk dirinya sendiri.
        if ((int) ($data['parent_id'] ?? 0) === $menu->id) {
            $data['parent_id'] = null;
        }

        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('status', 'Menu diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->delete(); // anak menu ikut terhapus (cascade FK)

        return redirect()->route('admin.menus.index')->with('status', 'Menu dihapus.');
    }

    /**
     * Opsi induk: hanya item level atas (maks. 2 level induk → total 3 level),
     * mengecualikan menu yang sedang diedit.
     *
     * @return \Illuminate\Support\Collection<int, Menu>
     */
    private function parentOptions(?Menu $except = null)
    {
        return Menu::with('children')
            ->where(fn ($q) => $q->whereNull('parent_id')
                ->orWhereHas('parent', fn ($p) => $p->whereNull('parent_id')))
            ->when($except, fn ($q) => $q->where('id', '!=', $except->id))
            ->orderBy('parent_id')
            ->orderBy('urutan')
            ->get();
    }
}
