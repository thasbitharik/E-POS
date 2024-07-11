@push('property', 'active')
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Property</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 col-md-8 offset-md-4">
            <div class="form-group">
                <div class="input-group">
                    <input type="search" class="form-control custom-form-input2" wire:model="searchKey"
                        wire:keyup="fetchData" placeholder="Search Here..." aria-label="" autofocus>
                    <div class="input-group-append">
                        <button class="btn btn-search" wire:click="fetchData">Search</button>
                    </div>

                    @if (in_array('Save', $page_action))
                    <button id="formOpen" wire:click="openModel" class="btn btn-tpos-primary ml-1"><i class="fa fa-plus"
                            aria-hidden="true"></i> Create New
                    </button>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @if (session()->has('del_message'))
    <div class="alert alert-danger"
        style="width: 100%; margin:0 auto 15px auto; text-align:center; padding: 10px; border-radius:10px">
        {{ session('del_message') }}
    </div>

    <script>
        $(function() {
            setTimeout(function() {
                $('.alert-danger').slideUp();
            }, 2000);
        });
    </script>
    @endif

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card card-tpos-secondary2">
                <div class="table-top-area">
                    <h4 class="table-title">Property</h4>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end">
                            <li class="page-item  {{($count>=10)?'':'disabled'}}">
                                <a class="page-link" wire:click='les' tabindex="-1">Prev</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">{{$count." - ".$count+10}}</a></li>
                            <li class="page-item {{(sizeOf($list_data)==10)?'':'disabled'}} ">
                                <a class="page-link" wire:click='add' href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="card-body p-2">
                    <div class="sub-card">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-bg mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-d-blue">No.</th>
                                        <th class="text-d-blue text-center">Logo</th>
                                        <th class="text-d-blue">Name</th>
                                        <th class="text-d-blue">Mobile Number</th>
                                        <th class="text-d-blue text-center">Stock</th>
                                        <th class="text-d-blue text-center">Actions</th>
                                    </tr>
                                </thead>
                                @php($x = 1)
                                @foreach ($list_data as $row)
                                <tr>
                                    <td>{{ $x }}.</td>
                                    <td class="text-center">
                                        @if (!empty($row->logo))
                                        <a href="{{ url('images/property/' . $row->logo) }}" target="_blank">
                                            <img src="{{ url('images/property/' . $row->logo) }}" class="profile-image"
                                                data-toggle="tooltip" alt="{{ $row->property_name }}"
                                                title="Click to view full size image." />
                                        </a>
                                        @else
                                        <img src="{{ asset('assets/img/image-not-found.jpg') }}" class="profile-image"
                                            alt="" title="Image not found!">
                                        @endif
                                    </td>
                                    <td>{{ $row->property_name }}</td>
                                    <td>
                                        <a href="tel:{{ $row->tp }}" class="text-d-blue">
                                            {{ $row->tp }}
                                        </a>
                                    </td>

                                    <td class="text-center">
                                        <a href="/stock-by-property/{{$row->id}}" class="btn btn-info text-white">
                                            <i class="fa fa-share" aria-hidden="true"></i>
                                        </a>
                                    </td>

                                    <td class="text-center whitespace-nowrap">
                                        {{-- @if (in_array('Delete', $page_action))
                                        <a href="#" class="text-danger mr-2"
                                            wire:click="deleteOpenModel({{ $row->id }})"><i class="fa fa-trash"
                                                aria-hidden="true"></i>
                                        </a>
                                        @endif --}}

                                        @if (in_array('Update', $page_action))
                                        <a href="#" class="text-info mr-2" wire:click="updateRecord({{ $row->id }})"><i
                                                class="fa fa-pen" aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if (in_array('View', $page_action))
                                        <a href="##" class="text-cyan" wire:click="openViewModel({{ $row->id }})">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @php($x++)
                                @endforeach
                                @if(count($list_data) == 0)
                                <tr>
                                    <td colspan="6" class="text-center text-secondary">
                                        No records found...!
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Insert model here --}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg" id="insert-model" tabindex="-1" role="dialog"
        aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content card-tpos-primary2">
                <div class="modal-header">
                    <h5 class="modal-title text-light-green" id="formModal">
                        {{ $key != 0 ? 'Update Data' : 'Create Data' }}
                    </h5>
                    <a href="##" type="button" class="close custom-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row ">
                        {{-- Logo --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Logo</label>
                                <input type="file" accept=".png, .jpg, .jpeg" class="form-control custom-form-input1"
                                    wire:model="new_logo">
                                @if ($showError)
                                @error('new_logo')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Name --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Name <font color="red">*</font></label>
                                <input type="text" class="form-control custom-form-input1" placeholder="Name"
                                    wire:model="new_name">
                                @if ($showError)
                                @error('new_name')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Mobile number --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Mobile Number <font color="red">*</font></label>
                                <input type="text" class="form-control custom-form-input1" placeholder="Mobile Number"
                                    wire:model="new_mobile_no">
                                @if ($showError)
                                @error('new_mobile_no')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Email</label>
                                <input type="text" class="form-control custom-form-input1" placeholder="Email"
                                    wire:model="new_email">
                                @if ($showError)
                                @error('new_email')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label class="text-blue">Address</label>
                                <textarea class="form-control custom-form-input1" placeholder="Address"
                                    wire:model="new_address">
                                    </textarea>
                                @if ($showError)
                                @error('new_address')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Landline number --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Landline Number</label>
                                <input type="text" class="form-control custom-form-input1" placeholder="Landline Number"
                                    wire:model="new_landline_no">
                                @if ($showError)
                                @error('new_landline_no')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Website --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Website</label>
                                <input type="text" class="form-control custom-form-input1" placeholder="Website"
                                    wire:model="new_website">
                                @if ($showError)
                                @error('new_website')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Logo url --}}
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label class="text-blue">Logo URL</label>
                                <input type="text" class="form-control custom-form-input1" placeholder="Logo URL"
                                    wire:model="logo_url">
                                @if ($showError)
                                @error('logo_url')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                    </div>

                    @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                    @endif

                    <div class="text-right">
                        <button type="button" wire:click="closeModel" class="btn btn-danger m-t-15 waves-effect">Close
                        </button>
                        <button type="button" wire:click="saveData"
                            class="btn btn-success m-t-15 waves-effect">Save</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- model end --}}

    {{-- delete model here --}}
    <div wire:ignore.self class="modal fade" id="delete-model" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content card-tpos-warning2">
                <div class="modal-header delete-model-header">
                    <h5 class="modal-title text-danger" id="formModal">Warning!</h5>
                    <a href="##" type="button" class="close custom-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body delete-model-body">
                    <p class="text-center">
                        If you want to remove this data, <b>you can't undone</b>, It will be affected that relevent
                        records!
                    </p>

                    <div class="text-right">
                        <button type="button" wire:click="deleteCloseModel"
                            class="btn btn-success m-t-15 waves-effect">No </button>
                        <button type="button" wire:click="deleteRecord"
                            class="btn btn-danger m-t-15 waves-effect">Yes</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- model end --}}

    {{-- view model here --}}
    <div wire:ignore.self class="modal fade bd-example-modal-lg" id="view-model" tabindex="-1" role="dialog"
        aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content card-tpos-primary2">
                <div class="modal-header">
                    <h5 class="modal-title text-light-green" id="formModal">Property Information</h5>
                    <a href="##" type="button" class="close custom-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row gutters-sm">
                        @if (sizeOf($view) != 0)
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="card card-tpos-secondary2">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        @if ($view[0]->logo != '')
                                        <a href="{{ url('images/property/' . $view[0]->logo) }}" target="_blank">
                                            <img src="{{ url('images/property/' . $view[0]->logo) }}"
                                                class="profile-image2" data-toggle="tooltip"
                                                alt="{{ $view[0]->property_name }}"
                                                title="Click to view full size image." />

                                        </a>
                                        @else
                                        <img src="{{ asset('assets/img/image-not-found.jpg') }}" class="profile-image2"
                                            title="Image not found!" alt="">
                                        @endif
                                        <div class="mt-3">
                                            <h4 class="text-blue name-bg">{{ $view[0]->property_name }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-12 col-sm-12">
                            <div class="card card-tpos-secondary2">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 view-title">Mobile :</h6>
                                        </div>
                                        <div class="col-sm-8">
                                            @if (!empty($view[0]->tp))
                                            <a class="text-blue font-weight-bold" href="tel:{{ $view[0]->tp }}">
                                                {{ $view[0]->tp }}
                                            </a>
                                            @else
                                            <span>---------------</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 view-title">E-mail :</h6>
                                        </div>
                                        <div class="col-sm-8">
                                            @if (!empty($view[0]->email))
                                            <a class="text-blue font-weight-bold" href="mailto:{{ $view[0]->email }}">
                                                {{ $view[0]->email }}
                                            </a>
                                            @else
                                            <span>---------------</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 view-title">Landline :</h6>
                                        </div>
                                        <div class="col-sm-8">
                                            @if (!empty($view[0]->landline))
                                            <a class="text-blue font-weight-bold" href="tel:{{ $view[0]->landline }}">
                                                {{ $view[0]->landline }}
                                            </a>
                                            @else
                                            <span>---------------</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 view-title">Website :</h6>
                                        </div>
                                        <div class="col-sm-8">
                                            @if (!empty($view[0]->web))
                                            <a class="text-blue font-weight-bold" href="{{ $view[0]->web }}"
                                                target="_blank">
                                                {{ $view[0]->web }}
                                            </a>
                                            @else
                                            <span>---------------</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 view-title">Logo URL :</h6>
                                        </div>
                                        <div class="col-sm-8">
                                            @if (!empty($view[0]->logo_url))
                                            <a class="text-blue font-weight-bold" href="{{ $view[0]->logo_url }}"
                                                target="_blank">
                                                {{ $view[0]->logo_url }}
                                            </a>
                                            @else
                                            <span>---------------</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 view-title">Address :</h6>
                                        </div>
                                        <div class="col-sm-8">
                                            @if (!empty($view[0]->address))
                                            <span class="text-blue font-weight-bold">
                                                {{ $view[0]->address }}
                                            </span>
                                            @else
                                            <span>---------------</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <hr>
                    <div class="text-center">
                        <button type="button" style="width: 30%" wire:click="viewCloseModel"
                            class="btn btn-danger waves-effect">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- model end --}}

    <script>
        // this is for insert
        window.addEventListener('insert-show-form', event => {
            $('#insert-model').modal('show');
        });
        window.addEventListener('insert-hide-form', event => {
            $('#insert-model').modal('hide');
        });

        // this is for delete
        window.addEventListener('delete-show-form', event => {
            $('#delete-model').modal('show');
        });

        window.addEventListener('delete-hide-form', event => {
            $('#delete-model').modal('hide');
        });

        // this is for view
        window.addEventListener('view-show-form', event => {
            $('#view-model').modal('show');
        });
        window.addEventListener('view-hide-form', event => {
            $('#view-model').modal('hide');
        });
    </script>
</div>