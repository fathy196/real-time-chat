<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .users-container {
            max-width: 600px;
            margin: 2rem auto;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: white;
        }
        
        .users-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .user-list {
            padding: 0;
        }
        
        .user-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #eee;
            transition: all 0.2s;
            text-decoration: none;
            color: var(--dark-color);
        }
        
        .user-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--light-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .user-info {
            flex: 1;
        }
        
        .user-name {
            font-weight: 500;
            margin-bottom: 0.2rem;
        }
        
        .user-status {
            font-size: 0.8rem;
        }
        
        .status-badge {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
        }
        
        .online {
            background-color: var(--success-color);
        }
        
        .offline {
            background-color: #adb5bd;
        }
        
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }
    </style>
    @vite(['resources/js/app.js'])
</head>
<body>
    <div class="users-container">
        <div class="users-header">
            <h2>Chat Users</h2>
        </div>
        
        <ul class="user-list">
            @forelse ($users as $user)
                <a href="{{ route('chat', $user->id) }}" class="user-item">
                    <div class="user-avatar">{{ substr($user->name, 0, 1) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-status">
                            <span class="status-badge {{ $user->isOnline() ? 'online' : 'offline' }}"></span>
                            {{ $user->isOnline() ? 'Online' : 'Offline' }}
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-muted"></i>
                </a>
            @empty
                <div class="empty-state">
                    <i class="fas fa-users fa-2x mb-3"></i>
                    <p>No other users available</p>
                </div>
            @endforelse
        </ul>
    </div>
</body>
</html>