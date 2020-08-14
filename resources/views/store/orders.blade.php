@extends('layouts.app')

@section('content')
<section class="container mx-auto py-20 px-10 bg-gray-100 max-w-4xl">
    <div class="flex flex-col flex-wrap -mx-2 ">
        <h1 class="text-3xl font-medium">Order Summary </h1>
        <table class="border-collaspe border-collapse table-auto ">
            <thead>
                <th class="px-4 py-2">Order Number</th>
                <th class="px-4 py-2">User Info</th>
                <th class="px-4 py-2">Order Info</th>
                <th class="px-4 py-2">Total Price</th>
                <th class="px-4 py-2">paid</th>
                <th class="px-4 py-2">uuid</th>
                <!--<th class="px-4 py-2">Show Details</th>
                <th class="px-4 py-2">paid</th>-->
            </thead>
            <tbody class="text-center">
                @foreach($orders as $order)
                <tr>
                    <td class="border px-4 py-2">{{$order->id}}</td>
                    <td class="border px-4 py-2">{{$order->name}}</td>
                    <td class="border px-4 py-2">
                        <ul>
                            @foreach(unserialize($order->cart)->items as $item)
                            <li>{{$item['item']['name']}},{{ $item['item']['price'] }}元×{{$item['qty']}}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="border px-4 py-2">{{unserialize($order->cart)->totalPrice}}元</td>
                    @if($order->paid == 1)
                        <td class="border px-4 py-2" style="color:green">已付款</td>
                    @else
                        <td class="border px-4 py-2" style="color:red">未付款</td>
                    @endif
                    <td class="border px-4 py-2">{{$order->uuid}}</td>
                    <!--{{-- <td class="border px-4 py-2">{{$order->cart}}</td> --}}
                    <td class="border px-4 py-2"><a href="#">Show</a></td>
                    <td class="border px-4 py-2">{{$order->paid}}</td>-->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection