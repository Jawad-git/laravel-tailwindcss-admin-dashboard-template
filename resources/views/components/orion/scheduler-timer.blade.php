@props([
'weekdays' => [],
'activeDays' => [],
'startTimes' => [],
'endTimes' => []
])

@foreach ($weekdays as $weekday)

<div class="row ">
    <div class=" col-md-2">
        <div class="mt-[2.25rem] flex flex-col justify-content-center align-center">
            <label class="self-center" for="{{$weekday['name'] . 'switch'}}">{{ $weekday['name'] }}</label>
            <div class="form-check form-switch h-100 w-100 d-flex justify-content-center ">

                <input class="form-check-input self-center"
                    id="{{$weekday['name'] . 'switch'}}"
                    {{ $activeDays[$weekday['name']] ? 'checked' : '' }}
                    type="checkbox"
                    role="switch"
                    wire:click="toggleAvailability('{{ $weekday['name'] }}')" />
            </div>
        </div>
    </div>

    <div class="col-md-5 ">
        <div class="form-group mt-4">
            <x-orion.form-label inputkey="startTimes.{{  $weekday['name'] }}">{{ __("start time" ) }}</x-orion.form-label>
            <x-orion.form-input disabled="{{ $activeDays[$weekday['name']] ? false : true }}" type="time" inputkey="startTimes.{{  $weekday['name'] }}"></x-orion.form-input>
            <x-orion.form-error inputkey="startTimes.{{  $weekday['name'] }}" />
        </div>
    </div>

    <div class="col-md-5 ">
        <div class="form-group mt-4">
            <x-orion.form-label inputkey="endTimes.{{  $weekday['name'] }}">{{ __("end time" ) }}</x-orion.form-label>
            <x-orion.form-input disabled="{{ $activeDays[$weekday['name']] ? false : true }}" type="time" inputkey="endTimes.{{  $weekday['name'] }}"></x-orion.form-input>
            <x-orion.form-error inputkey="endTimes.{{  $weekday['name'] }}" />
        </div>
    </div>
</div>
@endforeach