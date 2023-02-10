@extends('layouts.admin')
@section('content')
    <img src="{{ $data->localization }}" alt="" onerror="console.log('Image not found')">
@endsection
@section('js')

@endsection
