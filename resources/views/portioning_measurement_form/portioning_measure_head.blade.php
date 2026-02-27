@extends('layouts.main')
@section('content')
    <style>
        .form__radio-input {
            display: none;
        }

        .form__label-radio {
            font-size: 1.6rem;
            cursor: pointer;
            position: relative;
            padding-left: 3.2rem;
        }

        .form__radio-button {
            height: 2.5rem;
            width: 2.5rem;
            border: 2px solid #344050;
            border-radius: 50%;
            display: inline-block;
            position: absolute;
            left: 0;
            top: -0.4rem;
        }

        .form__radio-button::after {
            content: "";
            display: block;
            height: 1.3rem;
            width: 1.3rem;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #A982DD;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .form__radio-input:checked~.form__label-radio .form__radio-button::after {
            opacity: 1;
        }
    </style>
    <livewire:portioning-measure-date-model />
@endsection
