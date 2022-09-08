@extends('admin.layout')
@section('title', 'Pagamentos')
@section('section')
<div class="table-responsive">
    <table class="table table-bordered text-center">
        <thead class="fw-bold table-secondary">
        <tr>
            <td>#</td>
            <td>Valor</td>
            <td>Mesa</td>
            <td>Cliente</td>
            <td>Pago em(no)</td>
            <td>Data e hora</td>
        </tr>
        </thead>
        <tbody>
        @foreach($payments as $payment)
            <tr class="{{ $payment->getColor() }}">
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->getCurrentValue() }}</td>
                <td>
                    <a href="{{ route('admin.tables') }}#table-{{ $payment->table_id }}" class="text-decoration-none">
                        {{ $payment->table_id }}
                    </a>
                </td>
                <td>{{ $payment->client }}</td>
                <td>{{ $payment->method }}</td>
                <td>{{ $payment->time }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $payments->links() }}
@endsection
@section('plus')
<form class="ms-2 col-lg-2 col-sm-3 col-5" id="form-select">
    <label for="period-payment" class="visually-hidden"></label>
    <select class="form-select" id="period-payment" name="period-payment">
        <option value="" @selected(is_null(request('period-payment')))>
            Todo período
        </option>
        <option value="0" @selected(request('period-payment', 1) == 0)>Hoje</option>
        <option value="-1" @selected(request('period-payment') == -1)>Ontem</option>
        <option value="7" @selected(request('period-payment') == 7)>Últimos 7 dias</option>
        <option value="30" @selected(request('period-payment') == 30)>Últimos 30 dias</option>
        <option value="60" @selected(request('period-payment') == 60)>Últimos 60 dias</option>
        <option value="90" @selected(request('period-payment') == 90)>Últimos 90 dias</option>
    </select>
</form>
@endsection
