@extends('templates.app')

@section('title','Admin Dashboard')

@section('content')
<!-- CHART: only keep Borrowing Status -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-light shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-dark mb-4">Status Peminjaman</h5>
                <div class="row justify-content-center chart-container-small mx-auto"><div class="col-6">
                    <canvas id="borrowingStatusChart"></canvas></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let chartInstance = null;
const pieUrl = "{{ route('admin.borrowings.chart.pie') }}";

// Function to load and render chart
function loadChart() {
	fetch(pieUrl)
		.then(r => r.json())
		.then(json => {
			const ctx = document.getElementById('borrowingStatusChart').getContext('2d');

			// Destroy previous chart instance if exists
			if (chartInstance) {
				chartInstance.destroy();
			}

			chartInstance = new Chart(ctx, {
				type: 'pie',
				data: {
					labels: json.labels,
					datasets: [{
						data: json.data,
						backgroundColor: [
							'rgba(255, 193, 7, 0.8)',    // Pengembalian (yellow)
							'rgba(255, 99, 132, 0.8)'    // Denda (red)
						],
						borderColor: [
							'rgba(255, 193, 7, 1)',
							'rgba(255, 99, 132, 1)'
						],
						borderWidth: 2
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: true,
					plugins: {
						legend: { position: 'bottom' }
					}
				}
			});
		})
		.catch(e => console.error('borrowing status chart error', e));
}

document.addEventListener('DOMContentLoaded', function () {
	loadChart();

	// Auto-refresh chart every 10 seconds
	setInterval(loadChart, 10000);
});
</script>
@endpush


@push('style')
<style>
   .chart-container-small {
        width: 250px;
        height: 250px;
        margin: 0 auto;
    }

    #borrowingStatusChart {
        width: 100% !important;
        height: 100% !important;
    }

    .card {
        background-color: #fff;
        border: 1px solid #eaeaea;
        border-radius: 10px;
    }

    .card-title {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1.2rem;
    }
</style>
@endpush
