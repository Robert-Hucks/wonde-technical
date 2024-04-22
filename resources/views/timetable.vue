<script setup lang="ts">
import { DateTime, Interval } from 'luxon';
import DaySegment from '@/components/daySegment.vue';

const $props = defineProps<{
    lessons: Array<App.Data.LessonData>,
}>();

const now = DateTime.now();
const weekNumber = now.toFormat('WW');
const weekDays: Array<DateTime> = Interval.fromDateTimes(
    now.startOf('week', { zone: 'utc' }).toLocal(),
    now.endOf('week', { zone: 'utc' }).toLocal(),
).splitBy({ day: 1 }).map((day: Interval) => day.start);

const filterLessons = (date: DateTime): Array<App.Data.LessonData> => {
    return $props.lessons.filter((lesson: App.Data.LessonData) => {
        return DateTime.fromISO(lesson.start_at, { zone: 'utc' }).toLocal().hasSame(date, 'day');
    });
};

useHead({
    title: `Week ${weekNumber} | Timetable`
});
</script>

<template layout="default">
    <div class="grid grid-cols-7 h-full">
        <DaySegment v-for="date in weekDays" :day="date" :lessons="filterLessons(date)" />
    </div>
</template>
