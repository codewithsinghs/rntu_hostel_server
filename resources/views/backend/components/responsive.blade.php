<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .cust_box {
            margin-top: 15px;
            border: 1px solid #2125294d;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            overflow: auto;
        }

        .cust_heading {
            padding: 10px;
            margin-bottom: 20px;
            width: 100%;
            background: #0d2858;
            color: #fff;
            border-radius: 10px;
            text-align: center;
            font-size: 1.2rem;
        }

        /* Layout for group */
        .checkbox-group {
            display: flex;
            /* justify-content: space-between; equal spacing */
            align-items: center;
            gap: 20px;
            /* spacing between checkboxes */
            flex-wrap: wrap;
            /* wrap to next line if screen is small */
        }

        /* Wrapper */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            color: #333;
        }

        /* Hide default */
        .checkbox-wrapper input[type="checkbox"] {
            display: none;
        }

        /* Custom box */
        .checkmark {
            width: 18px;
            height: 18px;
            border: 2px solid #666;
            border-radius: 4px;
            display: inline-block;
            position: relative;
            transition: 0.2s;
        }

        /* On checked */
        .checkbox-wrapper input[type="checkbox"]:checked+.checkmark {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .checkbox-wrapper input[type="checkbox"]:checked+.checkmark::after {
            content: "";
            position: absolute;
            left: 5px;
            top: 1px;
            width: 4px;
            height: 9px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        @media only screen and (min-width: 200px) and (max-width: 800px) {

            div.dt-buttons>.dt-button,
            div.dt-buttons>div.dt-button-split .dt-button {
                padding: 5px 10px !important;
            }

            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_paginate {
                margin: 10px auto;
            }

            table.dataTable thead>tr>th.sorting,
            table.dataTable thead>tr>th.sorting_asc,
            table.dataTable thead>tr>th.sorting_desc,
            table.dataTable thead>tr>th.sorting_asc_disabled,
            table.dataTable thead>tr>th.sorting_desc_disabled,
            table.dataTable thead>tr>td.sorting,
            table.dataTable thead>tr>td.sorting_asc,
            table.dataTable thead>tr>td.sorting_desc,
            table.dataTable thead>tr>td.sorting_asc_disabled,
            table.dataTable thead>tr>td.sorting_desc_disabled {
                font-size: 13px;
                text-wrap-mode: nowrap;
            }

            table.dataTable tbody th,
            table.dataTable tbody td {
                font-size: 13px;
                text-wrap-mode: nowrap;
            }

            .form-select {
                margin-bottom: 10px !important;
            }

            .form-label {
                font-size: 14px !important;
            }

            .form-control {
                margin-bottom: 10px !important;
            }

            .cust_heading {
                font-size: 15px;
            }

        }
    </style>
</head>

<body>

</body>

</html>