import Alpine from 'alpinejs';
import focus from '@alpinejs/focus'

import * as dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import duration from 'dayjs/plugin/duration';

window.Alpine = Alpine;
window.Alpine.plugin(focus)

window.dayjs = dayjs;
window.dayjs.extend(duration)
window.dayjs.extend(relativeTime)

// Alpine.start();
