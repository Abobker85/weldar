@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Users Management</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Add New User</button>
    <table class="table table-bordered table-striped" id="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Is Admin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->is_admin ? 'Yes' : 'No' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.resetPassword', $user->id) }}" style="display:inline-block">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Reset password for {{ $user->email }} to 12345678?')">Reset Password</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                        <option value="qc">QC</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" value="12345678" readonly>
                    <div class="form-text">Default password is <b>12345678</b></div>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="12345678" readonly>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Add User</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function() {
    // Only initialize DataTable if there is at least one data row
    if ($('.table tbody tr').not(':has(td[colspan])').length > 0) {
        $('.table').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            lengthChange: true,
            pageLength: 10,
            language: {
                search: "Filter:"
            }
        });
    }
});
</script>
@endpush
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
@endpush
@endsection
