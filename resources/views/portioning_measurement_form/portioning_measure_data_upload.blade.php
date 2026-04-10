@extends('layouts.main')
@section('content')

<x-breadcrumb-component :get_route="$get_route" :back_route="$get_route" page_title="Upload Sheet" :breadcrumb_links="[['name' => 'Home', 'route' => $get_route], ['name' => 'Dashboard', 'route' => route('portioning_measure_dashboard')], ['name' => 'Upload Sheet', 'route' => '']]" />
<div id="flash-container" style="padding-left: 65px; padding-right: 65px;">
    <x-flash />
</div>
<!-- upload  -->
<form id="upload-form" data-url="{{ route('portioning_measure_data_upload_action') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <section class="upload mb-5">
        <div class="upload_box mx-auto container" id="drop-zone">
            <div class="upload_icon mx-auto">
                <i class="bi bi-cloud-upload fs-1"></i>
            </div>

            <h2 class="fs-4 mb-3">Upload Portioning Sheet</h2>
            <p class="col-8 mb-4 mx-auto text-muted ">Instantly sync material data, ensuring every unit is accurately
                accounted for in the system.</p>

            <div class="d-flex flex-column align-items-center gap-2">
                <input type="file" name="file" id="file-input" class="d-none" accept=".xls,.xlsx">

                <div class="d-flex gap-2">
                    <button type="button" class="btn_1 fw-bold" onclick="document.getElementById('file-input').click()">
                        Upload Sheet
                    </button>

                    <button type="button" id="submit-btn" class="btn-submit" disabled>
                        Submit Sheet
                    </button>
                </div>
            </div>

            <div id="file-status" class="text-muted">No file selected</div>

        </div>
    </section>
</form>

<!-- upload  -->


<!-- Past Upload Sheets -->

<section class="past_sheets mb-5">
    <div class="container">
        <h2 class="color mb-4">Past Upload Sheets</h2>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th scope="col" class="ps-4">Upload Date</th>
                            <th scope="col">Week</th>
                            <th scope="col">Week Days</th>
                            <th scope="col">Updated By</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($past_sheets->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center">No past upload sheets found.</td>
                        </tr>
                        @else
                        @foreach($past_sheets as $sheet)
                        <tr>
                            <td class="date-cell ps-4">{{ $sheet->created_at->format('m/d/Y') }}</td>
                            <td>{{ $sheet->week }}</td>
                            <td>{{ date('d M', strtotime($sheet->from_date)) }} to {{ date('d M Y', strtotime($sheet->to_date)) }}</td>
                            <td>{{ $sheet->name }}</td>
                            <td>
                                <div class="action-btns justify-content-center">
                                    <a href="{{route('download_portioning_excel', Crypt::encrypt($sheet->order_head_id))}}" class="btn-icon btn-download" title="Download">
                                        <i class="bi bi-download fw-bold fs-5"></i>
                                    </a>
                                    <a class="btn-icon btn-delete" onclick="return confirm('Are you sure you want to delete this sheet data?')" title="Delete" href="{{ route('portioning_measure_delete', Crypt::encrypt($sheet->order_head_id)) }}">
                                        <i class="bi bi-trash fs-5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>


@section('scripts')
@endsection
@endsection