@extends('layout2.theme')

@section('title', 'Thống kê - Quản lý KTX')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-primary mb-4">📊 Thống kê tổng quan</h2>

        <!-- Tổng quan -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white h-100 shadow-sm">
                    <div class="card-body">
                        <h5><i class="fas fa-user-graduate me-2"></i>Sinh viên đang ở</h5>
                        <h3>{{ $total_students }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-success text-white h-100 shadow-sm">
                    <div class="card-body">
                        <h5><i class="fas fa-door-open me-2"></i>Phòng đang sử dụng</h5>
                        <h3>{{ $occupied_rooms }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-warning text-white h-100 shadow-sm">
                    <div class="card-body">
                        <h5><i class="fas fa-bed me-2"></i>Phòng trống</h5>
                        <h3>{{ $available_rooms }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-danger text-white h-100 shadow-sm">
                    <div class="card-body">
                        <h5><i class="fas fa-coins me-2"></i>Doanh thu tháng {{ now()->format('m/Y') }}</h5>
                        <h3>{{ number_format($monthly_revenue, 0, ',', '.') }} VNĐ</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-chart-line me-2"></i>Doanh thu 12 tháng gần nhất
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-chart-pie me-2"></i>Tỷ lệ sử dụng phòng
            </div>
            <div class="card-body">
                <canvas id="roomChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Biểu đồ doanh thu
        const ctx1 = document.getElementById('revenueChart');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: {!! json_encode($revenues) !!},
                    borderWidth: 3,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.3)',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Biểu đồ phòng
        const ctx2 = document.getElementById('roomChart');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Phòng trống', 'Phòng đầy'],
                datasets: [{
                    data: [{{ $available_rooms }}, {{ $occupied_rooms }}],
                    backgroundColor: ['#28a745', '#dc3545'],
                }]
            }
        });
    </script>
@endsection
