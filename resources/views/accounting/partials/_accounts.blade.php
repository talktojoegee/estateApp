<select name="parent_account" id="parent_account" class="form-control ">
    <option disabled selected>--Select parent account --</option>
    @foreach($accounts as $account)
        <option value="{{$account->glcode ?? ''}}">{{$account->glcode ?? ''}} - {{$account->account_name ?? ''}}</option>
    @endforeach
</select>
