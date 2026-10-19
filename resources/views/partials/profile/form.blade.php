<div class="container py-4" style="max-width: 800px;">
    <div class="card shadow-sm">
        <div class="card-header"><h1 class="h4 mb-0">Mi perfil</h1></div>
        <div class="card-body">
            <p class="text-muted">Esta tienda es una demostración. Los datos de contacto son opcionales; evita ingresar información real que no quieras conservar. Para cambiar el correo o el DNI, escribe también tu contraseña actual.</p>
            @if (session('status')) <div class="alert alert-success" role="status">{{ session('status') }}</div> @endif
            @if ($errors->any()) <div class="alert alert-danger" role="alert">Revisa los campos marcados.</div> @endif
            <form action="{{ $updateRoute }}" method="post">
                @csrf
                @method('put')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="names" class="form-label">Nombres</label>
                        <input id="names" name="names" class="form-control @error('names') is-invalid @enderror" value="{{ old('names', $user->names) }}" required>
                        @error('names') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="surnames" class="form-label">Apellidos (opcional)</label>
                        <input id="surnames" name="surnames" class="form-control @error('surnames') is-invalid @enderror" value="{{ old('surnames', $user->surnames) }}">
                        @error('surnames') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="dni" class="form-label">DNI (opcional)</label>
                        <input id="dni" name="dni" class="form-control @error('dni') is-invalid @enderror" value="{{ old('dni', $user->dni) }}" inputmode="numeric" maxlength="8">
                        @error('dni') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="birthdate" class="form-label">Fecha de nacimiento (opcional)</label>
                        <input id="birthdate" name="birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror" value="{{ old('birthdate', $user->birthdate) }}">
                        @error('birthdate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Celular (opcional)</label>
                        <input id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" inputmode="numeric" maxlength="9">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">Dirección (opcional)</label>
                        <input id="address" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $user->address) }}">
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label for="current_password" class="form-label">Contraseña actual, si cambias correo o DNI</label>
                        <input id="current_password" name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
                        @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-4">Guardar cambios</button>
            </form>
        </div>
    </div>
</div>
