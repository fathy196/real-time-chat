<!-- resources/views/users.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h2>Available Users</h2>
    <ul class="list-group mt-3">
        @foreach($users as $user)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $user->name }}
                <a href="{{ route('chat', $user->id) }}" class="btn btn-sm btn-primary">Chat</a>
            </li>
        @endforeach
    </ul>
</body>
</html>
