@extends('layouts.main')
@section('content')

<x-breadcrumb-component :get_route="route('week_details',[(int) \Carbon\Carbon::now()->isoWeek(), $order_head_id])"
    :back_route="route('week_details',[(int) \Carbon\Carbon::now()->isoWeek(), $order_head_id])"
    page_title="Day Details"
    :breadcrumb_links="[['name' => 'Home', 'route' => $get_route],['name' => 'Dashboard', 'route' => route('portioning_measure_dashboard')], ['name' => 'Days Plan', 'route' => route('week_details',[(int) \Carbon\Carbon::now()->isoWeek(), $order_head_id])], ['name' => 'Day Details', 'route' =>'']]" />


<livewire:portioning-measure-form :order_head_id="$order_head_id" :portioning_category_id="$portioning_category_id" />

@section('scripts')
@endsection

@endsection
