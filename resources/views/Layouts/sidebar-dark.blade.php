<aside class="control-sidebar control-sidebar-dark">
    <div class="p-3">
        <h5>Actividades Recientes</h5>
        <div class="activity-feed">
            @php
                $recentActivities = \App\Models\ActivityLog::with('user')
                    ->whereDate('created_at', today())
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get()
                    ->groupBy(function($item) {
                        return $item->created_at->format('H:i');
                    });
            @endphp

            @foreach($recentActivities as $time => $activities)
                <div class="activity-item mb-3">
                    <div class="activity-time">
                        <i class="far fa-clock"></i> {{ $time }}
                    </div>
                    @foreach($activities as $activity)
                        <div class="activity-detail">
                            <small>
                                <i class="fas fa-user-circle mr-1"></i>
                                {{ $activity->user->name ?? 'Sistema' }}
                                <br>
                                <span class="text-muted">
                                    {{ $activity->formatted_action }} en {{ $activity->module }}
                                </span>
                            </small>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <div class="text-center mt-3">
                <a href="{{ route('activity-logs.today') }}" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-clock-history"></i> Ver Todas
                </a>
            </div>
        </div>
    </div>
</aside>
<style>
    .activity-feed {
        max-height: 400px;
        overflow-y: auto;
    }
    .activity-item {
        border-left: 3px solid #007bff;
        padding-left: 10px;
    }
    .activity-time {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 5px;
    }
    .activity-detail {
        background: rgba(255,255,255,0.1);
        padding: 5px;
        border-radius: 3px;
        margin-bottom: 5px;
        font-size: 12px;
    }
</style>
