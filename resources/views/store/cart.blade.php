@extends('layouts.app')

@section('content')
<section class="container mx-auto py-20 px-10 bg-gray-100 max-w-4xl">
    <div class="flex flex-col flex-wrap -mx-2 ">
        <h1 class="text-3xl font-medium">Your Shopping Cart </h1>
        @if(session()->has('cart'))
        <table class="border-collaspe border-collapse table-auto ">
            <thead>
                <th class="px-4 py-2">Item</th>
                <th class="px-4 py-2">Price</th>
                <th class="px-4 py-2">Number</th>
                <th class="px-4 py-2">Action</th>
            </thead>
            <tbody class="text-center">
                @foreach ($items as $item)
                <tr>
                    <td class="border px-4 py-2">{{$item['item']['name']}}</td>
                    <td class="border px-4 py-2">{{$item['item']['price']}}</td>
                    <!-- Remove Items / Increase +1 / Decrease By 1 -->
                    <td class="border px-4 py-2">{{$item['qty']}}</td>
                    <td class="border px-4 py-2">
                        <a class="py-1 px-2 bg-teal-400"
                            href="/increase-one-item/{{$item['item']['id']}}">+</a>|
                        <a class="py-1 px-2 bg-red-300"
                            href="/decrease-one-item/{{$item['item']['id']}}">-</a>|
                        <a class="py-1 px-2 bg-red-700 uppercase"
                            href="/remove-item/{{$item['item']['id']}}">Remove</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Cart is Empty</p>
        @endif
        <p class="mt-4 text-xl font-medium text-right">Total Qty: {{ $totalQty }}</p>
        <p class="mt-4 text-xl font-medium text-right">Total: {{ $totalPrice }}$</p>
        <div class="flex justify-end mt-4">
            <a href="/clear-cart" class="px-6 py-3 bg-orange-600">Clear
                Cart</a> |
            <a href="/order/new" class="px-6 py-3 bg-orange-600">Buy Now</a>
        </div>
    </div>
</section>
@endsection