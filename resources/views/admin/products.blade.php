@extends('admin.layout')
@section('title', 'Produtos')
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
                                       class="form-control">
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
@endsection
