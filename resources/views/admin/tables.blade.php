@extends('admin.layout')
@section('title', 'Mesas')
@section('plus')
    <div class="me-3 me-md-4 me-lg-5 pe-sm-1 pe-md-2 pe-xl-4">
        <button class="ms-auto btn btn-primary rounded-button-42" data-bs-toggle="modal" data-bs-target="#new-table">
            <i class="bi bi-plus"></i>
        </button>
    </div>
@endsection
@section('section')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger text-center w-100 mb-3">{{ $error }}</div>
        @endforeach
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success text-center w-100 mb-3">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="fw-bold table-secondary">
            <tr>
                <td>#</td>
                <td>Capacidade</td>
                <td>Produtos</td>
                <td>Status</td>
                <td>Editar</td>
                <td>Deletar</td>
            </tr>
            </thead>
            <tbody>
            @foreach ($tables as $table)
                <tr id="table-{{ $table->id }}">
                    <td>{{ $table->id }}</td>
                    <td id="capacity-{{ $table->id }}">{{ $table->capacity }}
                        pessoas
                    </td>
                    <td>
                        @if ($table->busy)
                            {{ count($table->products) }} -
                            <a href="#detail-table" class="text-primary text-decoration-none" data-bs-toggle="modal"
                               data-table-id="{{ $table->id }}">
                                Detalhar
                            </a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{!! $table->getBusyStatus() !!}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" data-table-id="{{ $table->id }}" data-bs-toggle="modal"
                                data-bs-target="#edit-capacity" data-capacity="{{ $table->capacity }}"
                                data-action="{{ route('admin.tables.edit', $table->id) }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm" data-table-id="{{ $table->id }}"
                                data-href="{{ route('admin.tables.delete', $table->id) }}"
                                data-bs-toggle="modal" data-bs-target="#delete-table">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $tables->links() }}
    <div class="modal fade" tabindex="-1" id="edit-capacity">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar capacidade da mesa #<span id="edit-table-id"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form class="needs-validation" novalidate method="post" id="form-edit">
                    @csrf
                    <div class="modal-body">
                        <div class="col-9 mx-auto">
                            <label for="capacity-table" class="form-label">Capacidade</label>
                            <div class="input-group">
                                <input type="number" min="1" required id="capacity-table" name="capacity"
                                       class="form-control" placeholder="4">
                                <span class="input-group-text">pessoas</span>
                                <div class="invalid-feedback text-center">
                                    A capacidade é obrigatória e não poder ser menor que 1!
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
    <div class="modal fade" tabindex="-1" id="delete-table">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tem certeza que quer deletar a mesa #<span id="delete-table-id"></span>?
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    Todas as informações referentes a essa mesa serão apagadas!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <a class="btn btn-success" id="btn-confirm-delete">Deletar</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="new-table">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nova mesa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.tables.new') }}" class="needs-validation" novalidate method="post"
                      id="form-new">
                    @csrf
                    <div class="modal-body">
                        <div class="col-9 mx-auto">
                            <label for="capacity-edit" class="form-label">Capacidade</label>
                            <div class="input-group">
                                <input type="number" min="1" required id="capacity-edit" name="capacity" value="4"
                                       class="form-control" placeholder="4">
                                <span class="input-group-text">pessoas</span>
                                <div class="invalid-feedback text-center">
                                    A capacidade é obrigatória e não poder ser menor que 1!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Criar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="detail-table">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Produtos da mesa #<span
                            id="detail-table-id"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="load" class="placeholder-glow d-none">
                        <ul class="list-group placeholder-glow">
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
                    </div>
                    <div id="load-error" class="d-none">
                        <div class="alert alert-danger w-100 text-center">Não foi possível carregar os detalhes, tente
                            novamente mais tarde!
                        </div>
                    </div>
                    <div id="detail" class="d-none">
                        <ul class="list-group">
                            <div id="products"></div>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total</span>
                                <strong id="total-products">R$ 0,00</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const deleteItem = '{{ route('admin.tables.delete.product', ['','']) }}'
        const productItem = '{{ route('admin.products') }}', apiDetails = '{{ route('tables.api', 0) }}'
    </script>
@endsection
