@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <span class="alert-inner--icon"><i class="ni ni-check-bold"></i></span>
        <span class="alert-inner--text">{{ session('success') }}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if(session('warning'))
<div class="alert alert-warning" role="alert">
    {{ session('warning') }}
</div>
@endif

{{--  @perfil("admin")
    <div class="alert alert-warning" role="alert">
        <strong>Cuidado!<br>Você é um usuário administrador.</strong>
    </div>
@endperfil  --}}
