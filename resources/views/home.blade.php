@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- {{ csrf_token() }} --}}
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Students</span>
                        <button type="button" class="btn btn-sm btn-primary ms-auto" data-bs-toggle="modal"
                            data-bs-target="#addUserModal">
                            Add Student
                        </button>
                    </div>


                    <div class="card-body">


                        <div id="successMessage" class="alert alert-success alert-dismissible fade show" role="alert"
                            style="display: none;">

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>


                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Index</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody id="studentsTableBody">
                                <!-- Student rows will be populated here via AJAX -->
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addUserModalLabel">Add Student</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addStudentForm">
                        @csrf
                        <div class="mb-3">
                            <label for="index" class="form-label">Index</label>
                            <input type="text" class="form-control" name="index" id="index"
                                placeholder="Enter index">
                            <small class="text-danger" id="indexError"></small> <!-- Error message for Index -->
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Enter name">
                            <small class="text-danger" id="nameError"></small> <!-- Error message for Name -->
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email"
                                placeholder="Enter email">
                            <small class="text-danger" id="emailError"></small> <!-- Error message for Email -->
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" id="phone"
                                placeholder="Enter phone number">
                            <small class="text-danger" id="phoneError"></small> <!-- Error message for Phone -->
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" id="address"
                                placeholder="Enter address">
                            <small class="text-danger" id="addressError"></small> <!-- Error message for Address -->
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="submitStudentForm" class="btn btn-primary">Add Student</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editStudentModalLabel">Edit Student</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editStudentForm">
                        @csrf
                        <input type="hidden" id="editStudentId" name="id">
                        <div class="mb-3">
                            <label for="editIndex" class="form-label">Index</label>
                            <input type="text" class="form-control" name="index" id="editIndex"
                                placeholder="Enter index">
                            <small class="text-danger" id="editIndexError"></small>
                        </div>
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="editName"
                                placeholder="Enter name">
                            <small class="text-danger" id="editNameError"></small>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="editEmail"
                                placeholder="Enter email">
                            <small class="text-danger" id="editEmailError"></small>
                        </div>
                        <div class="mb-3">
                            <label for="editPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" id="editPhone"
                                placeholder="Enter phone number">
                            <small class="text-danger" id="editPhoneError"></small>
                        </div>
                        <div class="mb-3">
                            <label for="editAddress" class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" id="editAddress"
                                placeholder="Enter address">
                            <small class="text-danger" id="editAddressError"></small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="submitEditStudentForm" class="btn btn-primary">Update
                                Student</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            function fetchStudents() {
                $.ajax({
                    url: '{{ route('students.index') }}', // The route defined in Laravel
                    type: 'GET',
                    success: function(response) {
                        // Clear the current table body
                        $('#studentsTableBody').empty();

                        // Populate the table with fetched data
                        response.forEach(function(student) {
                            $('#studentsTableBody').append(`
                        <tr>
                            <td>${student.id}</td>
                            <td>${student.index}</td>
                            <td>${student.name}</td>
                            <td>${student.email}</td>
                            <td>${student.phone}</td>
                            <td>${student.address}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary editStudent" data-id="${student.id}">Edit</button>
                                <button class="btn btn-sm btn-outline-danger deleteStudent" data-id="${student.id}">Delete</button></td>
                        </tr>
                    `);
                        });
                    },
                    error: function(xhr) {
                        console.log('Error fetching students:', xhr.responseText);
                    }
                });
            }

            // Fetch students on page load
            fetchStudents();

            // Handle form submission for adding a student
            $('#submitStudentForm').on('click', function(e) {
                e.preventDefault();

                // Clear previous error messages
                $('#indexError').text('');
                $('#nameError').text('');
                $('#emailError').text('');
                $('#phoneError').text('');
                $('#addressError').text('');

                // Collect form data
                var formData = {
                    index: $('#index').val(),
                    name: $('#name').val(),
                    email: $('#email').val(),
                    phone: $('#phone').val(),
                    address: $('#address').val(),
                    _token: $('input[name="_token"]').val() // CSRF token
                };

                // Send AJAX request to add student
                $.ajax({
                    url: '{{ route('store') }}', // Laravel route
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#addStudentForm')[0].reset(); // Reset form fields
                            $('#addUserModal').modal('hide'); // Close the modal
                            fetchStudents();
                            StatusAlert(response.message)
                        } else {
                            alert('Error adding student!');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            if (errors.index) $('#indexError').text(errors.index[0]);
                            if (errors.name) $('#nameError').text(errors.name[0]);
                            if (errors.email) $('#emailError').text(errors.email[0]);
                            if (errors.phone) $('#phoneError').text(errors.phone[0]);
                            if (errors.address) $('#addressError').text(errors.address[0]);
                        } else {
                            alert('An error occurred. Please try again.');
                        }
                    }
                });
            });

            // Open edit modal and populate with student data
            $(document).on('click', '.editStudent', function() {
                var studentId = $(this).data('id');

                // Fetch student data by ID
                $.ajax({
                    url: `/students/${studentId}`, // Adjust to your route
                    type: 'GET',
                    success: function(student) {
                        // Populate the edit form fields
                        $('#editStudentId').val(student.id);
                        $('#editIndex').val(student.index);
                        $('#editName').val(student.name);
                        $('#editEmail').val(student.email);
                        $('#editPhone').val(student.phone);
                        $('#editAddress').val(student.address);

                        // Open the edit modal
                        $('#editStudentModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('An error occurred while fetching student data.');
                    }
                });
            });

            // Handle form submission for updating a student
            $('#submitEditStudentForm').on('click', function(e) {
                e.preventDefault();

                // Clear previous error messages
                $('#editIndexError').text('');
                $('#editNameError').text('');
                $('#editEmailError').text('');
                $('#editPhoneError').text('');
                $('#editAddressError').text('');

                // Collect form data
                var formData = {
                    index: $('#editIndex').val(),
                    name: $('#editName').val(),
                    email: $('#editEmail').val(),
                    phone: $('#editPhone').val(),
                    address: $('#editAddress').val(),
                    _token: $('input[name="_token"]').val() // CSRF token
                };

                var studentId = $('#editStudentId').val();

                // Send AJAX request to update student
                $.ajax({
                    url: `/update/${studentId}`, // Update route
                    type: 'put',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#editStudentModal').modal('hide'); // Close modal
                            fetchStudents(); // Refresh student list
                            StatusAlert(response.message);
                        } else {
                            alert('Error updating student!');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            if (errors.index) $('#editIndexError').text(errors.index[0]);
                            if (errors.name) $('#editNameError').text(errors.name[0]);
                            if (errors.email) $('#editEmailError').text(errors.email[0]);
                            if (errors.phone) $('#editPhoneError').text(errors.phone[0]);
                            if (errors.address) $('#editAddressError').text(errors.address[0]);
                        } else {
                            alert('An error occurred. Please try again.');
                        }
                    }
                });
            });


            // Handle delete button click
            $(document).on('click', '.deleteStudent', function() {
                var studentId = $(this).data('id');

                // Confirm deletion
                if (confirm('Are you sure you want to delete this student?')) {
                    $.ajax({
                        url: `/destroy/${studentId}`, // Adjust URL as needed
                        type: 'DELETE',
                        data: {
                            _token: $('input[name="_token"]').val() // CSRF token
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                fetchStudents(); // Refresh the student list
                                StatusAlert(response.message)
                            } else {
                                alert('Error deleting student!');
                            }
                        },
                        error: function(xhr) {
                            alert('An error occurred. Please try again.');
                        }
                    });
                }
            });

            function StatusAlert(msg) {
                $('#successMessage').text(msg).show();
                setTimeout(function() {
                    $('#successMessage').fadeOut();
                }, 3000);
            }


        });
    </script>
@endsection
