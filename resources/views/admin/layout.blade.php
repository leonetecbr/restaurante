@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-auto d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('admin'))active @endif"
                               href="{{ route('admin') }}">
                                <i class="bi bi-bar-chart"></i>
                                Resumo
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('admin.products'))active @endif"
                               href="{{ route('admin.products')}}">
                                <i class="bi bi-cart"></i>
                                Produtos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('admin.tables'))active @endif"
                               href="{{ route('admin.tables')}}">
                                <i class="bi bi-people"></i>
                                Mesas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('admin.orders'))active @endif"
                               href="{{ route('admin.orders')}}">
                                <i class="bi bi-file-earmark"></i>
                                Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('admin.payments'))active @endif"
                               href="{{ route('admin.payments')}}">
                                <i class="bi bi-currency-dollar"></i>
                                Pagamentos
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="col-md-9 col-lg-10 ps-md-0 mx-auto">
                <div class="pt-3 pb-2 mb-3 border-bottom d-flex justify-content-between">
                    <h1 class="h2">@yield('title')</h1>
                    @yield('plus')
                </div>
                @yield('section')
            </main>
        </div>
    </div>
@endsection
