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
    <table class="table table-bordered table-striped table-hover text-center">
        <thead class="fw-bold">
            <tr>
                <td>#</td>
                <td>Nome</td>
                <td>Valor</td>
                <td>Editar</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td id="name-{{ $product->id }}">{{ $product->name }}</td>
                    <td id="value-{{ $product->id }}" data-value={{ $product->value }}>
                        {{ $product->getCurrentValue() }}
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm btn-edit-value" data-product-id={{ $product->id }}>
                            <i class="bi bi-pencil"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="modal" tabindex="-1" id="edit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar valor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form data-action="{{ route('admin.products.edit', '') }}" class="needs-validation" novalidate method="post"
                  id="form-edit">
                @csrf
                <div class="col-9 p-2 mx-auto">
                    <div class="form-floating">
                        <input type="text" disabled id="name-edit" class="form-control">
                        <label for="name-edit">Produto</label>
                    </div>
                    <div class="mt-3">
                        <label for="value-edit" class="mb-1 form-label">Valor</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" step="0.01" min="0" required id="value-edit" name="value"
                                   class="form-control">
                            <div class="invalid-feedback text-center">
                                O preço é obrigatório e não poder ser menor que 0
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
