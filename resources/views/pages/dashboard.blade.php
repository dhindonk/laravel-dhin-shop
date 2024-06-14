@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/chart.js/dist/Chart.css') }}">
    <style>
        .chart-container {
            position: relative;
            width: 100%;
            /* height: 300px; */
            overflow-x: auto;
            scrollbar-width: thin,
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Admin Dashboard</h1>
            </div>

            <div class="row">
                <div class="col-lg-8 col-md-4 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-stats">
                            <div class="card-stats-title">Order Statistics</div>
                            <div class="card-stats-items">
                                @foreach ($ordersByStatus as $status)
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count">{{ $status->total }}</div>
                                        <div class="card-stats-item-label">{{ $status->status }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-archive"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Orders</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalOrders }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-chart">
                            <canvas id="total-sales-chart" height="80"></canvas>
                        </div>
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-money-bill-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Sales</h4>
                            </div>
                            <div class="card-body">
                                Rp{{ number_format($totalSales, 2) }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Sales Per Month</h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="monthly-sales-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/chart.js/dist/Chart.js') }}"></script>

    <script>
        // Sales per month chart
        var ctx = document.getElementById('monthly-sales-chart').getContext('2d');
        var monthlySalesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    @foreach ($salesPerMonth as $month)
                        '{{ $month->month_name }} {{ $month->year }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Sales (Rp)',
                    data: [
                        @foreach ($salesPerMonth as $month)
                            {{ $month->total_sales }},
                        @endforeach
                    ],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            boxWidth: 20,
                            font: {
                                size: 10
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return 'Rp' + tooltipItem.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'month',
                            displayFormats: {
                                month: 'MMM YYYY'
                            }
                        },
                        ticks: {
                            autoSkip: true,
                            maxTicksLimit: 12, // Maximal 12 label
                            maxRotation: 0, // Rotasi label sumbu x
                            minRotation: 0 // Rotasi label sumbu x
                        }
                    },
                    yAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                return 'Rp' + value.toLocaleString();
                            }
                        }
                    }],
                }
            }
        });

        // Total sales chart
        var total_sales_chart = document.getElementById('total-sales-chart').getContext('2d');

        // Background color gradient for the chart
        var total_sales_chart_bg_color = total_sales_chart.createLinearGradient(0, 0, 0, 70);
        total_sales_chart_bg_color.addColorStop(0, 'rgba(75, 192, 192, 0.2)');
        total_sales_chart_bg_color.addColorStop(1, 'rgba(75, 192, 192, 0)');

        var myChart = new Chart(total_sales_chart, {
            type: 'line',
            data: {
                labels: [
                    @foreach ($salesPerMonth as $month)
                        '{{ $month->year }}-{{ $month->month }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Sales (Rp)',
                    data: [
                        @foreach ($salesPerMonth as $month)
                            {{ $month->total_sales }},
                        @endforeach
                    ],
                    backgroundColor: total_sales_chart_bg_color,
                    borderWidth: 3,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    pointBorderWidth: 0,
                    pointBorderColor: 'transparent',
                    pointRadius: 3,
                    pointBackgroundColor: 'transparent',
                    pointHoverBackgroundColor: 'rgba(75, 192, 192, 1)',
                }]
            },
            options: {
                layout: {
                    padding: {
                        bottom: -1,
                        left: -1
                    }
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            display: false,
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                return '';
                            }
                        }
                    }],
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'month',
                            displayFormats: {
                                month: 'MMM YYYY'
                            }
                        },
                        gridLines: {
                            drawBorder: false,
                            display: false,
                        },
                        ticks: {
                            display: false,
                            autoSkip: true,
                            maxTicksLimit: 12,
                            maxRotation: 0,
                            minRotation: 0
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return 'Rp' + tooltipItem.raw.toLocaleString();
                        }
                    }
                }
            }
        });
        // // Total sales chart Original
        // var total_sales_chart = document.getElementById('total-sales-chart').getContext('2d');

        // // Background color gradient for the chart
        // var total_sales_chart_bg_color = total_sales_chart.createLinearGradient(0, 0, 0, 70);
        // total_sales_chart_bg_color.addColorStop(0, 'rgba(75, 192, 192, 0.2)');
        // total_sales_chart_bg_color.addColorStop(1, 'rgba(75, 192, 192, 0)');

        // var myChart = new Chart(total_sales_chart, {
        //     type: 'line',
        //     data: {
        //         labels: [
        //             @foreach ($salesPerMonth as $month)
        //                 '{{ $month->year }}-{{ $month->month }}',
        //             @endforeach
        //         ],
        //         datasets: [{
        //             label: 'Sales (Rp)',
        //             data: [
        //                 @foreach ($salesPerMonth as $month)
        //                     {{ $month->total_sales }},
        //                 @endforeach
        //             ],
        //             backgroundColor: total_sales_chart_bg_color,
        //             borderWidth: 3,
        //             borderColor: 'rgba(75, 192, 192, 1)',
        //             pointBorderWidth: 0,
        //             pointBorderColor: 'transparent',
        //             pointRadius: 3,
        //             pointBackgroundColor: 'transparent',
        //             pointHoverBackgroundColor: 'rgba(75, 192, 192, 1)',
        //         }]
        //     },
        //     options: {
        //         layout: {
        //             padding: {
        //                 bottom: -1,
        //                 left: -1
        //             }
        //         },
        //         legend: {
        //             display: false
        //         },
        //         scales: {
        //             yAxes: [{
        //                 gridLines: {
        //                     display: false,
        //                     drawBorder: false,
        //                 },
        //                 ticks: {
        //                     beginAtZero: true,
        //                     callback: function(value, index, values) {
        //                         return 'Rp' + value.toLocaleString();
        //                     }
        //                 }
        //             }],
        //             xAxes: [{
        //                 type: 'time',
        //                 time: {
        //                     unit: 'month',
        //                     displayFormats: {
        //                         month: 'MMM YYYY'
        //                     }
        //                 },
        //                 gridLines: {
        //                     drawBorder: false,
        //                     display: false,
        //                 },
        //                 ticks: {
        //                     display: true,
        //                     autoSkip: true,
        //                     maxTicksLimit: 12, // Menampilkan maksimal 12 label
        //                     maxRotation: 0, // Rotasi label sumbu x
        //                     minRotation: 0 // Rotasi label sumbu x
        //                 }
        //             }]
        //         },
        //         tooltips: {
        //             callbacks: {
        //                 label: function(tooltipItem) {
        //                     return 'Rp' + tooltipItem.raw.toLocaleString();
        //                 }
        //             }
        //         }
        //     }
        // });
    </script>
@endpush
