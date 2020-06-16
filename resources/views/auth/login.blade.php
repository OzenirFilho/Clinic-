@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
    @include('layouts.headers.guest')

    <div class="container login-container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <img src="{{ asset('argon') }}/img/brand/LogoAtestadoValido.png" class="img-fluid logo-login" />
                            <div>
                                @if (session('info'))
                                {!! session('info') !!}
                                <br>
                                @else
                                Digite seu <strong>CPF</strong> e <strong>senha</strong> cadastrados para acesso ao sistema.</small>
                                @endif
                            </div>
                            <small>

                        </div>

                        <form role="form" method="POST" action="{{ route('login.do') }}">
                            @csrf
                            @if ($errors->has('login'))
                                <div class="alert alert-danger" role="alert">
                                        <strong>{{ $errors->first('login') }}</strong>
                                </div>
                            @endif
                            @if (session('warning'))
                                <div class="alert alert-warning" role="alert">
                                        <strong>{!! session('warning') !!}</strong>
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                        <strong>{!! session('success') !!}</strong>
                                </div>
                            @endif
                            <div class="form-group mb-3">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    {{--  TEL Field: is numeric. But no arrows. Different from the Number type, I can specify a Max Length.  --}}
                                    <input class="form-control{{ $errors->has('cpf') ? ' is-invalid' : '' }} cpf" placeholder="CPF" type="tel" name="cpf" required autofocus maxlength="11" autocomplete="off" id="protected-field"  value="{{ old('cpf') }}" >
                                </div>
                                    @if ($errors->has('cpf'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('cpf') }}</strong>
                                        </span>
                                    @endif
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control" name="password" placeholder="Senha" type="password" required id="protected-field">
                                </div>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <input type="hidden" name="srstn" id="srstn" value="">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-4">Entrar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        @if(Route::has('senha.recuperar'))
                            <a href="{{ route('senha.recuperar') }}" class="text-light">
                                <small>Esqueceu sua senha?</small>
                            </a>
                        @endif
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
        console.log("The application is running in debug mode. Some security features are disabled");
    @endif
    $( document ).ready(function() {
        $('input[name=srstn]').val($(window).width()+'x'+$(window).height());
        $('.cpf').focus();
    });
    </script>
@endpush
