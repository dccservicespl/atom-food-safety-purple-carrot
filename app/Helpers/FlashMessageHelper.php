<?php
    function flashMessage(){
        $output = '';
        if (session('success')){
            $output .= '<div class="mb-1 mt-3">
                <div class="alert alert-success border border-success alert-dismissible fade show" role="alert">';
                $output .= session('success');
                    $output .= '<button type="button" class="btn-close text-success" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>';
        }elseif(session('error')){
            $output .= '<div class="mb-1 mt-3">
                    <div class="alert alert-danger border border-danger alert-dismissible fade show" role="alert">';
                    $output .= session('error');
                        $output .= '<button type="button" class="btn-close text-danger" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>';
        }elseif (session('warning')) {
            $output .= '<div class="mb-1 mt-3">
                <div class="alert alert-warning border border-warning alert-dismissible fade show" role="alert">';
                $output .= session('warning');
                    $output .= '<button type="button" class="btn-close text-warning" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>';
        }elseif (session('value_over_rate')) {
            $output .='<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            $output .= '
            <script>
                Swal.fire({
                    title: "Corrective Action",
                    text: "'.session('value_over_rate').'",
                    confirmButtonText: "OK"
                });
            </script>
            ';
        }elseif (session('temperature_over_rate')) {
            $output .='<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            $output .= '
            <script>
                Swal.fire({
                    title: "Corrective Action",
                    text: "'.session('temperature_over_rate').'",
                    confirmButtonText: "OK"
                });
            </script>
            ';
        }
        return $output;
    }
?>