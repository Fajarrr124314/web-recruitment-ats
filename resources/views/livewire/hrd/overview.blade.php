<div class="h-full bg-slate-50/50 flex flex-col overflow-y-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Dashboard Analytics</h1>
        <p class="text-slate-500 mt-1">Ringkasan data pelamar dan performa rekrutmen tahun {{ $currentYear }}.</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute inset-y-0 left-0 w-1 bg-blue-500 rounded-l-2xl"></div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-500">Total Kandidat</h3>
                <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ number_format($totalCandidates) }}</p>
        </div>

        <!-- Active -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute inset-y-0 left-0 w-1 bg-amber-500 rounded-l-2xl"></div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-500">Proses Berjalan</h3>
                <div class="h-10 w-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ number_format($totalActive) }}</p>
        </div>

        <!-- Hired -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute inset-y-0 left-0 w-1 bg-emerald-500 rounded-l-2xl"></div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-500">Diterima (Hired)</h3>
                <div class="h-10 w-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ number_format($totalHired) }}</p>
        </div>

        <!-- Rejected -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute inset-y-0 left-0 w-1 bg-rose-500 rounded-l-2xl"></div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-500">Ditolak</h3>
                <div class="h-10 w-10 rounded-full bg-rose-50 flex items-center justify-center text-rose-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ number_format($totalRejected) }}</p>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex-1 min-h-[400px]">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-800">Statistik Pelamar Bulanan</h3>
            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-semibold">Tahun {{ $currentYear }}</span>
        </div>
        <div class="w-full h-full pb-8">
            <canvas id="candidatesChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        const ctx = document.getElementById('candidatesChart').getContext('2d');
        const chartData = @json($chartData);
        
        // Gradient for chart fill
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(239, 68, 68, 0.2)'); // red-500
        gradient.addColorStop(1, 'rgba(239, 68, 68, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Pelamar',
                    data: chartData,
                    borderColor: '#ef4444', // red-500
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#ef4444',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4 // Smooth curves
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b', // slate-800
                        padding: 12,
                        titleFont: { size: 13, family: "'Outfit', sans-serif" },
                        bodyFont: { size: 14, family: "'Outfit', sans-serif" },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Kandidat';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: { family: "'Outfit', sans-serif" },
                            color: '#64748b'
                        },
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false,
                        },
                        border: { display: false }
                    },
                    x: {
                        ticks: {
                            font: { family: "'Outfit', sans-serif" },
                            color: '#64748b'
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        border: { display: false }
                    }
                }
            }
        });
    });
</script>
