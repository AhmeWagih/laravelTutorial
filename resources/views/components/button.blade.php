@if ($href)
    <a {{ $attributes->merge(['href' => $href, 'class' => $variantClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => $buttonType, 'class' => $variantClasses]) }}>
        {{ $slot }}
    </button>
@endif