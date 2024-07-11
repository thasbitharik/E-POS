@push('expence', 'active')
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="fas fa-dollar-sign"></i>Account</li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Expence</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 {{$propertyId == 1 ? " col-md-3" : "col-md-4" }}">
            <div class="form-group">
                <select class="custom-form-input2 form-control text-center" wire:model="select_expence_type">
                    <option value="0">-- Filter by Expence Type --</option>
                    @foreach ($filter_expence_types as $filter)
                    <option value="{{ $filter->id }}">
                        {{ $filter->expence_type }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        @if ($propertyId == 1)
        <div class="col-12 {{$propertyId == 1 ? " col-md-3" : "col-md-4" }}">
            <div class="form-group">
                <select class="custom-form-input2 form-control text-center" wire:model="select_property">
                    <option value="0">-- Filter by Property --</option>
                    @foreach ($filter_properties as $p_filter)
                    <option value="{{ $p_filter->id }}">
                        {{ $p_filter->property_name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif

        <div class="col-12 {{$propertyId == 1 ? " col-md-6" : "col-md-8" }}">
            <div class="form-group">
                <div class="input-group">
                    <input type="search" class="form-control custom-form-input2" wire:model="searchKey"
                        placeholder="Search Here..." aria-label="" autofocus>
                    <div class="input-group-append">
                        <button class="btn btn-search">Search</button>
                    </div>

                    @if (in_array('Save', $page_action))
                    <button id="formOpen" wire:click="openModel" class="btn btn-tpos-primary ml-1"><i class="fa fa-plus"
                            aria-hidden="true"></i> Create-New
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
                    <h4 class="table-title">Expence</h4>
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
                                        @if ($propertyId == 1)
                                        <th class="text-d-blue">Property</th>
                                        @endif
                                        <th class="text-d-blue">Expence Type</th>
                                        <th class="text-d-blue">Expence</th>
                                        <th class="text-d-blue text-right">Amount</th>
                                        <th class="text-d-blue text-center">Expence Date</th>
                                        <th class="text-d-blue text-center">Actions</th>
                                    </tr>
                                </thead>
                                @php($x = 1)
                                @foreach ($list_data as $row)
                                <tr>
                                    <td>{{ $x }}.</td>
                                    @if ($propertyId == 1)
                                    <td>{{ $row->property_name }}</td>
                                    @endif
                                    <td>{{ $row->expence_type }}</td>
                                    <td>{{ $row->title }}</td>
                                    <td class="text-right">{{ number_format($row->amount, 2) }}</td>
                                    <td class="text-center">{{ $row->expence_date }}</td>

                                    <td class="text-center whitespace-nowrap">
                                        @if (in_array('Delete', $page_action))
                                        <a href="#" class="text-danger mr-2"
                                            wire:click="deleteOpenModel({{ $row->id }})">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                        @endif

                                        @if (in_array('Update', $page_action))
                                        <a href="#" class="text-info mr-2" wire:click="updateRecord({{ $row->id }})">
                                            <i class="fa fa-pen" aria-hidden="true"></i>
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
                                    <td colspan="{{$propertyId == 1 ? " 7" : "6" }}" class="text-center text-secondary">
                                        No records found...!
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="records-count-area" style="border-radius: 6px; margin-top:5px">
                        <div>
                            <small class="total-records">Total No.of Records :&nbsp;{{ $data_count }}</small>
                        </div>
                        @if ($select_expence_type || $select_property)
                        <div class="mt-1">
                            <small class="total-records">Records Count for the Selection:&nbsp;
                                {{ $filter_data_count }}</small>
                        </div>
                        @endif
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
                        {{-- Property --}}
                        @if ($propertyId == 1)
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label class="text-blue">Property <font color="red">*</font></label>
                                <select class="form-control custom-form-input1" wire:model="new_property">
                                    <option value="">-- Select Here -- </option>
                                    @foreach ($property_list as $property)
                                    @if ($new_property == $property->id)
                                    <option value="{{ $property->id }}" selected>
                                        {{ $property->property_name }}
                                    </option>
                                    @else
                                    <option value="{{ $property->id }}">{{ $property->property_name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @if ($showError)
                                @error('new_property')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Expence Type --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Select Expence Type <font color="red">*</font></label>
                                <select class="form-control custom-form-input1" wire:model="new_expence_type">
                                    <option value="">-- Select Here-- </option>
                                    @foreach ($expence_types as $e_types)
                                    @if ($new_expence_type == $e_types->id)
                                    <option value="{{ $e_types->id }}" selected>
                                        {{ $e_types->expence_type }}
                                    </option>
                                    @else
                                    <option value="{{ $e_types->id }}">{{ $e_types->expence_type }}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                                @if ($showError)
                                @error('new_expence_type')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        @if($new_expence_type == 31)
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Select Bank <font color="red">*</font></label>
                                <select class="form-control custom-form-input1" wire:model="new_bank_id">
                                    <option value="">-- Select Here -- </option>
                                    @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}" selected>
                                        {{ $bank->bank }} - {{ $bank->account_number }}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($showError)
                                @error('new_bank_id')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($new_expence_type == 32)
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Filter By Company</label>
                                <select class="form-control custom-form-input1" wire:model="filter_company_id">
                                    <option value="">-- Select Here -- </option>
                                    @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">
                                        {{ $company->company_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Select Dealer <font color="red">*</font></label>
                                <select class="form-control custom-form-input1" wire:model="new_dealer_id">
                                    <option value="">-- Select Here -- </option>
                                    @foreach ($dealers as $dealer)
                                    <option value="{{ $dealer->id }}">
                                        {{ $dealer->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($showError)
                                @error('new_dealer_id')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Select Staff Type if expense was salary --}}
                        @if($new_expence_type == 25)
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Select Staff Type <font color="red">*</font></label>
                                <select class="form-control custom-form-input1" wire:model="new_staff_type">
                                    <option value="">-- Select Here -- </option>
                                    @foreach ($userTypes as $u_types)
                                    <option value="{{ $u_types->id }}">
                                        {{ $u_types->user_type }}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($showError)
                                @error('new_staff_type')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Staff --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Select Staff <font color="red">*</font></label>
                                <select class="form-control custom-form-input1" wire:model="new_staff">
                                    <option value="">-- Select Here -- </option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($showError)
                                @error('new_staff')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>


                        {{-- Salary month --}}
                        <div class="col-12 col-md-6 col-lg-6 mx-auto">
                            <div class="form-group">
                                <label class="text-blue">Salary Month <font color="red">*</font></label>
                                <select class="form-control custom-form-input1" wire:model="new_salary_month">
                                    <option value="">-- Select Here -- </option>
                                    @foreach ($months as $month)
                                    <option value="{{ $month }}">{{ $month }}</option>
                                    @endforeach
                                </select>
                                @if ($showError)
                                @error('new_salary_month')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Expence Title --}}
                        <div class="col-12 {{$propertyId == 1 ? " col-md-12 col-lg-12" : "col-md-6 col-lg-6" }}">
                            <div class="form-group">
                                <label class="text-blue">Expence Title <font color="red">*</font></label>
                                <input type="text" class="form-control custom-form-input1" placeholder="Expence Title"
                                    wire:model="new_expence">
                                @if ($showError)
                                @error('new_expence')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Amount --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Amount <font color="red">*</font></label>
                                <input type="number" class="form-control custom-form-input1" placeholder="Amount"
                                    wire:model="new_amount">
                                @if ($showError)
                                @error('new_amount')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Date --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Date</label>
                                <input type="date" class="form-control custom-form-input1" wire:model="new_date">
                                @if ($showError)
                                @error('new_date')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label class="text-blue">Description</label>
                                <textarea class="form-control custom-form-input1" placeholder="Description"
                                    wire:model="new_description">
                                    </textarea>
                                @if ($showError)
                                @error('new_description')
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
                    <h5 class="modal-title text-light-green" id="formModal">Expence Information</h5>
                    <a href="##" type="button" class="close custom-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row gutters-sm">
                        @if (sizeOf($view) != 0)
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="card card-tpos-secondary2 mb-1">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 view-title">Expence Type :</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            @if (!empty($view[0]->expence_type))
                                            <span class="text-blue font-weight-bold">
                                                {{ $view[0]->expence_type }}
                                            </span>
                                            @else
                                            <span>---------------</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 view-title">Expence :</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            @if (!empty($view[0]->title))
                                            <span class="text-blue font-weight-bold">
                                                {{ $view[0]->title }}
                                            </span>
                                            @else
                                            <span>---------------</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>

                                    @if ($view[0]->expence_type_id == 31)
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 view-title">Bank :</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            @if (!empty($view[0]->bank))
                                            <span class="text-blue font-weight-bold">
                                                {{ $view[0]->bank }} - {{ $view[0]->account_number }}
                                            </span>
                                            @else
                                            <span>---------------</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    @endif

                                    @if ($view[0]->expence_type_id == 32)
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 view-title">Dealer :</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            @if (!empty($view[0]->dealer_name))
                                            <span class="text-blue font-weight-bold">
                                                {{ $view[0]->dealer_name }}
                                                <br>
                                                <span class="text-d-blue">(Company - {{ $view[0]->company_name
                                                    }})</span>
                                            </span>
                                            @else
                                            <span>---------------</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    @endif

                                    @if ($view[0]->expence_type_id == 25)
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 view-title">Staff Type :</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            @if (!empty($view[0]->staff_type))
                                            <span class="text-blue font-weight-bold">
                                                {{ $view[0]->staff_type }}
                                            </span>
                                            @else
                                            <span>---------------</span>
                                            @endif
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 view-title">Staff :</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            @if (!empty($view[0]->staff_name))
                                            <span class="text-blue font-weight-bold">
                                                {{ $view[0]->staff_name }}
                                            </span>
                                            @else
                                            <span>---------------</span>
                                            @endif
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 view-title">Salary Month :</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            @if (!empty($view[0]->salary_month))
                                            <span class="text-blue font-weight-bold">
                                                {{ $view[0]->salary_month }}
                                            </span>
                                            @else
                                            <span>---------------</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    @endif

                                    <div class="row">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 view-title">Amount :</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            @if (!empty($view[0]->amount))
                                            <span class="text-blue font-weight-bold">
                                                {{ number_format($view[0]->amount, 2) }}
                                            </span>
                                            @else
                                            <span>---------------</span>
                                            @endif
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 view-title">Date :</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            @if (!empty($view[0]->expence_date))
                                            <span class="text-blue font-weight-bold">
                                                {{ $view[0]->expence_date }}
                                            </span>
                                            @else
                                            <span>---------------</span>
                                            @endif
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 view-title">Description :</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            @if (!empty($view[0]->description))
                                            <span class="text-blue font-weight-bold">
                                                {{ $view[0]->description }}
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