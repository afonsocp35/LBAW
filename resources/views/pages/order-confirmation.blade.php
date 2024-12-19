@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <h3>Resumo do Carrinho</h3>

            @if ($items->isEmpty())
                <div class="alert alert-warning text-center" role="alert">
                    Seu carrinho está vazio. 
                    <a href="{{ route('home') }}" class="alert-link">Voltar para a página inicial</a> e adicionar itens ao carrinho.
                </div>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->_product->name }}</td>
                                <td>€{{ number_format($item->_product->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>€{{ number_format($item->quantity * $item->_product->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr>

                <h4>Total: €{{ number_format($total, 2) }}</h4>
            @endif
        </div>

        <div class="col-md-4">
            <h3>Detalhes do Pagamento</h3>

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="payment_method">Método de Pagamento</label>
                    <select id="payment_method" name="payment_method_id" class="form-control" required>
                        @foreach ($user->paymentMethods as $paymentMethod)
                            <option value="{{ $paymentMethod->id }}">
                                {{ ucfirst($paymentMethod->type) }} - {{ $paymentMethod->card_issuer ? $paymentMethod->card_issuer : 'N/A' }} 
                                ({{ $paymentMethod->phone ?? $paymentMethod->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="shipping_address">Endereço de Entrega</label>
                    <textarea id="shipping_address" name="shipping_address" class="form-control" required>{{ old('shipping_address') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Finalizar Compra</button>
            </form>

            <div class="mt-3">
                <a href="{{ route('home') }}" class="btn btn-secondary btn-block">Voltar para a Página Inicial</a>
            </div>
        </div>
    </div>
</div>
@endsection
<style>
.btn-secondary {
    background-color: #6c757d;
    color: #fff;
    border: none;
}

.btn-secondary:hover {
    background-color: #5a6268;
    color: #fff;
}

</style>