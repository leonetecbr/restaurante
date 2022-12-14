@extends('layout.app')
@section('title', 'Login')
@section('content')
    <main class="mt-auto">
        <form
            class="text-center needs-validation d-flex flex-column justify-content-center align-items-center form-signin m-auto p-3"
            method="post" action="{{ route('auth') }}" novalidate>
            <img class="mb-4" src="{{ url('img/icon.png') }}" alt="Parto com garfo e faca" width="90" height="90">
            <h1 class="h3 mb-3 fw-normal">Acessar o sistema</h1>
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger text-center w-100 mb-3 mx-auto">{{ $error }}</div>
                @endforeach
            @endif
            @csrf
            <div class="form-floating text-start w-100">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                       placeholder="name@example.com" required value="{{ old('email') }}">
                <label for="email">E-mail</label>
            </div>
            <div class="form-floating text-start w-100">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                       name="password" placeholder="Senha" required>
                <label for="password">Senha</label>
            </div>
            <div class="d-flex justify-content-between mt-3 w-100">
                <div class="checkbox mb-3">
                    <label>
                        <input type="checkbox" value=true name="remember"> Lembrar-me
                    </label>
                </div>
                <div class="mb-2 pointer" id="show-pass">
                    <i class="bi bi-eye-fill" id="icon-show-pass"></i> <span id="text-show-pass">Mostrar</span> senha
                </div>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Entrar</button>
        </form>
    </main>
@endsection
