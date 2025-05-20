<div class="container-fluid">
    <div class="main-content" x-data="{ loading: true }" x-init="() => { loading = false; }">
        <div class="alert alert-secondary" role="alert">
            <strong class="text-white"> {{ __('messages.Manage Admins') }}</strong>
        </div>

        @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible text-white mx-4">
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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        @can('admin-create')
                        <a class="btn bg-gradient-dark float-start" href="{{ route('add-admin') }}">
                            <i class="material-icons text-sm">add</i> {{ __('messages.Add New Admin') }}
                        </a>
                        @endcan
                    </div>

                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            ID</th>

                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{ __('messages.Name') }}
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{ __('messages.Email') }}
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{ __('messages.Roles') }}
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{ __('messages.Status') }}
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{ __('messages.Creation Date') }}
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{ __('messages.Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins as $admin)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <span
                                                class="text-secondary text-xs font-weight-bold">{{ $admin->id }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span
                                                class="text-secondary text-xs font-weight-bold">{{ $admin->name }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span
                                                class="text-secondary text-xs font-weight-bold">{{ $admin->email }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                @if (!empty($admin->getRoleNames()))
                                                {{ $admin->getRoleNames()->implode(', ') }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch h-100 w-100 d-flex justify-content-center">
                                                <input class="form-check-input self-center"
                                                    wire:model.live="neverExpired.{{ $admin->id }}"
                                                    type="checkbox" role="switch">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="text-secondary text-xs font-weight-bold">{{ $admin->created_at }}</span>
                                        </td>
                                        <td class="text-center">

                                            @can('admin-edit')
                                            <a rel="tooltip" class="btn btn-success btn-link"
                                                href="{{ route('edit-admin', $admin->id) }}" data-original-title=""
                                                title="">
                                                <i class="material-icons">edit</i>
                                                <div class="ripple-container"></div>
                                            </a>
                                            @endcan
                                            @can('admin-delete')
                                            <button type="button" class="btn btn-danger btn-link"
                                                data-original-title="" title=""
                                                onclick="confirmDelete({{ $admin->id }})">
                                                <i class="material-icons">close</i>
                                                <div class="ripple-container"></div>
                                            </button>
                                            @endcan
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7">
                                            {{ $admins->links() }}
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