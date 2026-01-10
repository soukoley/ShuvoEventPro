<?php
if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";
    exit;
}
?>

<h4 class="section-title">
    <i class="fa fa-bar-chart"></i> Payment Analytics
</h4>

<!-- FILTER -->
<div class="panel panel-default">
    <div class="panel-body row">
        <div class="col-md-3">
            <label class="edit-label">From Date</label>
            <input type="date" id="fromDate" class="form-control">
        </div>

        <div class="col-md-3">
            <label class="edit-label">To Date</label>
            <input type="date" id="toDate" class="form-control">
        </div>

        <div class="col-md-3" style="margin-top:25px;">
            <button class="btn btn-primary" id="loadChart">
                <i class="fa fa-refresh"></i> Load Chart
            </button>
        </div>
    </div>
</div>

<!-- CHARTS -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading corporate-heading">Daily Collection</div>
            <div class="panel-body">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading corporate-heading">Mode-wise Collection</div>
            <div class="panel-body">
                <canvas id="modeChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading corporate-heading">Received vs Due</div>
            <div class="panel-body">
                <canvas id="dueChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>

<script>
var dailyChart, modeChart, dueChart;

function loadCharts() {

    var from = $("#fromDate").val();
    var to   = $("#toDate").val();

    $.ajax({
        url: "ajax_payment_chart_data.php",
        type: "POST",
        dataType: "json",
        data: { from_date: from, to_date: to },
        success: function(res) {

            /* ---------- DAILY ---------- */
            if (dailyChart) dailyChart.destroy();
            dailyChart = new Chart(document.getElementById("dailyChart"), {
                type: 'line',
                data: {
                    labels: res.daily.labels,
                    datasets: [{
                        label: 'Amount (₹)',
                        data: res.daily.data,
                        backgroundColor: 'rgba(123,30,43,0.15)',
                        borderColor: '#7B1E2B',
                        borderWidth: 2,
                        fill: true
                    }]
                }
            });

            /* ---------- MODE ---------- */
            if (modeChart) modeChart.destroy();
            modeChart = new Chart(document.getElementById("modeChart"), {
                type: 'pie',
                data: {
                    labels: res.mode.labels,
                    datasets: [{
                        data: res.mode.data,
                        backgroundColor: ['#7B1E2B','#D4A017','#4CAF50','#2196F3']
                    }]
                }
            });

            /* ---------- DUE ---------- */
            if (dueChart) dueChart.destroy();

            var receivedAmt = (res.summary && !isNaN(res.summary.received))
                ? parseFloat(res.summary.received)
                : 0;

            var dueAmt = (res.summary && !isNaN(res.summary.due))
                ? parseFloat(res.summary.due)
                : 0;

            dueChart = new Chart(document.getElementById("dueChart"), {
                type: 'bar',
                data: {
                    labels: ['Received Amount', 'Due Amount'],
                    datasets: [{
                        label: 'Amount (₹)',
                        data: [receivedAmt, dueAmt],
                        backgroundColor: ['#4CAF50', '#C62828']
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return '₹ ' + tooltipItem.yLabel.toFixed(2);
                            }
                        }
                    }
                }
            });
            if (dueChart) dueChart.destroy();

            var receivedAmt = (res.summary && !isNaN(res.summary.received))
                ? parseFloat(res.summary.received)
                : 0;

            var dueAmt = (res.summary && !isNaN(res.summary.due))
                ? parseFloat(res.summary.due)
                : 0;

            dueChart = new Chart(document.getElementById("dueChart"), {
                type: 'bar',
                data: {
                    labels: ['Received Amount', 'Due Amount'],
                    datasets: [{
                        label: 'Amount (₹)',
                        data: [receivedAmt, dueAmt],
                        backgroundColor: ['#4CAF50', '#C62828']
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return '₹ ' + tooltipItem.yLabel.toFixed(2);
                            }
                        }
                    }
                }
            });

        }
    });
}

$("#loadChart").on("click", loadCharts);
</script>
