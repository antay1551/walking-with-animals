@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Checkout') }}</div>

                    <div class="card-body">
                        <form action="{{route('pay')}}" method="POST">
                            @csrf
                            <div class="col-6">
                                <div id="card-element">

                                </div>
                                <button type="submit" class="btn mt-4 btn-primary">
                                    Pay {{ round($order->product->price / 100, 2)}}$
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        let stripe = Stripe('{{ config('services.stripe.public_key') }}');
        let elements = stripe.elements();
        let cardElement = elements.create('card');

        cardElement.mount('#card-element');
    </script>
@endsection
