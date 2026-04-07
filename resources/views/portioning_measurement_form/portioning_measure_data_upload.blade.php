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
                <input type="file" name="file" id="file-input" class="d-none" accept=".csv,.xls,.xlsx,.pdf">
                
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
                        <!-- Row 1 -->
                        <tr>
                            <td class="date-cell ps-4">03/9/2026</td>
                            <td>3.2</td>
                            <td>9 Mar to 14 Mar 2026</td>
                            <td>Jack Will</td>
                            <td>
                                <div class="action-btns justify-content-center">
                                    <button class="btn-icon btn-download" title="Download">
                                        <i class="bi bi-download fw-bold fs-5"></i>
                                    </button>
                                    <button class="btn-icon btn-delete" title="Delete">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Row 2 -->
                        <tr>
                            <td class="date-cell ps-4">03/2/2026</td>
                            <td>3.1</td>
                            <td>2 Mar to 7 Mar 2026</td>
                            <td>Jack Will</td>
                            <td>
                                <div class="action-btns justify-content-center">
                                    <button class="btn-icon btn-download" title="Download">
                                        <i class="bi bi-download fs-5"></i>
                                    </button>
                                    <button class="btn-icon btn-delete" title="Delete">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>


@section('scripts')
@endsection
@endsection