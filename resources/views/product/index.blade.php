@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="row">

        @foreach($products as $product)

        <div class="col-md-3 mb-4">

            <div class="card h-100">

                <img src="{{ asset('storage/'.$product->image) }}"
                     class="card-img-top"
                     style="height:220px;object-fit:cover;">

                <div class="card-body">

                    <h5>{{ $product->name }}</h5>

                    <p>

                        ${{ number_format($product->price,2) }}

                    </p>

                    <p>

                        Stock:
                        {{ $product->stock }}

                    </p>

                    <form action="{{ route('cart.add',$product->id) }}"
                          method="POST">

                        @csrf

                        <button class="btn btn-success w-100">

                            Add to Cart

                        </button>

                    </form>

                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>

@endsection