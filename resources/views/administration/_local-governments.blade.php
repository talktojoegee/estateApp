<select name="lga" data-parsley-required-message="Select LGA" id="lga" class="form-control select2">
    @foreach($lgas as $lga)
        <option value="{{$lga->id}}">{{$lga->local_name ?? '' }}</option>
    @endforeach
</select>
