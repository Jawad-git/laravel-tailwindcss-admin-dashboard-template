<div class="container-fluid py-4">
    <div class="main-content" x-data="{ loading: true }" x-init="() => { loading = false; }">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white mx-3"><strong> {{ __('messages.Add, Edit, Delete') }}
                                    {{ __('ui.Amenities') }} </strong></h6>
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
                        @can('amenity-create')
                        <x-orion.create-button href="{{ route('amenity-create') }}">
                            {{ __('messages.Add New Amenity') }}
                        </x-orion.create-button>
                        @endcan
                    </div>



                    <div class="row">
                        <div class="col-12">
                            <div class="card">

                                <div class="row">
                                    <div class="col-md-3 px-5 ">
                                        <label for="name" class="form-label">{{ __('messages.Search') }}
                                            {{ __('messages.Amenities') }}</label>
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
                                                    <x-orion.dashboard-th>{{ __('messages.Name') }}</x-orion.dashboard-th>
                                                    <x-orion.dashboard-th> {{ __('messages.Creation Date') }}</x-orion.dashboard-th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($amenities as $key => $amenity)
                                                <tr>
                                                    <td class="align-middle text-center">
                                                        <span
                                                            class="text-secondary text-xs font-weight-bold">{{ $key + 1 }}</span>
                                                    </td>

                                                    <td class="align-middle text-center">
                                                        <span
                                                            class="text-secondary text-xs font-weight-bold">{{ $amenity->translations->first()->name }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        @can('amenity-edit')
                                                        <x-orion.edit-button href="{{ route('amenity-edit', $amenity->id) }}"></x-orion.edit-button>
                                                        @endcan


                                                        @can('amenity-delete')
                                                        <x-orion.delete-button onclick="confirmDelete({{ $amenity->id }})"></x-orion.delete-button>
                                                        @endcan
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="7">
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