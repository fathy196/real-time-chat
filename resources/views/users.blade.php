@foreach ($users as $user)
    <div class="user">
        <h2>{{ $user->name }}</h2>
        <p>Email: {{ $user->email }}</p>
        <p>Joined: {{ $user->created_at->format('Y-m-d') }}</p>
    </div>
    
@endforeach