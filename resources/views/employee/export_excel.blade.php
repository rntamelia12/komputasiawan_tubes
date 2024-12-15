<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Birth Date</th>
            <th>Position</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($employees as $index => $employee)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $employee->firstname }}</td>
                <td>{{ $employee->lastname }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->birth_date }}</td>
                <td>{{ $employee->position->name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
