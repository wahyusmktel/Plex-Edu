@extends('layouts.app')

@section('title', 'Dashboard Sekolah - Literasia')

@section('styles')
<style>
    .stats-card {
        border-radius: 12px;
        padding: 24px;
        background: #fff;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
    }
    
    .stats-card .icon-round {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    
    .stats-card .label {
        color: #9e9e9e;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 4px;
    }
    
    .stats-card .value {
        font-size: 24px;
        font-weight: 700;
        color: #424242;
    }
    
    /* Stats Colors */
    .icon-pink { background-color: #fce4ec; color: #d81b60; }
    .icon-purple { background-color: #f3e5f5; color: #8e24aa; }
    .icon-yellow { background-color: #fffde7; color: #fbc02d; }
    .icon-blue { background-color: #e3f2fd; color: #1e88e5; }
    .icon-red { background-color: #ffebee; color: #e53935; }
    .icon-lavender { background-color: #f3e5f5; color: #7e57c2; }
    
    .main-chart-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        min-height: 400px;
    }
    
    .small-info-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .small-info-card .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .small-info-card.yellow-box .icon-box { background-color: #fffde7; color: #fbc02d; }
    .small-info-card.blue-box .icon-box { background-color: #e3f2fd; color: #1e88e5; }
    .small-info-card.purple-box .icon-box { background-color: #f3e5f5; color: #8e24aa; }
    .small-info-card.red-box .icon-box { background-color: #ffebee; color: #e53935; }
    
    .small-info-card .content {
        display: flex;
        flex-direction: column;
    }
    
    .small-info-card .content .title {
        font-size: 14px;
        color: #9e9e9e;
        font-weight: 500;
    }
    
    .small-info-card .content .number {
        font-size: 18px;
        font-weight: 700;
        color: #424242;
    }
    
    .donut-placeholder {
        width: 100%;
        height: 250px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .donut-ring {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        border: 25px solid #f3f3f3;
        border-top-color: #e91e63;
        border-right-color: #e91e63;
        transform: rotate(45deg);
    }
    
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    
    .chart-header select {
        display: block;
        width: auto;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 4px 8px;
        height: auto;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col s12">
        <div class="page-header">
            <div class="icon-box-circle pink">
                <i class="material-icons">school</i>
            </div>
            <div class="school-info">
                <span class="label">Sekolah</span>
                <span class="name">Sekolah Literasia Edutekno Digital</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col s12 m4 l2">
        <div class="stats-card">
            <div class="icon-round icon-pink">
                <i class="material-icons">book</i>
            </div>
            <span class="label">E-Book</span>
            <span class="value">1921</span>
        </div>
    </div>
    <div class="col s12 m4 l2">
        <div class="stats-card">
            <div class="icon-round icon-purple">
                <i class="material-icons">music_note</i>
            </div>
            <span class="label">Audio Book</span>
            <span class="value">733</span>
        </div>
    </div>
    <div class="col s12 m4 l2">
        <div class="stats-card">
            <div class="icon-round icon-yellow">
                <i class="material-icons">play_circle_filled</i>
            </div>
            <span class="label">Video Book</span>
            <span class="value">745</span>
        </div>
    </div>
    <div class="col s12 m4 l2">
        <div class="stats-card">
            <div class="icon-round icon-blue">
                <i class="material-icons">people</i>
            </div>
            <span class="label">Siswa</span>
            <span class="value">36</span>
        </div>
    </div>
    <div class="col s12 m4 l2">
        <div class="stats-card">
            <div class="icon-round icon-red">
                <i class="material-icons">person</i>
            </div>
            <span class="label">Guru</span>
            <span class="value">13</span>
        </div>
    </div>
    <div class="col s12 m4 l2">
        <div class="stats-card">
            <div class="icon-round icon-lavender">
                <i class="material-icons">person</i>
            </div>
            <span class="label">Pegawai</span>
            <span class="value">13</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col s12 l7">
        <div class="main-chart-card">
            <div class="chart-header">
                <span style="font-weight:600; font-size: 16px;">Absensi Siswa</span>
                <select class="browser-default">
                    <option value="1">June 2022 - May 2022</option>
                </select>
            </div>
            <div class="donut-placeholder">
                <div class="donut-ring"></div>
                <div style="position: absolute; text-align: center;">
                    <div style="font-size: 12px; color: #9e9e9e;">Hadir</div>
                    <div style="font-size: 24px; font-weight: 700;">80%</div>
                </div>
            </div>
            <div style="display: flex; justify-content: center; gap: 30px; margin-top: 20px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 12px; height: 12px; background: #e91e63; border-radius: 2px;"></div>
                    <span style="font-size: 12px;">Hadir</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 12px; height: 12px; background: #eee; border-radius: 2px;"></div>
                    <span style="font-size: 12px;">Absen</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col s12 l5">
        <div class="small-info-card yellow-box">
            <div class="icon-box">
                <i class="material-icons">cast_for_education</i>
            </div>
            <div class="content">
                <span class="title">Materi E-Learning</span>
                <span class="number">9</span>
            </div>
        </div>
        <div class="small-info-card blue-box">
            <div class="icon-box">
                <i class="material-icons">account_balance</i>
            </div>
            <div class="content">
                <span class="title">Bank Soal</span>
                <span class="number">6</span>
            </div>
        </div>
        <div class="small-info-card purple-box">
            <div class="icon-box">
                <i class="material-icons">forum</i>
            </div>
            <div class="content">
                <span class="title">Postingan Forum</span>
                <span class="number">1</span>
            </div>
        </div>
        <div class="small-info-card red-box">
            <div class="icon-box">
                <i class="material-icons">report_problem</i>
            </div>
            <div class="content">
                <span class="title">Pelanggaran</span>
                <span class="number">6</span>
            </div>
        </div>
    </div>
</div>
@endsection
