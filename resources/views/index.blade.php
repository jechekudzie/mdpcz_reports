@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Report</div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('report.index') }}">
                            <div class="form-group row">
                                <label for="profession" class="col-md-2 col-form-label">Profession:</label>
                                <div class="col-md-4">
                                    <input id="profession" type="text" class="form-control" name="profession" value="{{ request('profession') }}">
                                </div>
                                <label for="province" class="col-md-2 col-form-label">Province:</label>
                                <div class="col-md-4">
                                    <input id="province" type="text" class="form-control" name="province" value="{{ request('province') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="city" class="col-md-2 col-form-label">City:</label>
                                <div class="col-md-4">
                                    <input id="city" type="text" class="form-control" name="city" value="{{ request('city') }}">
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <table class="table table-bordered" id="report-table">
                            <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Gender</th>
                                <th>Profession</th>
                                <th>Qualification</th>
                                <th>Province</th>
                                <th>City</th>
                                <th>Business Address</th>
                                <th>Contact</th>
                            </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>names</td>
                                    <td>names</td>
                                    <td>names</td>
                                    <td>names</td>
                                    <td>names</td>
                                    <td>names</td>
                                    <td>names</td>
                                    <td>names</td>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- Latest DataTables version -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

@push('scripts')

@endpush
