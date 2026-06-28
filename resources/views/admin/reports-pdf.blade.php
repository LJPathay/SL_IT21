<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Reports & Metrics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #1e3a8a;
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        .header p {
            color: #64748b;
            margin: 0;
            font-size: 11px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h2 {
            color: #1e40af;
            font-size: 16px;
            margin: 0 0 15px 0;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 8px;
        }
        .stat-label {
            color: #64748b;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-value {
            color: #0f172a;
            font-size: 20px;
            font-weight: bold;
        }
        .module-row {
            margin-bottom: 12px;
        }
        .module-name {
            font-weight: bold;
            color: #334155;
            margin-bottom: 4px;
        }
        .progress-bar {
            background: #e2e8f0;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: #2563eb;
            border-radius: 4px;
        }
        .module-stats {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #64748b;
            margin-top: 3px;
        }
        .audit-log {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        .audit-log:last-child {
            border-bottom: none;
        }
        .audit-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #2563eb;
            border-radius: 50%;
            margin-right: 8px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            color: #64748b;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Security Reports & Metrics</h1>
        <p>Generated on {{ $generatedAt }}</p>
    </div>

    <div class="section">
        <h2>Overall Statistics</h2>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ $totalUsers }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Total Courses</div>
                <div class="stat-value">{{ $totalCourses }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Completion Rate</div>
                <div class="stat-value">{{ $completionRate }}%</div>
            </div>
        </div>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-label">Total Enrollments</div>
                <div class="stat-value">{{ $totalEnrollments }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Completed Enrollments</div>
                <div class="stat-value">{{ $completedEnrollments }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Active Modules</div>
                <div class="stat-value">{{ $moduleCompletions->count() }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Module Completion Rates</h2>
        @forelse($moduleCompletions as $module)
        <div class="module-row">
            <div class="module-name">{{ $module['title'] }} ({{ $module['category'] }})</div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $module['completion_percentage'] }}%"></div>
            </div>
            <div class="module-stats">
                <span>{{ $module['completion_percentage'] }}% Completed</span>
                <span>{{ $module['completed_count'] }} / {{ $module['total_enrolled'] }} students</span>
            </div>
        </div>
        @empty
        <p style="color: #64748b; font-style: italic;">No modules found</p>
        @endforelse
    </div>

    <div class="section">
        <h2>Recent Audit Logs</h2>
        @forelse($recentAuditLogs as $log)
        <div class="audit-log">
            <span class="audit-dot"></span>
            <span>{{ $log->action ?? 'Audit log entry' }}</span>
            <span style="float: right; color: #64748b;">{{ $log->created_at ? $log->created_at->format('M d, Y H:i') : 'N/A' }}</span>
        </div>
        @empty
        <p style="color: #64748b; font-style: italic;">No audit logs found</p>
        @endforelse
    </div>

    <div class="footer">
        <p>This report was automatically generated by the Security Learning Platform.</p>
    </div>
</body>
</html>
