
$('#table').DataTable({
    'paging': true,
   'lengthChange': true,
   'searching': true,
   'ordering': true,
   'info': true,
   'autoWidth': false,
   "language": {
       "emptyTable": "Data Kosong",
       "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
       "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
       "infoFiltered": "(disaring dari _MAX_ total data)",
       "search": "Cari:",
       "lengthMenu": "Tampilkan _MENU_ Data",
       "zeroRecords": "Tidak Ada Data yang Ditampilkan",
       "oPaginate": {
           "sFirst": "Awal",
           "sLast": "Akhir",
           "sNext": "Selanjutnya",
           "sPrevious": "Sebelumnya"
       },
   },
    'aoColumnDefs': [{
    'bSortable': false,
    'aTargets': ['nosort']
  }],
});
