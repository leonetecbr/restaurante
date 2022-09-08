@extends('admin.layout')
@section('title', 'Pedidos')
@section('section')
<div class="table-responsive">
    <table class="table table-bordered text-center">
        <thead class="fw-bold table-secondary">
            <tr>
                <td>#</td>
                <td>Produtos</td>
                <td>Valor</td>
                <td>Mesa</td>
                <td>Data e hora</td>
            </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>
                    {{ count($order->products) }} -
                    <a href="#detail-order" class="text-primary detail-order-btn text-decoration-none" data-order-id="{{ $order->id }}">
                        Detalhar
                    </a>
                </td>
                <td>{{ $order->getCurrentValue() }}</td>
                <td>
                    <a href="{{ route('admin.tables') }}#table-{{ $order->table_id }}" class="text-decoration-none">
                        {{ $order->table_id }}
                    </a>
                </td>
                <td>{{ $order->time }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $orders->links() }}
<div class="modal fade" id="detail-order" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel">Produtos do pedido <span id="detail-order-id"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="load-detail" class="d-flex justify-content-center">
                    <div class="loader"></div>
                </div>
                <div id="load-detail-error" class="d-none">
                    <div class="alert alert-danger w-100 text-center">Não foi possível carregar os detalhes, tente novamente mais tarde!</div>
                </div>
                <div id="detail" class="d-none">
                    <ul class="list-group mb-3">
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
    const productItem = '{{ route('admin.products') }}'
</script>
@endsection
