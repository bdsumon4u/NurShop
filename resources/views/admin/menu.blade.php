@extends('layouts.app')
@section('content')
    {!! Menu::render() !!}
@endsection
@push('js')
    {!! Menu::scripts() !!}
@endpush
