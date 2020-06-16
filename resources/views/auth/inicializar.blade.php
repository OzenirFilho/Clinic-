@extends('layouts.guest', ['class' => 'bg-default'])

@section('content')
    @include('layouts.headers.guest')

    <div class="container login-container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <img src="{{ asset('argon') }}/img/brand/LogoAtestadoValido.png" class="img-fluid logo-login" />
                            <br>
                            <h3>Definir sua Senha</h3>
                            <small>Para ter acesso a este sistema é necessário que você defina uma senha para seu usuário. <strong> Sua senha precisa ter no mínimo 8 dígitos, uma letra e um número.</strong>
                            </small>
                            </p>


                        </div>
                        <form role="form" class="validatedForm" method="POST" action="{{ route('usuario.inicializar.do') }}">
                            @csrf
                            @if ($errors->has('login'))
                                <div class="alert alert-danger" role="alert">
                                        <strong>{{ $errors->first('login') }}</strong>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control senha" name="password" placeholder="Sua nova Senha" type="password" required id="protected-field">
                                </div>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control" name="password_confirmation" placeholder="Repita sua nova senha" type="password" required id="protected-field">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-4">Criar senha</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
    @if(env("APP_DEBUG")==false)
        $(document).contextmenu(function() {
            return false;
        });
        $(function(){
            $(document).on("cut copy paste","#protected-field",function(e) {
                alert('Infelizmente, copiar e colar não são permitidos nesta página.');
                e.preventDefault();
            });
        });
        $('#protected-field').contextmenu(function() {
            return false;
        });
    @else
        console.log("WARNING! The application is running in debug mode. Some security features are disabled");
    @endif
    //Deixar o campo de senha em foco
    $( document ).ready(function() {
        $('.senha').focus();
    });
    </script>
@endpush
