<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-blue border-b-blue">
            <li class="breadcrumb-item"><a href="/dash-board"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><i class="fas fa-user-check"></i>Auth</li>
            <li class="breadcrumb-item"><a href="/user-type"><i class="fas fa-user"></i>User Type</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i>Permissions</li>
        </ol>
    </nav>

    <section class="section mt-5">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-tpos-secondary2 p-2">
                        <div class="sub-card">
                            <div style="padding-top: 10px;">
                                <h4 align="center" class="pt-2 text-d-blue">
                                    Permission - <span class="text-blue"><b> ( {{ $user_type->user_type }} ) </b></span>
                                </h4>
                            </div>
                            <div class="card-body">
                                @foreach ($access_model as $rowx)
                                <div class="permission-area">
                                    <h5 class="text-d-blue pb-2"><u>{{ $rowx->access_model }}</u></h5>
                                    <div class="row">
                                        @foreach ($access_point as $row)
                                        @if ($rowx->id == $row->access_model_id)
                                        <div class="col-2">
                                            <div class="position-relative form-group">
                                                <div class="custom-checkbox custom-control">

                                                    @if (count($permissionData)!=0)
                                                    <?php
                                                        $val = json_decode($permissionData[0]->permission);
                                                    ?>
                                                    @if (in_array($row->id, $val))

                                                    <input type="checkbox" id={{ $row->id }}
                                                    value={{ $row->id }} checked
                                                    wire:click="givePermission({{$row->id}})"
                                                    class="custom-control-input">
                                                    <label class="custom-control-label" for={{ $row->id }}>
                                                        {{ $row->value }}
                                                    </label>
                                                    @else
                                                    <input type="checkbox" wire:click="givePermission({{$row->id}})"
                                                        id={{ $row->id }}
                                                    value={{ $row->id }}
                                                    class="custom-control-input">
                                                    <label class="custom-control-label" for={{ $row->id }}>
                                                        {{ $row->value }}
                                                    </label>
                                                    @endif
                                                    @else
                                                    <input type="checkbox" wire:click="givePermission({{$row->id}})"
                                                        id={{ $row->id }}
                                                    value={{ $row->id }}
                                                    class="custom-control-input">
                                                    <label class="custom-control-label" for={{ $row->id }}>
                                                        {{ $row->value }}
                                                    </label>
                                                    @endif

                                                </div>

                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>