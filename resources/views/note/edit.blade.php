<form action="{{ route('note.update', $task) }}" method="post">
    {{ csrf_field() }}
    {{ method_field('put') }}
    <textarea name="note[contents]">{{ $task->note?->contents }}</textarea>
    <button>Save</button>
</form>
