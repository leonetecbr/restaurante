@extends('layout.app')
@section('title', 'Resumo da mesa #'.$tableId)
@section('content')
<main class="container-fluid">
    <div class="p-3 pb-2 mb-3 border-bottom d-flex justify-content-between">
        <h1 class="h2">Resumo da mesa #{{ $tableId }}</h1>
    </div>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger text-center mb-3 container">{{ $error }}</div>
        @endforeach
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success text-center mb-3 container">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="col-12 d-md-flex flex-wrap justify-content-around">
        <div class="card col-md-5 w-18 mx-auto">
            <div class="card-header h3 text-center">
                Produtos
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <ul class="list-group list-group-flush col-10">
                    @for($i = 0; !empty($details[$i]); $i++)
                    <li class="list-group-item d-flex justify-content-between lh-sm">
                        <div>
                            <h6 class="my-1">
                                <span class="badge bg-primary rounded-pill me-1">
                                    {{ $details[$i]['quantity'] }}
                                </span>
                                {{ $details[$i]['name'] }}
                            </h6>
                            <small class="text-muted">{{ $details[$i]['unitaryValue'] }}</small>
                        </div>
                        <div>
                            <span class="text-muted me-2">{{ $details[$i]['value'] }}</span>
                        </div>
                    </li>
                    @endfor
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total</span>
                        <strong id="total-products">{{ $details['total'] }}</strong>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card col-md-5 w-18 mx-auto mt-3 mt-md-0">
            <div class="card-header h3 text-center">
                Pagamentos
            </div>
            <div class="my-auto px-3">
                <div class="display-6 text-center">
                    {{ $price }}
                </div>
                <div class="fw-light text-muted text-center">
                    Valor total
                </div>
                <div class="mt-3">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Pago por {{ count($methods) }} pessoa(s)</li>
                        <li class="list-group-item">Valor de {{ $value }}/pessoa</li>
                        @for($i = 1; $i <= count($methods); $i++)
                            <li class="list-group-item">O cliente {{ $i }} pagou em(no) {{ $methods[$i-1] }}</li>
                        @endfor
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7 col-11 mx-auto my-3">
        <a class="btn btn-primary w-100" href="{{ route('garcom') }}">
            Voltar para o painel das mesas
        </a>
    </div>
</main>
@endsection
