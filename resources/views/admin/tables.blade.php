@extends('admin.layout')
@section('title', 'Mesas')
@section('plus')
<div class="me-3 me-md-4 me-lg-5 pe-sm-1 pe-md-2 pe-xl-4" id="btn-new-table">
    <button class="ms-auto btn btn-primary rounded-button-42">
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
    <table class="table table-bordered table-striped table-hover text-center">
        <thead class="fw-bold">
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
            <tr>
                <td>{{ $table->id }}</td>
                <td id="capacity-{{ $table->id }}" data-capacity="{{ $table->capacity }}">{{ $table->capacity }} pessoas</td>
                <td>
                    @if ($table->busy)
                    {{ count($table->products) }} -
                    <span class="text-primary pointer detail-table" data-table-id="{{ $table->id }}">
                        Detalhar
                    </span>
                    @else
                        -
                    @endif
                </td>
                <td>{!! $table->getBusyStatus() !!}</td>
                <td>
                    <button class="btn btn-primary btn-sm btn-edit-capacity" data-table-id={{ $table->id }}>
                        <i class="bi bi-pencil"></i>
                    </button>
                </td>
                <td>
                    <a class="btn btn-danger btn-sm" href="{{ route('admin.tables.delete', $table->id) }}">
                        <i class="bi bi-trash"></i>
                    </a>
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
                <h5 class="modal-title">Editar capacidade</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form data-action="{{ route('admin.tables.edit', '') }}" class="needs-validation" novalidate method="post"
                  id="form-edit">
                @csrf
                <div class="col-9 p-2 mx-auto">
                    <div class="form-floating">
                        <input type="text" disabled id="table-edit" class="form-control">
                        <label for="table-edit">Mesa</label>
                    </div>
                    <div class="mt-3">
                        <label for="capacity-edit" class="mb-1 form-label">Capacidade</label>
                        <div class="input-group">
                            <input type="number" min="1" required id="capacity-edit" name="capacity"
                                   class="form-control">
                            <span class="input-group-text">pessoas</span>
                            <div class="invalid-feedback text-center">
                                A capacidade é obrigatória e não poder ser menor que 1
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
<div class="modal" tabindex="-1" id="new-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova mesa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.tables.new') }}" class="needs-validation" novalidate method="post"
                  id="form-new">
                @csrf
                <div class="col-9 p-2 mx-auto">
                    <label for="capacity-edit" class="mb-1 form-label">Capacidade</label>
                    <div class="input-group">
                        <input type="number" min="1" required id="capacity-edit" name="capacity" class="form-control" value="4">
                        <span class="input-group-text">pessoas</span>
                        <div class="invalid-feedback text-center">
                            A capacidade é obrigatória e não poder ser menor que 1
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
@endsection
