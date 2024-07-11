<select name="parent_account" id="parent_account" class="form-control">
    <option disabled selected>Select parent account</option>
    @foreach ($accounts as $item)
        <option value="{{$item->glcode}}">{{$item->account_name ?? ''}} - ({{$item->glcode ?? ''}})</option>
    @endforeach
</select>
