<x-popup>
    <a class="device-link-{{ $status }}" href="{{ $href }}">
        {{ $slot->isNotEmpty() ? $slot : $device->displayName() }}
    </a>
    <x-slot name="title">
        <span class="tw:text-nowrap tw:pr-1">
            <a href="{{ $href }}" class="tw:text-xl tw:font-bold">{{ $device->displayName() }}</a>
            {{ $device->hardware }}
        </span>
        <span class="tw:text-nowrap tw:pl-2 tw:pr-1">
            @if($device->os){{ \App\Facades\LibrenmsConfig::getOsSetting($device->os, 'text') }}@endif
            {{ $device->version }}
        </span>
        <span class="tw:text-nowrap tw:pl-2">
            @if($device->feature)({{ $device->features }})@endif
            @if($device->location)[{{ $device->location }}]@endif
        </span>
    </x-slot>
    <x-slot name="body">
        <template x-if="loadGraphs" x-data="{loadGraphs: false}" x-init="$watch('popupShow', shown => {if(shown) loadGraphs = true})">
            <div>
            @foreach($graphs as $graph)
                @isset($graph['text'], $graph['graph'])
                    <x-graph-row loading="lazy" :device="$device" :type="$graph['graph']" :title="$graph['text']" :graphs="[['from' => '-1d'], ['from' => '-7d']]"></x-graph-row>
                @endisset
            @endforeach
            </div>
        </template>
    </x-slot>
</x-popup>
