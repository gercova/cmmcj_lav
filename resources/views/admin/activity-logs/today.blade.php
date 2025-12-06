@extends('layouts.skelenton')
@section('title', 'Bitácora del Día')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bitácora del sistema</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Bitácora del sistema</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="timeline">
                                @foreach($activities as $key => $group)
                                    @php
                                        [$time, $action] = explode('|', $key);
                                    @endphp

                                    <div class="timeline-item">
                                        <i class="fas fa-{{ $group->first()->action === 'delete' ? 'trash' : ($group->first()->action === 'create' ? 'plus' : 'edit') }} bg-blue"></i>
                                        <div class="timeline-item-content">
                                            <span class="time">
                                                <i class="fas fa-clock"></i> {{ $time }}
                                            </span>
                                            <h3 class="timeline-header">
                                                <strong>{{ $action }}</strong>
                                            </h3>
                                            <div class="timeline-body">
                                                @foreach($group as $activity)
                                                    <div class="user-activity mb-2">
                                                        <i class="fas fa-user mr-1"></i>
                                                        <strong>{{ $activity->user->name ?? 'Sistema' }}</strong>
                                                        - Módulo: {{ $activity->module }}
                                                        @if($activity->record_id)
                                                            <span class="badge bg-info">ID: {{ $activity->record_id }}</span>
                                                        @endif
                                                        <a href="{{ route('activity-logs.detail', $activity->id) }}"
                                                        class="btn btn-xs btn-info float-right">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<style>
    .timeline {
        position: relative;
        margin: 0 0 30px 0;
        padding: 0;
        list-style: none;
    }
    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #ddd;
        left: 31px;
        margin: 0;
        border-radius: 2px;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 15px;
    }
    .timeline-item:before {
        content: " ";
        display: table;
    }
    .timeline-item:after {
        content: " ";
        display: table;
        clear: both;
    }
    .timeline-item > i {
        width: 30px;
        height: 30px;
        font-size: 15px;
        line-height: 30px;
        position: absolute;
        color: #666;
        background: #d2d6de;
        border-radius: 50%;
        text-align: center;
        left: 18px;
        top: 0;
    }
    .timeline-item-content {
        margin-left: 60px;
        background: #f4f4f4;
        padding: 10px;
        position: relative;
        border-radius: 3px;
    }
    .timeline-item-content:after {
        right: 100%;
        top: 10px;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
        border-right-color: #f4f4f4;
        border-width: 10px;
    }
    .timeline-header {
        margin: 0;
        color: #555;
        border-bottom: 1px solid #ddd;
        padding-bottom: 5px;
        font-size: 16px;
    }
    .timeline-body {
        padding: 10px 0;
    }
    .time {
        color: #999;
        float: right;
    }
    .user-activity {
        background: white;
        padding: 8px;
        border-radius: 4px;
        border-left: 3px solid #007bff;
    }
</style>
@endsection
