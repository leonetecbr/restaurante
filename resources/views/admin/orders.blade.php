@extends('admin.layout')
@section('title', 'Pedidos')
@section('section')
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
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
                        {{ $order->products_sum_quantity }} -
                        <a href="#detail-order" class="text-primary text-decoration-none" data-bs-toggle="modal"
                           data-order-id="{{ $order->id }}">
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
            @if (empty($orders[0]))
                <tr class="table-warning">
                    <td colspan="6">
                        Nenhum registro de pedido foi encontrado!
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
    <div class="modal fade" id="detail-order" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Produtos do pedido #<span
                            id="detail-order-id"></span></h5>
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
        const productItem = '{{ route('admin.products') }}', apiDetails = '{{ route('orders.api', 0) }}'
    </script>
@endsection
