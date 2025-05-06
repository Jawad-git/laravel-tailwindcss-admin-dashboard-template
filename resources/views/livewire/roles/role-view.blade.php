<div>
    <div class="main-content" x-data="{ loading: true }" x-init="() => { loading = false; }">
        <div class="alert alert-secondary mx-4" role="alert">
            <span class="text-white"><strong>{{__('messages.Manage Roles')}}</strong>
        </div>
        @if (\Session::has('success'))
        <div class="alert alert-success alert-dismissible text-white mx-4">
            <p>{{ \Session::get('success') }}</p>
            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4 p-3">
                    <div class=" me-3 my-3 text-start">
                        @can('role-create')
                        <a class="btn bg-gradient-dark mb-0" href="{{ route('add-role') }}"><i
                                class="material-icons text-sm">add</i>{{__('messages.Add New Role')}}</a>

                        @endcan
                    </div>

                    <div class="card-body px-0 pt-0 pb-2">
                        <div
                            x-data="{ loading: false }"
                            x-show="loading"
                            @loading.window="loading = $event.detail.loading"
                            class="absolute z-50 bottom-20 right-20 ">
                            <i class="fa fa-spinner fa-spin loading"></i>
                        </div>
                        <table x-show="!loading" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        #
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{__('messages.Name')}}
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{__('messages.Action')}}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($role as $key => $r)
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $key + 1 }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $r->name }}</p>
                                    </td>
                                    <td class="text-center">
                                        @if($r->id != 1)
                                        @can('role-edit')
                                        <a rel="tooltip" class="btn btn-success btn-link"
                                            href="{{ route('edit-role', $r->id) }}" data-original-title=""
                                            title="">
                                            <i class="material-icons">edit</i>
                                            <div class="ripple-container"></div>
                                        </a>
                                        @endcan
                                        @can('role-delete')
                                        <button type="button" class="btn btn-danger btn-link"
                                            data-original-title="" title=""
                                            onclick="confirmDelete({{ $r->id }})">
                                            <i class="material-icons">close</i>
                                            <div class="ripple-container"></div>
                                        </button>
                                        @endcan
                                        @endif


                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6">
                                        {{ $role->links() }}
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