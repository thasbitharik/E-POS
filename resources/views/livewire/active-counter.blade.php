<div>
    <style>
        .active-counter-area {
            background-color: #27415a;
            border-radius: 50px;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            box-shadow: inset 0px 0px 15px #182a3baf;
        }

        .active-counter-area h6 {
            color: #ffffff !important;
            margin-bottom: 0;
            font-size: 16px !important;
        }

        .online-indicator {
            display: inline-block;
            width: 15px;
            height: 15px;
            background-color: #9dd55a;
            border-radius: 50%;
            position: relative;
        }

        span.blink {
            display: block;
            width: 15px;
            height: 15px;
            background-color: #9dd55a;
            opacity: 0.7;
            border-radius: 50%;

            animation: blink 1s linear infinite;
        }

        /* /Animations/ */
        @keyframes blink {
            100% {
                transform: scale(3, 3);
                opacity: 0;
            }
        }
    </style>
    @if ($active_counter && Auth::user()->user_type_id == 5)
    <div class="active-counter-area">
        <h6><span class="text-light-green">{{ $active_counter }}</span> is Active</h6>
        <div id="blink" class="online-indicator">
            <span class="blink"></span>
        </div>
    </div>
    @endif
</div>