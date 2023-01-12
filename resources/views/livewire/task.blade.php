<div class="p-4 space-y-2 rounded has-checked:opacity-30">
    <div class="grid grid-cols-[1.5rem_auto] items-baseline">
        <div>
            <input id="id-{{ $task->id }}-check" class="peer hidden" type="checkbox" name="task[complete]" {{ $task->complete ? 'checked' : '' }} wire:change="toggleComplete" value="1">
            <label for="id-{{ $task->id }}-check" class="peer-checked:bg-rose-500 peer-unchecked:border-2 peer-unchecked:border-rose-500 text-white font-bold rounded w-4 h-4 overflow-hidden flex items-center justify-center" tabindex="0">
                <span class="{{ $task->complete ? '' : 'invisible' }}">&check;</span>
            </label>
        </div>
        <div>
            <input type="text" class="hidden" name="task[name]" value="{{ $task->name }}" {{ $task->remote_service ? 'disabled' : '' }}>
            <div x-data="{ content: @entangle('taskName'), doubled: false, editable: false }" @click.debounce.250ms="doubled ? doubled = false : $wire.toggleComplete()" @dblclick="doubled=true; editable=true">
                <div x-bind:contenteditable="editable" x-on:input.debounce="content = $event.target.innerHTML" x-on:blur="editable=false" title="Double click to edit">{{ $task->name }}</div>
            </div>
        </div>
    </div>
    <div class="flex space-x-4 ml-[1.5rem]">
        <div class="relative flex items-center gap-1 cursor-pointer select-none hover:bg-rose-100 {{ $task->timers->isRunning() ? 'bg-rose-100 text-rose-800' : 'text-gray-500' }} rounded py-1 px-2 -my-1 -mx-2"
             wire:key="{{ $task->getLivewireKey() }}"
             x-data="{ doubled: false, editing: false }"
             @click.debounce.250ms="doubled ? doubled = false : (!editing && $wire.toggleTimer())"
             @dblclick="doubled=true; editing=true; setTimeout(() => $refs.task{{ $task->id }}timeredit.focus(), 50)"
        >
            <span x-cloak x-show="editing" class="absolute">
                <input type="text"
                       class="bg-rose-50 border-2 border-rose-400 rounded z-10 shadow-lg p-2 -ml-2 w-32 text-center" value="{{ $task->timers->duration()->forHumans(null, true) }}"
                       @keyup.escape="$refs.task{{ $task->id }}timeredit.value='{{ $task->timers->duration()->forHumans(null, true) }}'; editing=false"
                       @keyup.enter="editing=false; $wire.updateTimer($refs.task{{ $task->id }}timeredit.value)"
                       @blur="editing=false; $wire.updateTimer($refs.task{{ $task->id }}timeredit.value)"
                       x-ref="task{{ $task->id }}timeredit"
                />
            </span>
            <span>
                @if ($task->timers()->where('stopped_at', null)->count())
                    <x-icon-timer-pause class="stroke-current w-5 h-5"/>
                @else
                    <x-icon-timer-play class="stroke-current w-5 h-5"/>
                @endif
            </span>
            <x-timer :task="$task"></x-timer>
        </div>
        {{-- <p><a href="{{ route('note.edit', $task) }}">{!! file_get_contents(resource_path('svg/notes.svg')) !!}</a></p> --}}
    </div>
</div>
