<h1><a href="{{ route('dashboard.show') }}">&larr;</a> Settings</h1>

<a href="{{ route('oauth.google-calendar.redirect') }}">Authorize with Google Calendar</a>

<form action="" method="post">
    {{ csrf_field() }}
    {{ method_field('put') }}
    <ul>
        @foreach (request()->user()->calendars as $calendar)
        <li>
            <label>
                <input type="checkbox" name="settings[sync_calendars][]" {{ request()->user()->isSyncingCalendar($calendar->id) ? 'checked' : '' }} value="{{ $calendar->id }}">
                {{ $calendar->name }}
            </label>
        </li>
        @endforeach
    </ul>
    <button>Save</button>
</form>
