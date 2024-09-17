@inject('Utility', 'App\Http\Controllers\Portal\PropertyController')
<div class="table-responsive mt-3">
    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
        <thead>
        <tr>
            <th class="">#</th>
            <th class="wd-15p">Property Name</th>
            <th class="wd-15p">House No.</th>
            <th class="wd-15p">Plot No.</th>
            <th class="wd-15p">Street</th>
            <th class="wd-15p">Customer</th>
            <th class="wd-15p">Allottee</th>
        </tr>
        </thead>
        <tbody>

        @foreach($properties as $key=> $item)
            <tr class="">
                <td>{{ $key+1 }}</td>
                <td>
                    <input type="hidden" name="records[]" value="{{$item->id}}">
                    <input type="text" style="width: 150px;" value="{{ $item->property_name ?? ''}}" placeholder="Property Name" name="property_name[]" class="form-control">
                </td>
                <td>
                    <input type="text" name="house_no[]" style="width: 150px;" value="{{ $item->house_no ?? '' }}" placeholder="House No" class="form-control">
                </td>
                <td>
                    <input type="text" name="plot_no[]" style="width: 150px;" value="{{ $item->plot_no ?? '' }}" placeholder="Plot No" class="form-control">
                </td>

                <td>
                    <input type="text"  name="street[]" style="width: 150px;" value="{{ $item->amount_paid ?? '' }}" placeholder="Street" class="form-control">
                </td>
                <td>
                    <select class="form-control select2" style="width: 150px;" name="occupied_by[]"><!-- Serves as customer -->
                        <option selected>--Select customer--</option>
                        @foreach($customers as $key=> $customer)
                            <option value="{{$customer->id}}" {{ $customer->id == $item->occupied_by ? 'selected' : null  }}>{{ $customer->first_name ?? '' }} {{ $customer->last_name ?? '' }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-control select2" style="width: 150px;" name="allottee[]" id="">
                        @for($i = 1; $i <= 30; $i++)
                            <option value="{{ $i }}">{{$Utility->numToOrdinalWord($i)}} Allottee</option>
                        @endfor
                    </select>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
