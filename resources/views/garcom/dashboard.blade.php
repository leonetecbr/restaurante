@extends('layout.app')
@section('title', 'Painel do garçom')
@section('content')
<main class="container-fluid">
    <div class="p-3 pb-2 mb-3 border-bottom d-flex justify-content-between">
        <h1 class="h2">Mesas</h1>
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
    <div class="d-flex flex-wrap justify-content-evenly">
        @foreach($tables as $table)
        <div class="card border-{{ ($table->busy)?'danger':'success' }} mb-3 me-2" style="max-width: 18rem;">
            <div class="card-header">Mesa #{{ $table->id }}</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="me-auto">
                            <div class="fw-bold">Status</div>
                            <span class="text-muted">{{ ($table->busy)?'Ocupada':'Livre' }}</span>
                        </div>
                        <span>{!! $table->getBusyStatus() !!}</span>
                    </li>
                </ul>
            </div>
            <div class="card-footer bg-transparent">
                @if ($table->busy)
                    <button class="btn btn-primary btn-manage-table" data-table-id="{{ $table->id }}">
                        Ajustar mesa
                    </button>
                @else
                    <a href="{{ route('garcom.tables.busy', $table->id) }}" class="btn btn-danger">
                        Ocupar mesa
                    </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    {{ $tables->links() }}
    <div class="my-3 p-3 bg-body rounded shadow-sm d-none container" id="manage-table">
        <h2 class="h3 pb-2 border-bottom mb-3 d-flex justify-content-between">
            <span>Mesa #<span id="manage-table-id"></span></span>
            <button class="btn btn-close" id="manage-table-close"></button>
        </h2>
        <div class="col-md-7 mx-auto px-3 border-bottom">
            <div id="load" class="placeholder-glow mb-3 d-none">
                <ul class="list-group mb-3 placeholder-glow">
                    <li class="list-group-item d-flex justify-content-between lh-sm">
                        <div class="col-6">
                            <h6 class="my-1 d-flex">
                                <span class="placeholder bg-primary me-1 col-2"></span>
                                <span class="placeholder col-7"></span>
                            </h6>
                            <small class="placeholder col-4 text-muted"></small>
                        </div>
                        <div class="col-3">
                            <span class="placeholder text-muted me-2 col-12"></span>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-sm">
                        <div class="col-6">
                            <h6 class="my-1 d-flex">
                                <span class="placeholder bg-primary me-1 col-2"></span>
                                <span class="placeholder col-7"></span>
                            </h6>
                            <small class="placeholder col-4 text-muted"></small>
                        </div>
                        <div class="col-3">
                            <span class="placeholder text-muted me-2 col-12"></span>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-sm">
                        <div class="col-6">
                            <h6 class="my-1 d-flex">
                                <span class="placeholder bg-primary me-1 col-2"></span>
                                <span class="placeholder col-7"></span>
                            </h6>
                            <small class="placeholder col-4 text-muted"></small>
                        </div>
                        <div class="col-3">
                            <span class="placeholder text-muted me-2 col-12"></span>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="placeholder col-2 text-muted"></span>
                        <strong class="placeholder col-3"></strong>
                    </li>
                </ul>
                <div class="d-flex flex-column justify-content-center col-10 col-md-5 mx-auto">
                    <button class="btn btn-primary disabled placeholder mb-3">
                    </button>
                    <button class="btn btn-danger disabled placeholder">
                    </button>
                </div>
            </div>
            <div id="load-error" class="mb-3 d-none">
                <div class="alert alert-danger w-100 text-center">Não foi possível carregar os detalhes, tente
                    novamente mais tarde!
                </div>
            </div>
            <div id="manage" class="d-none">
                <ul class="list-group mb-3">
                    <div id="products"></div>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total</span>
                        <strong id="total-products">R$ 0,00</strong>
                    </li>
                </ul>
            </div>
        </div>
        <div class="mt-3 d-none" id="btn-manage">
            <div class="d-flex flex-column justify-content-center col-10 col-md-5 mx-auto">
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#add-products">
                    Adicionar produtos
                </button>
                <button class="btn btn-danger" id="btn-close-bill" data-bs-toggle="modal" data-bs-target="#close-bill">
                    Fechar conta
                </button>
                <a class="btn btn-danger" data-href="{{ route('garcom.tables.busy', '') }}" id="btn-vacant-table">
                    Desocupar mesa
                </a>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="add-products">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar produtos na mesa #<span id="add-table-id"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="form-product" novalidate>
                        <div class="col-9 mx-auto">
                            <ul class="list-group mb-3">
                                <div id="products-add">
                                    <li class="list-group-item text-muted text-center">Ainda não há produtos para adicionar nessa mesa!</li>
                                </div>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total</span>
                                    <strong id="total-add-products">R$ 0,00</strong>
                                </li>
                            </ul>
                        </div>
                        <div class="col-11 mx-auto d-flex justify-content-between">
                            <div class="col-9 d-flex justify-content-between">
                                <div class="col-8">
                                    <label for="add-product-id" class="visually-hidden">Produtos</label>
                                    <select class="form-select" id="add-product-id" required>
                                        <option disabled selected value="" class="d-none">Selecione ...</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-value="{{ $product->value }}">
                                            {{ $product->getNameUpper() }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="add-product-quantity" class="visually-hidden">Quantidade</label>
                                    <input type="number" class="form-control" id="add-product-quantity" min="1" value="1"
                                           required>
                                </div>
                            </div>
                            <div class="col-2 text-end">
                                <button class="btn btn-primary rounded-button-42" type="submit">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <form id="form-add-products" method="post" data-action="{{ route('garcom.tables.add', '') }}">
                    @csrf
                    <input type="hidden" name="products-add-json" id="products-add-json"
                            data-value="{{ json_encode($quantityAdd, JSON_UNESCAPED_SLASHES) }}">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" disabled id="btn-submit-add">Adicionar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="close-bill">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Fechar conta da mesa #<span id="close-table-id"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-close-bill" novalidate>
                    <div class="modal-body">
                        <div class="col-9 mx-auto">
                            <label for="quantity-people-table" class="form-label">Quantas pessoas vão pagar ?</label>
                            <div class="input-group">
                                <input type="number" min="1" required id="quantity-people-table" name="value"
                                           class="form-control">
                                <span class="input-group-text">pessoas</span>
                                <div class="invalid-feedback text-center">
                                    A quantidade de pessoas é obrigatória e não poder ser menor que 1!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="pay-bill">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pagar conta da mesa #<span id="pay-table-id"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-pay-bill" class="needs-validation" method="post" novalidate
                      data-action="{{ route('garcom.tables.pay', '') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="col-9 mx-auto">
                            <div class="display-6 text-center">
                                <span id="value-pay"></span><small class="text-muted fw-light">/pessoa</small>
                            </div>
                            <div class="d-flex flex-column" id="method-pay-bill">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" data-bs-toggle="modal"
                                data-bs-target="#close-bill">Voltar</button>
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script>
    const deleteItem = '{{ route('admin.tables.delete.product', ['','']) }}'
    const apiDetails = '{{ route('tables.api', 0) }}'
</script>
@endsection
