<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\User;
$user_details = User::where('id', Auth::user()->id)->first();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/atom/css/style.css">
    <link rel="stylesheet" href="/assets/atom/css/responsive.css">

    <!-- Lucide Icons (similar to the ones in your image) -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
