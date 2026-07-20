<div {{ $attributes->merge(['class' => 'sprf-kpi-card hover:translate-y-[-2px] transition duration-150']) }}>
    <div>
        <div class="label">{{ $label }}</div>
        <div class="value">{{ $value }}</div>
        <div class="delta">▲ {{ $delta }}</div>
    </div>
    <div class="sprf-kpi-icon icon-{{ $icon }}">{{ $symbol }}</div>
</div>
