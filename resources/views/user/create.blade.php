<form action="{{ route('user.store') }}" method="post">
    {{ csrf_field() }}
    <label>
        <p>name</p>
        <input type="text" name="user[name]">
        {{ $errors->first('user.name') }}
    </label>
    <label>
        <p>email</p>
        <input type="text" name="user[email]">
        {{ $errors->first('user.email') }}
    </label>
    <label>
        <p>password</p>
        <input type="password" name="user[password]">
        {{ $errors->first('user.password') }}
    </label>
    <button type="submit">Submit</button>
</form>
