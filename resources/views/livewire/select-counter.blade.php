<div>
    <div class="select-counter-card">
        <h4>Select Counter</h4>
        <div class="counter-img-area">
            <img class="counter-img" src="{{asset('assets/img/cashier.png')}}" alt="Counter">
        </div>
        <div class="select-cou-form-group">
            <select class="select-cou-form-input" wire:model="select_counter" autofocus
                wire:keydown.enter.prevent="gotoSale">
                <option value="" selected>-- Please select a counter --</option>
                @foreach ($counters as $counter)
                @if ($counter->active_status == 1)
                <option value="{{$counter->id}}" disabled>{{$counter->counter}} - Already active!</option>
                @else
                <option value="{{$counter->id}}">{{$counter->counter}}</option>
                @endif
                @endforeach
            </select>
            @if ($select_counter == "")
            @error('select_counter')
            <span class="validation-msg">{{ $message }}</span>
            @enderror
            @endif
        </div>
        <div class="select-cou-btn-area">
            <button class="select-cou-btn back" wire:click="goBack"><span>&#8592;</span>&nbsp;Go Back</button>
            <button class="select-cou-btn continue" wire:click="gotoSale">Continue&nbsp;<span>&#8594;</span></button>
        </div>
    </div>
</div>