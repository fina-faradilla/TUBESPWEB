@extends('layouts.app')
@section('title', 'Edit Laporan - RoadFix')

@section('content')
    <p class="text-accent text-xs font-bold uppercase tracking-wider mb-1">Portal Warga</p>
    <h1 class="text-2xl font-extrabold text-white mb-2">Edit Laporan</h1>
    <p class="text-slate-400 text-sm mb-6">Perbarui detail laporan kerusakan jalan #{{ $laporan->id }}.</p>

    @include('laporan._form')
@endsection
