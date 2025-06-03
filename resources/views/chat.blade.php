<!-- resources/views/chat.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Chat with {{ $receiver->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h2>Chat with {{ $receiver->name }}</h2>

    <div class="border rounded p-3 mb-3" style="max-height: 400px; overflow-y: auto;">
        @foreach($messages as $msg)
            <div class="mb-2">
                <strong>{{ $msg->sender_id === auth()->id() ? 'You' : $receiver->name }}:</strong>
                <span>{{ $msg->message }}</span>
            </div>
        @endforeach
    </div>

<form action="{{ route('chat.send', ['receiverID' => $receiver->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $receiver->id }}">
        <div class="input-group">
            <input type="text" name="message" class="form-control" placeholder="Type a message..." required>
            <button type="submit" class="btn btn-success">Send</button>
        </div>
    </form>

    <a href="{{ route('users') }}" class="btn btn-link mt-3">← Back to users</a>
</body>
</html>
