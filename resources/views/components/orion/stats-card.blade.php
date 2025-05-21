@props(['value' => 1000, 'field' => 'number of users'])

<div class="flex flex-col col-span-full sm:col-span-6 xl:col-span-4 bg-white dark:bg-gray-800 shadow-xs rounded-xl">
    <div class="px-3 pt-3 flex flex-col gap-3">
        <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase mb-3">{{ $field }}</div>
        <div class="flex items-start">
            <div class="text-3xl font-bold text-gray-800 dark:text-gray-100 mr-2">{{ $value }}</div>
        </div>
    </div>
</div>