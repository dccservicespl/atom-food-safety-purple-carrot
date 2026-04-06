<div>
    <div class="shadow-none">
        <div class="container-fluid">
            <?php echo flashMessage(); ?>
            @if ($mode === 'list')
            <div class="mt-5 page_card bg_white pb-0 pt-0 mb-0 rounded-0 rounded-top border-bottom border-secondary">
                <div class="row">
                    <div class="col-5">
                        <div class="card_title_color text-dark text-start ps-4" style="font-size: 16px">
                            <a href="javascript:void(0);" style="color: unset;"><span>Purple
                                    Carrot</span></a>
                            <span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    style="display: inline; margin: 0 8px; vertical-align: middle;">
                                    <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                            <a style="color: unset;" href="{{ route('work_type') }}"><span>Kitting</span></a>
                            <span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    style="display: inline; margin: 0 8px; vertical-align: middle;">
                                    <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                            <strong>Portioning measure</strong>
                        </div>
                    </div>

                    <div class="col-7 text-end pt-3">
                        <div class="d-inline-flex align-items-right float-end ">
                            <button wire:click="createMeasureDate" class="btn btn-outline-warning pt-2 pb-2 ms-4">
                                <i class="bi bi-plus"></i> Measure Date
                            </button>
                            <div class="dropdown">
                                <a class="dropdown-toggle btn btn-outline-primary pt-2 pb-2 ms-4" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-plus"></i> Master
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('meal_kit_type') }}">Meal Kit Type</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">Ingredient Master</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">Meal Kit Master</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page_card mt-0 rounded-0 rounded-bottom">
                <div class="row">
                    <div class="col-12">

                        <div class="table-container">
                            <table class="table measure_date_list">
                                <thead>
                                    <tr style="background-color: #ddd; color: #000">
                                        <th>Date</th>
                                        <th>Start time</th>
                                        <th>End time</th>
                                        <th>Operator</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--[if BLOCK]><![endif]-->
                                    <tr>
                                        <td>02/12/2026</td>
                                        <td>01:23 am
                                        </td>
                                        <td>01:30 am
                                        </td>
                                        <td>Crish Nwak</td>
                                        <td>
                                            <span class="badge text-success badge_completed">
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <a href="http://127.0.0.1:8000/admin/portioning-measurement-form?measure_id=eyJpdiI6ImJ1MjFpd21MWTI0WGhOcVRXUk9OM3c9PSIsInZhbHVlIjoiVnRkenprT1g1NnNabDZPbGZYbkIyZz09IiwibWFjIjoiMTVhZjJhY2YyZWRiNTFmOWQ1ZGUyZTIzMGY5MGQ3N2M2ZTY2NmY3OWNhMjdmYzNjM2YzOWEzM2IyOGY5NTIyNSIsInRhZyI6IiJ9"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02/12/2026</td>
                                        <td>01:10 am
                                        </td>
                                        <td>01:30 am
                                        </td>
                                        <td>Jack Will</td>
                                        <td>
                                            <span class="badge text-success badge_completed">
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <a href="http://127.0.0.1:8000/admin/portioning-measurement-form?measure_id=eyJpdiI6IkJ5Qy9yN0YwbTA1WDZWd3FwamdGSlE9PSIsInZhbHVlIjoiWnJyS3N2bFU0VTh1dkN0UDBpSEJBQT09IiwibWFjIjoiYzMxM2NlOTdkMWJlOWQyMzJiOTQ3M2MyZTE1NWI0NmI4M2RkYzI1ZWRkNWY2ZjYwN2Y3ODhkZjcwZjk5MmQ0YiIsInRhZyI6IiJ9"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02/10/2026</td>
                                        <td>12:52 am
                                        </td>
                                        <td>06:43 am
                                        </td>
                                        <td>Jack Will</td>
                                        <td>
                                            <span class="badge text-success badge_completed">
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <a href="http://127.0.0.1:8000/admin/portioning-measurement-form?measure_id=eyJpdiI6IkFPQ1Qvd3lDeUYyRThOYWJ6U0FXYUE9PSIsInZhbHVlIjoiYVNMRENHYXVzcWlpbTNvM21yZjJvUT09IiwibWFjIjoiNGQxMDhkYWYxMjhhMTEzODE3YmNiY2Y1M2UzNDBkYTkwYWFlOWNkODdkNThkNzAxNDIwYzY1MWY1MTI2MTMxNSIsInRhZyI6IiJ9"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02/09/2026</td>
                                        <td>06:23 am
                                        </td>
                                        <td>07:44 am
                                        </td>
                                        <td>Jack Will</td>
                                        <td>
                                            <span class="badge text-success badge_completed">
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <a href="http://127.0.0.1:8000/admin/portioning-measurement-form?measure_id=eyJpdiI6IkRaN2xMNnJlbGh0NmpkZDhpb3VLdVE9PSIsInZhbHVlIjoiZXVYUy8rc2FBUzNlMWhYK0F1OEo0QT09IiwibWFjIjoiY2FjOWY1NWM2YTY5MDg2OTEyYTA2ZGQ0NzcxYzA0MzA4ODlhZTkyYjRlNDY5MTMwZjc4NjQ4ZTQ1YWQ5ODdlNiIsInRhZyI6IiJ9"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02/09/2026</td>
                                        <td>05:32 am
                                        </td>
                                        <td>06:22 am
                                        </td>
                                        <td>Jack Will</td>
                                        <td>
                                            <span class="badge text-success badge_completed">
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <a href="http://127.0.0.1:8000/admin/portioning-measurement-form?measure_id=eyJpdiI6IlM5L0hoVGUyTWkrV0ZUV2Y1bXNhUXc9PSIsInZhbHVlIjoiK1Bkb3ZpNzg0VjE2QkZTR3NNcmZkZz09IiwibWFjIjoiOTQ3NThhODc4NzAwODBkMzhkYmUwOTc1YzAwNDc1YmQ2Nzc4MzNkNzlmNDNkMGY4ZTBhZTI4MDIzZmZkYWJiMCIsInRhZyI6IiJ9"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02/09/2026</td>
                                        <td>05:12 am
                                        </td>
                                        <td>05:12 am
                                        </td>
                                        <td>Jack Will</td>
                                        <td>
                                            <span class="badge text-success badge_completed">
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <a href="http://127.0.0.1:8000/admin/portioning-measurement-form?measure_id=eyJpdiI6InJwdFQ5U3pGSkpucDNXVlZCWjY2U0E9PSIsInZhbHVlIjoiODFGMlJhSGNQQmhzTGxzOFdPR1IzQT09IiwibWFjIjoiMjU2OGQwOThiOTUxZmQ0MmRmY2U2Nzg1NGYwNjYyYTg0OTYwYzk0YWU5NGQwYzY3NWM2NWJmMzE1NzA2NWQwYiIsInRhZyI6IiJ9"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02/09/2026</td>
                                        <td>01:53 am
                                        </td>
                                        <td>04:40 am
                                        </td>
                                        <td>Jack Will</td>
                                        <td>
                                            <span class="badge text-success badge_completed">
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <a href="http://127.0.0.1:8000/admin/portioning-measurement-form?measure_id=eyJpdiI6IkY4S201bERqUC9hazZRMVBjM3ZwSEE9PSIsInZhbHVlIjoiMDJlT280dFcyTU5FbzAvRG1BNnVXdz09IiwibWFjIjoiY2ZhZmFhOGM3MTE2YTFhOWM3OGY3ZTdlMjg2OWM0MWVmNTIwYTc3OWEzODQ2MTQ2MmI4MTJmY2E5ZGVmZWE3MSIsInRhZyI6IiJ9"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02/06/2026</td>
                                        <td>07:08 am
                                        </td>
                                        <td>07:11 am
                                        </td>
                                        <td>Jack Will</td>
                                        <td>
                                            <span class="badge text-success badge_completed">
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <a href="http://127.0.0.1:8000/admin/portioning-measurement-form?measure_id=eyJpdiI6IitFZnBkV1VBS3FFL29nMm81STJteVE9PSIsInZhbHVlIjoielVTWlk5UTBSY0tGQTduaXZ2dHgwdz09IiwibWFjIjoiNGU3NzlkNWU0MTU3YTQ1MGZjNmU4MDI4MjczNTc2M2Q4ZGRlMzU0YTU2MTFlOTI5Zjc1ZjQ1ZWM0ODZlY2MyNiIsInRhZyI6IiJ9"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02/06/2026</td>
                                        <td>06:45 am
                                        </td>
                                        <td>07:07 am
                                        </td>
                                        <td>Jack Will</td>
                                        <td>
                                            <span class="badge text-success badge_completed">
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <a href="http://127.0.0.1:8000/admin/portioning-measurement-form?measure_id=eyJpdiI6Iit4alhWcXZUR0RkbUFhZExQVmxVdVE9PSIsInZhbHVlIjoiOVpOa3dZZlpkVTI4dDV6cVkxdGI3QT09IiwibWFjIjoiYzIwN2M0MWM2YTQ0MGI4MGE4MWQ0YmIxZGM2YTk5NmFlNjg1MjRlOTk0N2Y2MDJmOGI5NjIyMWU4ZjcyOTFmYSIsInRhZyI6IiJ9"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02/06/2026</td>
                                        <td>06:37 am
                                        </td>
                                        <td>06:45 am
                                        </td>
                                        <td>Jack Will</td>
                                        <td>
                                            <span class="badge text-success badge_completed">
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <a href="http://127.0.0.1:8000/admin/portioning-measurement-form?measure_id=eyJpdiI6InpmWmhoYS9TdVgyN3hzQWZJNzZWaUE9PSIsInZhbHVlIjoiOHpJSm5KM2x4OUxJK0NtK1ZqZGY0dz09IiwibWFjIjoiNmEzMjBhMWQxMTUzNTk2OWUxNWUzMWVmNjQ5ZDU1ZDA1YTAwNzkxN2IxOGRjYTcwNTBiN2MwOTNiMWM3MWNlZCIsInRhZyI6IiJ9"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02/06/2026</td>
                                        <td>05:53 am
                                        </td>
                                        <td>05:53 am
                                        </td>
                                        <td>Jack Will</td>
                                        <td>
                                            <span class="badge text-success badge_completed">
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <a href="http://127.0.0.1:8000/admin/portioning-measurement-form?measure_id=eyJpdiI6IlZUL1dzNllHNUVSWTBpak9pcDA3TGc9PSIsInZhbHVlIjoiTDRYd0ExeEFNbERNR0N6SWoyejBvQT09IiwibWFjIjoiMjk2M2M4OTkxZmE4ZDg4ODU3ZDBkODM1YjU2NGYzN2U1ZGE2ZDIwOTgyNGFlNGU5MDY0ODliZGIyZTE2Y2ExYSIsInRhZyI6IiJ9"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02/06/2026</td>
                                        <td>05:52 am
                                        </td>
                                        <td>05:52 am
                                        </td>
                                        <td>Jack Will</td>
                                        <td>
                                            <span class="badge text-success badge_completed">
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <a href="http://127.0.0.1:8000/admin/portioning-measurement-form?measure_id=eyJpdiI6IjYxQ2xLeHZCSEh2SjZCWCt5enlFMGc9PSIsInZhbHVlIjoiTXVSa0FjUFRXb2Mxd2JsdC9VWjJVUT09IiwibWFjIjoiYjRjMWYzOTI4MDAxYTQ3Mzk1MGQwNmNmM2U5MmQ1NzhiMWIwNmY4MWE1OTk0YTEzZTQzZGVlYWFiMzhiNjU1MSIsInRhZyI6IiJ9"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02/06/2026</td>
                                        <td>05:51 am
                                        </td>
                                        <td>05:51 am
                                        </td>
                                        <td>Jack Will</td>
                                        <td>
                                            <span class="badge text-success badge_completed">
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <a href="http://127.0.0.1:8000/admin/portioning-measurement-form?measure_id=eyJpdiI6Ii9vM1VlMTFNclBRcGdCU05xSXJVN0E9PSIsInZhbHVlIjoiaDh0bVhyUHFtcWtRZWcwS3NVZUgwZz09IiwibWFjIjoiZDFhMzc1NGVjMmIzY2M1MTI5ZTgwNDNlMTNiYTY1NmJkNzVkNmM1NDEwMGNjMDI2ZDc1ODExOGFhZjNmM2QwYyIsInRhZyI6IiJ9"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02/06/2026</td>
                                        <td>04:19 am
                                        </td>
                                        <td>05:50 am
                                        </td>
                                        <td>Jack Will</td>
                                        <td>
                                            <span class="badge text-success badge_completed">
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <a href="http://127.0.0.1:8000/admin/portioning-measurement-form?measure_id=eyJpdiI6IlhoNDdhVFBLbE56bCs3a0loaFR6dWc9PSIsInZhbHVlIjoiN2YxQVJDZkhxKzkvWnVCN3BPNU9MUT09IiwibWFjIjoiNjdiOGE1MDk4ODU4MWNmZTc1NWM0MDlmNTM2MmI1ODA1MDExOGMzZGI2YmVmZGQ0YmI5Mzk4NDQxZTYyNjEyNCIsInRhZyI6IiJ9"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02/06/2026</td>
                                        <td>01:20 am
                                        </td>
                                        <td>01:22 am
                                        </td>
                                        <td>Jack Will</td>
                                        <td>
                                            <span class="badge text-success badge_completed">
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <a href="http://127.0.0.1:8000/admin/portioning-measurement-form?measure_id=eyJpdiI6IjdkQnBiSU85bDJnSVYva2ZKbEdFSmc9PSIsInZhbHVlIjoieEpGWGcxQTlsU2Y3RGh4bmRNUTlqUT09IiwibWFjIjoiYTgwZjlkYjIwMDI0OTQ4MWVmMTVmNmEzYjI4YzdlZjE1YTQ2NjY1ZTFlM2RiYmQxNGI3N2JkMTQ4YzU3OWQ3ZiIsInRhZyI6IiJ9"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <!--[if ENDBLOCK]><![endif]-->
                                </tbody>
                            </table>
                            <div class="mt-0">
                                {{-- {{ $measurements->links() }} --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>