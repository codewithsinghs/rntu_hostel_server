<!-- ✅ Include jQuery + DataTables + Buttons extensions -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>

    // ✅ Initialize DataTable AFTER data load
    function InitializeDatatable() {

        $('table').DataTable({
            pageLength: 10,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'copy', className: 'btn btn-sm btn-outline-primary', exportOptions: { columns: ':not(:last-child)' } },
                { extend: 'csv', className: 'btn btn-sm btn-outline-success', exportOptions: { columns: ':not(:last-child)' } },
                { extend: 'excel', className: 'btn btn-sm btn-outline-info', exportOptions: { columns: ':not(:last-child)' } },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-sm btn-outline-danger',
                    exportOptions: { columns: ':not(:last-child)' },
                    customize: function (doc) {
                        doc.pageMargins = [20, 20, 20, 20];
                        doc.styles.tableHeader = {
                            bold: true,
                            fontSize: 12,
                            fillColor: '#343a40',
                            color: 'white',
                            alignment: 'center',
                            margin: [5, 5, 5, 5]
                        };
                        doc.styles.tableBodyEven = { margin: [5, 5, 5, 5] };
                        doc.styles.tableBodyOdd = { margin: [5, 5, 5, 5] };
                        doc.content.splice(0, 0, {
                            text: 'Department Report',
                            fontSize: 16,
                            bold: true,
                            alignment: 'center',
                            margin: [0, 0, 0, 10]
                        });
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-sm btn-outline-secondary',
                    exportOptions: { columns: ':not(:last-child)' },
                    customize: function (win) {
                        $(win.document.body)
                            .css('font-size', '12pt')
                            .css('padding', '20px');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('margin', '20px auto')
                            .css('border-collapse', 'collapse')
                            .css('width', '100%');

                        $(win.document.body).find('th, td')
                            .css('padding', '8px')
                            .css('border', '1px solid #ddd');
                    }
                }
            ]

        });
    }



</script>