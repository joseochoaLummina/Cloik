@extends('admin.layouts.admin_layout')
@section('content')
<style type="text/css">
    .table td, .table th {
        font-size: 12px;
        line-height: 2.42857 !important;
    }

</style>
<div class="page-content-wrapper"> 
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content"> 
        <!-- BEGIN PAGE HEADER--> 
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li> <a href="{{ route('admin.home') }}">Home</a> <i class="fa fa-circle"></i> </li>
                <li> <span>Recruiter</span> </li>
            </ul>
        </div>
        <!-- END PAGE BAR --> 
        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title">Manage Recruiter <small>Recruiter</small> </h3>
        <!-- END PAGE TITLE--> 
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12"> 
                <!-- Begin: life time stats -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption"> <i class="icon-settings font-dark"></i> <span class="caption-subject font-dark sbold uppercase">Recruiter</span> </div>                        
                        <div class="actions"> <a type="button" href="{{ route('new.recruiters') }}" class="btn btn-primary" >{{__('New Recruiter')}}</a> </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            <form method="post" role="form" id="datatable-search-form">
                                <table class="table table-striped table-bordered table-hover"  id="recruiterDatatableAjax">
                                    <thead>
                                        <tr role="row" class="filter">
                                            <td><input type="text" class="form-control" name="name" id="name" autocomplete="off" placeholder="Recruiter Name"></td>
                                            <td><input type="text" class="form-control" name="email" id="email" autocomplete="off" placeholder="Recruiter Email"></td>
                                            <td><input type="text" class="form-control" name="company" id="company" autocomplete="off" placeholder="Company"></td>                                        
                                            <td>
                                                <select name="is_master" id="is_master" class="form-control">
                                                    <option value="-1">All</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </td>                              
                                            <td></td>
                                        </tr>
                                        <tr role="row" class="heading">
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Company</th>
                                            <th>Is Master?</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CONTENT BODY --> 
</div>

@endsection
@push('scripts') 
<script>
    $(function () {
        var oTable = $('#recruiterDatatableAjax').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            searching: false,
            /*		
             "order": [[1, "asc"]],            
             paging: true,
             info: true,
             */
            ajax: {
                url: '{!! route('fetch.data.recruiters') !!}',
                data: function (d) {
                    d.name = $('#name').val();
                    d.email = $('#email').val();
                    d.company = $('#company').val();
                    d.is_master = $('#is_master').val();
                }
            }, columns: [
                {data: 'name', name: 'name',render:function(data, type, row){
                    return `${row.name} ${row.lastname} `
                }},
                {data: 'email', name: 'email'},
                {data: 'Cname', name: 'Cname'},
                {data: 'is_master', name: 'is_master'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
        $('#datatable-search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#name').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
       
        $('#email').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#company').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#is_master').on('change', function (e) {
            oTable.draw();
            e.preventDefault();
        });
    });
    function deleteRecruiter(id) {
        var msg = 'By confirming the deletion of the recruiter, your data is deleted and cannot be recovered, an email will also be sent to the company notifying its deletion.';
        if (confirm(msg)) {
            $.post("{{ route('delete.recruiter.admin') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        var table = $('#recruiterDatatableAjax').DataTable();
                        table.row('recruiterDtRow' + id).remove().draw(false);
                    } else
                    {
                        alert('Request Failed!');
                    }
            });
        }
    }
    function makeMaster(id) {
        $.post("{{ route('make.recruiter.master') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        var table = $('#recruiterDatatableAjax').DataTable();
                        table.row('recruiterDtRow' + id).remove().draw(false);
                    } else
                    {
                        alert(response);
                    }
                });
    }
    function makeJunior(id) {
        $.post("{{ route('make.recruiter.jr') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        var table = $('#recruiterDatatableAjax').DataTable();
                        table.row('recruiterDtRow' + id).remove().draw(false);
                    } else
                    {
                        alert(response);
                    }
                });
    }
</script> 
@endpush