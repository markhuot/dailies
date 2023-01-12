@dump($errors)

<form action="{{ route('session.store') }}" method="post">
    {{ csrf_field() }}
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
