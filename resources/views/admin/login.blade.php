<h2>Login Admin</h2>

@if(session('error'))
<p style="color:red">{{ session('error') }}</p>
@endif

<form method="POST" action="/admin/login">
@csrf
<input type="email" name="email" placeholder="Email" required>
<br><br>
<input type="password" name="password" placeholder="Password" required>
<br><br>
<button>Login</button>
</form>
