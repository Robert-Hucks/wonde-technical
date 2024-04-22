<script setup lang="ts">
import { NDrawer, NDrawerContent, NTable, NDescriptions, NDescriptionsItem } from 'naive-ui';
import { DateTime, Interval } from 'luxon';

const $props = defineProps<{
    day: DateTime,
    lessons: Array<App.Data.LessonData>,
}>();

const hours: Array<DateTime> = Interval.fromDateTimes(
    $props.day.startOf('day', { zone: 'utc'}).set({ hour: 7 }).toLocal(),
    $props.day.endOf('day', { zone: 'utc'}).set({ hour: 19 }).toLocal(),
).splitBy({ hour: 1 }).map((hour: Interval) => hour.start);

const inHour = (hour: DateTime, lesson: App.Data.LessonData): 'start'|'middle'|'end'|false => {
    if (DateTime.fromISO(lesson.start_at, { zone: 'utc' }).toLocal().startOf('hour').hasSame(hour, 'hour')) {
        return 'start';
    }

    if (DateTime.fromISO(lesson.end_at, { zone: 'utc' }).toLocal().endOf('hour').hasSame(hour, 'hour')) {
        return 'end';
    }

    const interval = Interval.fromDateTimes(
        DateTime.fromISO(lesson.start_at, { zone: 'utc' }).toLocal().startOf('hour'),
        DateTime.fromISO(lesson.end_at, { zone: 'utc' }).toLocal().endOf('hour'),
    );

    return interval.contains(hour) ? 'middle' : false;
}

const colourClasses: Array<string> = [
    'bg-blue-800',
    'bg-blue-700',
    'bg-blue-600',
    'bg-blue-500',
    'bg-blue-400',
];

const displayLessonDraw = ref(false);
const selectedLesson = ref<App.Data.LessonData>($props.lessons[0]);

const displayLesson = (lesson: App.Data.LessonData) => {
    selectedLesson.value = lesson;
    displayLessonDraw.value = true;
}
</script>

<template>
    <div class="flex flex-col border-r border-gray-300 flex-grow">
        <!-- Header -->
        <div class="aspect-square border-b p-2">
            <h1 class="text-4xl" :class="{ 'text-blue-500': day.hasSame(DateTime.now(), 'day') }">{{ day.day }}</h1>
            <span :class="{ 'text-blue-500': day.hasSame(DateTime.now(), 'day') }">{{ day.toFormat('cccc') }}</span>
        </div>

        <!-- Hour Breakdown -->
        <div class="grid flex-grow">
            <div
                v-for="hour in hours"
                class="px-1 border-b relative"
            >
                <span class="select-none">{{ hour.toFormat('HH:mm') }}</span>
                <template v-for="(lesson, index) in lessons">
                    <!-- Pipes (Start) -->
                    <div
                        v-if="inHour(hour, lesson) === 'start'"
                        class="absolute bottom-[-1px] w-[10px] rounded-t-md cursor-pointer"
                        :class="[colourClasses[index]]"
                        :style="{
                            right: `${12 * (index + 1)}px`,
                            height: `calc(100% * (1 - ${Number(DateTime.fromISO(lesson.start_at).toFormat('mm')) / 60}))`
                        }"
                        @click="displayLesson(lesson)"
                    ></div>
                    <!-- Pipes (Middle) -->
                    <div
                        v-if="inHour(hour, lesson) === 'middle'"
                        class="absolute bottom-[-1px] h-[calc(100%+2px)] w-[10px] cursor-pointer"
                        :class="[colourClasses[index]]"
                        :style="{
                            right: `${12 * (index + 1)}px`,
                        }"
                        @click="displayLesson(lesson)"
                    ></div>
                    <!-- Pipes (End) -->
                    <div
                        v-if="inHour(hour, lesson) === 'end'"
                        class="absolute top-[-1px] w-[10px] rounded-b-md cursor-pointer"
                        :class="[colourClasses[index]]"
                        :style="{
                            right: `${12 * (index + 1)}px`,
                            height: `calc(100% * ${Number(DateTime.fromISO(lesson.end_at).toFormat('mm')) / 60})`
                        }"
                        @click="displayLesson(lesson)"
                    ></div>
                </template>
            </div>
        </div>
    </div>

    <n-drawer
        v-model:show="displayLessonDraw"
        placement="right"
        :default-width="500"
        :on-update:show="(show) => !show ? displayLessonDraw = false : null"
    >
        <n-drawer-content :title="selectedLesson.class.name">
<!--            <pre>{{ selectedLesson }}</pre>-->
            <div class="flex flex-col gap-4">
                <n-descriptions bordered>
                    <n-descriptions-item label="Start time">
                        {{ DateTime.fromISO(selectedLesson.start_at, { zone: 'utc' }).toLocal().toFormat('cccc dd LLL, HH:mm') }}
                    </n-descriptions-item>
                    <n-descriptions-item label="End time">
                        {{ DateTime.fromISO(selectedLesson.end_at, { zone: 'utc' }).toLocal().toFormat('cccc dd LLL, HH:mm') }}
                    </n-descriptions-item>
                </n-descriptions>
                <!-- Employees -->
                <n-table :single-line="false">
                    <colgroup>
                        <col style="width: 45px;">
                        <col>
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Staff</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(employee, index) in selectedLesson.employees">
                            <td class="text-center"><span class="whitespace-nowrap">{{ index + 1 }}</span></td>
                            <td><span class="whitespace-nowrap">{{ `${employee.title} ${employee.forename} ${employee.surname}` }}</span></td>
                        </tr>
                    </tbody>
                </n-table>

                <!-- Students -->
                <n-table :single-line="false">
                    <colgroup>
                        <col style="width: 45px;">
                        <col>
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(student, index) in selectedLesson.students">
                            <td class="text-center"><span class="whitespace-nowrap">{{ index + 1 }}</span></td>
                            <td><span class="whitespace-nowrap">{{ `${student.forename} ${student.surname}` }}</span></td>
                        </tr>
                    </tbody>
                </n-table>
            </div>
        </n-drawer-content>
    </n-drawer>
</template>
