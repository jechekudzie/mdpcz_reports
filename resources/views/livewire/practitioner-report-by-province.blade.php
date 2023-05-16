<div>
    <div class="card-body">
        <div class="form-group row">
            <div class="col-md-6">
                <button class="btn btn-primary" wire:click="exportToExcel()">Export to Excel</button>
            </div>
        </div>
        <div class="form-group row d-flex">
            <div class="flex-row col-md-3">
                <label for="profession" class="col-form-label">Profession: </label>
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
                <label for="province" class="col-form-label">Province: {{$province}}</label>
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
                <input wire:model="search" type="text" name="search" id="search" placeholder="Search by First Name or Last Name" class="form-select form-control"/>
            </div>
        </div>
        <hr>


        <table class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Practitioner Type</th>
                <th>Province</th>
                <th>City</th>
                <th>Address</th>
                <th>Email</th>
                <th>Work Phone / Mobile</th>
                <th>Renewal Status</th>
            </tr>
            </thead>
            <tbody>
            @if ($practitioners->isNotEmpty())
                @foreach ($practitioners as $practitioner)
                    <tr>
                        <td>{{ $practitioner['firstName'] }} {{ $practitioner['lastName'] }}</td>
                        <td>{{ $practitioner['practitionerType'] ?? '' }}</td>
                        <td>{{ $practitioner['province'] ?? '' }}</td>
                        <td>{{ $practitioner['city'] ?? '' }}</td>
                        <td>{{ $practitioner['address'] ?? '' }}</td>
                        <td>{{ $practitioner['email'] }}</td>
                        <td>{{ $practitioner['workPhoneMobile'] }}</td>
                        <td>{{ $practitioner['renewalStatus'] ?? '' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6">No practitioners found.</td>
                </tr>
            @endif
            </tbody>
            <tfoot>
            <tr>
                <td colspan="6">
                    Showing {{ $startIndex }} to {{ $endIndex }} of {{ $resultsCount }} entries

                    <br/>
                    <br/>
                    {{ $renewals->links('livewire::bootstrap') }}
                </td>

            </tr>
            </tfoot>
        </table>
    </div>
</div>
