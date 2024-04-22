<script setup lang="ts">
import { NSelect } from 'naive-ui';

const $props = defineProps<{
    current_employee: App.Data.EmployeeData,
    employees: Array<App.Data.EmployeeData>,
}>();

const employeeOptions = $props.employees.map((employee: App.Data.EmployeeData) => ({
    label: `${employee.title} ${employee.forename} ${employee.surname}`,
    value: employee.id,
}));

const switchEmployee = (employee_id: string) => {
    router.get(
        route('timetable', { employee: employee_id }),
    );
};
</script>

<template>
    <div class="flex flex-col h-full">
        <nav class="p-4 flex items-center border border-b">
            <NSelect
                class="max-w-[300px]"
                placeholder="Select teacher..."
                filterable
                :value="current_employee?.id || null"
                :options="employeeOptions"
                :on-update:value="switchEmployee"
            />
        </nav>
        <main class="flex-grow">
            <slot />
        </main>
    </div>
</template>
