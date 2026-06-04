@extends('layouts.main')
@section('content')
<x-breadcrumb-component :get_route="$get_route" :back_route="$get_route" page_title="Dashboard"
    :breadcrumb_links="[['name' => 'Home', 'route' => $get_route], ['name' => 'Dashboard', 'route' => '']]" />

<livewire:portioning-measure-dashboard />

@section('scripts')
@endsection
@endsection
