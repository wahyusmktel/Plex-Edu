@extends('layouts.app')

@section('title', 'Dashboard Sekolah - Literasia')

@section('styles')
<style>
    .page-header {
        padding: 20px 0;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
    }
    
    .icon-box-circle-header {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #fce4ec;
        color: #d81b60;
    }

    .school-info .label {
        font-size: 12px;
        color: #9e9e9e;
        display: block;
    }
    
    .school-info .name {
        font-size: 20px;
        font-weight: 700;
        color: #333;
    }

    .stats-card {
        border-radius: 16px;
        padding: 24px;
        background: #fff;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        /* Clean Box Styling */
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        transition: all 0.3s cubic-bezier(.25,.8,.25,1);
        margin-bottom: 24px; /* Fix for mobile stacking */
    }
    
    .stats-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 20px rgba(0,0,0,0.06);
        border-color: #e2e8f0;
    }
    
    .stats-card .icon-round {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.02);
    }
    
    .stats-card .label {
        color: #757575;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 6px;
    }
    
    .stats-card .value {
        font-size: 28px;
        font-weight: 800;
        color: #2d3748;
    }
    
    /* Stats Colors */
    .icon-pink { background-color: #fff0f3; color: #ff2d55; }
    .icon-purple { background-color: #f3f0ff; color: #7048e8; }
    .icon-yellow { background-color: #fff9db; color: #fcc419; }
    .icon-blue { background-color: #e7f5ff; color: #228be6; }
    .icon-red { background-color: #fff5f5; color: #fa5252; }
    .icon-lavender { background-color: #f8f0fc; color: #be4bdb; }
    
    .main-chart-card {
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        min-height: 450px;
        margin-bottom: 24px; /* Fix for mobile stacking */
    }
    
    .small-info-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        margin-bottom: 24px; /* Fix for mobile stacking */
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .small-info-card .icon-box {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .small-info-card.yellow-box .icon-box { background-color: #fff9db; color: #fcc419; }
    .small-info-card.blue-box .icon-box { background-color: #e7f5ff; color: #228be6; }
    .small-info-card.purple-box .icon-box { background-color: #f3f0ff; color: #7048e8; }
    .small-info-card.red-box .icon-box { background-color: #fff5f5; color: #fa5252; }
    
    .small-info-card .content .title {
        font-size: 15px;
        color: #718096;
        font-weight: 600;
        display: block;
    }
    
    .small-info-card .content .number {
        font-size: 20px;
        font-weight: 800;
        color: #1a202c;
    }
    
    .donut-placeholder {
        width: 100%;
        height: 280px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .donut-ring {
        width: 220px;
        height: 220px;
        border-radius: 50%;
        border: 30px solid #f7fafc;
        border-top-color: #d90d8b;
        border-right-color: #d90d8b;
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
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 6px 12px;
        height: auto;
        font-size: 13px;
        color: #4a5568;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col s12">
        <div class="page-header">
            <div class="icon-box-circle-header">
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
                <span style="font-weight:700; font-size: 18px; color: #1a202c;">Absensi Siswa</span>
                <select class="browser-default">
                    <option value="1">June 2022 - May 2022</option>
                </select>
            </div>
            <div class="donut-placeholder">
                <div class="donut-ring"></div>
                <div style="position: absolute; text-align: center;">
                    <div style="font-size: 13px; color: #718096; font-weight: 500;">Hadir</div>
                    <div style="font-size: 32px; font-weight: 800; color: #1a202c;">80%</div>
                </div>
            </div>
            <div style="display: flex; justify-content: center; gap: 40px; margin-top: 30px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 14px; height: 14px; background: #d90d8b; border-radius: 4px;"></div>
                    <span style="font-size: 13px; font-weight: 600; color: #4a5568;">Hadir</span>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 14px; height: 14px; background: #f7fafc; border-radius: 4px; border: 1px solid #e2e8f0;"></div>
                    <span style="font-size: 13px; font-weight: 600; color: #4a5568;">Absen</span>
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
