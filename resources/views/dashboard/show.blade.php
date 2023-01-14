@php
$tasks = request()->user()->tasks()->where('date', '>=', $from)->where('date', '<', $to)->with('timers')->get()->groupBy(fn ($task) => $task->date->format('Y-m-d'));
@endphp
<x-app class="
    set-dashboard-primary-slate-900
    set-dashboard-primary-highlight-slate-800
    set-dashboard-primary-dim-slate-500
    set-dashboard-border-slate-700
    set-dashboard-dim-slate-600
    set-dashboard-highlight-slate-700
    set-dashboard-highlight-overlay-pink-600
    bg-slate-800
    text-white

{{--    set-dashboard-primary-rose-600--}}
{{--    set-dashboard-primary-highlight-rose-500--}}
{{--    set-dashboard-primary-dim-rose-300--}}
{{--    set-dashboard-border-gray-100--}}
{{--    set-dashboard-highlight-rose-100--}}
{{--    set-dashboard-highlight-overlay-rose-800--}}
{{--    set-dashboard-dim-gray-400--}}
{{--    bg-white--}}
{{--    text-black--}}
">
    <div class="flex">
        <table class="table-fixed">
            <thead>
                <tr class="bg-dashboard-primary text-white">
                    @foreach($days as $day)
                        <th class="py-4 px-2 min-w-64 align-top">
                            <p class="font-extralight font-medium space-x-2 py-1 rounded {{ $day->isSameDay(now()) ? 'bg-dashboard-primary-highlight' : '' }}">
                                <span>{{ $day->format('l') }}</span>
                                <span class="font-thin text-dashboard-primary-dim">
                                    {{ $day->format('j') }}<sup class="pl-[1px] text-[0.7rem]">{{ $day->format('S') }}</sup>
                                </span>
                            </p>
                            <p class="font-extralight text-dashboard-primary-dim">
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
                <tr class="divide-x divide-dashboard-border">
                    @foreach($days as $day)
                    <td class="align-top">
                        <ul class="divide-y divide-dashboard-border">
                            @foreach($tasks->get($day->format('Y-m-d'))?->sortBy('sort') ?? [] as $task)
                            <li>
                                <livewire:task :task="$task" />
                            </li>
                            @endforeach
                            <li class="p-4">
                                <form action="{{ route('task.store') }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="task[date]" value="{{ $day }}">
                                    <input type="text" name="task[name]" placeholder="New task..." class="font-light bg-transparent">
                                    <button class="hidden">Add</button>
                                </form>
                            </li>
                        </ul>
                    </td>
                    @endforeach
                </tr>
            </tbody>
        </table>

        <a class="absolute top-0 left-0 hover:bg-dashboard-highlight rounded p-2 flex justify-center items-center" href="{{ route('settings.index') }}">
            <x-icon-gear class="w-8 h-8 text-white fill-current"/>
        </a>
    </div>
    <div id="drag-placeholder" class="absolute h-2 w-full bg-dashboard-highlight-overlay hidden rounded-full shadow-lg"></div>
</x-app>
