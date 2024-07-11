@push('dealer', 'active')
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="fas fa-truck"></i>Purchase</li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Dealer</li>
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
                    <h4 class="table-title">Dealers</h4>
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
                                        <th class="text-d-blue">Company</th>
                                        <th class="text-d-blue">Name</th>
                                        <th class="text-d-blue">Contact</th>
                                        <th class="text-d-blue">Email</th>
                                        <th class="text-d-blue text-center">Action</th>
                                    </tr>
                                </thead>
                                @php($x = 1)
                                @foreach ($list_data as $row)
                                <tr>
                                    <td>{{ $x }}.</td>
                                    <td class="text-capitalized">{{ $row->company_name }}</td>
                                    <td class="text-capitalized">{{ $row->name }}</td>
                                    <td>{{ $row->tp }}</td>

                                    @if ($row->email)
                                    <td>{{ $row->email }}</td>
                                    @else
                                    <td class="text-secondary">No Email..!</td>
                                    @endif

                                    <td class="text-center whitespace-nowrap">
                                        @if (in_array('Delete', $page_action))
                                        <a href="#" class="text-danger mr-1"
                                            wire:click="deleteOpenModel({{ $row->id }})"><i class="fa fa-trash"
                                                aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if (in_array('Update', $page_action))
                                        <a href="#" class="text-info" wire:click="updateRecord({{ $row->id }})"><i
                                                class="fa fa-pen" aria-hidden="true"></i>
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
                    <div class="records-count-area" style="border-radius: 6px; margin-top:5px">
                        <small class="total-records">Total No.of Records :&nbsp;{{ $data_count }}</small>
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
                        {{-- Company --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Select Company <font color="red">*</font></label>
                                <select class="form-control custom-form-input1" wire:model="new_company"
                                    wire:keydown.enter.prevent="saveData">
                                    <option value="">-- Select Company-- </option>
                                    @foreach ($companies as $company)
                                    @if ($new_company == $company->id)
                                    <option class="text-capitalized" value="{{ $company->id }}" selected>
                                        {{ $company->company_name }}
                                    </option>
                                    @else
                                    <option class="text-capitalized" value="{{ $company->id }}">{{
                                        $company->company_name }}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                                @if ($showError)
                                @error('new_company')
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
                                    wire:model="new_name" wire:keydown.enter.prevent="saveData">
                                @if ($showError)
                                @error('new_name')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Contact --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Contact <font color="red">*</font></label>
                                <input type="text" class="form-control custom-form-input1" placeholder="Contact"
                                    wire:model="new_contact" wire:keydown.enter.prevent="saveData">
                                @if ($showError)
                                @error('new_contact')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Email</label>
                                <input type="email" class="form-control custom-form-input1" placeholder="Email"
                                    wire:model="new_email" wire:keydown.enter.prevent="saveData">
                                @if ($showError)
                                @error('new_email')
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
    </script>
</div>