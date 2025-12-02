<form action="{{ route('login.process') }}" method="POST">
    @csrf

    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">

    <button type="submit">Login</button>

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif
</form>
