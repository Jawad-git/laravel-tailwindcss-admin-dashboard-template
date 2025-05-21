<div class="container-fluid py-4">
    <div class="main-content" x-data="{ loading: true }" x-init="() => { loading = false; }">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white mx-3"><strong> {{ __('messages.Add, Edit, Delete') }}
                                    {{ __('ui.Rooms') }} </strong></h6>
                        </div>
                    </div>


                    @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible text-white mx-4 mt-2">
                        <p>{{ Session::get('success') }}</p>
                        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible text-white mx-4 mt-2">
                        <p>{{ Session::get('error') }}</p>
                        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    <div class=" ms-3 my-3 text-start">
                        @can('room-create')
                        <x-orion.create-button href="{{ route('room-create') }}">
                            {{ __('messages.Add New Room') }}
                        </x-orion.create-button>
                        @endcan
                    </div>



                    <div class="row">
                        <div class="col-12">
                            <div class="card">

                                <div class="row">
                                    <div class="col-md-3 px-5 ">
                                        <label for="name" class="form-label">{{ __('messages.Search') }}
                                            {{ __('messages.Rooms') }}</label>
                                        <input type="search" wire:model.live="search"
                                            class="form-control  border border-2 p-2"
                                            placeholder="{{ __('messages.Search') }}">
                                    </div>
                                </div>

                                <div class="card-body px-0 pb-2">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <x-orion.dashboard-th>#</x-orion.dashboard-th>
                                                    <x-orion.dashboard-th>{{ __('messages.Room Number') }}</x-orion.dashboard-th>
                                                    <x-orion.dashboard-th>{{ __('messages.Category') }}</x-orion.dashboard-th>
                                                    <x-orion.dashboard-th>{{ __('messages.Floor') }}</x-orion.dashboard-th>
                                                    <x-orion.dashboard-th>{{ __('messages.Available') }}</x-orion.dashboard-th>
                                                    <x-orion.dashboard-th>{{ __('messages.Bed count') }}</x-orion.dashboard-th>
                                                    <x-orion.dashboard-th>{{ __('messages.Price per night') }}</x-orion.dashboard-th>
                                                    <x-orion.dashboard-th> {{ __('messages.Creation Date') }}</x-orion.dashboard-th>
                                                    <x-orion.dashboard-th> {{ __('messages.Action') }}</x-orion.dashboard-th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($rooms as $key => $room)
                                                <tr>
                                                    <x-orion.dashboard-td>
                                                        <x-orion.dashboard-td-span>
                                                            {{ $key + 1 }}
                                                        </x-orion.dashboard-td-span>
                                                    </x-orion.dashboard-td>

                                                    <x-orion.dashboard-td>
                                                        <x-orion.dashboard-td-span>
                                                            {{-- add a link to a single room view page with comprehensive details. --}}
                                                            {{ $room->number }}
                                                        </x-orion.dashboard-td-span>
                                                    </x-orion.dashboard-td>

                                                    <x-orion.dashboard-td>
                                                        <x-orion.dashboard-td-span>
                                                            {{ $room->category->translations->first()->name }}
                                                        </x-orion.dashboard-td-span>
                                                    </x-orion.dashboard-td>

                                                    <x-orion.dashboard-td>
                                                        <x-orion.dashboard-td-span>
                                                            {{ $room->floor }}
                                                        </x-orion.dashboard-td-span>
                                                    </x-orion.dashboard-td>

                                                    <x-orion.dashboard-td>
                                                        <div class="form-check form-switch h-100 w-100 d-flex justify-content-center">
                                                            <input class="form-check-input self-center "
                                                                {{ $room->is_available ? 'checked' : '' }}
                                                                type="checkbox"
                                                                role="switch"
                                                                wire:click="toggleAvailability({{ $room->id }})">
                                                        </div>
                                                    </x-orion.dashboard-td>

                                                    <x-orion.dashboard-td>
                                                        <x-orion.dashboard-td-span>
                                                            {{ $room->bed_count }}
                                                        </x-orion.dashboard-td-span>
                                                    </x-orion.dashboard-td>

                                                    <x-orion.dashboard-td>
                                                        <x-orion.dashboard-td-span>
                                                            {{ $room->price_per_night }}
                                                        </x-orion.dashboard-td-span>
                                                    </x-orion.dashboard-td>

                                                    <x-orion.dashboard-td>
                                                        <x-orion.dashboard-td-span>
                                                            {{ $room->created_at->format('Y-m-d') }}
                                                        </x-orion.dashboard-td-span>
                                                    </x-orion.dashboard-td>


                                                    <td class="text-center">
                                                        @can('room-edit')
                                                        <x-orion.edit-button href="{{ route('room-edit', $room->id) }}"></x-orion.edit-button>
                                                        @endcan


                                                        @can('room-delete')
                                                        <x-orion.delete-button onclick="confirmDelete({{ $room->id }})"></x-orion.delete-button>
                                                        @endcan
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="7">
                                                        {{ $rooms->links() }}
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    {{--
        use selectize for both category and amenities in add
    --}}