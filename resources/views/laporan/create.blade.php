@extends('layouts.app')
@section('title', 'Buat Laporan Baru - RoadFix')

@section('content')
    <p class="text-accent text-xs font-bold uppercase tracking-wider mb-1">Portal Warga</p>
    <h1 class="text-2xl font-extrabold text-white mb-2">Buat Laporan Baru</h1>
    <p class="text-slate-400 text-sm mb-6">Isi detail kerusakan jalan selengkap mungkin agar dinas dapat menindaklanjuti dengan cepat.</p>

    @include('laporan._form')
@endsection