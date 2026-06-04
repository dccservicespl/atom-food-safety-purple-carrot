@extends('layouts.main')
@section('content')
<x-breadcrumb-component :get_route="$get_route" :back_route="$get_route" page_title="Week Details" :breadcrumb_links="[['name' => 'Home', 'route' => route('work_type')], ['name' => 'Dashboard', 'route' => route('portioning_measure_dashboard')], ['name' => 'Days Plan', 'route' => '']]"/>

<livewire:week-detail :week_id="$week_id" :order_head_id="$order_head_id" />

@endsection