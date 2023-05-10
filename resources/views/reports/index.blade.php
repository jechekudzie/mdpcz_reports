@extends('layouts.reports')


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
@livewireStyles
@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Report</div>
                    @livewire('practitioner-report-by-province')
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- Latest DataTables version -->

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>


<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>
@livewireScripts
