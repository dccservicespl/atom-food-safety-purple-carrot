<?php

use App\Models\BlendingMeasure;
use App\Models\GuacamoleMeasure;
use App\Models\OrderCategory;
use App\Models\Role;
use App\Models\RoleMst;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

function back_btn($route = Null)
{
    $output = '';
    $output .= '<a href="' . $route . '" class="btn btn-outline-dark mt-2 pl-5 pr-5 pt-2 pb-2 h5"> <i class="bi bi-arrow-left"></i>
                    Back</a>';
    return $output;
}

function profile()
{
    $role = RoleMst::where('id', Auth::user()->role_id)->first()->role_name;
    $output = '';
    $output .= '
                        <div class="col-3 col-md-5 text-end">
                            <p class="font-bold font-weight-bolder h5 text_dark">' . Auth::user()->name . '</span></p>
                        </div>
                    ';
    return $output;
}

function getDifference($num1 = NULL, $num2 = NULL)
{
    return $difference = abs($num1 - $num2);
}

function get_pack_by_user_name($user_id = NULL)
{
    return $get_user_name = User::select('name')->where('id', $user_id)->first()->name;
}

function toChicagoTime($timestamp)
{
    try {
        $cstTime = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC')
            ->setTimezone('America/Chicago');
        return $cstTime->format('m-d-Y H:i:s a');
    } catch (\Exception $e) {
        return 'Invalid timestamp';
    }
}

function no_record_found_in_table()
{
    $output = '';
    $output = '
                <p class="text-center h2 text-200 p-4"> No Record Found! </p>
            ';
    return $output;
}

function disabled_all_input($measure_item_status = NULL, $form_id = NULL, $unverified_measure_item = NULL)
{
    $output = '';
    if ($measure_item_status == 'Verified') {
        $output .= '
                <script>
                    $(document).ready(function() {
                        $("input, textarea").prop("disabled", true);
                        $("input, textarea, select, button").attr("disabled", "disabled");
                        $("#' . $form_id . '").removeAttr("action");
                        $("button[type=\'submit\']").hide();';

        if (Auth::user()->role_id == 2) {
            $output .= '$(".' . $unverified_measure_item . '").show();';
        }

        $output .= ' });
                </script>
            ';
    }
    return $output;
}

function add_back_button($route_name = null)
{
    $output = '';
    $output .= '
                <a class="" href="' . $route_name . '">
                    <img class="img-responsive" src="/assets/img/icons/back.png" alt="" width="40" />
                </a>
        ';
    return $output;
}

if (!function_exists('get_all_batch_it_by_item_id_and_daily_measure_id')) {
    function get_all_batch_it_by_item_id_and_daily_measure_id($daily_measure_id = null, $item_id = null)
    {
        $data = BlendingMeasure::where('daily_measure_id', $daily_measure_id)
            ->where('blending_item_id', $item_id)
            ->select('batch_no')
            ->pluck('batch_no');
        return ($data);
    }
}

if (!function_exists('check_old_pending_batch')) {
    function check_old_pending_batch($daily_measure_id = null, $item_id = null, $table_name = null)
    {
        switch ($table_name) {
            case 'guacamole_measures':
                $check_old_batch_is_pending = GuacamoleMeasure::where('daily_measure_id', $daily_measure_id)
                    ->where('guacamole_item_id', $item_id)
                    ->where(function ($query) {
                        $query->whereNull('status')
                            ->orWhere('status', 'Pending');
                    })
                    ->exists();
                break;
            case 'blending_measures':
                $check_old_batch_is_pending = BlendingMeasure::where('daily_measure_id', $daily_measure_id)
                    ->where('blending_item_id', $item_id)
                    ->where(function ($query) {
                        $query->whereNull('status')
                            ->orWhere('status', 'Pending');
                    })
                    ->exists();
                break;

            default:
                $check_old_batch_is_pending = false;
                break;
        }
        // dd($check_old_batch_is_pending);
        return $check_old_batch_is_pending;
    }
}

if (! function_exists('get_abbreviation')) {
    function get_abbreviation($string)
    {
        $words = preg_split("/\s+/", trim($string));
        $abbr = '';
        foreach ($words as $word) {
            $abbr .= strtoupper(mb_substr($word, 0, 1));
        }
        return $abbr;
    }
}

if (!function_exists('pass_fail_na_status_check')) {
    function pass_fail_na_status_check($status = Null)
    {
        switch ($status) {
            case 'P':
                $output = 'Pass';
                break;
            case 'F':
                $output = 'Fail';
                break;
            case 'N':
                $output = 'N/A';
                break;

            default:
                $output = '-';
                break;
        }
        return $output;
    }
}

if (!function_exists('chip_status_config')) {
    function status_config(string $status): array
    {
        return match ($status) {
            'Not Started' => [
                'bg' => '#E8F8FF',
                'border' => '#016B9D',
                'color' => '#016B9D',
            ],
            'In Process' => [
                'bg' => '#FFF9BC',
                'border' => '#7A7000',
                'color' => '#7A7000',
            ],
            'Completed' => [
                'bg' => '#CAFFB8',
                'border' => '#208200',
                'color' => '#208200',
            ],
            default => [
                'bg'     => '#E8F8FF',
                'border' => '#016B9D',
                'color'  => '#016B9D',
            ],
        };
    }
}
