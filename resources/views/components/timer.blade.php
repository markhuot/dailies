@pushOnce('js')
    <script type="module">
        Alpine.data('timer', ({isRunning = false, elapsed = 0, stored = 0}) => {
            return {
                elapsed,

                init()
                {
                    if (!isRunning) {
                        return;
                    }

                    const start = new Date();
                    setInterval(() => {
                        this.elapsed = stored + (new Date().getTime() - start.getTime())/1000;
                    }, 1000);
                },

                prettyPrint()
                {
                    const duration = dayjs.duration(this.elapsed, 'seconds')

                    return Object.entries({
                        'h': duration.format('H'),
                        'm': duration.format('m'),
                        's': duration.format('ss'),
                    }).filter(([k, v]) => v > 0)
                        .map(([k,v]) => `${v}${k}`)
                        .join(' ');
                },
            };
        });
    </script>
@endPushOnce

<span
    {{ $attributes->merge(['class' => 'tabular-nums text-sm']) }}
    x-data="timer({{ Js::from([
        'isRunning' => $task->timers->isRunning(),
        'elapsed' => $task->timers->duration()->total('seconds'),
        'stored' => $task->timers->duration()->total('seconds'),
    ]) }})"
    x-text="prettyPrint()">
    @if ($task->timers->duration()->total('seconds') > 0)
        {{ $task->timers->duration()->forHumans(null, true) }}
    @endif
</span>
