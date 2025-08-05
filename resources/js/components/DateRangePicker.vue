<script setup lang="ts">
import { CalendarDate, type DateValue, isEqualMonth } from '@internationalized/date';
import { Calendar, ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { type DateRange, RangeCalendarRoot, useDateFormatter } from 'reka-ui'; // Keep reka-ui imports

import { Button, buttonVariants } from '@/components/ui/button';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

import {
    RangeCalendarCell,
    RangeCalendarCellTrigger,
    RangeCalendarGrid,
    RangeCalendarGridBody,
    RangeCalendarGridHead,
    RangeCalendarGridRow,
    RangeCalendarHeadCell,
} from '@/components/ui/range-calendar';
import { cn } from '@/lib/utils';
import { createMonth, type Grid, toDate } from 'reka-ui/date'; // Keep reka-ui/date imports
import { type Ref, ref, watch } from 'vue';

// --- Define Emits ---
const emit = defineEmits<{
    (e: 'update:dateRange', range: DateRange): void;
}>();

// --- Define Props ---
const props = defineProps<{
    dateRange?: DateRange; // Optional prop for initial date range
}>();

const now = new Date();
const year = now.getFullYear();
const month = now.getMonth() + 1;
const day = now.getDate();

const today = new CalendarDate(year, month, day);

const value = ref(
    props.dateRange || {
        end: today,
        start: today.subtract({ days: 7 }), // Default to last 7 days initially if no prop is provided
    },
) as Ref<DateRange>;

const locale = ref('en-US');
const formatter = useDateFormatter(locale.value);

const placeholder = ref(value.value.start) as Ref<DateValue>;
const secondMonthPlaceholder = ref(value.value.end) as Ref<DateValue>;

const firstMonth = ref(
    createMonth({
        dateObj: placeholder.value,
        locale: locale.value,
        fixedWeeks: true,
        weekStartsOn: 0,
    }),
) as Ref<Grid<DateValue>>;
const secondMonth = ref(
    createMonth({
        dateObj: secondMonthPlaceholder.value,
        locale: locale.value,
        fixedWeeks: true,
        weekStartsOn: 0,
    }),
) as Ref<Grid<DateValue>>;

function updateMonth(reference: 'first' | 'second', months: number) {
    if (reference === 'first') {
        placeholder.value = placeholder.value.add({ months });
    } else {
        secondMonthPlaceholder.value = secondMonthPlaceholder.value.add({
            months,
        });
    }
}

watch(placeholder, (_placeholder) => {
    firstMonth.value = createMonth({
        dateObj: _placeholder,
        weekStartsOn: 0,
        fixedWeeks: false,
        locale: locale.value,
    });
    if (isEqualMonth(secondMonthPlaceholder.value, _placeholder)) {
        secondMonthPlaceholder.value = secondMonthPlaceholder.value.add({
            months: 1,
        });
    }
});

watch(secondMonthPlaceholder, (_secondMonthPlaceholder) => {
    secondMonth.value = createMonth({
        dateObj: _secondMonthPlaceholder,
        weekStartsOn: 0,
        fixedWeeks: false,
        locale: locale.value,
    });
    if (isEqualMonth(_secondMonthPlaceholder, placeholder.value)) placeholder.value = placeholder.value.subtract({ months: 1 });
});

// --- Watch 'value' to emit changes ---
watch(
    value,
    (newValue) => {
        // Only emit if both start and end are selected
        if (newValue.start && newValue.end) {
            emit('update:dateRange', newValue);
        }
    },
    { deep: true }, // Deep watch to detect changes within the start/end CalendarDate objects
);

// --- Modified Presets ---
const items = [
    { value: 'last3days', label: 'Last 3 Days' },
    { value: 'last7days', label: 'Last 7 Days' },
    { value: 'lastweek', label: 'Last Week' },
    { value: 'tmonth', label: 'This Month' },
    { value: 'lmonth', label: 'Last Month' },
    { value: 'today', label: 'Today' }, // Add Today as a preset
];

function applyPreset(presetValue: string) {
    const today = new CalendarDate(year, month, day); // Re-instantiate today to ensure it's fresh

    let startDate: CalendarDate;
    let endDate: CalendarDate;

    switch (presetValue) {
        case 'today':
            startDate = today;
            endDate = today;
            break;
        case 'last3days':
            startDate = today.subtract({ days: 2 });
            endDate = today;
            break;
        case 'last7days':
            startDate = today.subtract({ days: 6 });
            endDate = today;
            break;
        case 'lastweek':
            startDate = today.subtract({ days: 7 });
            endDate = today;
            break;
        case 'tmonth':
            startDate = today.set({ day: 1 });
            endDate = today.add({ months: 1 }).set({ day: 1 }).subtract({ days: 1 });
            break;
        case 'lmonth':
            startDate = today.subtract({ months: 1 }).set({ day: 1 });
            endDate = today.set({ day: 1 }).subtract({ days: 1 });
            break;
        default:
            // Fallback, perhaps do nothing or set a default
            return;
    }

    value.value = {
        start: startDate,
        end: endDate,
    };

    // Update placeholders to reflect the new range in the calendar display
    placeholder.value = value.value.start;
    secondMonthPlaceholder.value = value.value.end;
}
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button variant="outline" :class="cn('w-[280px] justify-start text-left font-normal', !value && 'text-muted-foreground')">
                <Calendar class="mr-2 h-4 w-4" />
                <template v-if="value.start">
                    <template v-if="value.end">
                        {{
                            formatter.custom(toDate(value.start), {
                                dateStyle: 'medium',
                            })
                        }}
                        -
                        {{
                            formatter.custom(toDate(value.end), {
                                dateStyle: 'medium',
                            })
                        }}
                    </template>

                    <template v-else>
                        {{
                            formatter.custom(toDate(value.start), {
                                dateStyle: 'medium',
                            })
                        }}
                    </template>
                </template>
                <template v-else> Pick a date </template>
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0">
            <div class="flex items-center justify-center space-x-2 pt-4">
                <Select @update:model-value="applyPreset">
                    <SelectTrigger>
                        <SelectValue placeholder="Date Range Preset" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="item in items" :key="item.value" :value="item.value">
                            {{ item.label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>
            <RangeCalendarRoot v-slot="{ weekDays }" v-model="value" v-model:placeholder="placeholder" class="px-4 pb-3">
                <div class="mt-4 flex flex-col gap-y-4 sm:flex-row sm:gap-x-4 sm:gap-y-0">
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center justify-between">
                            <button
                                :class="cn(buttonVariants({ variant: 'outline' }), 'h-7 w-7 bg-transparent p-0 opacity-50 hover:opacity-100')"
                                @click="updateMonth('first', -1)"
                            >
                                <ChevronLeft class="h-4 w-4" />
                            </button>
                            <div :class="cn('text-sm font-medium')">
                                {{ formatter.fullMonthAndYear(toDate(firstMonth.value)) }}
                            </div>
                            <button
                                :class="cn(buttonVariants({ variant: 'outline' }), 'h-7 w-7 bg-transparent p-0 opacity-50 hover:opacity-100')"
                                @click="updateMonth('first', 1)"
                            >
                                <ChevronRight class="h-4 w-4" />
                            </button>
                        </div>
                        <RangeCalendarGrid>
                            <RangeCalendarGridHead>
                                <RangeCalendarGridRow>
                                    <RangeCalendarHeadCell v-for="day in weekDays" :key="day" class="w-full">
                                        {{ day }}
                                    </RangeCalendarHeadCell>
                                </RangeCalendarGridRow>
                            </RangeCalendarGridHead>
                            <RangeCalendarGridBody>
                                <RangeCalendarGridRow v-for="(weekDates, index) in firstMonth.rows" :key="`weekDate-${index}`" class="mt-2 w-full">
                                    <RangeCalendarCell v-for="weekDate in weekDates" :key="weekDate.toString()" :date="weekDate">
                                        <RangeCalendarCellTrigger :day="weekDate" :month="firstMonth.value" />
                                    </RangeCalendarCell>
                                </RangeCalendarGridRow>
                            </RangeCalendarGridBody>
                        </RangeCalendarGrid>
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center justify-between">
                            <button
                                :class="cn(buttonVariants({ variant: 'outline' }), 'h-7 w-7 bg-transparent p-0 opacity-50 hover:opacity-100')"
                                @click="updateMonth('second', -1)"
                            >
                                <ChevronLeft class="h-4 w-4" />
                            </button>
                            <div :class="cn('text-sm font-medium')">
                                {{ formatter.fullMonthAndYear(toDate(secondMonth.value)) }}
                            </div>

                            <button
                                :class="cn(buttonVariants({ variant: 'outline' }), 'h-7 w-7 bg-transparent p-0 opacity-50 hover:opacity-100')"
                                @click="updateMonth('second', 1)"
                            >
                                <ChevronRight class="h-4 w-4" />
                            </button>
                        </div>
                        <RangeCalendarGrid>
                            <RangeCalendarGridHead>
                                <RangeCalendarGridRow>
                                    <RangeCalendarHeadCell v-for="day in weekDays" :key="day" class="w-full">
                                        {{ day }}
                                    </RangeCalendarHeadCell>
                                </RangeCalendarGridRow>
                            </RangeCalendarGridHead>
                            <RangeCalendarGridBody>
                                <RangeCalendarGridRow v-for="(weekDates, index) in secondMonth.rows" :key="`weekDate-${index}`" class="mt-2 w-full">
                                    <RangeCalendarCell v-for="weekDate in weekDates" :key="weekDate.toString()" :date="weekDate">
                                        <RangeCalendarCellTrigger :day="weekDate" :month="secondMonth.value" />
                                    </RangeCalendarCell>
                                </RangeCalendarGridRow>
                            </RangeCalendarGridBody>
                        </RangeCalendarGrid>
                    </div>
                </div>
            </RangeCalendarRoot>
        </PopoverContent>
    </Popover>
</template>
