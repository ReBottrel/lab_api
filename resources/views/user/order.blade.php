@extends('layouts.app')
@section('content')
    <h1>Order</h1>
    @foreach ($orders as $order)
        <p>Order ID: {{ $order->id }}</p>
        <p>Order Status: {{ $order->status }}</p>
        <p>Order Total: {{ $order->total }}</p>
        <p>Order Created: {{ $order->created_at }}</p>
        <p>Order Updated: {{ $order->updated_at }}</p>
        <p>Order Items:</p>
        <ul>

        </ul>
    @endforeach
@endsection
