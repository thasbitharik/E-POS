@push('items', 'active')
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="fas fa-plus-circle"></i>Create New</li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Item(s)</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 col-md-8 offset-md-4">
            <div class="form-group">
                <div class="input-group">
                    <input type="search" class="form-control custom-form-input2" wire:model="searchKey"
                        wire:keyup="fetchData" placeholder="Search by item name" aria-label="" autofocus>
                    <div class="input-group-append">
                        <button class="btn btn-search" wire:click="fetchData">Search</button>
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

    <div class="data-filter-area">
        <div class="data-filter-head">
            <h6>Filters</h6>
            <button class="btn btn-sm clear-btn only-border" title="Clear filter" wire:click="clearFilter">
                &times;
                Clear
            </button>
        </div>
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="form-group mb-3 mb-md-0">
                    <label for="select_category" class="filter-form-label">Filter by Category :</label>
                    <select class="custom-form-input1 form-control text-center" id="select_category"
                        wire:model="select_category">
                        <option value="0">-- Select Category --</option>
                        @foreach ($filter_categories as $cat_filter)
                        <option value="{{ $cat_filter->id }}">
                            {{ $cat_filter->category }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group mb-3 mb-md-0">
                    <label for="select_brand" class="filter-form-label">Filter by Brand :</label>
                    <select class="custom-form-input1 form-control text-center" id="select_brand"
                        wire:model="select_brand">
                        <option value="0">-- Select Brand --</option>
                        @foreach ($filter_brands as $br_filter)
                        <option class="text-capitalized" value="{{ $br_filter->id }}">
                            {{ $br_filter->brand }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group mb-0">
                    <label for="search_barcode" class="filter-form-label">Filter by Barcode :</label>
                    <input type="search" class="custom-form-input1 form-control text-center" id="search_barcode"
                        wire:model="search_barcode" placeholder="Enter / Scan Barcode here...">
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
                    <h4 class="table-title">Item(s)</h4>
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
                                        <th class="text-d-blue">Category</th>
                                        <th class="text-d-blue">Brand</th>
                                        <th class="text-d-blue">Measure</th>
                                        <th class="text-d-blue">Items</th>
                                        <th class="text-d-blue text-center">Action</th>
                                    </tr>
                                </thead>
                                @php($x = 1)
                                @foreach ($list_data as $row)
                                <tr>
                                    <td>{{ $x }}.</td>
                                    <td class="text-capitalized">{{ $row->category_name }}</td>
                                    <td class="text-capitalized">{{ $row->brand_name }}</td>
                                    <td>{{ $row->measure.$row->measurement_name }}</td>
                                    <td class="text-capitalized">{{ $row->item_name }}</td>

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
                        <div>
                            <small class="total-records">Total No.of Records :&nbsp;{{ $data_count }}</small>
                        </div>
                        @if ($select_brand || $select_category)
                        <div class="mt-1">
                            <small class="total-records">Records Count for the Selection:&nbsp;{{
                                $filter_data_count
                                }}</small>
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

                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-6">
                            {{-- Category --}}
                            <div class="form-group">
                                <label class="text-blue">Select Category <font color="red">*</font></label>
                                <select class="form-control custom-form-input1" wire:model="new_category"
                                    wire:keydown.enter.prevent="saveData">
                                    <option value="">-- Select Category-- </option>
                                    @foreach ($categories as $category)
                                    @if ($new_category == $category->id)
                                    <option class="text-capitalized" value="{{ $category->id }}" selected>{{
                                        $category->category }}
                                    </option>
                                    @else
                                    <option class="text-capitalized" value="{{ $category->id }}">{{ $category->category
                                        }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @if ($showError)
                                @error('new_category')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Brand --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Select Brand <font color="red">*</font></label>
                                <select class="form-control custom-form-input1" wire:model="new_brand"
                                    wire:keydown.enter.prevent="saveData">
                                    <option value="">-- Select Brand-- </option>
                                    @foreach ($brands as $brand)
                                    @if ($new_brand == $brand->id)
                                    <option class="text-capitalized" value="{{ $brand->id }}" selected>{{ $brand->brand
                                        }}
                                    </option>
                                    @else
                                    <option class="text-capitalized" value="{{ $brand->id }}">{{ $brand->brand }}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                                @if ($showError)
                                @error('new_brand')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Measurement --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Select Measurements <font color="red">*</font></label>
                                <select class="form-control custom-form-input1" wire:model="new_measurement"
                                    wire:keydown.enter.prevent="saveData">
                                    <option value="">-- Select Measurement-- </option>
                                    @foreach ($measurements as $measurement)
                                    @if ($measurement->id == $new_measurement)
                                    <option value="{{ $measurement->id }}" selected>
                                        {{ $measurement->measurement }}</option>
                                    @else
                                    <option value="{{ $measurement->id }}">{{ $measurement->measurement }}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                                @if ($showError)
                                @error('new_measurement')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Unit --}}
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-blue">Measure Unit <font color="red">*</font></label>
                                <input type="number" class="form-control custom-form-input1" placeholder="Measure Unit"
                                    required="" wire:model="new_measure" wire:keydown.enter.prevent="saveData">
                                @if ($showError)
                                @error('new_measure')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Item name --}}
                        <div class="col-12 col-md-12 col-lg-12 ">
                            <div class="form-group">
                                <label class="text-blue">Item Name <font color="red">*</font></label>
                                <input type="text" class="form-control custom-form-input1" placeholder="Item Name"
                                    required="" wire:model="new_item" wire:keydown.enter.prevent="saveData">
                                @if ($showError)
                                @error('new_item')
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

                    @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
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