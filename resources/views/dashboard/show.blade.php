@php
$tasks = request()->user()->tasks()->where('date', '>=', $from)->where('date', '<', $to)->with('timers')->get()->groupBy(fn ($task) => $task->date->format('Y-m-d'));
@endphp
<x-app>
    <div class="flex">
        <table class="table-fixed">
            <thead>
                <tr class="bg-rose-600 text-white">
                    @foreach($days as $day)
                        <th class="py-4 px-2 min-w-64 align-top">
                            <p class="font-extralight font-medium space-x-2 py-1 rounded {{ $day->isSameDay(now()) ? 'bg-rose-500' : '' }}">
                                <span>{{ $day->format('l') }}</span>
                                <span class="font-thin text-rose-300">
                                    {{ $day->format('j') }}<sup class="pl-[1px] text-[0.7rem]">{{ $day->format('S') }}</sup>
                                </span>
                            </p>
                            <p class="font-extralight text-rose-300">
                                @php
                                    $duration = \Carbon\CarbonInterval::seconds($tasks->get($day->format('Y-m-d'))?->map(fn ($t) => $t->timers?->duration()->total('seconds'))->sum())->cascade();
                                @endphp
                                @if ($duration->total('seconds') > 0)
                                    <small>{{ $duration->forHumans(null, true) }}</small>
                                @endif
                            </p>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr class="divide-x divide-gray-100">
                    @foreach($days as $day)
                    <td class="align-top">
                        <ul class="divide-y divide-gray-100">
                            @foreach($tasks->get($day->format('Y-m-d')) ?? [] as $task)
                            <li>
                                <livewire:task :task="$task" />
                            </li>
                            @endforeach
                            <li class="p-4">
                                <form action="{{ route('task.store') }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="task[date]" value="{{ $day }}">
                                    <input type="text" name="task[name]" placeholder="New task..." class="font-light">
                                    <button class="hidden">Add</button>
                                </form>
                            </li>
                        </ul>
                    </td>
                    @endforeach
                </tr>
            </tbody>
        </table>

        <a class="absolute top-0 left-0 hover:bg-rose-200 rounded p-2 flex justify-center items-center" href="{{ route('settings.index') }}">
            <x-icon-gear class="w-8 h-8 text-white fill-current"/>
        </a>
    </div>
</x-app>
