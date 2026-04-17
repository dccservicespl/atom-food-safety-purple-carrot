@props(['percentage'])
@php
if($percentage <= 30){
    $status='red' ;
    }elseif($percentage > 30 && $percentage < 75){
        $status='yellow' ;
        }elseif($percentage >= 75){
        $status = 'green';
        }
        @endphp
        <div>
            <div class="progress-card" style="--progress: {{ $percentage }}">
                <div class="circle-wrap {{ $status }}">
                    <svg viewBox="0 0 80 80">
                        <circle class="track" cx="40" cy="40" r="35" />
                        <circle class="progress-ring" cx="40" cy="40" r="35" />
                    </svg>
                    <div class="circle-inner"></div>
                </div>
                <div class="progress-label">Production<br>Completed</div>
            </div>
        </div>