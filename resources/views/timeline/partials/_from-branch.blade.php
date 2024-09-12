<select name="branches[]" id="" class="select2 form-control select2-multiple" multiple="multiple">
    <option disabled selected>--Select Department(s)--</option>
    @foreach($branches as $branch)
        <option value="{{$branch->cb_id}}">{{ $branch->cb_name ?? '' }}</option>
    @endforeach
</select>
