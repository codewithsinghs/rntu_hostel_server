@extends('admin.layout')

@section('content')

    <!-- ================= TOP BAR ================= -->
    <div class="top-breadcrumbs">
        <div class="breadcrumbs">
            <a>Live Attendance / </a>
            <a>Attendance of Rajat Pradhan (Scholar - 45465)</a>
        </div>

        <span>

            <button class="add-btn">Send Notification</button>
            <button class="add-btn">+ Add Item</button>
            <button class="add-btn">Download Excel</button>

        </span>

    </div>

    <!-- Attendance Summary Section -->
    <div class="attendance-chart">

        <!-- Attendance Days -->
        <div id="attendanceDays"></div>

        <!-- Legend -->
        <div class="legend">
            <div class="legend-item">
                <span class="legend-box" style="background-color: #28a745;"></span> 20 Thump
            </div>
            <div class="legend-item">
                <span class="legend-box" style="background-color: #dc3545;"></span> 33 Not Thump
            </div>
            <div class="legend-item">
                <span class="legend-box" style="background-color: #fd7e14;"></span> 02 Holidays
            </div>
        </div>
    </div>

    <!-- ================= MAIN CONTAINER ================= -->
    <section class="attendance-section">
        <div class="attendance-comparison">
            <div id="ss"></div>
        </div>
    </section>

    <!-- Thumb Time Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a>Hostel Check In-Out Thumb Time</a></div>

                <div class="overflow-auto">
                    <table class="status-table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Hostel</th>
                                <th>Room Number</th>
                                <th>Check Out Thump Time</th>
                                <th>Check In Thump Time </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Hostel One</td>
                                <td>304</td>
                                <td>01-Aug-2025 05:55PM</td>
                                <td>01-Aug-2025 10:22PM</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Hostel One</td>
                                <td>304</td>
                                <td>01-Aug-2025 11:22AM</td>
                                <td>01-Aug-2025 12:02PM</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

@endsection

@push('styles')
    <link rel="stylesheet"
        href="https://developer.mescius.com/spreadjs/demos/en/purejs/node_modules/@mescius/spread-sheets/styles/gc.spread.sheets.excel2013white.css">

    <style>
        /* ===== Layout Fix ===== */
        .attendance-section {
            width: 100%;
            height: calc(100vh - 130px);
            /* adjust if header size changes */
            padding: 10px;
        }

        .attendance-comparison {
            width: 100%;
            height: 100%;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
        }

        #ss {
            width: 100% !important;
            height: 100% !important;
        }
    </style>
@endpush

@push('scripts')

    <script
        src="https://developer.mescius.com/spreadjs/demos/en/purejs/node_modules/@mescius/spread-sheets/dist/gc.spread.sheets.all.min.js"></script>
    <script
        src="https://developer.mescius.com/spreadjs/demos/en/purejs/node_modules/@mescius/spread-sheets-print/dist/gc.spread.sheets.print.min.js"></script>
    <script src="https://developer.mescius.com/spreadjs/demos/spread/source/js/license.js"></script>
    <script src="https://developer.mescius.com/spreadjs/demos/spread/source/data/attendance-record.js"></script>

    <script>
        let spread;

        window.onload = function () {
            spread = new GC.Spread.Sheets.Workbook(
                document.getElementById("ss"),
                { sheetCount: 1 }
            );

            initSpread(spread);
        };

        function initSpread(spread) {
            spread.suspendPaint();

            spread.fromJSON(data);

            // ===== Spread Options =====
            spread.options.showHorizontalScrollbar = false;
            spread.options.showVerticalScrollbar = true;
            spread.options.allowUserZoom = false;

            const sheet = spread.sheets[0];

            // ===== Print Settings =====
            const printInfo = sheet.printInfo();
            printInfo.showBorder(false);
            printInfo.showGridLine(false);
            printInfo.showColumnHeader(GC.Spread.Sheets.Print.PrintVisibilityType.hide);
            printInfo.showRowHeader(GC.Spread.Sheets.Print.PrintVisibilityType.hide);

            // ===== Fix Column Width to Fit Screen =====
            const colCount = sheet.getColumnCount();
            const containerWidth = document.getElementById("ss").offsetWidth;
            const colWidth = Math.floor(containerWidth / colCount);

            for (let i = 0; i < colCount; i++) {
                sheet.setColumnWidth(i, colWidth);
            }

            // ===== Print Button Style (FIXED HEIGHT) =====
            const style = new GC.Spread.Sheets.Style();
            style.cellButtons = [
                {
                    caption: "Print",
                    buttonBackColor: "#FA896B",
                    hoverBackColor: "#e17055",
                    useButtonStyle: true,
                    width: 120,
                    height: 40,
                    command: () => {
                        spread.print(0);
                    }
                }
            ];

            style.foreColor = "#fff";
            style.font = "14px Calibri";
            sheet.setStyle(0, 20, style);

            spread.resumePaint();

            // Force refresh after load
            setTimeout(() => {
                spread.refresh();
            }, 100);
        }

        // ===== Resize Fix =====
        window.addEventListener("resize", function () {
            if (spread) {
                spread.refresh();
            }
        });
    </script>

@endpush