<div>
    <div class="card-body">
        <form method="post">

            <div class="form-group row d-flex">
                <div class="flex-row col-md-3">
                    <label for="profession" class="col-form-label">Profession: {{$practitionerType}}</label>
                    <select wire:model="practitionerType" id="practitionerType" class="form-select form-control"
                            name="practitionerType">
                        <option value="">-- Select profession --</option>
                        @foreach($practitionerTypes as $practitionerType)
                            <option
                                value="{{$practitionerType->id}}">{{$practitionerType->name}}</option>
                        @endforeach

                    </select>
                </div>
                <div class="flex-row col-md-3">
                    <label for="province" class="col-form-label">Province:</label>
                    <select wire:model="province" id="province" class="form-select form-control" name="province">
                        <option value="">-- Select province --</option>
                        @foreach($provinces as $province)
                            <option value="{{$province->name}}">{{$province->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-row col-md-3">
                    <label for="city" class="col-form-label">City:</label>
                    <select wire:model="city" id="city" class="form-select form-control" name="city">
                        <option value="">-- Select city --</option>
                        @foreach($cities as $city)
                            <option value="{{$city->id}}">{{$city->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-row col-md-3">
                    <label for="city" class="col-form-label">Search By Name:</label>
                    <input wire:model="search" type="text" name="search" id="search" class="form-select form-control"/>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary btn-block">Get Report</button>
                </div>
            </div>
        </form>
        <hr>
        <table class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                <th>Full Name</th>
                <th>Profession</th>
                <th>Province</th>
                <th>City</th>
                <th>Business Address</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>

            @foreach($practitioners as $practitioner)
                <tr>
                    <td>{{ $practitioner->firstName }} {{ $practitioner->lastName }}</td>
                    <td>
                        @if($practitioner->lastRegistration)
                            {{ $practitioner->lastRegistration->practitionerType->name }}
                        @endif
                    </td>
                    <td>
                        @if($practitioner->address)
                            @if($practitioner->address->province)
                                {{ $practitioner->address->province }}
                            @endif
                        @endif
                    </td>
                    <td>
                        @if($practitioner->address)
                            @if($practitioner->address->city)
                                {{ $practitioner->address->city->name }}
                            @endif
                        @endif
                    </td>
                    <td>
                        @if($practitioner->address)
                            {{ $practitioner->address->addressLine1.' '.$practitioner->address->addressLine2 }}
                        @endif
                    </td>
                    <td>
                        @if($practitioner->lastRegistration && $practitioner->lastRegistration->renewals->isNotEmpty())
                            @if($activeRenewal = $practitioner->lastRegistration->renewals->where('renewalStatus', 'ACTIVE')->first())
                                {{ $activeRenewal->renewalStatus }}
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach


            </tbody>
        </table>

        {!! $practitioners->links() !!}

        <div>
            Showing {!! $practitioners->firstItem() !!} of {!! $practitioners->lastItem() !!} out
            of {!! $practitioners->total() !!}

        </div>
    </div>
</div>
