@extends('users.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"> Employee {{ __('Dashboard') }}
                    <a class="btn btn-success float-right" href="javascript:void(0)" id="createNewEmployee"> Add New Employee</a>
                </div>

                <div class="card-body">
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>Sl. No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Designation</th>
                                <th>Salary</th>
                                <th width="300px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>  
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="employeeForm" name="employeeForm" class="form-horizontal">
                   <input type="hidden" name="employee_id" id="employee_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                        </div>
                    </div>
     
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-12">
                            <input type="email" id="email" name="email" required="" placeholder="Enter Email" class="form-control"  required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Mobile</label>
                        <div class="col-sm-12">
                            <input type="number" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile" value="" maxlength="10" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Designation</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="designation" name="designation" placeholder="Enter Designation" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Salary</label>
                        <div class="col-sm-12">
                            <input type="number" class="form-control" id="salary" name="salary" placeholder="Enter Salary" value="" maxlength="10" required="">
                        </div>
                    </div>
      
                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    </main>
</div>
@endsection

@section('bottomcontent')
    
    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('employees.index') }}",
                
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'designation', name: 'designation'},
                    {data: 'salary', name: 'salary'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('#createNewEmployee').click(function () {
                $('#saveBtn').val("create-employee");
                $('#employee_id').val('');
                $('#employeeForm').trigger("reset");
                $('#modelHeading').html("Create New Employee");
                $('#ajaxModel').modal('show');
            });

            $('body').on('click', '.editEmployee', function () {
                var employee_id = $(this).data('id');
                $.get("{{ route('employees.index') }}" +'/' + employee_id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Employee");
                    $('#saveBtn').val("edit-employee");
                    $('#ajaxModel').modal('show');
                    $('#employee_id').val(data.id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#mobile').val(data.mobile);
                    $('#designation').val(data.designation);
                    $('#salary').val(data.salary);
                })
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Save');
            
                $.ajax({
                data: $('#employeeForm').serialize(),
                url: "{{ route('employees.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
            
                    $('#employeeForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();
                
                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save Changes');
                }
            });
            });
            
            $('body').on('click', '.deleteEmployee', function () {
            
                var employee_id = $(this).data("id");
                confirm("Are You sure want to delete !");
            
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('employees.store') }}"+'/'+employee_id,
                    success: function (data) {
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
            
        });
    </script>

@endsection


