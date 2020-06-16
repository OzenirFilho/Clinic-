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
                            <div>
                                Digite seu <strong>endereço de e-mail</strong> cadastrado para recuperar seu acesso
                            </div>
                        </div>
                        <form role="form" method="POST" action="{{ route('senha.recuperar.request') }}">
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                    @foreach ($errors->all() as $error )
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="form-group mb-3">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                            <input class="form-control email" placeholder="seu@email.com" type="email" name="email" required autofocus autocomplete="off" id="protected-field"  value="{{ old('email') }}" >
                                </div>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                            </div>
                            <center>
                                @if((env('APP_DEBUG')==false) || (!env('DISABLE_CAPTCHA_IN_DEV') && env('APP_DEBUG')))
                                {!! NoCaptcha::display(['data-theme' => 'light']) !!}
                                @endif
    
                            </center>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-4">Enviar</button>
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
        $('.email').focus();
    });
    </script>
    {!! NoCaptcha::renderJs('pt_BR') !!}


@endpush
