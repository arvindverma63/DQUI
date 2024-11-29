<!-- Javascript -->
<script src="assets/plugins/popper.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

<!-- Charts JS -->
<script src="assets/plugins/chart.js/chart.min.js"></script>
<script src="assets/js/index-charts.js"></script>

<!-- Page Specific JS -->
<script src="assets/js/app.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script>
    $(document).ready(function() {
        var table = $('table').DataTable({
            dom: 'Bfrtip',
            order: [[0, 'desc']], // Default order (desc)
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    className: 'btn btn-success text-white',
                    title: 'Transactions Data'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Export to PDF',
                    className: 'btn btn-danger text-white',
                    title: 'Transactions Data',
                    orientation: 'landscape', // Adjust orientation for better layout
                    pageSize: 'A4'
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'btn btn-info text-white',
                    title: 'Transactions Data'
                },
                {
                    text: 'Reverse Order', // Button text for reverse
                    className: 'btn btn-warning text-white',
                    action: function(e, dt, button, config) {
                        // Get the current order of the table
                        var currentOrder = dt.order();
                        
                        // Reverse the order
                        if (currentOrder[0][0] === 0) {
                            // If it is already in descending order, change to ascending
                            dt.order([0, 'asc']).draw();
                        } else {
                            // If it is in ascending order, change to descending
                            dt.order([0, 'desc']).draw();
                        }
                    }
                }
            ]
        });
    });
</script>
