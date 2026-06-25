{{-- Form bersama untuk create & edit pengguna. Variabel: $user (opsional) --}}
@php($u = $user ?? null)

@if ($errors->any())
    <div class="alert alert-danger">
        <div class="fw-bold">Periksa kembali isian berikut:</div>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="mb-3">
            <label class="form-label required">Nama lengkap</label>
            <input type="text" name="name" value="{{ old('name', $u->name ?? '') }}"
                   class="form-control @error('name') is-invalid @enderror"
                   placeholder="mis. Admin Prodi" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label required">Email</label>
            <input type="email" name="email" value="{{ old('email', $u->email ?? '') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="nama@unm.ac.id" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label @if (! $u) required @endif">Kata sandi</label>
                <input type="password" name="password" autocomplete="new-password"
                       class="form-control @error('password') is-invalid @enderror"
                       @if (! $u) required @endif>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @if ($u)
                    <small class="form-hint">Kosongkan untuk mempertahankan sandi saat ini.</small>
                @endif
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label @if (! $u) required @endif">Konfirmasi kata sandi</label>
                <input type="password" name="password_confirmation" autocomplete="new-password"
                       class="form-control"
                       @if (! $u) required @endif>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if (auth()->user()->isAdmin())
            <div class="mb-3">
                <label class="form-label required">Peran</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror">
                    @foreach (\App\Models\User::ROLES as $val => $lbl)
                        <option value="{{ $val }}" @selected(old('role', $u->role ?? 'editor') === $val)>{{ $lbl }}</option>
                    @endforeach
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="form-hint">Editor hanya dapat mengelola konten. Administrator memiliki akses penuh.</small>
            </div>
        @elseif ($u)
            <div class="mb-3">
                <label class="form-label">Peran</label>
                <div><span class="badge bg-secondary-lt">{{ $u->roleLabel() }}</span></div>
            </div>
        @endif
    </div>
</div>

<div class="d-flex">
    <a href="{{ auth()->user()->isAdmin() ? route('admin.users.index') : url('/admin') }}" class="btn btn-link">Batal</a>
    <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
</div>
