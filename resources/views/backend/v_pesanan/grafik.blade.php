@extends('backend.v_layouts.app')

@section('content')
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="card-title">{{ $judul }}</h4>
            <h6 class="card-subtitle mb-2 text-muted">{{ $subJudul }}</h6>
            <canvas id="orderChart" height="100"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Include Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('orderChart').getContext('2d');

    // Fetch data from API
    fetch("{{ route('pesanan.grafik.data') }}")
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.date);
            const totals = data.map(item => item.total);

            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Pesanan',
                        data: totals,
                        fill: false,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                tooltipFormat: 'DD MMM YYYY',
                                displayFormats: {
                                    day: 'DD MMM'
                                }
                            },
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Pesanan'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching grafik data:', error);
        });
});
</script>
@endsection