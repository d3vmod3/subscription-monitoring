<div class="p-6 space-y-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
            Dashboard
        </h2>
        <span class="text-sm text-zinc-500 dark:text-zinc-400">
            Welcome back, {{ Auth::user()->first_name }}
        </span>
    </div>
    <div class="border rounded-lg">
        <div class="bg-zinc-600 dark:text-zinc-600 rounded-t-lg p-2">
            <h3 class="text-xl font-bold text-zinc-100 dark:text-zinc-100">Net Sales</h3>
        </div>
        <div class="p-6">
            <!-- Month Range Filter -->
            <div class="flex flex-wrap items-end gap-4">
                <div class="flex flex-col">
                    <label for="chartMonthFrom" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        Month From
                    </label>
                    <input type="month" id="chartMonthFrom" wire:model.live="chart_monthFrom"
                        class="border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white focus:ring focus:ring-lime-400">
                </div>

                <div class="flex flex-col">
                    <label for="chartMonthTo" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        Month To
                    </label>
                    <input type="month" id="chartMonthTo" wire:model.live="chart_monthTo"
                        class="border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white focus:ring focus:ring-lime-400">
                </div>
                @error('monthFrom')
                    <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                @enderror
                @error('monthTo')
                    <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div wire:ignore>
                <canvas id="salesChart"></canvas>
            </div>
            
        </div>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-2 gap-4">
        <div class="border rounded-lg">
            <div class="bg-zinc-600 dark:text-zinc-600 rounded-t-lg p-2">
                <h3 class="text-xl font-bold text-zinc-100 dark:text-zinc-100">Paid and Unpaid Subscriptions</h3>
            </div>
            <div class="p-6">
                <!-- Month Range Filter -->
                <div class="flex flex-wrap items-end gap-4 mb-4">

                    <div class="flex flex-col">
                        <label for="summaryMonthFrom" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Month From
                        </label>
                        <input type="month" id="summaryMonthFrom" wire:model.live="monthFrom"
                            class="border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white focus:ring focus:ring-lime-400">
                    </div>

                    <div class="flex flex-col">
                        <label for="summaryMonthTo" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Month To
                        </label>
                        <input type="month" id="summaryMonthTo" wire:model.live="monthTo"
                            class="border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white focus:ring focus:ring-lime-400">
                    </div>
                    @error('monthFrom')
                        <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                    @enderror
                    @error('monthTo')
                        <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-2 gap-4">
                <!-- Summary Card -->
                    <div
                        class="p-6 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg transition-transform hover:-translate-y-1 hover:shadow-2xl duration-200 flex items-center justify-between">

                        <div>
                            <h3 class="text-md font-semibold text-zinc-900 dark:text-zinc-100">
                                Paid Subscriptions
                            </h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                Approved payments within selected month range
                            </p>
                        </div>

                        <p class="text-4xl font-bold text-lime-600 dark:text-lime-400">
                            {{ $approvedPaymentsCount }}
                        </p>

                    </div>
                    <div
                        class="p-6 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg transition-transform hover:-translate-y-1 hover:shadow-2xl duration-200 flex items-center justify-between">

                        <div>
                            <h3 class="text-md font-semibold text-zinc-900 dark:text-zinc-100">
                                Unpaid Subscriptions
                            </h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                Unpaid subscriptions within selected month range
                            </p>
                        </div>

                        <p class="text-4xl font-bold text-red-600 dark:text-red-400">
                            {{ $unpaidSubscriptionsCount }}
                        </p>

                    </div>
                    <div
                        class="p-6 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg transition-transform hover:-translate-y-1 hover:shadow-2xl duration-200 flex items-center justify-between">

                        <div>
                            <h3 class="text-md font-semibold text-zinc-900 dark:text-zinc-100">
                                Net Sales
                            </h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                Total collected in selected months
                            </p>
                        </div>

                        <p class="text-4xl font-bold text-lime-600 dark:text-lime-400">
                            {{ number_format($netSales, 2) }}
                        </p>

                    </div>
                    <div
                        class="p-6 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg transition-transform hover:-translate-y-1 hover:shadow-2xl duration-200 flex items-center justify-between">

                        <div>
                            <h3 class="text-md font-semibold text-zinc-900 dark:text-zinc-100">
                                Unpaid Amount
                            </h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                Total Unpaid Amount
                            </p>
                        </div>

                        <p class="text-4xl font-bold text-red-600 dark:text-red-400">
                            {{ number_format($unpaidAmount, 2) }}
                        </p>

                    </div>
                </div>
            </div>
        </div>
        <div class="border rounded-lg">
            <div class="bg-zinc-600 dark:text-zinc-600 rounded-t-lg p-2">
                <h3 class="text-xl font-bold text-zinc-100 dark:text-zinc-100">Subscribers and Subscriptions</h3>
            </div>
            <div class="p-6">

                <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-3 gap-4">
                <!-- Summary Card -->
                    <div
                        class="p-6 space-x-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg transition-transform hover:-translate-y-1 hover:shadow-2xl duration-200 flex items-center justify-between">

                        <div>
                            <h3 class="text-md font-semibold text-zinc-900 dark:text-zinc-100">
                                Active Subscribers
                            </h3>
                        </div>

                        <p class="text-4xl font-bold text-lime-600 dark:text-lime-400">
                            {{ $active_subscribers }}
                        </p>
                    </div>
                    <div
                        class="p-6 space-x-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg transition-transform hover:-translate-y-1 hover:shadow-2xl duration-200 flex items-center justify-between">

                        <div>
                            <h3 class="text-md font-semibold text-zinc-900 dark:text-zinc-100">
                                Inactive Subscribers
                            </h3>
                        </div>

                        <p class="text-4xl font-bold text-red-600 dark:text-red-400">
                            {{ $inactive_subscribers }}
                        </p>

                    </div>
                    <div
                        class="p-6 space-x-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg transition-transform hover:-translate-y-1 hover:shadow-2xl duration-200 flex items-center justify-between">

                        <div>
                            <h3 class="text-md font-semibold text-zinc-900 dark:text-zinc-100">
                                Active Subscriptions
                            </h3>
                        </div>

                        <p class="text-4xl font-bold text-lime-600 dark:text-lime-400">
                            {{ $active_subscriptions }}
                        </p>
                    </div>
                    <div
                        class="p-6 space-x-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg transition-transform hover:-translate-y-1 hover:shadow-2xl duration-200 flex items-center justify-between">

                        <div>
                            <h3 class="text-md font-semibold text-zinc-900 dark:text-zinc-100">
                                Inactive Subscriptions
                            </h3>
                        </div>

                        <p class="text-4xl font-bold text-red-600 dark:text-red-400">
                            {{ $inactive_subscriptions }}
                        </p>

                    </div>
                    <div
                        class="p-6 space-x-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg transition-transform hover:-translate-y-1 hover:shadow-2xl duration-200 flex items-center justify-between">

                        <div>
                            <h3 class="text-md font-semibold text-zinc-900 dark:text-zinc-100">
                                Disconnected Subscriptions
                            </h3>
                        </div>

                        <p class="text-4xl font-bold text-red-600 dark:text-red-400">
                            {{ $disconnected_subscriptions }}
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@assets
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.js"></script>
@endassets
@script
<script>
    const ctx = document.getElementById('salesChart');
    let isDark = document.documentElement.classList.contains('dark');
    let salesLabels = $wire.salesLabels;
    let salesData = $wire.salesData;
    let unpaidSalesData = $wire.unpaidSalesData;
    
    let salesChart = new Chart(ctx, {
        type: 'line',
        data: {
        labels: salesLabels,
        datasets: [
                    {
                        label: 'Paid',
                        data: salesData,
                        backgroundColor: 'rgba(54, 162, 235, 0.3)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: true
                    },
                    {
                        label: 'Unpaid',
                        data:unpaidSalesData,
                        backgroundColor: 'rgba(255, 99, 132, 0.3)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: true
                    }
                ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    ticks: {
                        color: isDark ? "#ffffff" : "#000000",
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => '₱' + value.toLocaleString(),
                        color: isDark ? "#ffffff" : "#000000",
                    }
                }
            }
        }
    });

    $wire.on('salesUpdated', (payload) => {
        // console.log(payload[0].labels);
        salesChart.data.labels = payload[0].labels;
        salesChart.data.datasets[0].data = payload[0].paid;
        salesChart.data.datasets[1].data = payload[0].unpaid;
        salesChart.update();
    });
    const observer = new MutationObserver(() => {
        const dark = document.documentElement.classList.contains('dark');
        salesChart.options.scales.x.ticks.color = dark ? "#ffffff" : "#000000";
        salesChart.options.scales.y.ticks.color = dark ? "#ffffff" : "#000000";
        salesChart.update();
    });

    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
</script>
@endscript