@extends('admin.layout')
@section('title', 'Produtos')
@section('plus')
    <div class="me-3 me-md-4 me-lg-5 pe-sm-1 pe-md-2 pe-xl-4">
        <button class="ms-auto btn btn-primary rounded-button-42" data-bs-toggle="modal" data-bs-target="#new-product">
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
                <td>Nome</td>
                <td>Valor</td>
                <td>Editar</td>
                <td>Deletar</td>
            </tr>
            </thead>
            <tbody>
            @foreach ($products as $product)
                <tr id="product-{{ $product->id }}">
                    <td>{{ $product->id }}</td>
                    <td id="name-{{ $product->id }}">{{ $product->getNameUpper() }}</td>
                    <td id="value-{{ $product->id }}">
                        {{ $product->getCurrentValue() }}
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#edit-value"
                                data-product-id="{{ $product->id }}" data-value="{{ $product->value }}"
                                data-action="{{ route('admin.products.edit', $product->id) }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm" data-id="{{ $product->id }}"
                                data-href="{{ route('admin.products.delete', $product->id) }}"
                                data-bs-toggle="modal" data-bs-target="#delete-product">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $products->links() }}
    <div class="modal fade" tabindex="-1" id="edit-value">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar valor do(a) <span id="name-product"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form class="needs-validation" novalidate method="post" id="form-edit">
                    @csrf
                    <div class="modal-body">
                        <div class="col-9 mx-auto">
                            <label for="value-product" class="form-label">Valor</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" step="0.01" min="0" required id="value-product" name="value"
                                       class="form-control" placeholder="1,00">
                                <div class="invalid-feedback text-center">
                                    O preço é obrigatório e não poder ser menor que 0!
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
    <div class="modal fade" tabindex="-1" id="new-product">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Novo produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.products.new') }}" class="needs-validation" novalidate method="post"
                      id="form-new">
                    @csrf
                    <div class="modal-body">
                        <div class="col-9 mx-auto">
                            <div class="mb-2">
                                <label for="name-product" class="form-label">Nome</label>
                                <input type="text" class="form-control" name="name" required id="name-product"
                                       placeholder="Nome do produto">
                                <div class="invalid-feedback text-center">
                                    O nome é obrigatório!
                                </div>
                            </div>
                            <label for="value-product" class="form-label">Valor</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" step="0.01" min="0" required id="value-product" name="value"
                                       class="form-control" placeholder="1,00">
                                <div class="invalid-feedback text-center">
                                    O preço é obrigatório e não poder ser menor que 0!
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
    <div class="modal fade" tabindex="-1" id="delete-product">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tem certeza que quer deletar o produto #<span id="delete-id"></span>?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    Todas as informações referentes a esse produto serão apagadas e ele será removido de todas as mesas
                    e pedidos!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <a class="btn btn-success" id="btn-confirm-delete">Deletar</a>
                </div>
            </div>
        </div>
    </div>
@endsection
